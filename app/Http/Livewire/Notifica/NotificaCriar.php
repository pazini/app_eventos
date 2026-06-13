<?php

namespace App\Http\Livewire\Notifica;

use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\ModEvent\Event;
use App\Models\Notificacao;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;


class NotificaCriar extends Component
{
    use WithFileUploads;

    //
    public $organizer;
    public $organizerId;
    public $target_ref;
    public $target_id;
    public $target;
    public $target_tickets;
    public $target_tickets_status;

    //
    public $envio_target_status;
    public $envio_qtd=0;
    public $envio_teste_email;
    public $envio_lista;

    //
    public $status;
    public $envio_tipo;
    public $envio_nome;
    public $envio_descricao;
    public $envio_assunto;
    public $envio_assunto_nome;
    public $envio_header;
    public $envio_header_nome;
    public $envio_body;
    public $envio_footer;
    public $programado;
    public $programado_datahora;
    public $data_envio_ini;
    public $data_envio_fim;

    //
    public $prefix_assunto;
    public $notificacao_id;
    public $notificacao_prever;
    public $notificacao;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($notificacao_id=false)
    {
        // GET
        $this->organizer   = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;
        $this->target_ref  = sessionTargetRef();
        $this->target_id   = sessionTargetId();

        //
        $this->notificacao_id = $notificacao_id;

        // SE ORGANIZER / TARGET_ID
        if(!$this->organizerId || !$this->target_id)
            return redirect()->route('dashboard');

        // LIMPA ORDER ID
        sessionOrderIdClear();
        //
        if(!$this->target = Event::where('organizer_id',$this->organizerId)->where('id',$this->target_id)->first())
        {
            return returnEventoDashboard('Evento não localizado','error');
        }

        if(!$this->target_tickets = AppEventOrderTicket::where('event_id',$this->target->id)->get(["ticket_control","ticket_status","user_name","user_email","user_doc_type","user_doc_num","user_contact_country","user_contact_ddd","user_contact_num","user_birth_date","user_json_answers"]))
        {
            return returnEventoDashboard('Evento não possui pedidos concluidos','error');
        }

        //
        foreach ($this->target_tickets->groupBy('ticket_status')->toArray() ?? [] as $ticket_Status => $ticket_values)
        {
            $this->$ticket_Status = false;
            //
            $this->envio_target_status[$ticket_Status]   = false;
            $this->target_tickets_status[$ticket_Status] = count($ticket_values);
        }

        // DEFINE PREFIX
        $this->prefix_assunto = mb_strtoupper('[' . $this->target->event_name . '] ');

        //
        if($this->notificacao_id ?? false)
        {
            $this->notificacao = Notificacao::find($this->notificacao_id);
            //
            $this->envio_nome    = $this->notificacao->envio_nome;
            $this->envio_assunto = str_replace($this->prefix_assunto,'', $this->notificacao->envio_assunto);
            $this->envio_body    = $this->notificacao->envio_body;

            // dd(
            //     $this->notificacao->notificacaoEnvio->where('status','pendente')->toArray(),
            //     $this->notificacao_id,
            //     $this->notificacao->toArray(),
            // );
        }
    }

    public function render()
    {
        return view('livewire.notifica.notifica-criar')->layout('layouts.app-pep-auth');
    }

