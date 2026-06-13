<?php

namespace App\Http\Livewire\Pagamento;

use App\Models\AppPayGateway;
use App\Models\CustomerOrganizationPlace;
use App\Models\CustomerPayGateway;
use App\Models\ModEvent\Event;
use App\Models\RefAppEventCategory;
use App\Models\RefAppStates;
use App\Models\RefAppType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class MetodoPagamento extends Component
{
    //
    public $app;
    public $appUserRole;
    public $organizer;
    public $organizerId;
    public $target;
    public $target_id;
    public $target_ref='app_event';
    public $metodoAlterar;

    //
    public $customerPaymentGateways;

    public $gateway;

    //
    public $event_datetime_start;
    public $pay_gateway_id;
    public $pay_sandbox;
    public $pay_boleto;
    public $pay_boleto_date_end;
    public $pay_pix;
    public $pay_card_debit;
    public $pay_card_credit;
    public $pay_card_credit_installment_max=1;
    public $pay_card_credit_installment_amount_min=5.00;
    public $pay_slip_pix;
    public $pay_slip_pix_installment_amount_min;
    public $pay_slip_pix_installment_min=1;
    public $pay_slip_pix_installment_max=10;
    public $pay_slip_pix_installment_max_auto=1;
    public $pay_slip_pix_installment_max_days_before=1;
    public $pay_slip_pix_installment_max_event_date_finish=0;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        // GET
        $this->app         = sessionApp();
        $this->appUserRole = sessionUserRole();
        $this->organizer   = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;
        $this->target_id   = sessionTargetId();

        // SE ORGANIZER / TARGET_ID
        if(!$this->organizerId || !$this->target_id)
            return redirect()->route('dashboard');

        // LIMPA ORDER ID
        sessionOrderIdClear();

        $this->customerPaymentGateways = CustomerPayGateway::where('customer_id',$this->organizer->customer->id)
            ->where('pay_active',1)
            ->where('use_events',1)
            ->get();

        //
        $this->target = Event::with(['customer','customer.paymentGateways','gatewayPay'])->where('organizer_id',$this->organizerId)->where('id',$this->target_id)->first();

        //
        $this->gateway = $this->target->gatewayPay;

        //
        $this->event_datetime_start                   = \Carbon\Carbon::parse($this->target->event_datetime_start)->format('Y-m-d');
        $this->pay_gateway_id                         = $this->target->pay_gateway_id;
        $this->pay_sandbox                            = $this->target->pay_sandbox;

        //
        $this->pay_boleto                             = $this->target->pay_boleto;
        $this->pay_boleto_date_end                    = $this->target->pay_boleto_date_end ? \Carbon\Carbon::parse($this->target->pay_boleto_date_end)->format('Y-m-d') : \Carbon\Carbon::parse($this->target->event_datetime_start)->subDays(3)->format('Y-m-d');

        //
        $this->pay_card_credit                        = $this->target->pay_card_credit;
        $this->pay_card_credit_installment_max        = $this->target->pay_card_credit_installment_max;
        $this->pay_card_credit_installment_amount_min = toMoneyDot($this->target->pay_card_credit_installment_amount_min);

        // PIX
        $this->pay_pix                                        = $this->target->pay_pix ?? 0;
        $this->pay_slip_pix                                   = $this->target->pay_slip_pix ?? 0;
        $this->pay_slip_pix_installment_amount_min            = toMoneyDot($this->target->pay_slip_pix_installment_amount_min ?? ($this->gateway->pay_slip_pix_installment_amount_min ?? 10));
        $this->pay_slip_pix_installment_max                   = $this->target->pay_slip_pix_installment_max ?? ($this->gateway->pay_slip_pix_installment_max ?? 1);
        $this->pay_slip_pix_installment_max_event_date_finish = $this->target->pay_slip_pix_installment_max_event_date_finish ?? ($this->gateway->pay_slip_pix_installment_max_event_date_finish ?? 1);
        $this->pay_slip_pix_installment_max_auto              = 1; // CALCULO AUTOMATICO
        $this->pay_slip_pix_installment_max_days_before       = 1; // ATÉ 1 DIA ANTES


        //
        if($this->pay_gateway_id)
        {
            $this->metodoAlterar = true;
            $this->updatedPayGatewayId();
        }

        // SIMULA DADOS
        // $this->simula();
    }

    public function updatedPayGatewayId()
    {
        $this->gateway = false;

        if($this->pay_gateway_id ?? false)
        {
            $this->gateway = CustomerPayGateway::find($this->pay_gateway_id);

            // dd($this->pay_gateway_id,$this->gateway->toArray());
        }
    }

    public function render()
    {
        return view('livewire.pagamento.metodo-pagamento')->layout('layouts.app-pep-auth');
    }

    public function simula()
    {

    }

    public function metodoPagamentoSubmit()
    {
        // return;

        $rules = [
            "event_datetime_start"                           => ['nullable'],
            "pay_gateway_id"                                 => ['required', 'string'],
            "pay_sandbox"                                    => ['nullable', 'boolean'],
            "pay_boleto"                                     => ['nullable', 'boolean'],
            "pay_boleto_date_end"                            => ['nullable'],
            "pay_card_credit"                                => ['nullable', 'boolean'],
            "pay_card_credit_installment_max"                => ['required_if:pay_card_credit,true','integer'],
            "pay_card_credit_installment_amount_min"         => ['required_if:pay_card_credit,true'],
            "pay_pix"                                        => ['nullable', 'boolean'],
            "pay_slip_pix"                                   => ['nullable', 'boolean'],
            "pay_slip_pix_installment_amount_min"            => ['required_if:pay_slip_pix,true'],
            "pay_slip_pix_installment_max"                   => ['required_if:pay_slip_pix,true','integer'],
            "pay_slip_pix_installment_max_event_date_finish" => ['required_if:pay_slip_pix,true','integer'],
            "pay_slip_pix_installment_max_auto"              => ['nullable'],
            "pay_slip_pix_installment_max_days_before"       => ['nullable'],
        ];

        //
        if($this->pay_boleto ?? false)
        {
            $rules["pay_boleto_date_end"] = ['required', 'before:event_datetime_start', 'date'];
        }

        $validateData = $this->validate($rules);

        // TESTA PROVEDOR
        if(!$gatewaySelecionado = $this->customerPaymentGateways->find($this->pay_gateway_id))
        {
            return session()->flash('error','Selecione um Provedor de Pagamentos válido');
        }

        // SE CARTAO
        if($this->pay_card_credit ?? false)
        {
            // CREDITO - APPEND
            $validateData['pay_card_credit_installment_amount_min'] = toMoneyInt($this->pay_card_credit_installment_amount_min);

            // CREDITO - QTD MINIMA PARCELAS
            if($validateData['pay_card_credit_installment_max'] < 1)
            {
                return $this->addError('pay_card_credit_installment_max', "Cartão de crédito // Mínimo 1 parcela");
            }

            // CREDITO - QTD MAXIMO PARCELAS
            if($validateData['pay_card_credit_installment_max'] > $gatewaySelecionado->pay_card_credit_installment_max)
            {
                return $this->addError('pay_card_credit_installment_max', "Cartão de crédito // Máximo " . $gatewaySelecionado->pay_card_credit_installment_max . ' parcelas');
            }

            // CREDITO - VALOR MINIMO
            if($validateData['pay_card_credit_installment_amount_min'] < $gatewaySelecionado->pay_card_credit_installment_amount_min)
            {
                return $this->addError('pay_card_credit_installment_amount_min', "Cartão de crédito // Valor mínimo " . toMoney($gatewaySelecionado->pay_card_credit_installment_amount_min,'R$ '));
            }

            // PIX - APPEND
            $validateData['pay_slip_pix_installment_amount_min'] = toMoneyInt($this->pay_slip_pix_installment_amount_min);
        }
        else{
            // SE NAO FOR CARTAO DE CREDITO
            unset($validateData['pay_card_credit_installment_max']);
            unset($validateData['pay_card_credit_installment_amount_min']);
        }

        // SE CARNÊ
        if($this->pay_slip_pix ?? false)
        {
            // PIX - QTD MINIMA PARCELAS
            if($validateData['pay_slip_pix_installment_max'] < 1)
            {
                return $this->addError('pay_slip_pix_installment_max', "Carnê // Mínimo 1 parcela");
            }

            // PIX - QTD MAXIMO PARCELAS
            if($validateData['pay_slip_pix_installment_max'] > $gatewaySelecionado->pay_slip_pix_installment_max)
            {
                return $this->addError('pay_slip_pix_installment_max', "Carnê // Máximo " . $gatewaySelecionado->pay_slip_pix_installment_max . ' parcelas');
            }

            // PIX - VALOR MINIMO
            if($validateData['pay_slip_pix_installment_amount_min'] < $gatewaySelecionado->pay_slip_pix_installment_amount_min)
            {
                return $this->addError('pay_slip_pix_installment_amount_min', "Carnê // Valor mínimo " . toMoney($gatewaySelecionado->pay_slip_pix_installment_amount_min,'R$ '));
            }
        }
        else{
            unset($validateData['pay_slip_pix_installment_amount_min']);
            unset($validateData['pay_slip_pix_installment_max']);
            unset($validateData['pay_slip_pix_installment_max_event_date_finish']);
            unset($validateData['pay_slip_pix_installment_max_auto']);
            unset($validateData['pay_slip_pix_installment_max_days_before']);
        }

        // FORÇA SE NAO FOR BOLETO
        if(!$this->pay_boleto)
        {
            $validateData['pay_boleto_date_end'] = \Carbon\Carbon::parse($this->target->event_datetime_start)->subDays(3)->format('Y-m-d');
        }

        unset($validateData['event_datetime_start']);

        try
        {
            // TRANSACTION
            DB::beginTransaction();

            $this->target->update($validateData);

            // TRANSACTION FIM
            DB::commit();

            // EVENTO CRIADO
            if($this->metodoAlterar ?? false)
                session()->flash('success','Método Pagamento Alterado');
            else
                session()->flash('success','Método Pagamento Cadastrado');

            return redirect()->route('dashboard-evento');
        }
        catch (\Throwable $th)
        {
            // TRANSACTION ERROR
            DB::rollBack();

            // dd($th);

            return session()->flash('error',$th->getMessage());
        }
    }
}

