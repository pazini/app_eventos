<?php

namespace App\Http\Livewire;

use App\Models\ModEvent\Event;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class DashboardFinanceiro extends Component
{
    public $appModules;
    public $financeiroItems = [];

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        $app = sessionApp();
        $this->appModules = $app->modules ?? [];

        // GET ORGANIZER
        $this->organizer   = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;

        //
        if(!$this->organizerId)
        {
            // session()->flash('error','É PRECISO SELECIONAR UM ORGANIZADOR');
            return redirect()->route('dashboard');
        }

        //
        if ($this->appModules ?? false) {

            foreach ($this->appModules as $module)
            {
                switch ($module->module_name) {

                    case 'app_event':
                        $all = Event::where('organizer_id',$this->organizerId)->get();

                        //
                        foreach ($all ?? [] as $allValues) {
                            $this->financeiroItems[$allValues->created_at->format('YmdHis') .'-'.$module->slug.'-'.$allValues->event_slug] = [
                                'moduleName'     => $module->singular_name,
                                'moduleObj'      => $module,
                                'itemName'       => $allValues->event_name,
                                'itemType'       => $allValues->type,
                                'itemActive'     => $allValues->active,
                                'itemStatus'     => $allValues->status,
                                'itemDateStart'  => $allValues->event_datetime_start,
                                'itemDateFinish' => $allValues->event_datetime_finish,
                                'itemObj'        => $allValues,
                            ];
                        }
                        break;

                    case 'app_workshop':
                        $all = Event::where('organizer_id',$this->organizerId)->get();
                        //
                        foreach ($all ?? [] as $allValues) {
                            $this->financeiroItems[$allValues->created_at->format('YmdHis') .'-'.$module->slug.'-'.$allValues->event_slug] = [
                                'moduleName'     => $module->singular_name,
                                'moduleObj'      => $module,
                                'itemName'       => $allValues->event_name,
                                'itemType'       => $allValues->type,
                                'itemActive'     => $allValues->active,
                                'itemStatus'     => $allValues->status,
                                'itemDateStart'  => $allValues->event_datetime_start,
                                'itemDateFinish' => $allValues->event_datetime_finish,
                                'itemObj'        => $allValues,
                            ];
                        }
                        break;
                    }
            }
        }
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-financeiro')->layout('layouts.app-pep-auth');
    }
}