    public function criar($envio_teste=false)
    {
        $dataValidate = $this->validate([
            'envio_nome'    => ['required','string'],
            'envio_assunto' => ['required','string'],
            'envio_body'    => ['required','string'],
        ]);

        try
        {
            //
            if($this->envio_qtd < 1)
            {
                return session()->flash('error','É necessário selecionar pelo menos um status das vendas');
            }

            //
            $situacaoEnvio     = [];
            $this->envio_lista = [];

            //
            foreach ($this->envio_target_status as $situacao => $boolean)
            {
                if($boolean ?? false)
                {
                    $situacaoEnvio[] = mb_strtoupper(__($situacao));

                    $tickets = AppEventOrderTicket::where('event_id',$this->target->id)->where('ticket_status',$situacao)->get(['ticket_status','user_name','user_email']);

                    foreach ($tickets ?? [] as $ticket_value)
                    {
                        $this->envio_lista[] = [
                            'destino'      => mb_strtolower($ticket_value->user_email),
                            'destino_nome' => mb_strtolower($ticket_value->user_name),
                        ];
                    }
                }
            }

            //
            $dataValidate = array_merge($dataValidate, [
                'status'           => 'pendente',
                'target_ref'       => $this->target_ref,
                'target_id'        => $this->target_id,
                'envio_descricao'  => $situacaoEnvio ? 'ENVIADO PARA STATUS: ' . (implode(',', $situacaoEnvio)) : 'ND',
                'envio_header'     => mb_strtoupper($this->envio_assunto),
                'envio_assunto'    => mb_strtoupper($this->prefix_assunto . $this->envio_assunto),
                'envio_url_logo'   => $this->target->url_image_logo ? asset($this->target->url_image_logo) : null,
                'envio_color_bg'   => $this->target->color_primary ?? null,
            ]);

            DB::beginTransaction();

            //
            if(($this->notificacao_id ?? false) && $notificacao = Notificacao::find($this->notificacao_id))
            {
                $notificacao->update($dataValidate);
                $msg = 'ATUALIZADA COM SUCESSO';
            }
            else
            {
                // CREATE
                $notificacao = Notificacao::create($dataValidate);
                $msg = 'CRIADA COM SUCESSO';
            }

            //
            if($notificacaoEnvioPendente = $notificacao->notificacaoEnvio->where('status','pendente'))
            {
                foreach ($notificacaoEnvioPendente as $envio)
                {
                    $envio->delete();
                }
            }

            //
            if (in_array(auth()->user()->email,['proeventpay@gmail.com']))
            {
                // PERCORRE OS ENVIOS
                $notificacaoEnvio[] = $notificacao->notificacaoEnvio()->create([
                    'status'       => 'pendente',
                    'tipo'         => 'email',
                    'destino'      => 'proeventpay@gmail.com',
                    'destino_nome' => 'Santos',
                    'assunto'      => 'INICIO - ' . $notificacao->envio_assunto,
                    'header'       => $notificacao->envio_header,
                    'body'         => $notificacao->envio_body,
                    'footer'       => $notificacao->envio_footer,
                    'url_logo'     => $notificacao->envio_url_logo,
                    'color_bg'     => $notificacao->envio_color_bg,
                ]);
            }


            foreach ($this->envio_lista ?? [] as $destino_item)
            {
                //
                if(empty($destino_item['destino']))
                    continue;

                //
                $notificacaoEnvio[] = $notificacao->notificacaoEnvio()->create([
                    'status'       => 'pendente',
                    'tipo'         => 'email',
                    'destino'      => $destino_item['destino'],
                    'destino_nome' => $destino_item['destino_nome'],
                    'assunto'      => $notificacao->envio_assunto,
                    'header'       => $notificacao->envio_header,
                    'body'         => $notificacao->envio_body,
                    'footer'       => $notificacao->envio_footer,
                    'url_logo'     => $notificacao->envio_url_logo,
                    'color_bg'     => $notificacao->envio_color_bg,
                ]);
            }

            //

            if (in_array(auth()->user()->email,['proeventpay@gmail.com']))
            {
                $notificacaoEnvio[] = $notificacao->notificacaoEnvio()->create([
                    'status'       => 'pendente',
                    'tipo'         => 'email',
                    'destino'      => 'proeventpay@gmail.com',
                    'destino_nome' => 'Santos',
                    'assunto'      => 'TERMINO - ' . $notificacao->envio_assunto,
                    'header'       => $notificacao->envio_header,
                    'body'         => $notificacao->envio_body,
                    'footer'       => $notificacao->envio_footer,
                    'url_logo'     => $notificacao->envio_url_logo,
                    'color_bg'     => $notificacao->envio_color_bg,
                ]);
            }

            DB::commit();

            session()->flash('success','NOTIFICAÇÃO ' . $msg);

            return redirect()->route('notifica-exibir',['notificacao_id' => $notificacao->id]);
        }
        catch (\Throwable $th)
        {
            return session()->flash('error',$th->getMessage());
        }
    }


