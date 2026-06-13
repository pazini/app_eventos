<?php

namespace App\Http\Livewire\Evento;

use App\Models\AppEvent\AppEvent;
use App\Models\CustomerOrganizationPlace;
use App\Models\ModEvent\Event;
use App\Models\RefAppEventCategory;
use App\Models\RefAppType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class AlteraEvento extends Component
{
    //
    public $organizer;
    public $organizerId;
    public $target_ref='app_event';

    //
    public $debug;
    public $target_id;
    public $target;

    //
    public $myPlaces;
    public $place;

    public $listStates;
    public $listType;
    public $listCategory;

    //
    public $type;
    public $category;
    public $active;
    public $event_name;
    public $event_description;
    public $event_about;
    public $notification_text_1;
    public $notification_text_2;
    public $event_datetime_start;
    public $event_datetime_finish;
    public $sales_amount_max;
    public $sales_label;
    public $sales_btn;
    public $event_tickets_nomenclature;
    public $organization_id;
    public $place_id;
    public $place_name;
    public $place_description;
    public $address;
    public $address_number;
    public $address_complement;
    public $address_reference;
    public $city_neighborhood;
    public $city;
    public $state;
    public $zip_code;
    public $iframe_google_maps;
    public $google_maps_iframe;
    public $cod_latitude = 0;
    public $cod_longitude = 0;
    //
    public $novo_local;

    //
    public $sales_label_item;
    public $sales_label_item_tipos = [];

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

        // LIMPA ORDER ID
        sessionOrderIdClear();


        if(!$this->target = Event::with(['page','place'])->where('organizer_id',$this->organizerId)->where('id',$this->target_id)->first())
        {
            return returnEventoDashboard('Evento não localizado','error');
        }

        $this->type                       = $this->target->type;
        $this->category                   = $this->target->category;
        $this->active                     = $this->target->active;
        $this->event_name                 = $this->target->event_name;
        $this->event_about                = $this->target->event_about;
        $this->notification_text_1        = $this->target->notification_text_1;
        $this->notification_text_2        = $this->target->notification_text_2;
        $this->event_description          = $this->target->event_description;
        $this->event_datetime_start       = $this->target->event_datetime_start ? \Carbon\Carbon::parse($this->target->event_datetime_start)->format('Y-m-d H:i:s') : null;
        $this->event_datetime_finish      = $this->target->event_datetime_finish ? \Carbon\Carbon::parse($this->target->event_datetime_finish)->format('Y-m-d H:i:s') : null;
        $this->sales_amount_max           = $this->target->sales_amount_max;
        $this->sales_label                = $this->target->sales_label;
        $this->sales_btn                  = $this->target->sales_btn;
        $this->event_tickets_nomenclature = $this->target->event_tickets_nomenclature;
        $this->place_id                   = $this->target->place_id;
        $this->place_name                 = $this->target->place->place_name ?? null;
        $this->place_description          = $this->target->place->place_description ?? null;
        $this->address                    = $this->target->address;
        $this->address_number             = $this->target->address_number;
        $this->address_complement         = $this->target->address_complement;
        $this->address_reference          = $this->target->address_reference;
        $this->city_neighborhood          = $this->target->city_neighborhood;
        $this->city                       = $this->target->city;
        $this->state                      = $this->target->state;
        $this->zip_code                   = $this->target->zip_code;
        $this->google_maps_iframe         = $this->target->google_maps_iframe;
        $this->sales_label_item           = strtolower($this->target->sales_label_item);

        //
        $this->myPlaces     = CustomerOrganizationPlace::where('organization_id',$this->organizer->organization_id)->get();
        $this->listStates   = listStates();
        $this->listType     = RefAppType::all();
        $this->listCategory = RefAppEventCategory::all();

        //
        $this->sales_label_item_tipos['casal'] = 'casais';
        $this->sales_label_item_tipos['participante'] = 'participantes';
        $this->sales_label_item_tipos['voluntário'] = 'voluntários';
        $this->sales_label_item_tipos['comprador'] = 'compradores';
        $this->sales_label_item_tipos['compra'] = 'compras';
    }

    public function render()
    {
        // Recarrega myPlaces para garantir que esteja disponível em todas as renderizações
        if ($this->organizer && $this->organizer->organization_id) {
            $this->myPlaces = CustomerOrganizationPlace::where('organization_id', $this->organizer->organization_id)->get();
        } else {
            $this->myPlaces = collect([]);
        }

        return view('livewire.evento.altera-evento')->layout('layouts.app-pep-auth');
    }

    public function updatedPlaceId()
    {
        if($this->place_id == 'novo-local')
        {
            $this->novo_local          = true;
            $this->place_name          = '';
            $this->place_description   = '';
            $this->address             = '';
            $this->address_number      = '';
            $this->address_complement  = '';
            $this->address_reference   = '';
            $this->city_neighborhood   = '';
            $this->city                = '';
            $this->state               = '';
            $this->zip_code            = '';
            $this->google_maps_iframe  = '';
        }
        elseif($place = $this->myPlaces->find($this->place_id))
        {
            $this->place_id = $place->id;
            //
            foreach ($place->toArray() as $loopKey => $loopValue)
            {
                if(in_array($loopKey ,['id']))
                    continue;

                if($loopKey == 'zip_code')
                    $loopValue = putMask($loopValue);

                $this->$loopKey = $loopValue;
            }
            $this->novo_local = false;
        }
        else
        {
            $this->novo_local = false;
            $this->address                    = $this->target->address;
            $this->address_number             = $this->target->address_number;
            $this->address_complement         = $this->target->address_complement;
            $this->address_reference          = $this->target->address_reference;
            $this->city_neighborhood          = $this->target->city_neighborhood;
            $this->city                       = $this->target->city;
            $this->state                      = $this->target->state;
            $this->zip_code                   = $this->target->zip_code;
            $this->google_maps_iframe         = $this->target->google_maps_iframe;
        }
    }

    public function buscarEndereco()
    {
        $this->validate([
            'zip_code' => ['required'],
        ]);

        $busca = buscarCep($this->zip_code);
        //
        if($busca->error ?? false)
            return session()->flash('errorBuscaCep', $busca->msg);

        $this->address           = $busca->endereco;
        $this->city_neighborhood = $busca->bairro;
        $this->city              = $busca->cidade;
        $this->state             = strtolower($busca->estado);
        $this->zip_code          = $busca->cep;
    }

    public function alterarEvento()
    {
        // return;

        $rules = [
            'type'                       => ['required', 'string'],
            'category'                   => ['required', 'string'],
            'event_name'                 => ['required', 'string'],
            'active'                     => ['nullable'],
            'event_description'          => ['nullable', 'string'],
            'event_about'                => ['nullable', 'string'],
            'notification_text_1'        => ['nullable', 'string'],
            'notification_text_2'        => ['nullable', 'string'],
            'sales_amount_max'           => ['required', 'integer'],
            'sales_label'                => ['required', 'string'],
            'sales_btn'                  => ['required', 'string'],
            'event_tickets_nomenclature' => ['required', 'string'],
            'address'                    => ['required', 'string'],
            'address_number'             => ['required', 'string'],
            'address_complement'         => ['nullable', 'string'],
            'address_reference'          => ['nullable', 'string'],
            'city_neighborhood'          => ['required', 'string'],
            'city'                       => ['required', 'string'],
            'state'                      => ['required', 'string'],
            'zip_code'                   => ['required'],
            'google_maps_iframe'         => ['nullable', 'string'],
            'event_datetime_start'       => ['required','date','before:event_datetime_finish'],
            'event_datetime_finish'      => ['required','date','after:event_datetime_start'],
            //
            'cod_latitude'               => ['nullable'],
            'cod_longitude'              => ['nullable'],
            //
            'sales_label_item'           => ['required'],
        ];

        if($this->place_id == 'novo-local')
        {
            $rules = array_merge($rules, [
                'place_name'                 => ['required', 'string'],
                'place_description'          => ['nullable', 'string'],
            ]);
        }

        $validateData = $this->validate($rules);

        // EVENT
        $event = new Event();

        // SET SLUG
        $slugLoop = 0;
        //
        while (true)
        {
            $event_slug = Str::slug($this->event_name . '-' . $this->target->created_at->timestamp);

            if ($event->where('event_slug', $event_slug)->whereNot('event_slug',$this->target->event_slug)->count())
            {
                $slugLoop++;
                continue;
            }

            break;
        }

        // POR SEGURANÇA
        if($slugCount = $event->where('event_slug',$event_slug)->whereNot('event_slug',$this->target->event_slug)->count())
        {
            $event_slug = Str::slug($this->event_name . '-' . ($this->target->created_at->timestamp + $slugCount));
        }

        // APPEND
        $validateData['status']             = 'criado';
        $validateData['event_slug']         = $event_slug;
        $validateData['referer_url']        = config('domains.eventos');
        $validateData['place_id']           = null;
        $validateData['customer_id']        = $this->organizer->customer_id;
        $validateData['organizer_id']       = $this->organizer->id;
        $validateData['organization_id']    = $this->organizer->organization_id;
        $validateData['iframe_google_maps'] = $this->google_maps_iframe;
        $validateData['google_maps_iframe'] = $this->google_maps_iframe;

        //
        $validateData['sales_label_item']          = $this->sales_label_item;
        $validateData['sales_label_item_multiple'] = $this->sales_label_item_tipos[$this->sales_label_item];

        try
        {
            // TRANSACTION
            DB::beginTransaction();

            // LOCAL - NAO EXISTE, CRIA
            if($this->place_id == 'novo-local')
            {
                $validateData['place_name'] = mb_strtolower($validateData['place_name'], 'UTF-8');

                if($placeObj = CustomerOrganizationPlace::where('place_name',$validateData['place_name'])->first())
                {
                    $placeObj->update($validateData);
                    $validateData['tipo_alterar'] = 'update';
                }
                else
                {
                    $placeObj = CustomerOrganizationPlace::create($validateData);
                    $validateData['tipo_alterar'] = 'create';
                }

                $this->place_id           = $placeObj->id;
                $validateData['place_id'] = $placeObj->id;
            }

            // UPDATE
            $eventUpdate = $event->find($this->target->id);
            $eventUpdate->update($validateData);

            // CREATE APP_EVENT
            if($appEvent = AppEvent::find($eventUpdate->id))
            {
                $appEvent->update([
                    'organizer_id'            => $this->organizer->id,
                    'organizer_slug'          => $this->organizer->organizer_slug,
                    'event_slug'              => $eventUpdate->event_slug,
                    'type_id'                 => $this->listType->where('ref_slug',$validateData['type'])->first()->id,
                    'category_id'             => $this->listCategory->where('ref_slug',$validateData['category'])->first()->id,
                    'active'                  => true,
                    'status'                  => 'ativo',
                    'event_visibility_public' => 1,
                    'json_event'              => '{}',
                ]);
            }
            else
            {
                $appEvent = AppEvent::create([
                    'id'                      => $eventUpdate->id,
                    'organizer_id'            => $this->organizer->id,
                    'organizer_slug'          => $this->organizer->organizer_slug,
                    'event_slug'              => $eventUpdate->event_slug,
                    'type_id'                 => $this->listType->where('ref_slug',$validateData['type'])->first()->id,
                    'category_id'             => $this->listCategory->where('ref_slug',$validateData['category'])->first()->id,
                    'active'                  => true,
                    'status'                  => 'ativo',
                    'event_visibility_public' => 1,
                    'json_event'              => '{}',
                ]);
            }

            // TRANSACTION FIM
            // DB::rollBack();
            DB::commit();

            // PUT SESSION
            sessionTargetRef('evento');
            sessionTargetId($eventUpdate->id);

            // EVENTO CRIADO
            session()->flash('success','Evento Alterado');

            return redirect()->route('dashboard-evento');
        }
        catch (\Throwable $th)
        {
            // TRANSACTION ERROR
            DB::rollBack();

            dd($th);

            return session()->flash('error',$th->getMessage());
        }
    }
}
