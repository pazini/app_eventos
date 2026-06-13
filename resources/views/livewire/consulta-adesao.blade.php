<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        @if(!$authenticated)
            {{-- Formulário de Consulta --}}
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-8 text-center">
                    <h1 class="text-3xl font-bold text-white mb-2">Consulta de Adesões</h1>
                    <p class="text-blue-100">Informe seus dados para consultar suas contribuições</p>
                </div>
                
                <div class="p-6 md:p-8">
                    <x-jet-validation-errors />
                    
                    @if($errorMessage)
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm text-red-700 font-semibold">{{ $errorMessage }}</p>
                            </div>
                        </div>
                    @endif
                    
                    <form wire:submit.prevent="consultar" class="space-y-6">
                        {{-- CPF/CNPJ --}}
                        <div>
                            <x-input 
                                wire:model.defer="doc_num" 
                                label="CPF/CNPJ *" 
                                placeholder="000.000.000-00"
                                type="tel"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="mt-1"
                            />
                        </div>
                        
                        {{-- Data de Nascimento --}}
                        <div>
                            <x-input 
                                wire:model.defer="birth_date" 
                                label="Data de Nascimento *" 
                                type="date"
                                max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                class="mt-1"
                            />
                        </div>
                        
                        {{-- Telefone --}}
<div>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                                {{-- País (DDI) --}}
                                <div class="md:col-span-3">
                                    @php
                                        $ddiList = listDdi();
                                    @endphp
                                    <x-native-select
                                        label="País *" 
                                        wire:model.defer="contact_country" class="mt-1">
                                        @foreach($ddiList as $ddiValue => $ddiLabel)
                                            <option value="{{ $ddiValue }}">{{ $ddiLabel }}</option>
                                        @endforeach
                                    </x-native-select>
                                </div>
                                
                                {{-- DDD (apenas para Brasil) --}}
                                @if($contact_country === '55')
                                    <div class="md:col-span-2">
                                        <x-input 
                                            wire:model.defer="contact_ddd" 
                                            label="DDD *" 
                                            placeholder="00"
                                            type="tel"
                                            inputmode="numeric"
                                            pattern="[0-9]*"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)"
                                            maxlength="3"
                                            class="mt-1"
                                        />
                                    </div>
                                @endif
                                
                                {{-- Número --}}
                                <div class="{{ $contact_country === '55' ? 'md:col-span-7' : 'md:col-span-9' }}">
                                    <x-input 
                                        wire:model.defer="contact_num" 
                                        label="Número *" 
                                        placeholder="00000-0000"
                                        type="tel"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        class="mt-1"
                                    />
                                </div>
                            </div>
                        </div>
                        
                        {{-- Botão Consultar --}}
                        <div class="pt-4">
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg wire:loading.remove wire:target="consultar" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <svg wire:loading wire:target="consultar" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="consultar">Consultar Adesões</span>
                                <span wire:loading wire:target="consultar">Consultando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            {{-- Página de Boas-vindas e Listagem --}}
            <div class="space-y-6">
                {{-- Cabeçalho de Boas-vindas --}}
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-xl overflow-hidden">
                    <div class="px-6 py-8 text-center text-white">
                        <div class="flex justify-center mb-4">
                            <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h1 class="text-3xl font-bold mb-2">Bem-vindo, {{ $buyer->name }}!</h1>
                        <p class="text-green-100 text-lg">Aqui estão todas as suas contribuições</p>
                    </div>
                </div>
                
                {{-- Botão Sair --}}
                <div class="flex justify-end">
                    <button 
                        wire:click="sair"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Sair
                    </button>
                </div>
                
                {{-- Lista de Adesões --}}
                @if($orders->count() > 0)
                    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-gray-900">Suas Contribuições ({{ $orders->count() }})</h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <div class="p-6 hover:bg-gray-50 transition">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-start gap-4">
                                                <div class="flex-shrink-0">
                                                    @if($order->campaign)
                                                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                                                            @if($order->campaign->image_thumb)
                                                                <img src="{{ asset('storage/' . $order->campaign->image_thumb) }}" 
                                                                     alt="{{ $order->campaign->name }}" 
                                                                     class="w-full h-full object-cover">
                                                            @else
                                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                                        {{ $order->campaign->name ?? 'Campanha não encontrada' }}
                                                    </h3>
                                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                            </svg>
                                                            <span class="font-mono font-semibold">{{ $order->order_control }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col md:items-end gap-2">
                                            <div class="text-right">
                                                <div class="text-sm text-gray-600">Valor Contribuído</div>
                                                <div class="text-2xl font-black text-green-600">
                                                    {{ toMoney($order->amount_total, 'R$ ') }}
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                                    {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' : 
                                                       ($order->status === 'pending' ? 'bg-orange-100 text-orange-700' : 
                                                       'bg-gray-100 text-gray-700') }}">
                                                    @if($order->status === 'paid')
                                                        PAGO
                                                    @elseif($order->status === 'pending')
                                                        PENDENTE
                                                    @else
                                                        {{ strtoupper($order->status) }}
                                                    @endif
                                                </span>
                                                
                                                @if($order->campaign)
                                                    <a 
                                                        href="{{ campanhaUrl($order->campaign->customer_organization_slug, $order->campaign->slug, $order->id) }}" 
                                                        target="_blank"
                                                        class="px-3 py-1 text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline"
                                                        title="Ver detalhes da adesão"
                                                    >
                                                        Ver Detalhes
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-xl p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Nenhuma adesão encontrada</h3>
                        <p class="text-gray-600">Você ainda não realizou nenhuma contribuição.</p>
                    </div>
                @endif
            </div>
        @endif
        
    </div>
</div>