    public function remover($envio_teste=false)
    {
        $dataValidate = $this->validate([
            'envio_nome'    => ['required','string'],
            'envio_assunto' => ['required','string'],
            'envio_body'    => ['required','string'],
        ]);

        try
        {
            //
            if($this->envio_qtd < 1)
            {
                return session()->flash('error','É necessário selecionar pelo menos um status das vendas');
            }

            //
            $situacaoEnvio     = [];
            $this->envio_lista = [];

            //
            foreach ($this->envio_target_status as $situacao => $boolean)
            {
                if($boolean)
                {
                    $situacaoEnvio[] = mb_strtoupper(__($situacao));

                    $tickets = AppEventOrderTicket::where('event_id',$this->target->id)->where('ticket_status',$situacao)->get(['ticket_status','user_name','user_email']);

                    foreach ($tickets ?? [] as $ticket_value)
                    {
                        $this->envio_lista[] = [
                            'destino'      => mb_strtolower($ticket_value->user_email),
                            'destino_nome' => mb_strtolower($ticket_value->user_name),
                        ];
                    }
                }
            }

            //
            $dataValidate = array_merge($dataValidate, [
                'target_ref'       => $this->target_ref,
                'target_id'        => $this->target_id,
                'envio_descricao'  => $situacaoEnvio ? 'ENVIADO PARA STATUS: ' . (implode(',', $situacaoEnvio)) : 'ND',
                'envio_header'     => mb_strtoupper($this->envio_assunto),
                'envio_assunto'    => mb_strtoupper('[' . $this->target->event_name . '] ' . $this->envio_assunto),
                'envio_url_logo'   => $this->target->url_image_logo ? asset($this->target->url_image_logo) : null,
                'envio_color_bg'   => $this->target->color_primary ?? null,
            ]);

            DB::beginTransaction();

            // CREATE
            $notificacao = Notificacao::create($dataValidate);

            // PERCORRE OS ENVIOS

            $notificacaoEnvio[] = $notificacao->notificacaoEnvio()->create([
                'status'       => 'pendente',
                'tipo'         => 'email',
                'destino'      => 'proeventpay@gmail.com',
                'destino_nome' => 'Santos',
                'assunto'      => 'INI - ' . $notificacao->envio_assunto,
                'header'       => $notificacao->envio_header,
                'body'         => $notificacao->envio_body,
                'footer'       => $notificacao->envio_footer,
                'url_logo'     => $notificacao->envio_url_logo,
                'color_bg'     => $notificacao->envio_color_bg,
            ]);

            foreach ($this->envio_lista ?? [] as $destino_item)
            {
                //
                if(empty($destino_item['destino']))
                    continue;

                //
                $notificacaoEnvio[] = $notificacao->notificacaoEnvio()->create([
                    'status'       => 'pendente',
                    'tipo'         => 'email',
                    'destino'      => $destino_item['destino'],
                    'destino_nome' => $destino_item['destino_nome'],
                    'assunto'      => $notificacao->envio_assunto,
                    'header'       => $notificacao->envio_header,
                    'body'         => $notificacao->envio_body,
                    'footer'       => $notificacao->envio_footer,
                    'url_logo'     => $notificacao->envio_url_logo,
                    'color_bg'     => $notificacao->envio_color_bg,
                ]);
            }

            $notificacaoEnvio[] = $notificacao->notificacaoEnvio()->create([
                'status'       => 'pendente',
                'tipo'         => 'email',
                'destino'      => 'proeventpay@gmail.com',
                'destino_nome' => 'Santos',
                'assunto'      => 'FIM - ' . $notificacao->envio_assunto,
                'header'       => $notificacao->envio_header,
                'body'         => $notificacao->envio_body,
                'footer'       => $notificacao->envio_footer,
                'url_logo'     => $notificacao->envio_url_logo,
                'color_bg'     => $notificacao->envio_color_bg,
            ]);

            DB::commit();

            session()->flash('success','Notificação cadastrada');

            return redirect()->route('notifica-exibir',['notificacao_id' => $notificacao->id]);
        }
        catch (\Throwable $th)
        {
            dd(
                $th,
            );
        }
    }

    public function updated($name, $value)
    {
        // STATUS LIST
        $target_status = array_keys($this->envio_target_status);

        // SE TARGET STATUS
        if (in_array($name, $target_status))
        {
            $this->envio_target_status[$name] = $value;

            if($value ?? false)
                $this->envio_qtd += $this->target_tickets_status[$name] ?? 0;
            else
                $this->envio_qtd -= $this->target_tickets_status[$name] ?? 0;

            if($this->envio_qtd < 1)
                $this->envio_qtd = 0;
        }

        // dd(
        //     $name,
        //     $value,
        //     $this->envio_qtd,
        //     $this->envio_target_status,
        //     $this->target_tickets_status[$name] ?? 0,
        // );
    }
}


