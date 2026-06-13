<?php

namespace App\Http\Livewire\Evento;

use App\Models\CustomerOrganizationPlace;
use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventTicketType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CampoAdicional extends Component
{
    //
    public $organizer;
    public $organizerId;
    public $target;
    public $target_id;
    public $target_ref = 'app_event';
    public $ticketsTypes;

    //
    public $questions_fields;
    public $questions_fields_order = [];
    public $questions_fields_count;
    public $questions_user_json;

    //
    public $novo_campo;
    public $alterar_campo;

    //
    public $input_ref;
    public $input_label;
    public $input_placeholder;

    //
    public $input_opcao_key;
    public $input_opcao_value;

    //
    public $input_type;
    public $input_name;
    public $input_value;
    public $input_type_options=[];
    public $input_required;
    public $input_filter;
    public $input_hidden_lotes=[];

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        // GET
        $this->organizer   = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;
        $this->target_id   = sessionTargetId();

        // SE ORGANIZER / TARGET_ID
        if(!$this->organizerId || !$this->target_id)
            return redirect()->route('dashboard');
    }

    public function getTarget($call=false)
    {
        //
        if(!$this->target = Event::where('organizer_id',$this->organizerId)->where('id',$this->target_id)->first())
        {
            returnEventoDashboard('Evento não localizado','error');
        }

        // GET QUESTIONS
        $this->questions_user_json = json_decode($this->target->questions_user_json ?? '{}', true);
        $this->questions_fields    = $this->questions_user_json['campos'] ?? [];

        // SE NAO TIVER SIDO CARREGADO
        if(!$this->input_hidden_lotes)
        {
            $this->input_hidden_lotes  = $this->questions_user_json['input_hidden_lotes'] ?? [];

            // LOTES
            $this->ticketsTypes = [];

            //
            foreach (($this->target->ticketsTypes ?? false) ?? [] as $ticketsTypeValues)
            {
                $var        = 'hidden_' . Str::slug($ticketsTypeValues->ticket_slug,'_');
                $hidden     = in_array($ticketsTypeValues->id, $this->input_hidden_lotes ?? []) ?? false;
                $this->$var = $hidden;

                $this->ticketsTypes[$ticketsTypeValues->id] = [
                    'id'          => $ticketsTypeValues->id,
                    'var'         => $var,
                    'ticket_name' => $ticketsTypeValues->ticket_name,
                    'ticket_slug' => $ticketsTypeValues->ticket_slug,
                    'hidden'      => $hidden,
                ];
            }
        }

        // RESET
        $ordem_campos = [];
        $this->questions_fields_count = 0;

        //
        if($this->questions_fields ?? false)
        {
            //
            foreach (collect($this->questions_fields)->sortBy('input_order') as $question_key => $question_values)
            {
                $this->questions_fields_count++;
                //
                $question_values['id']                         = $question_key;
                $question_values['count']                      = $this->questions_fields_count;
                $ordem_campos[$question_values['input_order']] = $question_values;
            }

            foreach ($ordem_campos as $campo_values)
            {
                $this->questions_fields_order[$campo_values['count']] = $campo_values;
            }

            // session()->flash('success','GET TARGET: ' . now()->format('Hms') . ' - ' . $call);
        }
    }

    public function render()
    {
        $this->getTarget('render');
        return view('livewire.evento.campo-adicional')->layout('layouts.app-pep-auth');
    }

    public function updated($name, $value)
    {
        // dd($name,$value);

        //
        if($name == 'novo_campo')
        {
            $this->alterar_campo      = false;
            $this->input_label        = '';
            $this->input_placeholder  = '';
            $this->input_name         = '';
            $this->input_value        = '';
            $this->input_type         = '';
            $this->input_type_options = [];
            $this->input_required     = true;
            $this->input_filter       = false;
        }

        //
        if($name == 'alterar_campo')
        {
            $this->novo_campo         = false;
            $this->input_ref          = $value;
            $this->input_label        = $this->questions_fields[$value]['input_label'] ?? '';
            $this->input_placeholder  = $this->questions_fields[$value]['input_placeholder'] ?? '';
            $this->input_name         = $this->questions_fields[$value]['input_name'] ?? '';
            $this->input_value        = $this->questions_fields[$value]['input_value'] ?? '';
            $this->input_type         = $this->questions_fields[$value]['input_type'] ?? '';
            $this->input_type_options = $this->questions_fields[$value]['input_type_options'] ?? '';
            $this->input_required     = $this->questions_fields[$value]['input_required'] ?? '';
            $this->input_filter       = $this->questions_fields[$value]['input_filter'] ?? '';
            $this->input_hidden_lotes = $this->questions_fields[$value]['input_hidden_lotes'] ?? [];

            // PERCORRE INPUTS LOTES HIDDEN
            foreach ($this->input_hidden_lotes as $lote_id)
            {
                if($this->ticketsTypes[$lote_id] ?? false)
                {
                    $var = $this->ticketsTypes[$lote_id]['var'];
                    $this->$var = 1;
                    //
                    $this->ticketsTypes[$lote_id]['hidden'] = true;
                    $this->input_hidden_lotes[$lote_id] = $lote_id;

                    // dd(
                    //     $this->$var,
                    //     $this->input_hidden_lotes,
                    //     $this->ticketsTypes[$lote_id],
                    // );
                }
            }
        }
    }

    public function adicionarOpcao()
    {
        // VALIDATE
        $validateData = $this->validate([
            'input_opcao_value' => ['required', 'string'],
        ]);

        $this->input_type_options['item_' . (count($this->input_type_options) + 1)] = $this->input_opcao_value;
        $this->input_opcao_value = '';
    }

    public function alterarOpcao($input_opcao_key=false)
    {
        // VALIDATE
        $validateData = $this->validate([
            'input_opcao_value' => ['required', 'string'],
        ]);

        $this->input_type_options[$input_opcao_key] = $this->input_opcao_value;
        $this->input_opcao_value = '';

    }

    public function removerOpcao($input_opcao_key=false)
    {
        unset($this->input_type_options[$input_opcao_key]);
        $this->input_opcao_value = '';
    }

    public function editarOpcao($input_opcao_key=false)
    {
        if($input_opcao_key ?? false)
        {
            $this->input_opcao_key   = $input_opcao_key;
            $this->input_opcao_value = $this->input_type_options[$input_opcao_key];
        }
        else
        {
            $this->input_opcao_key   = '';
            $this->input_opcao_value = '';
        }
    }

    public function loteHiddenView($lote_id=false,$resetBd=false)
    {
        if ($lote = $this->ticketsTypes[$lote_id] ?? false)
        {
            $var = $lote['var'];

            if($this->input_hidden_lotes[$lote_id] ?? false)
            {
                unset($this->input_hidden_lotes[$lote_id]);
                $this->$var = 0;
            }
            else
            {
                $this->input_hidden_lotes[$lote_id] = $lote_id;
                $this->$var = 1;
            }
        }

        // dd(
        //     $var,
        //     $this->$var,
        //     $lote,
        //     $this->input_hidden_lotes,
        // );
    }

    public function submit($input_ref=false)
    {
        // return;

        // VALIDATE
        $validateData = $this->validate([
            'input_label'       => ['required', 'string'],
            'input_placeholder' => ['nullable', 'string'],
            'input_value'       => ['nullable', 'string'],
            'input_type'        => ['required', 'string'],
            'input_required'    => ['required', 'boolean'],
            'input_filter'      => ['required', 'boolean'],
        ]);

        try
        {
            //
            if($this->input_type == 'select')
            {
                if(empty($this->input_type_options))
                    return session()->flash('error','Campo tipo Seleção precisa possuir opções cadastradas');

                $validateData['input_type_options'] = $this->input_type_options;
            }
            else
            {
                if($this->input_filter)
                    return session()->flash('error','Somente Campos tipo Seleção podem ser filtros');

                $validateData['input_filter'] = false;
            }

            // SET NAME
            $validateData['input_name'] = Str::slug($validateData['input_label'],'_');

            //
            if($input_ref ?? false)
            {
                $campo_ref                   = $input_ref;
                $validateData['input_order'] = $this->questions_user_json['campos'][$campo_ref]['input_order'];
            }
            else
            {
                $campo_ref                              = now()->format('ymdHis');
                $this->questions_user_json['order_max'] = ($this->questions_user_json['order_max'] ?? 0) + 1;
                $validateData['input_order']            = $this->questions_user_json['order_max'];
            }

            //
            $validateData['input_hidden_lotes'] = $this->input_hidden_lotes ?? [];

            //
            $this->questions_user_json['campos'][$campo_ref] = $validateData;

            //
            $this->target->questions_user_json = json_encode($this->questions_user_json);
            $this->target->save();

            //
            if ($input_ref ?? false)
            {
                session()->flash('success','Campo Atualizado');
            }
            else
            {
                session()->flash('success','Campo Adicionado');
            }

            //
            $this->novo_campo    = false;
            $this->alterar_campo = false;

            return;
        }
        catch (\Throwable $th)
        {
            // dd($th);
            return session()->flash('error',$th->getMessage());
        }
    }

    public function remover($input_ref)
    {
        try
        {
            //
            unset($this->questions_user_json['campos'][$input_ref]);

            // SE NAO EXISTEM MAIS CAMPOS
            if(!count($this->questions_user_json['campos'] ?? []))
            {
                $this->questions_user_json = null;
            }

            //
            $this->target->questions_user_json = json_encode($this->questions_user_json ?? []);
            $this->target->save();

            //
            session()->flash('success','Campo Removido');

            //
            $this->novo_campo    = false;
            $this->alterar_campo = false;

            return redirect()->route('evento-campo-adicional');
        }
        catch (\Throwable $th)
        {
            // dd($th);
            return session()->flash('error',$th->getMessage());
        }
    }

    public function alterar_ordem(int $campo_origem, $direcao)
    {
        try
        {
            if($direcao == 'voltar')
                $campo_destino = $campo_origem - 1;

            if($direcao == 'avancar')
                $campo_destino = $campo_origem + 1;

            // PEGA INPUT_ORDER
            $input_order_origem  = $this->questions_fields[$this->questions_fields_order[$campo_origem]['id']]['input_order'];
            $input_order_destino = $this->questions_fields[$this->questions_fields_order[$campo_destino]['id']]['input_order'];

            // TROCA INPUT_ORDER
            $this->questions_fields[$this->questions_fields_order[$campo_origem]['id']]['input_order']  = $input_order_destino;
            $this->questions_fields[$this->questions_fields_order[$campo_destino]['id']]['input_order'] = $input_order_origem;

            // ENCODE QUESTIONS >> SAVE
            $this->questions_user_json['campos'] = $this->questions_fields;
            $this->target->questions_user_json   = json_encode($this->questions_user_json);
            $this->target->save();

            //
            session()->flash('success','Ordem dos Campos Alterada');

            // dd(
            //     $campo_origem,
            //     $this->questions_fields_order[$campo_origem],
            //     $this->questions_fields[$this->questions_fields_order[$campo_origem]['id']]['input_order'],
            //     $campo_destino,
            //     $this->questions_fields_order[$campo_destino],
            //     $this->questions_fields[$this->questions_fields_order[$campo_destino]['id']]['input_order'],
            //     $direcao,
            // );

            return;
        }
        catch (\Throwable $th)
        {
            // dd($th);
            return session()->flash('error',$th->getMessage());
        }
    }
}

