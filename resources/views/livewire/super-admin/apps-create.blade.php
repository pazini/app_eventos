<div class="pt-2 pb-6">
    <div class="max-w-7xl mx-auto">
        <div class="space-y-6">
            {{-- Header --}}
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Nova Aplicação</h1>
                        <p class="text-sm text-gray-500">Configure uma white label em alguns passos.</p>
                    </div>
                    <a href="{{ route('super-administrador.apps.index') }}"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>

            {{-- Card principal --}}
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 font-semibold">
                                {{ $currentStep }}
                            </span>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-500">Etapa {{ $currentStep }} de {{ $totalSteps }}</p>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    @switch($currentStep)
                                        @case(1) Informações Básicas @break
                                        @case(2) Branding @break
                                        @case(3) Domínios @break
                                        @case(4) Módulos @break
                                        @case(5) Confirmação @break
                                        @default Etapa
                                    @endswitch
                                </h2>
                            </div>
                        </div>
                        <div class="w-48">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full transition-all duration-300"
                                     style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Etapas --}}
                    @if ($currentStep === 1)
                        @include('livewire.super-admin.wizard.step-1-basic')
                    @elseif ($currentStep === 2)
                        @include('livewire.super-admin.wizard.step-2-branding')
                    @elseif ($currentStep === 3)
                        @include('livewire.super-admin.wizard.step-3-domain')
                    @elseif ($currentStep === 4)
                        @include('livewire.super-admin.wizard.step-4-modules')
                    @elseif ($currentStep === 5)
                        @include('livewire.super-admin.wizard.step-5-confirm')
                    @endif

                    {{-- Navegação --}}
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-3">
                            <a href="{{ route('super-administrador.apps.index') }}"
                               class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancelar
                            </a>
                            @if ($currentStep > 1)
                                <button wire:click="previousStep"
                                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm font-medium">
                                    Anterior
                                </button>
                            @endif
                        </div>

                        <div class="flex space-x-3">
                            @if ($currentStep < $totalSteps)
                                <button wire:click="nextStep"
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium">
                                    Próximo
                                </button>
                            @else
                                <button wire:click="createApp"
                                        wire:loading.attr="disabled"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium disabled:opacity-60">
                                    <span wire:loading.remove><i class="fas fa-check mr-1"></i>Criar Aplicação</span>
                                    <span wire:loading><i class="fas fa-spinner fa-spin mr-1"></i>Criando...</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Notificações --}}
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('notify', (message, type) => {
            window.scrollTo({ top: 0, behavior: 'smooth' });

            window.$wireui.notify({
                title: type === 'success' ? 'Sucesso!' : type === 'error' ? 'Erro!' : 'Aviso!',
                description: message,
                icon: type === 'success' ? 'success' : type === 'error' ? 'error' : 'warning'
            });
        });
    });

    window.addEventListener('scroll-top', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
