<?php

namespace App\Http\Livewire\Notifica;

use App\Http\Controllers\Notificacao\MailController;
use App\Models\ModEvent\Event;
use App\Models\Notificacao;
use Livewire\Component;
use Livewire\WithFileUploads;

class NotificaDashboard extends Component
{
    use WithFileUploads;

    //
    public $organizer;
    public $organizerId;
    public $target;
    public $target_id;

    //
    public $notificacao;
    public $notificacao_pendente;
    public $notificacoes;
    public $notificacao_id;

    //
    public $envios;
    public $enviosCount;
    public $processar;
    public $envios_count=0;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($notificacao_id=false, $event_id=null)
    {
        // Resolução via UUID na URL
        if ($event_id) {
            $org   = sessionOrganizer();
            $orgId = $org->id ?? null;
            $query = \App\Models\ModEvent\Event::where('id', $event_id);
            if ($orgId) $query->where('organizer_id', $orgId);
            $ev = $query->first();
            if (! $ev) {
                session()->flash('error', 'Evento não encontrado ou sem permissão.');
                return redirect()->route('dashboard-eventos');
            }
            if (! $orgId) sessionOrganizer($ev->organizer_id);
            sessionTargetRef('evento');
            sessionTargetId($ev->id);
            sessionOrderIdClear();
        }

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
        $this->notificacao_id = $notificacao_id;
    }

    public function render()
    {
        $this->target = Event::where('organizer_id',$this->organizerId)->where('id',$this->target_id)->first();

        $this->notificacao  = false;
        $this->notificacoes = false;

        if($this->notificacao_id ?? false)
        {
            $this->notificacao = Notificacao::with(['notificacaoEnvio'])->find($this->notificacao_id);

            if($this->notificacao->notificacaoEnvio->whereNull('datahora')->count() ?? false)
            {
                $this->notificacao_pendente = true;
            }
            else
            {
                $this->notificacao_pendente = false;
            }
        }
        else
        {
            $this->notificacoes = Notificacao::with(['notificacaoEnvio'])->where('target_ref','evento')->where('target_id',$this->target->id)->get();
        }

        return view('livewire.notifica.notifica-dashboard')->layout('layouts.app-pep-auth');
    }

    public function processarEnvio()
    {
        try
        {
            $this->processar = true;

            $this->envios = $this->notificacao->notificacaoEnvio->whereIn('status',['cadastrado','aguardando','pendente']);

            $this->enviosCount = $this->envios->count();

            if($this->enviosCount ?? false)
            {
                //
                if($this->notificacao->status != 'em_processamento')
                {
                    $this->notificacao->status = 'em_processamento';
                    $this->notificacao->save();
                }

                $mail = new MailController();

                $count = 0;

                foreach ($this->envios as $notificacaoEnvioKey => $notificacaoEnvio)
                {
                    $count++;

                    if($count > 3)
                        break;

                    $notificacaoEnvio->status = 'iniciado';
                    $notificacaoEnvio->save();

                    $r = $mail->enviarEmail(
                        tipo:     'enviar',
                        to:       $notificacaoEnvio->destino,
                        subject:  $notificacaoEnvio->assunto,
                        urlLogo:  $notificacaoEnvio->url_logo,
                        colorBg:  $notificacaoEnvio->color_bg,
                        header:   $notificacaoEnvio->header,
                        body:     $notificacaoEnvio->body,
                        dispatch: false
                    );

                    $notificacaoEnvio->status   = 'ok';
                    $notificacaoEnvio->datahora = now();
                    $notificacaoEnvio->save();
                }
            }
            else
            {
                $this->processar = false;
                $this->notificacao->status = 'concluido';
                $this->notificacao->save();
                //
                return session()->flash('success','Nenhum envio pendente');
            }
        }
        catch (\Throwable $th)
        {
            return session()->flash('error',$th->getMessage());
        }
    }
}

