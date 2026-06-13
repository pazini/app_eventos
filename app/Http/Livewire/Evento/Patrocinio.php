<?php

namespace App\Http\Livewire\Evento;

use App\Models\CustomerOrganizationPlace;
use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventSponsorshipPlan;
use App\Models\ModEvent\EventTicketType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class Patrocinio extends Component
{
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

    public function mount($patrocinio_id=false)
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

        //
        if(!$this->target = Event::with(['gatewayPay'])->where('organizer_id',$this->organizerId)->where('id',$this->target_id)->first())
        {
            returnEventoDashboard('Evento não localizado','error');
        }

        // da($this->target);

        $this->sponsorship       = $this->target->sponsorship ?? false;
        $this->sponsorship_plans = $this->target->sponsorshipPlans ?? false;

        // da($this->sponsorship);

        // SE ENVIADO SLUG
        if($patrocinio_id ?? false)
        {
            // SE NAO EXISTIR TIPO
            if (!$this->sponsorship_plan = $this->sponsorship_plans->where('id',$patrocinio_id)->first())
            {
                return returnEventoDashboard("Patrocínio não localizado",'error');
            }

            $this->sponsorship_plan_id   = $this->sponsorship_plan->id;
            $this->event_id              = $this->sponsorship_plan->event_id;
            $this->sponsorship_id        = $this->sponsorship_plan->sponsorship_id;
            $this->slug                  = $this->sponsorship_plan->slug;
            $this->name                  = $this->sponsorship_plan->name;
            $this->description           = $this->sponsorship_plan->description;
            $this->price                 = toMoneyDot($this->sponsorship_plan->price);
            $this->installments_max      = $this->sponsorship_plan->installments_max;
            $this->installments_fees_pay = $this->sponsorship_plan->installments_fees_pay;
            $this->amount                = $this->sponsorship_plan->amount;
            $this->pay_pix               = $this->sponsorship_plan->pay_pix;
            $this->pay_credit            = $this->sponsorship_plan->pay_credit;
            $this->pay_boleto            = $this->sponsorship_plan->pay_boleto;
            $this->pay_boleto_date_max   = $this->sponsorship_plan->pay_boleto_date_max ? \Carbon\Carbon::parse($this->sponsorship_plan->pay_boleto_date_max)->format('Y-m-d') : \Carbon\Carbon::parse($this->target->event_datetime_finish)->format('Y-m-d');
            $this->data_finish           = $this->sponsorship_plan->data_finish ? \Carbon\Carbon::parse($this->sponsorship_plan->data_finish)->format('Y-m-d') : \Carbon\Carbon::parse($this->target->event_datetime_finish)->format('Y-m-d');

            // da($this->sponsorship_plan->sponsorship);
        }
        else
        {
            // Defaults para criação
            $this->pay_boleto_date_max = \Carbon\Carbon::parse($this->target->event_datetime_finish)->format('Y-m-d');
            $this->data_finish         = \Carbon\Carbon::parse($this->target->event_datetime_finish)->format('Y-m-d');
            $this->pay_pix             = false;
            $this->pay_credit          = false;
            $this->pay_boleto          = false;
            $this->installments_fees_pay = false;
        }

        // SIMULA DADOS
        // $this->simula();
    }

    public function render()
    {
        return view('livewire.evento.patrocinio-plano')->layout('layouts.app-pep-auth');
    }

    public function simula()
    {

    }

    public function patrocinioSubmit()
    {
        $rules = [
            'name'                  => ['required', 'string'],
            'price'                 => ['required'],
            'data_finish'           => ['required', 'date'],
            'installments_max'      => ['required', 'integer'],
            'installments_fees_pay' => ['nullable', 'boolean'],
            'pay_pix'               => ['nullable', 'boolean'],
            'pay_credit'            => ['nullable', 'boolean'],
            'pay_boleto'            => ['nullable', 'boolean'],
            'description'           => ['nullable', 'string'],
        ];

        $this->validate($rules);

        //
        if(!$this->sponsorship)
        {
            $name = 'Plano de Patrocínio';

            $this->sponsorship = $this->target->sponsorship()->create([
                'slug'        => Str::slug($this->target->event_name . ' ' . $name),
                'name'        => $name,
            ]);
        }

        try
        {
            // TRANSACTION
            DB::beginTransaction();

            // MONTA DADOS DIRETAMENTE DAS PROPRIEDADES (mesmo padrão do MetodoPagamento)
            $data = [
                'name'                  => $this->name,
                'description'           => $this->description,
                'price'                 => toMoneyInt($this->price),
                'slug'                  => Str::slug($this->sponsorship->slug . ' ' . $this->name),
                'installments_max'      => $this->installments_max,
                'installments_fees_pay' => $this->installments_fees_pay ? true : false,
                'pay_pix'               => $this->pay_pix ? true : false,
                'pay_credit'            => $this->pay_credit ? true : false,
                'pay_boleto'            => $this->pay_boleto ? true : false,
                'pay_boleto_date_max'   => $this->pay_boleto ? Carbon::create($this->pay_boleto_date_max)->format('Y-m-d 23:59:59') : null,
                'data_finish'           => Carbon::create($this->data_finish)->format('Y-m-d 23:59:59'),
                'sponsorship_id'        => $this->sponsorship->id,
            ];

            //
            if($this->sponsorship_plan = EventSponsorshipPlan::find($this->sponsorship_plan_id ?? null))
            {
                $this->sponsorship_plan->update($data);
                session()->flash('success','Plano Patrocínio Alterado');
            }
            else
            {
                $this->sponsorship_plan = $this->target->sponsorshipPlans()->create($data);
                session()->flash('success','Plano Patrocínio Cadastrado');
            }

            // TRANSACTION FIM
            DB::commit();

            return redirect()->route('dashboard-evento');
        }
        catch (\Throwable $th)
        {
            // TRANSACTION ERROR
            DB::rollBack();

            return session()->flash('error',$th->getMessage());
        }
    }

    public function patrocinioRemove($id)
    {
        try
        {
            if ($plan = EventSponsorshipPlan::with(['orders'])->find($id))
            {
                if($plan->orders->count() ?? 0)
                {
                    $plan->plan_active = false;
                    $plan->save();
                }
                else
                {
                    $plan->delete();
                }

                session()->flash('success','Plano Patrocínio removido');
            }
            else
            {
                session()->flash('error','Plano Patrocínio não localizado');
            }

            return redirect()->route('dashboard-evento');
        }
        catch (\Throwable $th)
        {
            // dd($th);
            return session()->flash('error',$th->getMessage());
        }
    }
}

