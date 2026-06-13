<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Header do Wizard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Wizard de Onboarding</h1>
        <p class="text-gray-600">Crie uma nova aplicação white label em alguns passos simples</p>
    </div>

    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            @for ($i = 1; $i <= $totalSteps; $i++)
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium mb-2
                        @if ($i < $currentStep || isset($completedSteps[$i]))
                            bg-green-500 text-white
                        @elseif ($i == $currentStep)
                            bg-blue-500 text-white
                        @else
                            bg-gray-200 text-gray-600
                        @endif">
                        @if ($i < $currentStep || isset($completedSteps[$i]))
                            <i class="fas fa-check"></i>
                        @else
                            {{ $i }}
                        @endif
                    </div>
                    <span class="text-xs text-gray-500 text-center max-w-20">
                        @if ($i == 1) Básico
                        @elseif ($i == 2) Branding
                        @elseif ($i == 3) Domínio
                        @elseif ($i == 4) Módulos
                        @elseif ($i == 5) Admin
                        @elseif ($i == 6) Confirmar
                        @endif
                    </span>
                </div>
                @if ($i < $totalSteps)
                    <div class="flex-1 h-1 mx-2
                        @if ($i < $currentStep) bg-green-500
                        @else bg-gray-200
                        @endif">
                    </div>
                @endif
            @endfor
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                 style="width: {{ $this->progressPercentage }}%"></div>
        </div>
    </div>

    <!-- Título do Step Atual -->
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            Step {{ $currentStep }} de {{ $totalSteps }}: {{ $this->stepTitle }}
        </h2>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            Por favor, corrija os erros abaixo:
                        </p>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="completeWizard">
            @if ($currentStep == 1)
                @include('livewire.super-admin.wizard.step-1-basic')
            @elseif ($currentStep == 2)
                @include('livewire.super-admin.wizard.step-2-branding')
            @elseif ($currentStep == 3)
                @include('livewire.super-admin.wizard.step-3-domain')
            @elseif ($currentStep == 4)
                @include('livewire.super-admin.wizard.step-4-modules')
            @elseif ($currentStep == 5)
                @include('livewire.super-admin.wizard.step-5-admin')
            @elseif ($currentStep == 6)
                @include('livewire.super-admin.wizard.step-6-confirmation')
            @endif
        </form>
    </div>

    <!-- Navigation -->
    <div class="flex justify-between items-center">
        <div class="flex space-x-3">
            @if ($currentStep > 1)
                <button wire:click="previousStep"
                        class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Anterior
                </button>
            @endif

            <a href="{{ route('super-administrador.dashboard') }}"
               class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
        </div>

        <div class="flex space-x-4">
            @if ($currentStep < $totalSteps)
                <button wire:click="nextStep"
                        class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                    Próximo<i class="fas fa-arrow-right ml-2"></i>
                </button>
            @elseif ($currentStep == $totalSteps)
                <button wire:click="completeWizard"
                        wire:loading.attr="disabled"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50">
                    <div wire:loading.remove>
                        <i class="fas fa-check mr-2"></i>Criar Aplicação
                    </div>
                    <div wire:loading>
                        <i class="fas fa-spinner fa-spin mr-2"></i>Criando...
                    </div>
                </button>
            @endif
        </div>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="completeWizard"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-center mb-2">Criando aplicação...</h3>
            <p class="text-gray-600 text-center text-sm">
                Configurando storage, criando usuário e aplicando configurações.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-scroll para erros quando aparecem
document.addEventListener('livewire:load', function () {
    Livewire.hook('message.processed', (message, component) => {
        if (component.errors && Object.keys(component.errors).length > 0) {
            const firstError = document.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Sistema de notificações
    Livewire.on('notify', (message, type) => {
        // Rolar para o topo da página para mostrar a notificação
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Usar WireUI notify
        window.$wireui.notify({
            title: type === 'success' ? 'Sucesso!' : type === 'error' ? 'Erro!' : 'Aviso!',
            description: message,
            icon: type === 'success' ? 'success' : type === 'error' ? 'error' : 'warning'
        });
    });
});
</script>
@endpush
