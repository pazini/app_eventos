<div class="bg-white sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
    {{-- NAVIGATION MENU APP-VERSION (sempre mobile, sem breakpoints) --}}
    <div class="max-w-[480px] mx-auto px-4 py-3">
        {{-- Linha 1: Nome da empresa + Minhas Compras --}}
        <div class="flex items-center justify-between mb-3">
            {{-- Nome da Empresa Selecionada --}}
            <div class="flex-1 min-w-0">
                @if($appCustomerName)
                    <div class="flex flex-col">
                        <span class="text-lg font-bold text-gray-900 leading-snug truncate uppercase">
                            {{ $appCustomerName }}
                        </span>
                    </div>
                @else
                    <a href="{{ route('app-version-home') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        ← Selecionar Empresa
                    </a>
                @endif
            </div>

            {{-- Botão Minhas Compras --}}
            <a
                href="{{ route('app-version-minhas-compras') }}"
                class="px-3 py-2 text-xs border border-green-500 rounded-lg bg-white text-green-600 font-semibold active:bg-green-50 transition-colors flex items-center gap-1.5 ml-3"
            >
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                <span>Compras</span>
            </a>
        </div>

        {{-- Linha 2: Organizador + Status (50% cada) --}}
        <div class="flex gap-2 mb-3">
            {{-- Dropdown Organizador --}}
            <div class="relative w-1/2" x-data="{ open: @entangle('showOrganizerDropdown') }">
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-start text-left gap-2 px-3 py-2.5 border border-gray-200 rounded-lg active:border-gray-300 transition-colors bg-white h-[42px]"
                >
                    <div class="flex flex-col items-start flex-1 min-w-0">
                        <span class="text-[10px] text-gray-400 uppercase leading-none tracking-wide">Organizador</span>
                        <span class="text-xs font-semibold text-gray-800 leading-snug truncate w-full uppercase">
                            @if($filterOrganizer)
                                {{ Str::limit($organizers->firstWhere('id', $filterOrganizer)->organizer_name ?? 'Todos', 11) }}
                            @else
                                Todos
                            @endif
                        </span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 max-h-80 overflow-y-auto"
                    style="display: none;"
                >
                    <button
                        wire:click="$set('filterOrganizer', '')"
                        class="w-full text-left px-4 py-2.5 text-xs active:bg-gray-50 transition-colors {{ !$filterOrganizer ? 'bg-gray-50 font-medium text-gray-900' : 'text-gray-700' }}"
                    >
                        Todos os Organizadores
                    </button>
                    @foreach($organizers as $organizer)
                        <button
                            wire:click="$set('filterOrganizer', '{{ $organizer->id }}')"
                            title="{{ $organizer->organizer_name_full }}"
                            class="w-full text-left px-3 py-2 active:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0 {{ $filterOrganizer == $organizer->id ? 'bg-gray-50' : '' }}"
                        >
                            {{-- Linha 1: Cliente | Organization --}}
                            <p class="text-[10px] text-gray-400 uppercase truncate leading-tight">
                                {{ Str::limit($organizer->customer->name_corporate ?? '', 18) }}
                                @if($organizer->organization)
                                    <span class="mx-0.5">|</span>{{ Str::limit($organizer->organization->organization_name, 18) }}
                                @endif
                            </p>
                            {{-- Linha 2: Setor --}}
                            <p class="text-xs font-bold text-gray-800 uppercase truncate leading-tight mt-0.5">
                                {{ $organizer->organizer_name }}
                            </p>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Dropdown Status --}}
            <div class="relative w-1/2" x-data="{ open: @entangle('showStatusDropdown') }">
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-start text-left gap-2 px-3 py-2.5 border border-gray-200 rounded-lg active:border-gray-300 transition-colors bg-white h-[42px]"
                >
                    <div class="flex flex-col items-start flex-1">
                        <span class="text-[10px] text-gray-400 uppercase leading-none tracking-wide">Status</span>
                        <span class="text-xs font-semibold text-gray-800 leading-snug uppercase">
                            @if($filterStatus === 'ativas') Ativas
                            @elseif($filterStatus === 'todas') Todas
                            @elseif($filterStatus === 'finalizadas') Finalizadas
                            @endif
                        </span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                    style="display: none;"
                >
                    <button
                        wire:click="$set('filterStatus', 'ativas')"
                        class="w-full text-left px-4 py-2 text-xs active:bg-gray-50 transition-colors {{ $filterStatus === 'ativas' ? 'bg-gray-50 font-medium text-gray-900' : 'text-gray-700' }}"
                    >
                        Ativas
                    </button>
                    <button
                        wire:click="$set('filterStatus', 'todas')"
                        class="w-full text-left px-4 py-2 text-xs active:bg-gray-50 transition-colors {{ $filterStatus === 'todas' ? 'bg-gray-50 font-medium text-gray-900' : 'text-gray-700' }}"
                    >
                        Todas
                    </button>
                    <button
                        wire:click="$set('filterStatus', 'finalizadas')"
                        class="w-full text-left px-4 py-2 text-xs active:bg-gray-50 transition-colors {{ $filterStatus === 'finalizadas' ? 'bg-gray-50 font-medium text-gray-900' : 'text-gray-700' }}"
                    >
                        Finalizadas
                    </button>
                </div>
            </div>
        </div>

        {{-- Linha 3: Busca --}}
        <div class="relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input
                type="text"
                wire:model.debounce.300ms="search"
                placeholder="Buscar eventos..."
                class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm h-[42px]"
            />
        </div>

        {{-- Link opcional para resetar sessão (DESCOMENTE SE QUISER) --}}
        {{--
        <div class="mt-2 text-center">
            <a href="{{ route('app-version-reset') }}" class="text-xs text-gray-500 active:text-gray-700">
                Sair do modo empresa
            </a>
        </div>
        --}}
    </div>
</div>
