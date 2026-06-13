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

class EventoNovo extends Component
{
    //
    public $organizer;
    public $organizerId;
    public $target_ref='app_event';

    //
    public $myPlaces;
    public $place;

    public $listStates;
    public $listType;
    public $listCategory;

    //
    public $type;
    public $category;
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
    public $cod_latitude = 0;
    public $cod_longitude = 0;


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

        // SE ORGANIZER / TARGET_ID
        if(!$this->organizerId)
        {
            session()->flash('error','Comece selecionando um organizador!');
            return redirect()->route('dashboard');
        }

        $this->myPlaces     = $this->organizer->places;
        $this->listStates   = listStates();
        $this->listType     = RefAppType::all();
        $this->listCategory = RefAppEventCategory::all();

        // SIMULA DADOS
        // $this->simula();
    }

    public function render()
    {
        return view('livewire.evento.evento-novo')->layout('layouts.app-pep-auth');
    }

    public function simula()
    {
        $this->type='evento';
        $this->category='pago';
        $this->event_name='volunter day use';
        $this->event_description='dia do voluntario de teste';
        $this->event_datetime_start='2023-03-01 10:00';
        $this->event_datetime_finish='2023-03-01 22:00';
        $this->sales_amount_max=200;
        $this->sales_label='INGRESSOS';
        $this->sales_btn='COMPRAR';
        $this->event_tickets_nomenclature='INGRESSO';
        $this->place_name='casa do teste';
        $this->place_description='local de teste';
        $this->address='rua do i123';
        $this->address_number='300';
        $this->address_complement='fundos';
        $this->address_reference='apenas de teste';
        $this->city_neighborhood='nikiti';
        $this->city='niteroi';
        $this->state='rj';
        $this->zip_code='24120-196';
        $this->iframe_google_maps='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3674.5119609792114!2d-43.068393585624285!3d-22.93136514451226!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x99876dc712e919%3A0xd1564386f71c5915!2sLagoinha%20Niter%C3%B3i!5e0!3m2!1spt-BR!2sbr!4v1674667401131!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
    }

    public function updatedPlace()
    {
        if($place = $this->myPlaces->find($this->place))
        {
            $this->place_id = $place->id;
            //
            foreach ($place->toArray() as $loopKey => $loopValue)
            {
                if(in_array($loopKey ,['id']))
                    continue;

                $this->$loopKey = $loopValue;
            }
        }
        else
        {
            $this->place_id           = '';
            $this->place_name         = '';
            $this->place_description  = '';
            $this->address            = '';
            $this->address_number     = '';
            $this->address_complement = '';
            $this->address_reference  = '';
            $this->city_neighborhood  = '';
            $this->city               = '';
            $this->state              = '';
            $this->zip_code           = '';
            $this->iframe_google_maps = '';
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

    public function cadastrarNovoEvento()
    {
        // return;

        $validateData = $this->validate([
            'type'                       => ['required', 'string'],
            'category'                   => ['required', 'string'],
            'event_name'                 => ['required', 'string'],
            'event_description'          => ['nullable', 'string'],
            'event_about'                => ['nullable', 'string'],
            'notification_text_1'        => ['nullable', 'string'],
            'notification_text_2'        => ['nullable', 'string'],
            'sales_amount_max'           => ['required', 'integer'],
            'sales_label'                => ['required', 'string'],
            'sales_btn'                  => ['required', 'string'],
            'event_tickets_nomenclature' => ['required', 'string'],
            'place'                      => ['nullable', 'string'],
            'place_name'                 => ['required', 'string'],
            'place_description'          => ['nullable', 'string'],
            'address'                    => ['required', 'string'],
            'address_number'             => ['required', 'string'],
            'address_complement'         => ['nullable', 'string'],
            'address_reference'          => ['nullable', 'string'],
            'city_neighborhood'          => ['required', 'string'],
            'city'                       => ['required', 'string'],
            'state'                      => ['required', 'string'],
            'zip_code'                   => ['required'],
            'iframe_google_maps'         => ['nullable', 'string'],
            'event_datetime_start'       => ['required','date','before:event_datetime_finish'],
            'event_datetime_finish'      => ['required','date','after:event_datetime_start'],
            //
            'cod_latitude'               => ['nullable'],
            'cod_longitude'              => ['nullable'],
        ]);

        // EVENT
        $event = new Event();

        // SET SLUG
        $slugLoop = 0;

        //
        while ($slugLoop++ < 100) // TENTA 100x
        {
            $event_slug = Str::slug($this->event_name . '-' . now()->timestamp);

            if ($event->where('event_slug', $event_slug)->count())
            {
                $slugLoop++;
                sleep(1);
                continue;
            }

            break;
        }

        //
        if($slugCount = $event->where('event_slug',$event_slug)->count())
        {
            $event_slug = Str::slug($this->event_name . '-' . ($slugCount + 1));
        }

        // APPEND
        $validateData['status']             = 'criado';
        $validateData['event_slug']         = $event_slug;
        $validateData['referer_url']        = config('domains.eventos');
        $validateData['place_id']           = $this->place ?? null;
        $validateData['customer_id']        = $this->organizer->customer_id;
        $validateData['organizer_id']       = $this->organizer->id;
        $validateData['organization_id']    = $this->organizer->organization_id;
        $validateData['google_maps_iframe'] = $this->iframe_google_maps;

        try
        {
            // TRANSACTION
            DB::beginTransaction();

            // LOCAL - NAO EXISTE, CRIA
            if(!$this->place ?? false)
            {
                $validateData['place_name'] = mb_strtolower($validateData['place_name'], 'UTF-8');

                $validateData['place_slug'] = toSlug($this->organizer->customer_id . ' ' . $validateData['place_name'],'-');

                $validateData['organization_id'] = 'de8047e4-f881-4738-8c46-6e6ac0cb3468';

                if($placeObj = CustomerOrganizationPlace::where('place_slug',$validateData['place_slug'])->first())
                {
                    $placeObj->update($validateData);
                    $validateData['tipo_alterar'] = 'update';
                }
                else
                {
                    $placeObj = CustomerOrganizationPlace::create($validateData);
                    $validateData['tipo_alterar'] = 'create';
                }

                $validateData['place_id'] = $placeObj->id;
            }

            // CREATE
            $eventCreate = $event->create($validateData);

            // CREATE APP_EVENT
            $appEvent = AppEvent::create([
                'id'                      => $eventCreate->id,
                'organizer_id'            => $this->organizer->id,
                'organizer_slug'          => $this->organizer->organizer_slug,
                'event_slug'              => $event_slug,
                'type_id'                 => $this->listType->where('ref_slug',$validateData['type'])->first()->id,
                'category_id'             => $this->listCategory->where('ref_slug',$validateData['category'])->first()->id,
                'active'                  => true,
                'status'                  => 'ativo',
                'event_visibility_public' => 1,
                'json_event'              => '{}',
            ]);

            // TRANSACTION FIM
            // DB::rollBack();
            DB::commit();

            // PUT SESSION
            sessionTargetRef('evento');
            sessionTargetId($eventCreate->id);

            // EVENTO CRIADO
            session()->flash('success','Evento criado');
            session()->flash('success_sub', 'Seu evento esta pronto para ser parametrizado');

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

