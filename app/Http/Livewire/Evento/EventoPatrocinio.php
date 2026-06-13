<?php

namespace App\Http\Livewire\Evento;

use App\Models\AppEvent\AppEventOrderSponsorship;
use App\Models\AppPayment\AppPayment;
use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventSponsorshipPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventoPatrocinio extends Component
{
    use WithFileUploads;

    //
    public $organizer;
    public $organizerId;
    public $target;
    public $target_id;
    public $target_ref = 'app_event_sponsorship';

    //
    public $sponsorship;
    public $patrocinio_id;
    public $sponsorship_plans;
    public $sponsorship_plan;
    public $sponsorship_plan_id;
    public $sponsorship_orders;

    //
    public $order_id;
    public $pagamento_id;
    public $adicionar_pagamento;
    public $gerar_boleto;

    // NOVO PATROCÍNIO MANUAL
    public $novo_patrocinio_manual = false;
    public $manual_buyer_doc_num;
    public $manual_plan_id;
    public $manual_description;
    public $manual_amount;

    // EDITAR PATROCINADOR
    public $editar_patrocinio = false;
    public $edit_order_id;
    public $edit_url_logo_file;
    public $edit_buyer_name;
    public $edit_buyer_segment;
    public $edit_buyer_description;
    public $edit_buyer_email;
    public $edit_buyer_doc_type;
    public $edit_buyer_doc_num;
    public $edit_buyer_contact_name;
    public $edit_buyer_contact_ddd;
    public $edit_buyer_contact_num;
    public $edit_buyer_url_logo;
    public $edit_buyer_url_website;
    public $edit_buyer_url_instagram;

    //
    public $pay_type;
    public $pay_nsu;
    public $pay_datetime;
    public $value_paid;
    public $paid_description;

    //
    public $event_id;
    public $sponsorship_id;
    public $slug;
    public $name;
    public $description;
    public $price;
    public $installments_max;
    public $installments_fees_pay;
    public $amount;
    public $amount_sales;
    public $plan_active;
    public $pay_pix;
    public $pay_credit;
    public $pay_boleto;
    public $pay_boleto_date_max;
    public $data_finish;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($acao=false,$patrocinio_id=false)
    {
        // GET
        $this->organizer   = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;
        $this->target_id   = sessionTargetId();

        // SE ORGANIZER / TARGET_ID
        if(!$this->organizerId || !$this->target_id)
            return redirect()->route('dashboard');

        // LIMPA ORDER ID
        sessionOrderIdClear();
        sessionClear('pedido');
    }

    public function render()
    {
        //
        if(!$this->target = Event::with(['sponsorship','sponsorshipPlans','sponsorshipOrders'])->where('organizer_id',$this->organizerId)->where('id',$this->target_id)->first())
        {
            returnEventoDashboard('Evento não localizado','error');
        }

        // da($this->target);

        $this->sponsorship        = $this->target->sponsorship ?? false;
        $this->sponsorship_plans  = $this->target->sponsorshipPlans ?? false;
        $this->sponsorship_orders = $this->target->sponsorshipOrders ?? false;

        return view('livewire.evento.patrocinio')->layout('layouts.app-pep-auth');
    }

    public function getOrder($order_id)
    {
        return AppEventOrderSponsorship::with(['payments'])->find($order_id);
    }

    public function editarPatrocinio($order_id)
    {
        $order = AppEventOrderSponsorship::find($order_id);
        if (!$order) return;

        $this->edit_order_id            = $order_id;
        $this->edit_buyer_name          = $order->buyer_name;
        $this->edit_buyer_segment       = $order->buyer_segment;
        $this->edit_buyer_description   = $order->buyer_description;
        $this->edit_buyer_email         = $order->buyer_email;
        $this->edit_buyer_doc_type      = $order->buyer_doc_type;
        $this->edit_buyer_doc_num       = $order->buyer_doc_num;
        $this->edit_buyer_contact_name  = $order->buyer_contact_name;
        $this->edit_buyer_contact_ddd   = $order->buyer_contact_ddd;
        $this->edit_buyer_contact_num   = $order->buyer_contact_num;
        $this->edit_buyer_url_logo      = $order->buyer_url_logo;
        $this->edit_buyer_url_website   = $order->buyer_url_website;
        $this->edit_buyer_url_instagram = $order->buyer_url_instagram;
        $this->edit_url_logo_file       = null;
        $this->editar_patrocinio        = true;
    }

    public function removerLogoPatrocinio()
    {
        $this->edit_buyer_url_logo = null;
        $this->edit_url_logo_file  = null;
    }

    public function salvarEdicaoPatrocinio($order_id)
    {
        $this->validate([
            'edit_buyer_name'          => ['required', 'string'],
            'edit_buyer_segment'       => ['nullable', 'string'],
            'edit_buyer_description'   => ['nullable', 'string'],
            'edit_buyer_email'         => ['required', 'email'],
            'edit_buyer_contact_name'  => ['required', 'string'],
            'edit_buyer_contact_ddd'   => ['required'],
            'edit_buyer_contact_num'   => ['required'],
            'edit_buyer_url_website'   => ['nullable', 'string'],
            'edit_buyer_url_instagram' => ['nullable', 'string'],
        ]);

        try {
            // UPLOAD DE NOVO LOGO
            if ($this->edit_url_logo_file) {
                $this->validate(['edit_url_logo_file' => ['image', 'max:5120']]);

                $app      = currentApp();
                $appId    = $app->id ?? 1;
                $relativePath = 'events/' . $this->target->event_slug . '/sponsors';
                $physicalPath = "{$appId}/{$relativePath}";
                $fullPath = storage_path("app/public/{$physicalPath}");

                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0755, true);
                }

                $extension = $this->edit_url_logo_file->getClientOriginalExtension();
                $filename  = time() . '_' . Str::random(10) . '.' . $extension;
                $this->edit_url_logo_file->storeAs($physicalPath, $filename, 'public');
                $this->edit_buyer_url_logo = "{$relativePath}/{$filename}";
            }

            $order = AppEventOrderSponsorship::find($order_id);
            $order->update([
                'buyer_name'          => mb_strtolower($this->edit_buyer_name),
                'buyer_segment'       => mb_strtolower($this->edit_buyer_segment),
                'buyer_description'   => mb_strtolower($this->edit_buyer_description),
                'buyer_email'         => mb_strtolower($this->edit_buyer_email),
                'buyer_contact_name'  => mb_strtolower($this->edit_buyer_contact_name),
                'buyer_contact_ddd'   => $this->edit_buyer_contact_ddd,
                'buyer_contact_num'   => $this->edit_buyer_contact_num,
                'buyer_url_logo'      => $this->edit_buyer_url_logo,
                'buyer_url_website'   => mb_strtolower($this->edit_buyer_url_website),
                'buyer_url_instagram' => mb_strtolower($this->edit_buyer_url_instagram),
            ]);

            $this->editar_patrocinio = false;

            session()->flash('success', 'Dados do patrocinador atualizados');

            return redirect()->route('evento-patrocinios');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function adicionarPagamento($order_id, $pagamento_id=false)
    {
        $this->order_id     = $order_id;
        $this->pagamento_id = $pagamento_id;

        //
        if($pagamento_id)
        {
            $order = $this->getOrder($order_id);

            //
            if($pagamento = $order->payments->find($pagamento_id))
            {
                // da($pagamento);
                $this->pay_type         = $pagamento->pay_type;
                $this->pay_nsu          = $pagamento->pay_nsu;
                $this->pay_datetime     = $pagamento->pay_datetime ? \Carbon\Carbon::parse($pagamento->pay_datetime)->format('Y-m-d') : null;
                $this->value_paid       = toMoney($pagamento->value_paid);
                $this->paid_description = $pagamento->paid_description;
            }
        }

        $this->adicionar_pagamento = true;
    }

    public function registrarPagamento($order_id, $pagamento_id=false)
    {
        try
        {
            DB::beginTransaction();

            // VALIDATE
            $validatedData = $this->validate([
                'pay_type'          => ['required'],
                'pay_nsu'           => ['nullable'],
                'pay_datetime'      => ['required','date'],
                'value_paid'        => ['required'],
                'paid_description'  => ['nullable','string'],
            ]);

            //
            $validatedData['pay_nsu']          = $this->pay_nsu ? $this->pay_nsu : ('M.' .now()->format('ymdHis'));
            $validatedData['value_paid']       = toMoneyInt($validatedData['value_paid']);
            $validatedData['paid_description'] = mb_strtoupper($this->paid_description ?? ('PAGO COM ' . __($this->pay_type)));

            $order = $this->getOrder($order_id);

            if ($pagamento_id && $pagamento = $order->payments->find($pagamento_id))
            {
                // CRIA PAGAMENTO
                $pagamento->update(array_merge($validatedData,[
                    'app_ref'                 => 'evento_patrocinador',
                    'app_ref_order_id'        => $order_id,
                    'gateway_id'              => null,
                    'gateway_slug'            => 'manual',
                    'gateway_sandbox'         => false,
                    'status'                  => 'paid',
                    'description'             => strtoupper($this->target->event_name),
                    'value_liquid'            => $this->order->order_amount ?? 0,
                    'paid_label'              => '1X DE R$ ' . toMoney($validatedData['value_paid']),
                    'pay_type'                => $this->pay_type ?? null,
                    'pay_code_promo_id'       => $this->pay_code_promo_id ?? null,
                    'pay_installments_number' => $this->pay_installments_number ?? 1,
                    'pay_installment_value'   => $this->value_paid,
                    'pay_integration_type'    => 'manual',
                ]));
            }
            else
            {
                // CRIA PAGAMENTO
                $pagamento = $order->payments()->create(array_merge($validatedData,[
                    'app_ref'                 => 'evento_patrocinador',
                    'app_ref_order_id'        => $order_id,
                    'gateway_id'              => null,
                    'gateway_slug'            => 'manual',
                    'gateway_sandbox'         => false,
                    'status'                  => 'paid',
                    'description'             => strtoupper($this->target->event_name),
                    'value_liquid'            => $this->order->order_amount ?? 0,
                    'paid_label'              => '1X DE R$ ' . toMoney($validatedData['value_paid']),
                    'pay_type'                => $this->pay_type ?? null,
                    'pay_code_promo_id'       => $this->pay_code_promo_id ?? null,
                    'pay_installments_number' => $this->pay_installments_number ?? 1,
                    'pay_installment_value'   => $this->value_paid,
                    'pay_integration_type'    => 'manual',
                ]));
            }

            //
            $valores_pagos = $validatedData['value_paid'];

            //
            foreach ($order->payments ?? [] as $payment_value)
            {
                $valores_pagos += $payment_value->value_paid;
            }

            //
            if($valores_pagos >= $order->order_amount)
            {
                $order->status = 'paid';
                $order->save();

                session()->flash('success','Pagamento adicionado - Patrocínio quitado');
            }
            elseif($valores_pagos < $order->order_amount)
            {
                $order->status = 'fase_pagamento';
                $order->save();

                session()->flash('success','Pagamento adicionado - Patrocínio atualizado');
            }
            else
            {
                session()->flash('success','Pagamento adicionado');
            }

            // dd(
            //     $order->toArray(),
            //     $pagamento->toArray(),
            //     $order->payments->toArray(),
            // );

            //
            $this->order_id            = false;
            $this->pagamento_id        = false;
            $this->adicionar_pagamento = false;

            //
            $this->pay_type         = '';
            $this->pay_nsu          = '';
            $this->pay_datetime     = '';
            $this->value_paid       = '';
            $this->paid_description = '';

            DB::commit();

            return redirect()->route('evento-patrocinios');
        }
        catch (\Throwable $th)
        {
            session()->flash('error',$th->getMessage());
        }
    }

    public function gerarBoleto($order_id, $pagamento_id=false)
    {
        $this->order_id = $order_id;
        $this->pagamento_id = $pagamento_id;

        $this->gerar_boleto = true;
    }


    public function registrarBoleto($order_id, $pagamento_id=false)
    {
        DB::beginTransaction();

        // VALIDATE
        $validatedData = $this->validate([
            'pay_type'          => ['required'],
            'pay_nsu'           => ['nullable'],
            'pay_datetime'      => ['required','date'],
            'value_paid'        => ['required'],
            'paid_description'  => ['nullable','string'],
        ]);

        //
        $validatedData['pay_nsu']          = $this->pay_nsu ?? ('M.' . now()->format('YmdHis'));
        $validatedData['value_paid']       = toMoneyInt($validatedData['value_paid']);
        $validatedData['paid_description'] = mb_strtoupper($this->paid_description ?? ('PAGO COM ' . __($this->pay_type)));

        // CRIA PAGAMENTO
        $pagamento = AppPayment::create(array_merge($validatedData,[
            'app_ref'                 => 'evento_patrocinador',
            'app_ref_order_id'        => $order_id,
            'gateway_id'              => null,
            'gateway_slug'            => 'manual',
            'gateway_sandbox'         => false,
            'status'                  => 'paid',
            'description'             => strtoupper($this->target->event_name),
            'value_liquid'            => $this->order->order_amount ?? 0,
            'paid_label'              => 'LANÇAMENTO MANUAL',
            'pay_type'                => $this->pay_type ?? null,
            'pay_code_promo_id'       => $this->pay_code_promo_id ?? null,
            'pay_installments_number' => $this->pay_installments_number ?? 1,
            'pay_installment_value'   => $this->value_paid,
            'pay_integration_type'    => 'manual',
        ]));

        dd(
            $validatedData,
            $pagamento->toArray(),
        );

        $this->order_id = $order_id;

        $this->adicionar_pagamento = true;

        /*

        */
    }

    public function removerPagamento($order_id,$pagamento_id)
    {
        if ($pagamento_id)
        {
            $pagamento = AppPayment::find($pagamento_id);

            $pagamento->delete();

            $this->adicionar_pagamento = false;

            return session()->flash('success','Pagamento removido');
        }
    }

    public function paymentCheckProcessed($payment_id)
    {
        try {
            $payment = AppPayment::find($payment_id);
            if (!$payment) {
                session()->flash('error', 'Pagamento não encontrado.');
                return;
            }
            $result = safe2payValidarPagamento(
                $payment->app_ref_order_id,
                $payment->pay_nsu,
                $payment->id,
                null,
                'evento_patrocinador'
            );
            if ($result) {
                session()->flash('success', 'Pagamento verificado e atualizado.');
            } else {
                session()->flash('info', 'Nenhuma atualização necessária.');
            }
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error('paymentCheckProcessed patrocinio: ' . $th->getMessage());
            session()->flash('error', 'Erro ao verificar pagamento.');
        }
    }

    public function abrirNovoPatrocinioManual($buyer_doc_num)
    {
        // Busca o primeiro order deste comprador para pré-preencher os dados
        $rep = ($this->sponsorship_orders ?? collect())
            ->where('buyer_doc_num', $buyer_doc_num)
            ->first();

        if (!$rep) return;

        $this->manual_buyer_doc_num = $buyer_doc_num;
        $this->manual_plan_id       = null;
        $this->manual_description   = '';
        $this->manual_amount        = '';
        $this->pay_type             = '';
        $this->pay_nsu              = '';
        $this->pay_datetime         = now()->format('Y-m-d');
        $this->value_paid           = '';
        $this->paid_description     = '';
        $this->novo_patrocinio_manual = true;
    }

    public function salvarNovoPatrocinioManual()
    {
        $this->validate([
            'manual_description' => ['required', 'string'],
            'manual_amount'      => ['required'],
            'pay_type'           => ['required'],
            'pay_datetime'       => ['required', 'date'],
            'value_paid'         => ['required'],
            'pay_nsu'            => ['nullable'],
            'paid_description'   => ['nullable', 'string'],
        ]);

        try {
            DB::beginTransaction();

            // Busca rep para copiar dados do comprador
            $rep = ($this->sponsorship_orders ?? collect())
                ->where('buyer_doc_num', $this->manual_buyer_doc_num)
                ->first();

            if (!$rep) {
                session()->flash('error', 'Patrocinador não localizado');
                return;
            }

            $orderControl  = 'EVP.' . now()->format('ymds') . '.' . strtoupper(hash('adler32', $this->target->event_slug . $this->manual_buyer_doc_num . now()->timestamp)) . '-M';
            $orderAmount   = toMoneyInt($this->manual_amount);
            $valuePaid     = toMoneyInt($this->value_paid);
            $orderStatus   = $valuePaid >= $orderAmount ? 'paid' : 'fase_pagamento';

            $order = AppEventOrderSponsorship::create([
                'event_id'                   => $this->target->id,
                'plan_id'                    => $this->manual_plan_id ?: null,
                'sponsorship_id'             => $rep->sponsorship_id,
                'channel_order'              => 'manual',
                'status'                     => $orderStatus,
                'order_control'              => $orderControl,
                'order_amount'               => $orderAmount,
                'order_amount_pay'           => $orderAmount,
                'order_description'          => mb_strtoupper($this->manual_description),
                'order_generation_datetime'  => now(),
                'buyer_name'                 => $rep->buyer_name,
                'buyer_segment'              => $rep->buyer_segment,
                'buyer_description'          => $rep->buyer_description,
                'buyer_email'                => $rep->buyer_email,
                'buyer_doc_type'             => $rep->buyer_doc_type,
                'buyer_doc_num'              => $rep->buyer_doc_num,
                'buyer_contact_name'         => $rep->buyer_contact_name,
                'buyer_contact_ddd'          => $rep->buyer_contact_ddd,
                'buyer_contact_num'          => $rep->buyer_contact_num,
                'buyer_url_logo'             => $rep->buyer_url_logo,
                'buyer_url_website'          => $rep->buyer_url_website,
                'buyer_url_instagram'        => $rep->buyer_url_instagram,
            ]);

            // Cria o pagamento manual junto
            $order->payments()->create([
                'app_ref'                 => 'evento_patrocinador',
                'app_ref_order_id'        => $order->id,
                'gateway_id'              => null,
                'gateway_slug'            => 'manual',
                'gateway_sandbox'         => false,
                'status'                  => 'paid',
                'description'             => strtoupper($this->target->event_name),
                'value_liquid'            => $orderAmount,
                'value_paid'              => $valuePaid,
                'paid_label'              => '1X DE R$ ' . toMoney($valuePaid),
                'paid_description'        => mb_strtoupper($this->paid_description ?? ('PAGO COM ' . __($this->pay_type))),
                'pay_type'                => $this->pay_type,
                'pay_nsu'                 => $this->pay_nsu ?: ('M.' . now()->format('ymdHis')),
                'pay_datetime'            => $this->pay_datetime,
                'pay_installments_number' => 1,
                'pay_installment_value'   => $valuePaid,
                'pay_integration_type'    => 'manual',
            ]);

            DB::commit();

            $this->novo_patrocinio_manual = false;
            $this->manual_buyer_doc_num   = null;
            $this->manual_plan_id         = null;
            $this->manual_description     = '';
            $this->manual_amount          = '';
            $this->pay_type               = '';
            $this->pay_nsu                = '';
            $this->pay_datetime           = '';
            $this->value_paid             = '';
            $this->paid_description       = '';

            session()->flash('success', $orderStatus === 'paid' ? 'Patrocínio manual adicionado e quitado' : 'Patrocínio manual adicionado');

            return redirect()->route('evento-patrocinios');

        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }

    public function cancelarPatrocinio($order_id)
    {
        //
        if(!$order = $this->getOrder($order_id))
        {
            return session()->flash('error','Patrocínio não localizado');
        }

        //
        if($order->payments->count() && $order->payments->whereIn('status',listOrderStatusEmPagamento())->count())
        {
            return session()->flash('error','Patrocínio não pode ser cancelado, já possui pagamentos');
        }

        $order->delete();
        return session()->flash('success','Patrocínio removido');
    }
}

