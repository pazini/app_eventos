<?php

namespace App\Http\Livewire\Evento;

use App\Models\CustomerOrganizationPlace;
use App\Models\ModEvent\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;


class LayoutPagina extends Component
{
    use WithFileUploads;

    //
    public $organizer;
    public $organizerId;
    public $target;
    public $target_id;

    //
    public $color_primary;
    public $color_secondary;
    public $color_default;
    public $color_default_inverse;
    public $url_image_logo;
    public $url_image_thumbnail;
    public $url_image;
    public $url_image_bg;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($event_id = null)
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
        $this->organizer = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;
        $this->target_id = sessionTargetId();

        // SE ORGANIZER / TARGET_ID
        if (!$this->organizerId || !$this->target_id)
            return redirect()->route('dashboard');

        // LIMPA ORDER ID
        sessionOrderIdClear();

        // SIMULA DADOS
        // $this->simula();
    }

    public function render()
    {
        if (!$this->target = Event::with(['page'])->where('organizer_id', $this->organizerId)->where('id', $this->target_id)->first()) {
            return returnEventoDashboard('Layout não localizado', 'error');
        }

        //
        $this->color_default = $this->target->color_default;
        $this->color_default_inverse = $this->target->color_default_inverse;
        $this->color_primary = $this->target->color_primary;
        $this->color_secondary = $this->target->color_secondary;
        //
        $this->url_image_logo = $this->target->url_image_logo;
        $this->url_image_thumbnail = $this->target->url_image_thumbnail;
        $this->url_image = $this->target->url_image;
        $this->url_image_bg = $this->target->url_image_bg;

        return view('livewire.evento.layout-pagina')->layout('layouts.app-pep-auth');
    }

    public function carregarImagem($arquivo)
    {
    }

    public function updated($name, $value)
    {
        // UPLOADS LIST
        $uploads = ['url_image_logo', 'url_image_thumbnail', 'url_image', 'url_image_bg'];

        // SE UPLOADS
        if (in_array($name, $uploads) && $value ?? false)
        {
            $this->validate([
                $name => ['image', 'max:1024'],
            ]);

            try
            {
                // Upload isolado por tenant usando UUID
                $app = currentApp();
                $appId = $app->id ?? session('app_id');

                // Path com UUID para salvar fisicamente
                $physicalPath = "{$appId}/events/" . $this->target->event_slug . '/layouts';
                $fullPath = storage_path("app/public/{$physicalPath}");

                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0755, true);
                }

                $extension = $value->getClientOriginalExtension();
                $filename = time() . '_' . Str::random(10) . '.' . $extension;
                $value->storeAs($physicalPath, $filename, 'public');

                // Salvar no banco SEM o UUID (tenantAsset vai adicionar depois)
                $relativePathForDB = 'events/' . $this->target->event_slug . '/layouts/' . $filename;
                $this->target->update([$name => $relativePathForDB]);
            }
            catch (\Throwable $th)
            {
                return session()->flash('error', $th->getMessage());
            }
        }
        else
        {
            $this->target->update([$name => $value]);
        }

        setSessionFlash(__($name) . " foi alterada", "success");
    }
}

