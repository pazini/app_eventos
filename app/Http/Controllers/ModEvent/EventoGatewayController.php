<?php

namespace App\Http\Controllers\ModEvent;

use App\Http\Controllers\Controller;
use App\Models\ModEvent\Event;
use Illuminate\Http\Request;

class EventoGatewayController extends Controller
{
    /**
     * Seleciona o evento pelo UUID, seta as sessions necessárias
     * e redireciona para o dashboard do evento.
     * Substitui o fluxo via Livewire wire:click + selecionaTarget().
     */
    public function select(Request $request, string $event_id)
    {
        // Carrega o organizer da session (já setado na tela de /eventos)
        $organizer   = sessionOrganizer();
        $organizerId = $organizer->id ?? null;

        // Busca o evento garantindo que pertence ao organizador do usuário.
        // Admins e super-admins veem todos os organizadores, então para eles
        // buscamos apenas pelo id do evento.
        $query = Event::where('id', $event_id);

        if ($organizerId) {
            $query->where('organizer_id', $organizerId);
        }

        $event = $query->first();

        if (! $event) {
            session()->flash('error', 'Evento não encontrado ou sem permissão de acesso.');
            return redirect()->route('dashboard-eventos');
        }

        // Se o organizerId ainda não estava na session (fluxo admin sem pré-seleção),
        // seta o organizer a partir do evento encontrado.
        if (! $organizerId) {
            sessionOrganizer($event->organizer_id);
        }

        // Seta as sessions que os componentes Livewire dependem
        sessionTargetRef('evento');
        sessionTargetId($event->id);
        sessionOrderIdClear();

        return redirect()->route('dashboard-evento');
    }
}
