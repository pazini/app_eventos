<div class="container mx-auto px-4 py-8 max-w-5xl">
    <!-- Header do Wizard -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Onboarding de Cliente</h1>
                <p class="text-gray-600">Configure um novo cliente de forma guiada em {{ appName() }}</p>
            </div>
            <div class="text-right">
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <i class="fas fa-building"></i>
                    <span>{{ appName() }}</span>
                </div>
                @if($currentApp)
                    <div class="text-xs text-gray-400">App ID: {{ Str::limit($currentApp->id, 8) }}</div>
                @endif
            </div>
        </div>
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
                    <span class="text-xs text-gray-500 text-center max-w-16">
                        @if ($i == 1) Empresa
                        @elseif ($i == 2) Config
                        @elseif ($i == 3) Logo
                        @elseif ($i == 4) Domínio
                        @elseif ($i == 5) Pagamento
                        @elseif ($i == 6) Admin
                        @elseif ($i == 7) Textos
                        @elseif ($i == 8) Tour
                        @elseif ($i == 9) Teste
                        @elseif ($i == 10) Confirmar
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
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-blue-500 h-3 rounded-full transition-all duration-300"
                 style="width: {{ $progressPercentage }}%"></div>
        </div>
    </div>

    <!-- Título do Step Atual -->
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            Passo {{ $currentStep }} de {{ $totalSteps }}: {{ $stepTitle }}
        </h2>
        @if($currentStep <= 5)
            <p class="text-gray-600 mt-2">Configure as informações básicas do cliente</p>
        @elseif($currentStep <= 8)
            <p class="text-gray-600 mt-2">Personalize a experiência do cliente</p>
        @else
            <p class="text-gray-600 mt-2">Finalize e ative o cliente</p>
        @endif
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

        <form wire:submit.prevent="completeOnboarding">
            @if ($currentStep == 1)
                @include('livewire.dashboard.client-onboarding.step-1-company')
            @elseif ($currentStep == 2)
                @include('livewire.dashboard.client-onboarding.step-2-settings')
            @elseif ($currentStep == 3)
                @include('livewire.dashboard.client-onboarding.step-3-materials')
            @elseif ($currentStep == 4)
                @include('livewire.dashboard.client-onboarding.step-4-domain')
            @elseif ($currentStep == 5)
                @include('livewire.dashboard.client-onboarding.step-5-payment')
            @elseif ($currentStep == 6)
                @include('livewire.dashboard.client-onboarding.step-6-admin')
            @elseif ($currentStep == 7)
                @include('livewire.dashboard.client-onboarding.step-7-texts')
            @elseif ($currentStep == 8)
                @include('livewire.dashboard.client-onboarding.step-8-tour')
            @elseif ($currentStep == 9)
                @include('livewire.dashboard.client-onboarding.step-9-test')
            @elseif ($currentStep == 10)
                @include('livewire.dashboard.client-onboarding.step-10-confirmation')
            @endif
        </form>
    </div>

    <!-- Navigation -->
    <div class="flex justify-between items-center">
        <div>
            @if ($currentStep > 1)
                <button wire:click="previousStep"
                        class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Anterior
                </button>
            @else
                <a href="{{ route('dashboard-organizadores') }}"
                   class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-medium transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            @endif
        </div>

        <div class="flex space-x-4">
            @if ($currentStep < $totalSteps)
                <button wire:click="nextStep"
                        class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                    Próximo<i class="fas fa-arrow-right ml-2"></i>
                </button>
            @elseif ($currentStep == $totalSteps)
                <button wire:click="completeOnboarding"
                        wire:loading.attr="disabled"
                        wire:confirm="Tem certeza que deseja finalizar o onboarding do cliente?"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50">
                    <div wire:loading.remove wire:target="completeOnboarding">
                        <i class="fas fa-check mr-2"></i>Finalizar Onboarding
                    </div>
                    <div wire:loading wire:target="completeOnboarding">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Criando cliente...
                    </div>
                </button>
            @endif
        </div>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="completeOnboarding"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md mx-4">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-center mb-2">Criando cliente...</h3>
            <div class="space-y-2 text-sm text-gray-600">
                <p><i class="fas fa-check text-green-500 mr-2"></i>Salvando dados da empresa</p>
                <p><i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>Configurando permissões</p>
                <p><i class="fas fa-clock text-gray-400 mr-2"></i>Criando usuário administrador</p>
                <p><i class="fas fa-clock text-gray-400 mr-2"></i>Enviando e-mail de boas-vindas</p>
            </div>
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

    // Inicializar tour se solicitado
    @if(session('start_tour'))
        // Aqui seria inicializado o tour interativo
        console.log('Tour seria iniciado aqui');
    @endif
});

// Máscara para CNPJ/CPF
function formatDocument(input) {
    let value = input.value.replace(/\D/g, '');

    if (value.length <= 11) {
        // CPF
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else {
        // CNPJ
        value = value.replace(/^(\d{2})(\d)/, '$1.$2');
        value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
    }

    input.value = value;
}

// Máscara para CEP
function formatZipcode(input) {
    let value = input.value.replace(/\D/g, '');
    value = value.replace(/^(\d{5})(\d)/, '$1-$2');
    input.value = value;
}

// Máscara para telefone
function formatPhone(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length === 11) {
        value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    } else {
        value = value.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
    }
    input.value = value;
}
</script>
@endpush
