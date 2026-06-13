<div class="min-h-screen bg-white">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white px-4 py-6">
        <div class="max-w-md mx-auto text-center">
            <h1 class="text-2xl font-bold mb-2">Configuração</h1>
            <p class="text-gray-300 text-sm">Informe os dados para acessar os eventos</p>
        </div>
    </div>

    {{-- Formulário --}}
    <div class="px-4 py-6">
        <div class="max-w-md mx-auto">
            <form wire:submit.prevent="submitCustomerSelection" class="space-y-5">

                {{-- Campo App User ID --}}
                <div>
                    <label for="appUserIdInput" class="block text-sm font-semibold text-gray-700 mb-1.5">ID do Usuário - appUserId</label>
                    <input
                        id="appUserIdInput"
                        type="text"
                        wire:model.defer="appUserId"
                        placeholder="UUID do usuário do app"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:border-gray-900 focus:ring-0 transition-colors"
                    />
                    @error('appUserId')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Select Empresa --}}
                <div>
                    <label for="selectedCustomer" class="block text-sm font-semibold text-gray-700 mb-1.5">Empresa - filterCustomerSlug <span class="text-red-500">*</span></label>
                    <select
                        id="selectedCustomer"
                        wire:model.defer="selectedCustomerId"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm text-gray-900 bg-white focus:border-gray-900 focus:ring-0 transition-colors appearance-none"
                    >
                        <option value="">Selecione a empresa...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name_corporate }}</option>
                        @endforeach
                    </select>
                    @error('selectedCustomerId')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botão Submit --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="submitCustomerSelection"
                        class="w-full px-4 py-3.5 bg-gray-900 text-white font-semibold text-sm rounded-xl active:bg-gray-700 transition-colors disabled:opacity-50 min-h-[48px] flex items-center justify-center gap-2"
                    >
                        <svg wire:loading wire:target="submitCustomerSelection" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="submitCustomerSelection">Acessar Eventos</span>
                        <span wire:loading wire:target="submitCustomerSelection">Carregando...</span>
                    </button>
                </div>
            </form>

            @if(!$customers || $customers->count() === 0)
                {{-- Mensagem quando não há empresas --}}
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma empresa disponível</h3>
                    <p class="text-sm text-gray-500">Não há empresas cadastradas no momento.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Link para versão normal --}}
    <div class="px-4 py-6 border-t border-gray-200 mt-8">
        <div class="max-w-md mx-auto text-center">
            <a href="{{ route('eventos-home') }}" class="text-sm text-gray-600 active:text-gray-900 inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar para versão normal
            </a>
        </div>
    </div>
</div>
