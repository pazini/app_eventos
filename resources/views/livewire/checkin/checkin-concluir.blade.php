<div class="w-full max-w-7xl mx-auto mb-6">

    {{-- HEADER MODERNO COM GRADIENTE --}}
    <div class="mb-4 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-checkin-concluir" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-checkin-concluir)"/>
            </svg>
        </div>
        <div class="relative z-10 p-4">
            <div class="flex items-center justify-center space-x-2">
                <div class="p-1.5 bg-white/20 rounded backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Check-in</h1>
                    <p class="text-white/90 text-sm mt-0.5">Confirmação</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col items-center">
        <div class="w-full max-w-2xl">

            {{-- CARD DE INFORMAÇÕES --}}
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-base font-semibold text-gray-800">Informações do Ingresso</h2>
                </div>
                <div class="p-6 space-y-4">
                    {{-- CONTROLE --}}
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Controle</div>
                        <div class="text-xl font-bold text-gray-900 font-mono">{{ $control ?? '--' }}</div>
                    </div>

                    {{-- VALIDAÇÕES --}}
                    <div class="rounded">
                        <x-jet-validation-errors />
                    </div>

                    @if ($targetCheckin ?? false)
                        @switch($ref_target ?? false)
                            @case('evento')
                            @default
                                {{-- EVENTO --}}
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                                    <div class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">Evento</div>
                                    <div class="text-lg font-semibold text-blue-900">{{ $targetCheckin->event_name ?? '--' }}</div>
                                </div>

                                {{-- TIPO DE INGRESSO --}}
                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                                    <div class="text-xs font-medium text-purple-600 uppercase tracking-wide mb-1">{{ $targetCheckin->event_tickets_nomenclature ?? 'INGRESSO' }} Tipo</div>
                                    <div class="text-lg font-semibold text-purple-900">{{ $targetCheckin->event_ticket_name ?? '--' }}</div>
                                </div>

                                {{-- UTILIZADOR --}}
                                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-4 border border-indigo-200">
                                    <div class="text-xs font-medium text-indigo-600 uppercase tracking-wide mb-1">Participante</div>
                                    <div class="text-lg font-semibold text-indigo-900">{{ $targetCheckin->user_name ?? '--' }}</div>
                                </div>
                                @break
                        @endswitch

                        {{-- STATUS: DISPONÍVEL --}}
                        @if (in_array($targetCheckin->ticket_status,['disponivel']))
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                @php
                                    $explode      = explode('.',$control);
                                    $controlCkeck = $explode[1] ?? $explode[0];
                                    $controlCkeck = substr($controlCkeck, 0, 2);

                                    $checkList[$controlCkeck] = (string) $controlCkeck;

                                    foreach (range(1,5) as $range)
                                    {
                                        $numRand = str_pad(rand(0,99) , 2 , '0' , STR_PAD_LEFT);

                                        if($numRand == $controlCkeck)
                                            $numRand = str_pad(rand(0,99) , 2 , '0' , STR_PAD_LEFT);

                                        $checkList[$numRand] = (string) $numRand;
                                    }

                                    ksort($checkList);
                                @endphp
                                <div class="space-y-3">
                                    <div class="text-center">
                                        <p class="text-sm font-medium text-gray-700 mb-4">Confirme o check-in do participante</p>
                                    </div>
                                    <div class="flex gap-3">
                                        @if ($referer ?? false)
                                            <a class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all uppercase text-center" href="{{ $referer }}">
                                                Cancelar
                                            </a>
                                        @else
                                            <a class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all uppercase text-center" href="{{ route('checkin-target',['ref_target' => $ref_target,'ref_target_slug' => $ref_target_slug]) }}">
                                                Cancelar
                                            </a>
                                        @endif
                                        <button class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all uppercase" wire:click="concluirCheckin('{{ $controlCkeck }}')">
                                            Confirmar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        {{-- STATUS: UTILIZADO --}}
                        @elseif (in_array($targetCheckin->ticket_status,['utilizado']))
                            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white text-center shadow-md">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <div class="text-xl font-bold uppercase">{{ $targetCheckin->event_tickets_nomenclature ?? 'INGRESSO' }} Utilizado</div>
                                </div>
                                @if($targetCheckin->ticket_checkin_datetime)
                                    <div class="text-sm font-medium opacity-90 mt-2">
                                        {{ $targetCheckin->ticket_checkin_datetime->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </div>
                        {{-- STATUS: CANCELADO --}}
                        @elseif (in_array($targetCheckin->ticket_status,['canceled','cancelado']))
                            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg p-6 text-white text-center shadow-md">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <div class="text-xl font-bold uppercase">{{ $targetCheckin->event_tickets_nomenclature ?? 'INGRESSO' }} Cancelado</div>
                                </div>
                                @if($targetCheckin->ticket_cancel_datetime)
                                    <div class="text-sm font-medium opacity-90 mt-2">
                                        {{ $targetCheckin->ticket_cancel_datetime->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                                @if($targetCheckin->ticket_cancel_description)
                                    <div class="text-sm font-medium opacity-90 mt-1">
                                        {{ $targetCheckin->ticket_cancel_description }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- BOTÃO VOLTAR/NOVO CHECKIN --}}
            <div class="text-center">
                @if ($referer ?? false)
                    <a class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors" href="{{ $referer }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar
                    </a>
                @else
                    <a class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" href="{{ route('checkin-target',['ref_target' => $ref_target,'ref_target_slug' => $ref_target_slug]) }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        Novo Check-in
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
