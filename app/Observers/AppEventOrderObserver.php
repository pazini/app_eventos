<?php

namespace App\Observers;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppBuyers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AppEventOrderObserver
{
    /**
     * Handle the AppEventOrder "creating" event.
     *
     * Este observer é executado ANTES de criar um pedido
     * e garante que o comprador seja registrado em app_buyers
     * com o my_key (birth_date + cpf) e vincula o buyer_id
     */
    public function creating(AppEventOrder $order)
    {
        // Captura rastreabilidade sempre, mesmo quando não há dados suficientes para vincular buyer.
        $this->captureSecurityTracking($order);

        // Só processa se tiver CPF e data de nascimento
        if (empty($order->buyer_doc_num) || empty($order->buyer_birth_date)) {
            Log::warning('AppEventOrderObserver: Pedido sem CPF ou data de nascimento', [
                'order_control' => $order->order_control ?? 'N/A',
                'buyer_doc_num' => $order->buyer_doc_num ?? 'NULL',
                'buyer_birth_date' => $order->buyer_birth_date ?? 'NULL'
            ]);
            return;
        }

        try {
            // Gera my_key (formato: 1992-02-14.14225119793)
            $birthDate = Carbon::parse($order->buyer_birth_date)->format('Y-m-d');
            $myKey = $birthDate . '.' . $order->buyer_doc_num;

            // Determina app_source e app_user_uuid a partir da sessão (app-version)
            $appSource = session('app_source', 'checkout');
            $appUserUuid = session('app_user_id');

            // Busca ou cria o comprador
            $buyer = AppBuyers::firstOrCreate(
                ['my_key' => $myKey],
                [
                    'doc_type' => $order->buyer_doc_type ?? 'cpf',
                    'doc_num' => preg_replace('/\D/', '', $order->buyer_doc_num ?? ''),
                    'name' => $order->buyer_name,
                    'email' => $order->buyer_email,
                    'birth_date' => $order->buyer_birth_date,
                    'contact_country' => $order->buyer_contact_country ?? 55,
                    'contact_ddd' => (int) preg_replace('/\D/', '', $order->buyer_contact_ddd ?? ''),
                    'contact_num' => (int) preg_replace('/\D/', '', $order->buyer_contact_num ?? ''),
                    'app_source' => $appSource,
                    'app_user_uuid' => $appUserUuid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Se o buyer já existia, atualiza SEMPRE com os dados mais recentes do pedido
            if (!$buyer->wasRecentlyCreated) {
                $updates = [];

                // Atualiza nome se vier preenchido no pedido
                if (!empty($order->buyer_name)) {
                    $updates['name'] = $order->buyer_name;
                }

                // Atualiza email se vier preenchido no pedido
                if (!empty($order->buyer_email)) {
                    $updates['email'] = $order->buyer_email;
                }

                // Atualiza doc_type e doc_num se virem preenchidos
                if (!empty($order->buyer_doc_type)) {
                    $updates['doc_type'] = $order->buyer_doc_type;
                }
                if (!empty($order->buyer_doc_num)) {
                    $updates['doc_num'] = preg_replace('/\D/', '', $order->buyer_doc_num);
                }

                // Atualiza telefone se vier preenchido no pedido
                if (!empty($order->buyer_contact_ddd)) {
                    $updates['contact_ddd'] = (int) preg_replace('/\D/', '', $order->buyer_contact_ddd);
                }
                if (!empty($order->buyer_contact_num)) {
                    $updates['contact_num'] = (int) preg_replace('/\D/', '', $order->buyer_contact_num);
                }

                // Atualiza país se vier preenchido
                if (!empty($order->buyer_contact_country)) {
                    $updates['contact_country'] = $order->buyer_contact_country;
                }

                // Atualiza app_source e app_user_uuid da sessão (app-version)
                if (!empty($appSource) && $appSource !== 'checkout') {
                    $updates['app_source'] = $appSource;
                }
                if (!empty($appUserUuid)) {
                    $updates['app_user_uuid'] = $appUserUuid;
                }

                if (!empty($updates)) {
                    $updates['updated_at'] = now();
                    $buyer->update($updates);

                    Log::info('AppEventOrderObserver: Buyer atualizado com dados do pedido', [
                        'buyer_id' => $buyer->id,
                        'order_control' => $order->order_control ?? 'N/A',
                        'updated_fields' => array_keys($updates)
                    ]);
                }
            }

            // Vincula o buyer_id ao pedido
            $order->buyer_id = $buyer->id;

            // Log de sucesso
            if (config('app.debug')) {
                Log::debug('AppEventOrderObserver: Buyer vinculado', [
                    'order_control' => $order->order_control ?? 'N/A',
                    'buyer_id' => $buyer->id,
                    'my_key' => $myKey,
                    'was_created' => $buyer->wasRecentlyCreated
                ]);
            }

        } catch (\Exception $e) {
            // Log de erro mas não bloqueia a criação do pedido
            Log::error('AppEventOrderObserver: Erro ao criar/vincular buyer', [
                'order_control' => $order->order_control ?? 'N/A',
                'buyer_doc_num' => $order->buyer_doc_num,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle the AppEventOrder "created" event.
     */
    public function created(AppEventOrder $order)
    {
        // Fallback: garante rastreabilidade quando, por qualquer razão, não veio no creating.
        if (empty($order->order_tracking_timestamp)) {
            try {
                $this->captureSecurityTracking($order);
                $order->saveQuietly();
            } catch (\Throwable $e) {
                Log::warning('AppEventOrderObserver: Falha no fallback de tracking (created)', [
                    'order_id' => $order->id ?? null,
                    'order_control' => $order->order_control ?? 'N/A',
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Captura dados de segurança e rastreabilidade do pedido
     */
    protected function captureSecurityTracking(AppEventOrder $order)
    {
        try {
            $request = request();

            // IP do cliente (considera proxy/load balancer)
            $order->order_ip_address = $request->ip();

            // User Agent completo
            $order->order_user_agent = $request->userAgent();

            // Detecta tipo de dispositivo, browser e plataforma
            $agent = (string) ($request->userAgent() ?? '');

            // Device Type
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $agent)) {
                $order->order_device_type = 'tablet';
            } elseif (preg_match('/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile/', $agent)) {
                $order->order_device_type = 'mobile';
            } else {
                $order->order_device_type = 'desktop';
            }

            // Browser
            $order->order_browser = $this->detectBrowser($agent);

            // Platform/OS
            $order->order_platform = $this->detectPlatform($agent);

            // Session ID (se disponível)
            if ($request->hasSession()) {
                $order->order_session_id = $request->session()->getId();
            }

            // Timestamp do tracking
            $order->order_tracking_timestamp = now();

            if (config('app.debug')) {
                Log::debug('AppEventOrderObserver: Tracking capturado', [
                    'order_control' => $order->order_control ?? 'N/A',
                    'ip' => $order->order_ip_address,
                    'device' => $order->order_device_type,
                    'browser' => $order->order_browser,
                    'platform' => $order->order_platform
                ]);
            }

        } catch (\Throwable $e) {
            Log::warning('AppEventOrderObserver: Erro ao capturar tracking', [
                'order_control' => $order->order_control ?? 'N/A',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Detecta o navegador baseado no User Agent
     */
    protected function detectBrowser(string $userAgent): string
    {
        $browsers = [
            'Edg' => 'Edge',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'Firefox' => 'Firefox',
            'MSIE' => 'Internet Explorer',
            'Trident' => 'Internet Explorer',
            'Opera' => 'Opera',
            'OPR' => 'Opera',
        ];

        foreach ($browsers as $key => $name) {
            if (stripos($userAgent, $key) !== false) {
                return $name;
            }
        }

        return 'Unknown';
    }

    /**
     * Detecta a plataforma/sistema operacional baseado no User Agent
     */
    protected function detectPlatform(string $userAgent): string
    {
        $platforms = [
            'Windows NT 10.0' => 'Windows 10',
            'Windows NT 11.0' => 'Windows 11',
            'Windows NT 6.3' => 'Windows 8.1',
            'Windows NT 6.2' => 'Windows 8',
            'Windows NT 6.1' => 'Windows 7',
            'Windows NT 6.0' => 'Windows Vista',
            'Windows NT 5.1' => 'Windows XP',
            'Mac OS X' => 'Mac OS X',
            'Macintosh' => 'Mac OS',
            'iPhone' => 'iOS',
            'iPad' => 'iOS',
            'iPod' => 'iOS',
            'Android' => 'Android',
            'Linux' => 'Linux',
            'Ubuntu' => 'Ubuntu',
        ];

        foreach ($platforms as $key => $name) {
            if (stripos($userAgent, $key) !== false) {
                return $name;
            }
        }

        return 'Unknown';
    }

    /**
     * Handle the AppEventOrder "updated" event.
     */
    public function updated(AppEventOrder $order)
    {
        // Se os dados do comprador mudaram, atualiza o buyer
        if ($order->isDirty(['buyer_name', 'buyer_email', 'buyer_contact_ddd', 'buyer_contact_num'])) {
            if ($order->buyer_id) {
                try {
                    $buyer = AppBuyers::find($order->buyer_id);
                    if ($buyer) {
                        $updates = [];

                        if ($order->isDirty('buyer_name') && !empty($order->buyer_name)) {
                            $updates['name'] = $order->buyer_name;
                        }
                        if ($order->isDirty('buyer_email') && !empty($order->buyer_email)) {
                            $updates['email'] = $order->buyer_email;
                        }
                        if ($order->isDirty('buyer_contact_ddd') && !empty($order->buyer_contact_ddd)) {
                            $updates['contact_ddd'] = $order->buyer_contact_ddd;
                        }
                        if ($order->isDirty('buyer_contact_num') && !empty($order->buyer_contact_num)) {
                            $updates['contact_num'] = $order->buyer_contact_num;
                        }

                        if (!empty($updates)) {
                            $updates['updated_at'] = now();
                            $buyer->update($updates);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('AppEventOrderObserver: Erro ao atualizar buyer', [
                        'order_id' => $order->id,
                        'buyer_id' => $order->buyer_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }
}
