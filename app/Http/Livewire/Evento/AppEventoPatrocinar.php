<?php

namespace App\Http\Livewire\Evento;

use App\Jobs\AppEvent\NotificationAppEventCompra;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderItem;
use App\Models\AppEvent\AppEventOrderSponsorship;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\ModEvent\Event;
use App\Models\Sponsorship;
use App\Rules\NomeCompleto;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class AppEventoPatrocinar extends Component
{
    use WithFileUploads;

    //
    public $event;
    public $slug;
    public $planos;

    //
    public $patrocinio;
    public $plano_id;
    public $plano;

    //
    public $adesao;
    public $order;
    public $order_json;

    //
    public $buyer_name;
    public $buyer_segment;
    public $buyer_description;
    public $buyer_email;
    public $buyer_doc_type;
    public $buyer_doc_num;
    public $buyer_contact_name;
    public $buyer_contact_ddd;
    public $buyer_contact_num;
    public $buyer_url_logo; //='images_patrocinadores/pink/sponsorship_url_logo/bJCElpr5bd5gR768mmvFHtEphcPkqMO8jg635VGo.jpg';
    public $buyer_url_website;
    public $buyer_url_instagram;
    public $buyer_json_answers;

    //
    public $order_num;

    protected $messages = [
        '*.required' => 'Obrigatório',
    ];

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($slug=false)
    {
        // SLUG
        $this->slug = strtolower($slug);

        if($this->slug ?? false)
        {
            $this->event = Event::with(['sponsorshipPlans'])->where('event_slug', $this->slug)->first();

            if(!$this->event)
            {
                session()->flash('error','Evento não localizado');
            }
        }
    }

    public function render()
    {
        // SE SESSION PEDIDO
        if(sessionPedido())
        {
            $this->order_json = sessionPedido();
        }

        // SE PEDIDO E PENDENTE PAGAMENTO
        if(($this->order_json ?? false) && ($this->order_json['status']) && in_array($this->order_json['status'], listOrderStatusIrProPagamento()))
        {
            return view('livewire.evento.evento-pagamento-patrocinador')->layout('layouts.app-pep-home');
        }

        // SE EVENTO
        if($this->event)
        {
            // SE TIPO SELECIONADO
            if(!$this->plano)
            {
                $this->planos = [];

                // PERCORRE LOTES
                foreach ($this->event->sponsorshipPlans->sortBy('slug') ?? [] as $plano_item)
                {
                    // SE VISIVEL
                    if(!$plano_item->plan_active ?? false)
                        continue;

                    //
                    $this->planos[$plano_item->id] = $plano_item;

                    // $this->selecionarPlano($plano_item->id);
                }
            }

            return view('livewire.app-evento-patrocinar')->layout('layouts.app-pep-home');
        }

        session()->flash('error','Evento não localizado');;

        return redirect(getEventosUrl() . '/');
    }

    public function selecionarPlano($plano_id)
    {
        $this->plano_id   = $plano_id;
        $this->plano      = $this->event->sponsorshipPlans->where('id', $plano_id)->first();
        $this->patrocinio = $this->plano->sponsorship;
    }

    public function cancelarPlano()
    {
        $this->plano_id   = false;
        $this->plano      = false;
        $this->patrocinio = false;
    }

    public function updated($name, $value)
    {
        // UPLOADS LIST
        $uploads = ['buyer_url_logo'];

        // SE UPLOADS
        if (in_array($name, $uploads) && $value ?? false)
        {
            $this->validate([
                $name => ['image','max:5120'],
            ]);

            try
            {
                // Upload isolado por tenant
                $app = currentApp();
                $appId = $app->id ?? 1;
                $relativePath = 'events/' . $this->event->event_slug . '/sponsors';
                $physicalPath = "{$appId}/{$relativePath}";
                $fullPath = storage_path("app/public/{$physicalPath}");

                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0755, true);
                }

                $extension = $value->getClientOriginalExtension();
                $filename = time() . '_' . Str::random(10) . '.' . $extension;
                $value->storeAs($physicalPath, $filename, 'public');

                $this->buyer_url_logo = "{$relativePath}/{$filename}";
            }
            catch (\Throwable $th)
            {
                return session()->flash('error', $th->getMessage());
            }
        }

        if($name == 'buyer_doc_num')
        {
            $this->validarDocumento();
        }
    }

    public function validarDocumento()
    {
        $this->buyer_doc_type = false;

        // VALIDATE
        $validatedData = $this->validate([
            "buyer_doc_num" => ['required','cpf_cnpj'],
        ]);

        //
        if(strlen($this->buyer_doc_num) > 11 )
            $this->buyer_doc_type = 'cnpj';
        else
            $this->buyer_doc_type = 'cpf';

        if($sponsorship = Sponsorship::where('doc_type',$this->buyer_doc_type)->where('doc_num',$this->buyer_doc_num)->first())
        {
            $this->buyer_name          = $sponsorship->name;
            $this->buyer_segment       = $sponsorship->segment;
            $this->buyer_description   = $sponsorship->description;
            $this->buyer_email         = $sponsorship->email;
            $this->buyer_contact_name  = $sponsorship->contact_name;
            $this->buyer_contact_ddd   = $sponsorship->contact_ddd;
            $this->buyer_contact_num   = $sponsorship->contact_num;
            $this->buyer_url_logo      = $sponsorship->url_logo;
            $this->buyer_url_website   = $sponsorship->url_website;
            $this->buyer_url_instagram = $sponsorship->url_instagram;
        }
    }

    public function concluirAdesao()
    {
        // return;

        // RESET
        $this->order = false;

        // RULE
        $rules = [
            "buyer_name"          => ['required','string'],
            "buyer_segment"       => ['nullable','string'],
            "buyer_description"   => ['nullable','string'],
            "buyer_email"         => ['required','email'],
            "buyer_doc_num"       => ['required','cpf_cnpj'],
            "buyer_contact_name"  => ['required',new NomeCompleto],
            "buyer_contact_ddd"   => ['required','integer'],
            "buyer_contact_num"   => ['required','integer'],
            "buyer_url_logo"      => ['required','string'],
            "buyer_url_website"   => ['nullable','string'],
            "buyer_url_instagram" => ['nullable','string'],
        ];

        // VALIDATE
        $validatedData = $this->validate($rules);

        try
        {
            DB::beginTransaction();

            // GERAR NUMERO DO PEDIDO
            $this->order_num = "EVP." . now()->format('ymds') . "." . strtoupper(hash('adler32', $this->event->event_slug . $this->buyer_doc_num . now()->timestamp));

            // SET AGORA
            $agora = now();

            // SE NAO Sponsorship
            if($sponsorship = Sponsorship::where('doc_type',$this->buyer_doc_type)->where('doc_num',$this->buyer_doc_num)->first())
            {
                $sponsorship->update([
                    'doc_type'        => mb_strtolower($this->buyer_doc_type ?? null),
                    'doc_num'         => mb_strtolower($this->buyer_doc_num ?? null),
                    'name'            => mb_strtolower($this->buyer_name ?? null),
                    'segment'         => mb_strtolower($this->buyer_segment ?? null),
                    'description'     => mb_strtolower($this->buyer_description ?? null),
                    'email'           => mb_strtolower($this->buyer_email ?? null),
                    'contact_name'    => mb_strtolower($this->buyer_contact_name ?? null),
                    'contact_country' => 55,
                    'contact_ddd'     => $this->buyer_contact_ddd ?? null,
                    'contact_num'     => $this->buyer_contact_num ?? null,
                    'url_logo'        => $this->buyer_url_logo ?? null,
                    'url_website'     => $this->buyer_url_website ?? null,
                    'url_instagram'   => $this->buyer_url_instagram ?? null,
                ]);
            }
            else
            {
                $organizer = $this->event->organizer;

                $sponsorship = Sponsorship::create([
                    'customer_id'     => $organizer->customer_id,
                    'organizer_id'    => $organizer->id,
                    'doc_type'        => mb_strtolower($this->buyer_doc_type ?? null),
                    'doc_num'         => mb_strtolower($this->buyer_doc_num ?? null),
                    'name'            => mb_strtolower($this->buyer_name ?? null),
                    'segment'         => mb_strtolower($this->buyer_segment ?? null),
                    'description'     => mb_strtolower($this->buyer_description ?? null),
                    'email'           => mb_strtolower($this->buyer_email ?? null),
                    'contact_name'    => mb_strtolower($this->buyer_contact_name ?? null),
                    'contact_country' => 55,
                    'contact_ddd'     => $this->buyer_contact_ddd ?? null,
                    'contact_num'     => $this->buyer_contact_num ?? null,
                    'url_logo'        => $this->buyer_url_logo ?? null,
                    'url_website'     => $this->buyer_url_website ?? null,
                    'url_instagram'   => $this->buyer_url_instagram ?? null,
                ]);
            }

            // REGISTRA PEDIDO
            $orderCreate = [
                'event_id'                  => $this->event->id,
                'plan_id'                   => $this->plano->id,
                'channel_order'             => config('domains.eventos') . '/' . $this->event->event_slug,
                'channel_user_id'           => null,
                'status'                    => 'fase_pagamento',
                'order_control'             => $this->order_num,
                'order_amount'              => $this->plano->price,
                'order_amount_pay'          => $this->plano->price,
                'order_description'         => mb_strtoupper('PATROCINIO ' . $this->plano->name),
                'order_generation_datetime' => now()->format('Y-m-d H:i:s'),
                'sponsorship_id'            => $sponsorship->id,
                'buyer_name'                => mb_strtolower($this->buyer_name),
                'buyer_segment'             => mb_strtolower($this->buyer_segment),
                'buyer_description'         => mb_strtolower($this->buyer_description),
                'buyer_email'               => mb_strtolower($this->buyer_email),
                'buyer_doc_type'            => mb_strtolower($this->buyer_doc_type),
                'buyer_doc_num'             => mb_strtolower($this->buyer_doc_num),
                'buyer_contact_name'        => mb_strtolower($this->buyer_contact_name),
                'buyer_contact_country'     => 55,
                'buyer_contact_ddd'         => $this->buyer_contact_ddd,
                'buyer_contact_num'         => $this->buyer_contact_num,
                'buyer_url_logo'            => $this->buyer_url_logo,
                'buyer_url_website'         => $this->buyer_url_website,
                'buyer_url_instagram'       => $this->buyer_url_instagram,
                'buyer_json_answers'        => $this->buyer_json_answers,
            ];

            // CRIA ORDEM
            $this->order = AppEventOrderSponsorship::create($orderCreate);

            // SET ORDER JSON
            $this->order_json                    = [];
            $this->order_json['localizador']     = $this->order_num;
            $this->order_json['status']          = $this->order->status;
            $this->order_json['order_type']      = 'evento_patrocinador';
            // SANTOS - NAO USADO - BD CLEAR // $this->order_json['order_type_data'] = $this->event->toArray();
            $this->order_json['order_data']      = $this->order->toArray();

            // SAVE JSON
            $this->order->order_json = json_encode($this->order_json);
            $this->order->save();

            // CONCLUI
            DB::commit();

            // SALVA DADOS SESSION PARA PAGAMENTO
            sessionPedido($this->order_json);

            // REDIRECT PARA PAGAMENTO via RENDER
            return;

        }
        catch (\Throwable $th)
        {
            DB::rollBack();

            $this->order = false;

            session()->flash('conclusao_error',$th->getMessage());
            session()->flash('conclusao_error_sub','CÓDIGO: ' . $th->getCode());
        }
    }

    public function concluirCompra()
    {
        // RESET
        $this->order = false;

        // RULE
        $rules = [
            "comprador_nome"            => ['required'],
            "comprador_sobrenome"       => ['required'],
            "comprador_email"           => ['required', 'email'],
            "comprador_cpf"             => ['required', 'cpf'],
            "comprador_celular_ddd"     => ['required', 'ddd'],
            "comprador_celular_num"     => ['required', 'integer'],
            "comprador_nascimento_dd"   => ['required'],
            "comprador_nascimento_mm"   => ['required'],
            "comprador_nascimento_aaaa" => ['required'],
            "comprador_ingressos_qtd"   => ['required', 'integer'],
        ];

        // VALIDATE - PERGUNTAS COMPRADOR
        $questions_buyer = [];
        if($this->event_questions_buyer ?? false)
        {
            foreach ($this->event_questions_buyer ?? [] as $questions_key => $questions_item)
            {
                $arrayRule = [];

                // SE OBRIGATORIO
                $arrayRule[] = ($questions_item['input_required'] ?? false) ? 'required' : 'nullable';

                // TIPOS
                switch ($questions_item['input_type'])
                {
                    case 'text':
                        $arrayRule[] = 'string';
                        break;
                }

                //
                $rules[$questions_key]           = $arrayRule;
                $questions_buyer[$questions_key] = $questions_item;
            }
        }

        // RULE - PARTICIPANTE
        $questions_user = [];
        foreach (range(1, $this->comprador_ingressos_qtd ?? 1) as $range)
        {
            $participante_range = $this->participante_prefix . "_" . $range;

            $rules[$participante_range] = ['required', 'string'];

            // VALIDATE - PERGUNTAS PARTICIPANTE
            if($this->event_questions_item ?? false)
            {
                //
                foreach ($this->event_questions_item ?? [] as $questions_key => $questions_item)
                {
                    $arrayRule = [];

                    // SE OBRIGATORIO
                    $arrayRule[] = ($questions_item['input_required'] ?? false) ? 'required' : 'nullable';

                    // TIPOS
                    switch ($questions_item['input_type'])
                    {
                        case 'text':
                            $arrayRule[] = 'string';
                            break;
                    }

                    //
                    $rules[$participante_range . '_' . $questions_key] = $arrayRule;
                    $questions_user[$range][$questions_key]            = $questions_item;
                }
            }
        }

        // VALIDATE
        $validatedData = $this->validate($rules);

        // CHECK CAPACIDADE

        // GET TICKETS
        $tickets = AppEventOrderTicket::where('event_id',$this->event->id)->whereIn('ticket_status',ticketStatusCapacidade())->get();

        // CHECK CAPACIDADE TOTAL
        $capacidadeTotalDisp = ($this->event->sales_amount_max ?? 0) - ($tickets->count() ?? 0);
        if($capacidadeTotalDisp < 1)
        {
            $this->cancelTicketType();
            session()->flash("conclusao_error", __('event_capacidade_max'));
            session()->flash("conclusao_error_sub", __('event_capacidade_max_sub'));
            return;
        }

        // CHECK CAPACIDADE LOTE
        $capacidadeLoteDisp = ($this->ticketTypeSelected->amount ?? 0) - ($tickets->where('event_ticket_id',$this->ticketTypeSelected->id)->count());
        //
        if($capacidadeLoteDisp < 1)
        {
            $this->cancelTicketType();
            session()->flash("conclusao_error", __('event_capacidade_max_lote'));
            session()->flash("conclusao_error_sub", __('event_capacidade_max_lote_sub'));
            return;
        }

        // CHECK CAPACIDADE x INGRESSOS QTD
        if($capacidadeTotalDisp < (int) $validatedData['comprador_ingressos_qtd'] || $capacidadeLoteDisp < (int) $validatedData['comprador_ingressos_qtd'])
        {
            // $this->cancelTicketType();
            session()->flash("conclusao_error", __('event_capacidade_compra_max'));
            session()->flash("conclusao_error_sub", __('event_capacidade_compra_max_sub'));
            return;
        }

        try
        {
            // APPEND
            $this->comprador_nascimento               = Carbon::create($this->comprador_nascimento_aaaa.'-'.$this->comprador_nascimento_mm.'-'.$this->comprador_nascimento_dd);
            $validatedData['comprador_nascimento']    = $this->comprador_nascimento->format('Y-m-d');
            $validatedData['comprador_nome_completo'] = strtolower(trim($this->comprador_nome) . ' ' . trim($this->comprador_sobrenome));
            $validatedData['url_comprar_novamente']   = url('/' . $this->event->event_slug);

            DB::beginTransaction();

            // GERAR NUMERO DO PEDIDO
            $this->order_num = "EV." . now()->format('ymds') . "." . strtoupper(hash('adler32', $this->event->event_slug . $this->comprador_cpf . now()->timestamp));

            // SET AGORA
            $agora = now();

            // REGISTRA PEDIDO
            $orderCreate = [
                'event_id'                    => $this->event->id,
                'status'                      => $this->orderPrice ? 'fase_pagamento' : 'concluido',
                'order_control'               => $this->order_num,
                'order_generation_datetime'   => now()->format('Y-m-d H:i:s'),
                'payment_id'                  => null,
                'order_amount'                => $this->orderPrice ?? 0,
                'order_amount_pay'            => $this->orderPrice ?? 0,
                'buyer_name'                  => $validatedData['comprador_nome_completo'],
                'buyer_email'                 => strtolower($validatedData['comprador_email']),
                'buyer_doc_type'              => "cpf",
                'buyer_doc_num'               => $validatedData['comprador_cpf'],
                'buyer_contact_country'       => 55,
                'buyer_contact_ddd'           => (int) $validatedData['comprador_celular_ddd'],
                'buyer_contact_num'           => (int) preg_replace('/\D/', '', $validatedData['comprador_celular_num']),
                'buyer_birth_date'            => $validatedData['comprador_nascimento'],
                'buyer_json_answers'          => null,
                'channel_order'               => config('domains.eventos') . '/' . $this->event->event_slug,
                'order_items_ticket_type_id'  => $this->ticketTypeSelected->id ?? null,
                'order_items_qtd'             => (int) $validatedData['comprador_ingressos_qtd'],
                'order_items_amount'          => $this->orderPrice ?? null,
                'order_items_amount_total'    => null,
                'order_description'           => null,
                'code_promo_id'               => null,
                'code_promo_discount_amount'  => 0,
                'reservation_expiration_date' => $agora->addMinutes(30),
            ];

            // CRIA ORDEM
            $order = AppEventOrder::create($orderCreate);

            // SET ORDER JSON
            $this->order_json                    = [];
            $this->order_json['localizador']     = $this->order_num;
            $this->order_json['status']          = $order->status;
            $this->order_json['order_type']      = 'evento';
            // SANTOS - NAO USADO - BD CLEAR // $this->order_json['order_type_data'] = $this->event->toArray();
            $this->order_json['order_data']      = $order->toArray();

            // PERCORRE PARTICIPANTES
            $loopCount = 0;
            $this->order_json['order_data']['itens']   = [];
            // SANTOS BD CLEAR // $this->order_json['order_data']['tickets'] = [];
            //
            foreach (range(1,$this->comprador_ingressos_qtd ?? 1) as $compradorRange)
            {
                // INCREMENTO
                $loopCount++;

                // DESCRIPTION
                $description = strtoupper(($this->event->event_name ?? 'EVENTO') . ' ' . ($this->ticketTypeSelected->ticket_name ?? 'TICKET'));

                // SE EXISTE QUESTIONS USER ITEM
                if($questions_user[$loopCount] ?? false)
                {
                    // PERCORRE QUESTIONS
                    foreach ($questions_user[$loopCount] as $questionKey => $questionValues)
                    {
                        // MONTA VARIAVEL
                        $varName = $this->participante_prefix . "_" . $loopCount . '_' . $questionKey;

                        // SE EXISTE VAIAVEL >> SET
                        if(isset($this->$varName))
                        {
                            $questions_user[$loopCount][$questionKey] = $this->$varName ?? null;
                        }
                    }

                    // CONVERT JSON
                    $user_json_answers = json_encode($questions_user[$loopCount]);
                }

                // ITENS
                $orderItemCreate = AppEventOrderItem::create([
                    'order_id'             => $order->id,
                    'item_ticket_type_id'  => $this->ticketTypeSelected->id,
                    'item_description'     => $description,
                    'item_status'          => 'adicionado',
                    'item_amount'          => (int) $this->ticketTypeSelected->price ?? 0,
                    'user_name'            => strtolower(trim(($validatedData[$this->participante_prefix . '_' . $compradorRange]) ?? $this->comprador_nome)),
                    'user_email'           => strtolower(trim($this->comprador_email)),
                    'user_doc_type'        => $this->comprador_cpf ? 'CPF' : null,
                    'user_doc_num'         => $this->comprador_cpf ?? null,
                    'user_contact_country' => 55,
                    'user_contact_ddd'     => (int) preg_replace('/\D/', '', $this->comprador_celular_ddd),
                    'user_contact_num'     => (int) preg_replace('/\D/', '', $this->comprador_celular_num),
                    'user_json_answers'    => $user_json_answers ?? null,
                ]);

                $this->order_json['order_data']['itens'][$loopCount] = $orderItemCreate->toArray();

                // MONTA CREATE TICKET
                $orderTicketCreate = [
                    'order_id'                   => $order->id,
                    'organizer_id'               => $this->event->organizer_id,
                    'organizer_name'             => $this->event->organizer->organizer_name,
                    'event_id'                   => $this->event->id,
                    'event_name'                 => strtoupper($this->event->event_name ?? null),
                    'event_description'          => strtoupper($description),
                    'event_datetime'             => $this->event->event_datetime_start ?? null,
                    'event_ticket_id'            => $this->ticketTypeSelected->id ?? null,
                    'event_ticket_slug'          => $this->ticketTypeSelected->ticket_slug ?? null,
                    'event_ticket_name'          => strtoupper($this->ticketTypeSelected->ticket_name ?? null),
                    'event_ticket_price'         => $this->ticketTypeSelected->price ?? null,
                    'ticket_control'             => $this->order_num . '-' . $loopCount,
                    'ticket_status'              => ($this->orderPrice ?? 0) ? "reserva_temp" : 'disponivel',
                    'ticket_generation_datetime' => now()->format('Y-m-d H:i:s'),
                    'user_name'                  => strtolower(trim(($validatedData[$this->participante_prefix . '_' . $compradorRange]) ?? $this->comprador_nome)),
                    'user_email'                 => strtolower(trim($this->comprador_email)),
                    'user_doc_type'              => $this->comprador_cpf ? 'CPF' : null,
                    'user_doc_num'               => $this->comprador_cpf ?? null,
                    'user_contact_country'       => 55,
                    'user_contact_ddd'           => (int) preg_replace('/\D/', '', $this->comprador_celular_ddd),
                    'user_contact_num'           => (int) preg_replace('/\D/', '', $this->comprador_celular_num),
                    'user_json_answers'          => $user_json_answers ?? null,
                ];

                $orderTicket = AppEventOrderTicket::create($orderTicketCreate);
                // SANTOS BD CLEAR // $this->order_json['order_data']['tickets'][$loopCount] = $orderTicket->toArray();
            }

            // if($this->event->event_slug == 'pink')
            //     {
            //         // return;
            //         dd(
            //             $user_json_answers,
            //             $questions_user,
            //             $orderItemCreate->toArray(),
            //             $this->event_questions_item,
            //             $validatedData,
            //         );
            //     }

            // SE TEM VALOR A PAGAR
            if($this->orderPrice ?? false)
            {
                // SAVE JSON
                $order->order_json = json_encode($this->order_json);
                $order->save();

                // CONCLUI
                DB::commit();

                // SALVA DADOS SESSION PARA PAGAMENTO
                sessionPedido($this->order_json);

                // REDIRECT PARA PAGAMENTO via RENDER
                return;
            }
            else
            {
                // ENVIA NOTIFICAÇÃO - EMAIL
                $job = NotificationAppEventCompra::dispatch($this->order_json['order_data'], $validatedData['comprador_email'], 'noreply@proeventpay.com.br');

                // CONCLUI
                DB::commit();

                // ALERT SUCESSO
                session()->flash('conclusao_success', 'Compra realizada com sucesso');
                session()->flash('conclusao_success_sub','LOCALIZADOR: ' . $this->order['order_control']);
            }
        }
        catch (\Throwable $th)
        {
            DB::rollBack();

            $this->order = false;

            session()->flash('conclusao_error',$th->getMessage());
            session()->flash('conclusao_error_sub','CÓDIGO: ' . $th->getCode());
        }
    }

    public function calculaValor()
    {
        $this->orderPrice = ($this->ticketTypeSelected->price ?? 0) * ($this->comprador_ingressos_qtd ?? 0);
        return $this->orderPrice;
    }

    public function buscaEndereco($comprador_endereco_cep=false)
    {
        if($comprador_endereco_cep)
            $this->comprador_endereco_cep = $comprador_endereco_cep;

        if(!$this->comprador_endereco_cep)
        {
            session()->flash('error','CEP precisa ser preenchido');
            return;
        }

        $this->card_credit_endereco_enable = false;

        $buscaCep = buscarCep($this->comprador_endereco_cep);


        // if($buscaCep->error)
        // {
        //     session()->flash('error', $buscaCep->msg ?? 'Erro ao cunsutar CEP. Tente novamente');
        //     return;
        // }

        if($buscaCep->error && empty($buscaCep->cidade))
        {
            session()->flash('error', $buscaCep->msg ?? 'Erro ao cunsutar CEP. Tente novamente');
            return;
        }
        else
        {
            $this->card_credit_endereco_enable = true;
            session()->flash('info', 'Endereço não lozalizado! Informe manualmente no campo acima.');
            return;
        }

        // $this->card_credit_endereco        = $buscaCep->endereco ?? null;
        // $this->card_credit_endereco_estado = ($buscaCep->cidade ?? '--') . '/' . ($buscaCep->estado ?? '--');

        $this->card_credit_endereco = ($buscaCep->endereco ?? null) . ' - ' . ($buscaCep->cidade ?? '--') . '/' . ($buscaCep->estado ?? '--');

        return $buscaCep;
    }
}

