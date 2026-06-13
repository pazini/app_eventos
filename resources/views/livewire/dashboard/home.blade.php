<div>
    <div class="max-w-7xl mx-auto">
        <div class="mt-6 mb-4 rounded-sm bg-white shadow border px-6 py-6">
            <div class="flex flex-col gap-2">
                <div class="text-xs font-semibold uppercase tracking-widest text-gray-500">
                    Bem-vindo!
                </div>
                <div class="text-xl md:text-2xl font-semibold text-gray-800">
                    Selecione um módulo para começar
                </div>
                <div class="text-xs text-gray-600 max-w-2xl">
                    Selecione um módulo no menu do usuário para começar a utilizar o sistema.
                </div>
            </div>

            @if($checkedModules && ! $canEvents && ! $canCampaigns)
                <div class="mt-4 p-3 rounded bg-amber-50 border border-amber-200 text-[11px] text-amber-800">
                    Nenhum módulo de Eventos ou Campanhas está liberado para este cliente/usuário.
                    Fale com o administrador para configurar seus acessos.
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de Seleção de Módulo -->
    <x-modal.card wire:model="showModuleSelectionModal" title="Módulos Disponíveis" max-width="2xl" persistent>
        <div class="px-4 md:px-6 pb-4 md:pb-6">
            <p class="text-base md:text-lg font-semibold text-gray-600 mb-4 text-center">Selecione qual módulo deseja acessar</p>
            <div class="flex flex-col gap-3">
                @if($canEvents)
                    <a href="{{ route('dashboard-eventos') }}"
                       class="group flex items-center gap-4 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 md:py-4 hover:border-blue-400 hover:bg-blue-100 hover:shadow-sm transition-all duration-200">
                        <div class="flex-shrink-0 w-11 h-11 bg-blue-500 rounded-full flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold text-gray-800 group-hover:text-blue-700 transition-colors">Eventos</div>
                            <div class="text-xs text-gray-500 mt-0.5">Gerencie seus eventos, ingressos e participantes</div>
                        </div>
                        <svg class="flex-shrink-0 w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif

                @if($canCampaigns)
                    <a href="{{ route('dashboard-campanhas') }}"
                       class="group flex items-center gap-4 bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3 md:py-4 hover:border-indigo-400 hover:bg-indigo-100 hover:shadow-sm transition-all duration-200">
                        <div class="flex-shrink-0 w-11 h-11 bg-indigo-500 rounded-full flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">Campanhas</div>
                            <div class="text-xs text-gray-500 mt-0.5">Crie e gerencie suas campanhas de arrecadação</div>
                        </div>
                        <svg class="flex-shrink-0 w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif

            </div>
        </div>
    </x-modal.card>
</div>


