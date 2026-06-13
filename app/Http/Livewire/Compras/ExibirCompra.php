<?php

namespace App\Http\Livewire\Compras;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayment\AppPayment;
use Exception;
use Illuminate\Http\Request;
use Livewire\Component;

use WireUi\Traits\Actions;

class ExibirCompra extends Component
{
    use Actions;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //
    public $localizador;
    public $order;
    public $target;
    public $slipInstallmentControl;
    public $reservation_expiration;
    public $pg_timestamp;
    public $returnInfo;

    public function mount(Request $request,$localizador,$timestamp=false)
    {
        //
        $this->localizador            = strtoupper($localizador);
        $this->pg_timestamp           = ($timestamp == 'debug') ? false : $timestamp;

        //
        $this->slipInstallmentControl = $request->input('slipInstallmentControl') ?? false;
        $this->returnInfo             = $request->input('returnInfo') ?? false;

        //
        if(!$this->order = AppEventOrder::with(['event'])->where('order_control',$this->localizador)->first())
        {
            session()->flash('error',"Localizador {$localizador} inexistente");
            return redirect()->route('home');
        }

        //
        $this->target = $this->order->event;

        // Fallback para pedidos antigos/sem tracking preenchido.
        $this->ensureTrackingData($request);

        //
        $this->checkExpiration();
    }

    public function cancelarPedido()
    {
        // SE STATUS
        if(!in_array($this->order->status, listOrderStatusNaoCancelar()))
        {
            $this->order->status                      = 'cancelado_no_pagamento';
            $this->order->order_cancel_datetime       = now()->format('Y-m-d H:i:s');
            $this->order->order_cancel_description    = 'Desistiu no momento do pagamento';
            $this->order->reservation_expiration_date = null;
            $this->order->save();
            //
            session()->flash('error','Pedido cancelado');

            // SE TICKETS
            if ($tickets = $this->order->tickets)
            {
                foreach ($tickets ?? [] as $ticketKey => $ticket)
                {
                    $tickets[$ticketKey]->delete();
                }
            }
        }

        sessionClear('pedido');

        return;
    }

    public $reservation_expiration_date;
    public $reservation_expiration_date_seconds;
    public function checkExpiration($delay=false)
    {
        $this->reservation_expiration = false;

        //
        if($this->order ?? false)
        {
            // SE JA ESTA PAGO
            if(in_array($this->order->status,listOrderStatusPaid()))
            {
                // LIMPA CACHE
                sessionClear('pedido');
                $this->order->reservation_expiration_date = null;
                $this->order->save();
            }
            else
            {
                //
                if($this->order->reservation_expiration_date ?? false)
                {
                    //
                    if(($delay ?? false) && ($delay > 0) && in_array($this->order->status ?? false,['expired_order','fase_pagamento']))
                    {
                        // PEGA DATA HORA ATUAL + ADD MINUTOS
                        $agora = now();
                        $this->reservation_expiration_date = $agora->addMinutes($delay);

                        // ABRE O PEDIDO NOVAMENTE
                        $this->order->status = 'fase_pagamento';
                        $this->order->save();
                    }
                    else
                    {
                        $this->reservation_expiration_date = $this->order->reservation_expiration_date ?? now();
                    }

                    // CALCULA SEGUNDOS
                    $this->reservation_expiration_date_seconds = calculaSegundosDif($this->reservation_expiration_date, now());

                    // SE EXPIRADO
                    if($this->reservation_expiration_date_seconds < 1)
                    {
                        $this->reservation_expiration = true;

                        // PEDIDO EXPIRADO
                        $this->order->status                      = 'expired_order';
                        $this->order->order_cancel_datetime       = now()->format('Y-m-d H:i:s');
                        $this->order->order_cancel_description    = 'Ultrapassou o tempo da reserva';
                        $this->order->reservation_expiration_date = null;
                        $this->order->save();

                        // SE TICKETS > LIMPA
                        if ($tickets = $this->order->tickets)
                        {
                            foreach ($tickets ?? [] as $ticketKey => $ticket)
                            {
                                $tickets[$ticketKey]->delete();
                            }
                        }

                        // LIMPA CACHE
                        sessionClear('pedido');
                    }
                    else
                    {
                        $this->reservation_expiration = false;
                    }
                }
            }
        }

        //
        return $this->reservation_expiration;
    }


    public function updated($name, $value)
    {
        //
    }

    protected function ensureTrackingData(Request $request): void
    {
        if (!$this->order) {
            return;
        }

        if ($this->order->order_tracking_timestamp) {
            return;
        }

        $agent = (string) ($request->userAgent() ?? '');
        $this->order->order_ip_address = $request->ip();
        $this->order->order_user_agent = $agent;
        $this->order->order_device_type = preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $agent)
            ? 'tablet'
            : (preg_match('/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile/', $agent) ? 'mobile' : 'desktop');
        $this->order->order_browser = $this->detectBrowser($agent);
        $this->order->order_platform = $this->detectPlatform($agent);
        $this->order->order_session_id = $request->hasSession() ? $request->session()->getId() : null;
        $this->order->order_tracking_timestamp = now();
        $this->order->save();
    }

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

    public $payments;
    public $payment;
    public function validarPagamento($showMessages = true)
    {
        if (!$this->order) return false;

        $this->payments = AppPayment::where('app_ref', 'app_event')
            ->where('app_ref_order_id', $this->order->id)
            ->get();

        $validarLoop = [];
        foreach ($this->payments as $pmt) {
            if (!$pmt->pay_nsu) continue;
            $validarLoop[$pmt->pay_nsu] = $pmt;
        }

        if (empty($validarLoop)) return false;

        ksort($validarLoop);

        foreach ($validarLoop as $nsu => $pmt) {
            $this->payment = $pmt;

            $token   = $this->order->event->gatewayPay->token_live ?? false;
            $sandbox = false;

            if (!$token) continue;

            $resultado = safe2payValidarPagamento($this->order->id, $nsu, $pmt->id, $token, 'app_event');

            if ($resultado->pagamento_ok ?? false) {
                $this->order->payment_id = $pmt->id;
                $this->order->save();
                $this->order->refresh();
                return true;
            }
        }

        $this->order->refresh();
        return false;
    }

    public function render()
    {
        return view('livewire.compras.compra-exibir')->layout('layouts.app-pep-home');
    }
}
