<div class="mb-10">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .print-only {
            display: none;
        }

        .dropdown-menu {
            position: absolute !important;
            z-index: 1000 !important;
        }

        @media print {
            @page {
                size: auto;
                margin: 10mm;
            }

            html,
            body {
                height: auto !important;
                overflow: visible !important;
                background: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print,
            .no-print * {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .min-h-screen,
            .flex,
            .flex-grow,
            .overflow-hidden,
            .overflow-auto,
            .overflow-x-auto,
            .overflow-y-auto {
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
            }

            .overflow-x-auto>table {
                width: 100% !important;
                min-width: 100% !important;
                table-layout: auto !important;
            }

            .whitespace-nowrap {
                white-space: normal !important;
            }
        }
    </style>
    <x-notifications position="top-right" />

    <script>
        // Função para exibir notificação
        function showNotification(type, message) {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ?
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' :
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
            const title = type === 'success' ? 'Sucesso!' : 'Erro!';

            const notification = document.createElement('div');
            notification.className =
                `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-2xl max-w-md transform transition-all duration-300 ease-in-out pointer-events-auto`;
            notification.style.zIndex = '99999';
            notification.style.position = 'fixed';
            notification.style.transform = 'translateX(400px)';
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${icon}
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold text-base">${title}</p>
                        <p class="text-sm mt-1">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 ml-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(notification);

            // Anima entrada
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);

            // Remove após 5 segundos
            setTimeout(() => {
                notification.style.transform = 'translateX(400px)';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // Listener para eventos do Livewire
        document.addEventListener('livewire:load', function() {
            window.addEventListener('notification', event => {
                showNotification(event.detail.type, event.detail.message);
            });

            Livewire.on('showNotification', (type, message) => {
                showNotification(type, message);
            });
        });

        // Listener para Livewire 3
        document.addEventListener('livewire:init', function() {
            Livewire.on('showNotification', (type, message) => {
                showNotification(type, message);
            });
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('success', @json(session('success')));
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('error', @json(session('error')));
            });
        </script>
    @endif

    @if ($standaloneCreate || $standaloneEdit)

        {{-- Página dedicada para criar/editar cliente --}}
        <div class="max-w-7xl mx-auto bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $standaloneEdit ? 'Editar Cliente' : 'Novo Cliente' }}</h2>
                    <p class="text-sm text-gray-500">
                        {{ $standaloneEdit ? 'Atualize os dados do cliente selecionado.' : 'Preencha os dados para criar um novo cliente.' }}
                    </p>
                </div>
                <x-button flat label="Voltar"
                    href="{{ $standaloneEdit && $customerId ? route('configuracoes-customer', ['customer_id' => $customerId]) : route('configuracoes') }}"
                    as="a" />
            </div>

            <div class="space-y-6">
                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- DADOS PRINCIPAIS --}}
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Dados Principais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-input label="Razão Social / Nome do Cliente" wire:model.defer="customerNameCorporate"
                                placeholder="Razão social da empresa" class="w-full" />
                        </div>
                        <div>
                            <x-input label="Nome Fantasia / Nome Comercial"
                                wire:model.debounce.800ms="customerNameFantasy" placeholder="Nome fantasia"
                                class="w-full" />
                        </div>
                        <div>
                            <x-input label="Nome Curto" wire:model.defer="customerNameShort"
                                placeholder="Nome curto/abreviação" class="w-full" />
                        </div>
                        <div class="md:col-span-1">
                            <x-native-select label="Tipo de Documento" wire:model="customerDocType" class="w-full">
                                <option value="cpf">CPF</option>
                                <option value="cnpj">CNPJ</option>
                            </x-native-select>
                        </div>
                        <div class="md:col-span-1">
                            <x-input label="Número do Documento" wire:model.defer="customerDocNum"
                                placeholder="Digite o documento" class="w-full" />
                        </div>
                        @if ($standaloneEdit)
                            <div class="md:col-span-2">
                                <x-input label="Slug do Cliente" wire:model.defer="customerSlug"
                                    placeholder="slug-do-cliente" class="w-full" />
                                @error('customerSlug')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="md:col-span-2">
                                <label class="block text-base font-light uppercase text-black dark:text-gray-400"
                                    for="f3b7a19df077e8015b72f1e5877e0ac8">URL do Cliente</label>
                                <div
                                    class="placeholder-secondary-400 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 bg-gray-100 dark:bg-secondary-800 form-input block w-full sm:text-sm transition ease-in-out duration-100 focus:outline-none shadow-sm rounded-none cursor-not-allowed">
                                    www.sitecliente.com.br/{{ $customerSlug ?? '{slug}' }}</div>
                                @error('customerSlug')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>

                {{-- CONTATOS --}}
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Contatos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <x-input label="Contato Comercial" wire:model.defer="customerComercialContactName"
                                    placeholder="Nome do contato comercial" class="w-full" />
                            </div>
                            <div>
                                <x-input label="DDD Comercial" wire:model.defer="customerComercialContactDdd"
                                    placeholder="DDD" class="w-full" />
                            </div>
                            <div>
                                <x-inputs.maskable label="Telefone Comercial"
                                    wire:model.defer="customerComercialContactNum" placeholder="Telefone" class="w-full"
                                    mask="['####-####','#####-####']" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input label="E-mail Comercial" wire:model.defer="customerComercialContactEmail"
                                    placeholder="email@empresa.com" class="w-full" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <x-input label="Contato Financeiro" wire:model.defer="customerFinancialContactName"
                                    placeholder="Nome do contato financeiro" class="w-full" />
                            </div>
                            <div>
                                <x-input label="DDD Financeiro" wire:model.defer="customerFinancialContactDdd"
                                    placeholder="DDD" class="w-full" />
                            </div>
                            <div>
                                <x-inputs.maskable label="Telefone Financeiro"
                                    wire:model.defer="customerFinancialContactNum" placeholder="Telefone" class="w-full"
                                    mask="['####-####','#####-####']" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input label="E-mail Financeiro" wire:model.defer="customerFinancialContactEmail"
                                    placeholder="financeiro@empresa.com" class="w-full" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ENDEREÇO --}}
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Endereço</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                        <div class="lg:col-span-2">
                            <x-input label="Endereço" wire:model.defer="customerAddress" placeholder="Rua / Avenida"
                                class="w-full" />
                        </div>
                        <div>
                            <x-input label="Número" wire:model.defer="customerAddressNumber" placeholder="Número"
                                class="w-full" />
                        </div>
                        <div>
                            <x-input label="Complemento" wire:model.defer="customerAddressComplement"
                                placeholder="Complemento" class="w-full" />
                        </div>
                        <div>
                            <x-input label="Bairro" wire:model.defer="customerCityNeighborhood" placeholder="Bairro"
                                class="w-full" />
                        </div>
                        <div>
                            <x-input label="Cidade" wire:model.defer="customerCity" placeholder="Cidade"
                                class="w-full" />
                        </div>
                        <div>
                            <x-input label="Estado" wire:model.defer="customerState" placeholder="UF"
                                class="w-full uppercase" />
                        </div>
                        <div>
                            <x-inputs.maskable label="CEP" wire:model.defer="customerZipCode" mask="#####-###"
                                placeholder="_____-___" class="w-full" />
                        </div>
                    </div>
                </div>

                {{-- ONLINE --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Online</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input label="Site" wire:model.defer="customerUrlSite" placeholder="https://site.com"
                                class="w-full" />
                        </div>
                        <div>
                            <x-input label="Instagram" wire:model.defer="customerUrlInstagram"
                                placeholder="https://instagram.com/exemplo" class="w-full" />
                        </div>
                        <div>
                            <x-input label="Facebook" wire:model.defer="customerUrlFacebook"
                                placeholder="https://facebook.com/exemplo" class="w-full" />
                        </div>
                        <div class="md:col-span-3">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Gerar Fatura</p>
                                    <p class="text-xs text-gray-500 mt-1">Habilitar geração automática de notas fiscais
                                    </p>
                                </div>
                                <x-toggle wire:model.defer="customerGenerateInvoice" lg color="green" />
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <x-button flat label="Cancelar"
                        href="{{ $standaloneEdit && $customerId ? route('configuracoes-customer', ['customer_id' => $customerId]) : route('configuracoes') }}"
                        as="a" />
                    <x-button primary label="{{ $standaloneEdit ? 'Salvar Alterações' : 'Criar Cliente' }}"
                        wire:click="saveCustomer" spinner="saveCustomer" />
                </div>
            </div>
        </div>
    @else
        @if (count($customers ?? []))

            {{-- HEADER MODERNO COM GRADIENTE --}}
            <div class="mb-6 w-full max-w-7xl mx-auto bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl relative shadow-lg"
                style="overflow: visible;">

                <!-- Decorative Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid-pattern-config" width="8" height="8"
                                patternUnits="userSpaceOnUse">
                                <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid-pattern-config)" />
                    </svg>
                </div>

                <div class="relative z-10 p-6 space-y-6">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-white">Configurações</h1>
                                    <p class="text-sm text-white/90 mt-1">Gerencie usuários e módulos do sistema</p>
                                </div>
                            </div>
                        </div>

                        {{-- FILTRO DE CLIENTE E MENU DROPDOWN --}}
                        <div class="flex items-center gap-4">
                            {{-- Seletor de Cliente --}}
                            <div class="min-w-[300px]">
                                <select id="customerSelector" onchange="forceCustomerChange(this.value)"
                                    class="w-full uppercase pt-2 pb-2 bg-white/95 backdrop-blur-sm border-white/30 focus:border-white focus:ring-2 focus:ring-white/50 rounded-lg shadow-sm transition-all duration-200">
                                    <option value="">Selecione um cliente</option>
                                    @foreach ($customers as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $customerId == $item->id || request()->route('customer_id') == $item->id || request('customer_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name_corporate }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <script>
                                function forceCustomerChange(customerId) {
                                    console.log('Forçando mudança para:', customerId);
                                    const configuracoesBaseUrl = @json(route('configuracoes'));
                                    if (customerId) {
                                        // Recarrega a página com customer_id no path
                                        window.location.href = `${configuracoesBaseUrl}/${encodeURIComponent(customerId)}`;
                                    } else {
                                        // Volta para a rota base sem cliente selecionado
                                        window.location.href = configuracoesBaseUrl;
                                    }
                                }
                            </script>

                            {{-- Menu Dropdown --}}
                            <div class="relative">
                                <button onclick="toggleDropdown()"
                                    class="p-3 text-white gray-700 hover:bg-white hover:text-gray-900 transition-all duration-200 rounded-lg font-medium shadow-sm"
                                    title="Mais opções" id="dropdown-button">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>

                                <div id="dropdown-menu"
                                    class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-1 hidden"
                                    style="z-index: 99999;">
                                    <a href="{{ route('configuracoes-novo-cliente') }}"
                                        class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Novo Cliente
                                    </a>

                                    {{-- <button
                                    wire:click="resetPerfil"
                                    class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 w-full text-left transition-colors"
                                    onclick="hideDropdown()"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Atualizar
                                </button> --}}

                                    {{-- <button
                                    onclick="window.print(); hideDropdown();"
                                    class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 w-full text-left transition-colors"
                                    title="Imprimir configurações"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    Imprimir
                                </button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function toggleDropdown() {
                        const menu = document.getElementById('dropdown-menu');
                        if (menu) {
                            menu.classList.toggle('hidden');
                        }
                    }

                    function hideDropdown() {
                        const menu = document.getElementById('dropdown-menu');
                        if (menu) {
                            menu.classList.add('hidden');
                        }
                    }

                    // Fechar dropdown quando clicar fora
                    document.addEventListener('click', function(event) {
                        const button = document.getElementById('dropdown-button');
                        const menu = document.getElementById('dropdown-menu');

                        if (button && menu && !button.contains(event.target) && !menu.contains(event.target)) {
                            menu.classList.add('hidden');
                        }
                    });
                </script>

                <!-- Decorative Elements -->
                <div class="absolute top-4 right-4 w-16 h-16 bg-white/10 rounded-full blur-xl"></div>
                <div class="absolute bottom-4 left-4 w-12 h-12 bg-pink-400/20 rounded-full blur-lg"></div>
            </div>

            {{-- CONTEÚDO PRINCIPAL --}}
            @if ($customerId)
                <div wire:key="customer-{{ $customerId }}"
                    class="w-full max-w-7xl mx-auto bg-white shadow-sm border-x border-b rounded-b-lg"
                    x-data="{ activeTab: '{{ $activeTab }}' }">
                    {{-- TABS MODERNAS --}}
                    <div class="border-b border-gray-200 bg-gray-50 no-print">
                        <nav class="grid grid-cols-4 space-x-1" aria-label="Tabs">
                            <button type="button" @click="activeTab = 'cliente'"
                                :class="activeTab === 'cliente' ? 'border-b-2 border-blue-500 text-blue-600 bg-white' :
                                    'text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-blue-50'"
                                class="px-6 py-4 text-lg font-semibold transition-all duration-200">
                                <div class="flex justify-center items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Cliente
                                </div>
                            </button>
                            <button type="button" @click="activeTab = 'modulos'"
                                :class="activeTab === 'modulos' ? 'border-b-2 border-blue-500 text-blue-600 bg-white' :
                                    'text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-blue-50'"
                                class="px-6 py-4 text-lg font-semibold transition-all duration-200">
                                <div class="flex justify-center items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Módulos
                                </div>
                            </button>
                            <button type="button" @click="activeTab = 'usuarios'"
                                :class="activeTab === 'usuarios' ? 'border-b-2 border-blue-500 text-blue-600 bg-white' :
                                    'text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-blue-50'"
                                class="px-6 py-4 text-lg font-semibold transition-all duration-200">
                                <div class="flex justify-center items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Usuários
                                </div>
                            </button>
                            <button type="button" @click="activeTab = 'gateways'"
                                :class="activeTab === 'gateways' ? 'border-b-2 border-blue-500 text-blue-600 bg-white' :
                                    'text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-blue-50'"
                                class="px-6 py-4 text-lg font-semibold transition-all duration-200">
                                <div class="flex justify-center items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    Gateways
                                </div>
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        {{-- TAB: CLIENTE --}}
                        <div x-show="activeTab === 'cliente'">
                            {{-- Título para impressão --}}
                            <div class="print-only border-b-2 border-gray-300 pb-2 mb-4">
                                <h2 style="font-size: 16px; font-weight: bold; color: #000;">INFORMAÇÕES DO CLIENTE
                                </h2>
                            </div>
                            <div class="space-y-6">
                                @if ($customerId)
                                    <div class="flex justify-between items-center">
                                        <h2 class="text-lg font-semibold text-gray-800">Informações do Cliente</h2>
                                        <x-button primary sm label="Editar Cliente"
                                            href="{{ route('configuracoes-editar-cliente', ['customer_id' => $customerId]) }}"
                                            as="a" icon="pencil-alt" class="px-4 py-2" />
                                    </div>

                                    @php
                                        $customer = \App\Models\Customer::find($customerId);
                                    @endphp

                                    @if ($customer)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            {{-- DADOS PRINCIPAIS --}}
                                            <div
                                                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                                                <h3
                                                    class="text-sm font-semibold text-blue-800 uppercase tracking-wide mb-4">
                                                    Dados Principais</h3>
                                                <div class="space-y-3">
                                                    <div>
                                                        <div class="text-xs font-medium text-blue-600 uppercase mb-1">
                                                            Razão Social</div>
                                                        <div class="text-base font-semibold text-blue-900">
                                                            {{ $customer->name_corporate ?? '--' }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-blue-600 uppercase mb-1">
                                                            Nome Fantasia</div>
                                                        <div class="text-base font-semibold text-blue-900">
                                                            {{ $customer->name_fantasy ?? '--' }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-blue-600 uppercase mb-1">
                                                            Nome Curto</div>
                                                        <div class="text-base font-semibold text-blue-900">
                                                            {{ $customer->name_short ?? '--' }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-blue-600 uppercase mb-1">
                                                            {{ strtoupper($customer->doc_type ?? 'Documento') }}</div>
                                                        <div class="text-base font-semibold text-blue-900">
                                                            {{ $customer->doc_num ?? '--' }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-blue-600 uppercase mb-1">
                                                            Slug</div>
                                                        <div class="text-base font-semibold text-blue-900 font-mono">
                                                            {{ $customer->customer_slug ?? '--' }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- ENDEREÇO --}}
                                            <div
                                                class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-6 border border-amber-200">
                                                <h3
                                                    class="text-sm font-semibold text-amber-800 uppercase tracking-wide mb-4">
                                                    Endereço</h3>
                                                <div class="space-y-3">
                                                    <div>
                                                        <div class="text-xs font-medium text-amber-600 uppercase mb-1">
                                                            Logradouro</div>
                                                        <div class="text-base font-semibold text-amber-900">
                                                            {{ $customer->address ?? '--' }}
                                                            @if ($customer->address_number)
                                                                , {{ $customer->address_number }}
                                                            @endif
                                                            @if ($customer->address_complement)
                                                                - {{ $customer->address_complement }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-amber-600 uppercase mb-1">
                                                            Bairro</div>
                                                        <div class="text-base font-semibold text-amber-900">
                                                            {{ $customer->city_neighborhood ?? '--' }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-amber-600 uppercase mb-1">
                                                            Cidade / Estado</div>
                                                        <div class="text-base font-semibold text-amber-900">
                                                            {{ $customer->city ?? '--' }} /
                                                            {{ $customer->state ?? '--' }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-amber-600 uppercase mb-1">
                                                            CEP</div>
                                                        <div class="text-base font-semibold text-amber-900">
                                                            {{ $customer->zip_code ?? '--' }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- CONTATO COMERCIAL --}}
                                            <div
                                                class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                                                <h3
                                                    class="text-sm font-semibold text-green-800 uppercase tracking-wide mb-4">
                                                    Contato Comercial</h3>
                                                <div class="space-y-3">
                                                    <div>
                                                        <div class="text-xs font-medium text-green-600 uppercase mb-1">
                                                            Nome</div>
                                                        <div class="text-base font-semibold text-green-900">
                                                            {{ $customer->comercial_contact_name ?? '--' }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-green-600 uppercase mb-1">
                                                            E-mail</div>
                                                        <div class="text-base font-semibold text-green-900 break-all">
                                                            {{ $customer->comercial_contact_email ?? '--' }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-green-600 uppercase mb-1">
                                                            Telefone</div>
                                                        <div class="text-base font-semibold text-green-900">
                                                            @if ($customer->comercial_contact_ddd && $customer->comercial_contact_num)
                                                                ({{ $customer->comercial_contact_ddd }})
                                                                {{ $customer->comercial_contact_num }}
                                                            @else
                                                                --
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- CONTATO FINANCEIRO --}}
                                            <div
                                                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
                                                <h3
                                                    class="text-sm font-semibold text-purple-800 uppercase tracking-wide mb-4">
                                                    Contato Financeiro</h3>
                                                <div class="space-y-3">
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-purple-600 uppercase mb-1">
                                                            Nome</div>
                                                        <div class="text-base font-semibold text-purple-900">
                                                            {{ $customer->financial_contact_name ?? '--' }}</div>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-purple-600 uppercase mb-1">
                                                            E-mail</div>
                                                        <div class="text-base font-semibold text-purple-900 break-all">
                                                            {{ $customer->financial_contact_email ?? '--' }}</div>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-purple-600 uppercase mb-1">
                                                            Telefone</div>
                                                        <div class="text-base font-semibold text-purple-900">
                                                            @if ($customer->financial_contact_ddd && $customer->financial_contact_num)
                                                                ({{ $customer->financial_contact_ddd }})
                                                                {{ $customer->financial_contact_num }}
                                                            @else
                                                                --
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- LINKS E CONFIGURAÇÕES --}}
                                            <div
                                                class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-6 border border-indigo-200 md:col-span-2">
                                                <h3
                                                    class="text-sm font-semibold text-indigo-800 uppercase tracking-wide mb-4">
                                                    Links e Configurações</h3>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-indigo-600 uppercase mb-1">
                                                            Site</div>
                                                        <div class="text-sm font-semibold text-indigo-900 break-all">
                                                            @if ($customer->url_site)
                                                                <a href="{{ $customer->url_site }}" target="_blank"
                                                                    class="hover:underline">{{ $customer->url_site }}</a>
                                                            @else
                                                                --
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-indigo-600 uppercase mb-1">
                                                            Instagram</div>
                                                        <div class="text-sm font-semibold text-indigo-900 break-all">
                                                            @if ($customer->url_instagram)
                                                                <a href="{{ $customer->url_instagram }}"
                                                                    target="_blank"
                                                                    class="hover:underline">{{ $customer->url_instagram }}</a>
                                                            @else
                                                                --
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-indigo-600 uppercase mb-1">
                                                            Facebook</div>
                                                        <div class="text-sm font-semibold text-indigo-900 break-all">
                                                            @if ($customer->url_facebook)
                                                                <a href="{{ $customer->url_facebook }}"
                                                                    target="_blank"
                                                                    class="hover:underline">{{ $customer->url_facebook }}</a>
                                                            @else
                                                                --
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-indigo-600 uppercase mb-1">
                                                            Gerar Fatura</div>
                                                        <div class="text-sm font-semibold text-indigo-900">
                                                            @if ($customer->generate_invoice)
                                                                <span class="text-green-600">Sim</span>
                                                            @else
                                                                <span class="text-gray-500">Não</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Cliente não encontrado
                                            </h3>
                                            <p class="text-sm text-gray-600">Não foi possível carregar as informações
                                                do cliente.</p>
                                        </div>
                                    @endif
                                @else
                                    <div
                                        class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Selecione um Cliente</h3>
                                        <p class="text-sm text-gray-600 mb-4">Escolha um cliente no filtro acima para
                                            visualizar e editar suas informações.</p>
                                        <x-button primary sm label="Criar Novo Cliente"
                                            wire:click="openNewCustomerModal" icon="plus" />
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- TAB: MÓDULOS --}}
                        <div x-show="activeTab === 'modulos'">
                            {{-- Título para impressão --}}
                            <div class="print-only border-b-2 border-gray-300 pb-2 mb-4">
                                <h2 style="font-size: 16px; font-weight: bold; color: #000;">MÓDULOS DO SISTEMA</h2>
                            </div>
                            <div class="space-y-4">
                                <h2 class="text-lg font-semibold text-gray-800">Módulos do Cliente</h2>

                                @if (!$customerId)
                                    <div
                                        class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Selecione um Cliente</h3>
                                        <p class="text-sm text-gray-600">Escolha um cliente no topo para gerenciar os
                                            módulos disponíveis.</p>
                                    </div>
                                @elseif(($allModules ?? collect())->count())
                                    @php $blockedModules = ['workshops']; @endphp
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                                        @foreach ($allModules as $module)
                                            @php $slug = $module->module_slug ?? $module->slug ?? null; @endphp
                                            @if ($slug && in_array($slug, $blockedModules))
                                                @continue
                                            @endif
                                            <div
                                                class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                                <div class="p-6">
                                                    <div class="flex items-start justify-between mb-4">
                                                        <div class="flex-1">
                                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                                {{ $module->module_name }}</h3>
                                                            <p
                                                                class="text-xs text-gray-500 uppercase tracking-wide mb-2">
                                                                {{ $module->slug }}</p>
                                                            <p class="text-sm text-gray-600 line-clamp-2">
                                                                {{ $module->module_description }}</p>
                                                            {{-- Indicador de status global do módulo --}}
                                                            @if (!$module->module_active)
                                                                <div class="mt-2">
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                        Desativado Globalmente
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            @if (in_array($module->id, $customerModuleIds))
                                                                <span
                                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                    Ativo
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                                    <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                    Inativo
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="mt-4">
                                                        @if (in_array($module->id, $customerModuleIds))
                                                            <x-button red outline sm
                                                                wire:click="toggleModule('{{ $module->id }}')"
                                                                label="Remover Módulo" icon="x"
                                                                class="w-full" />
                                                        @elseif(!$module->module_active)
                                                            <x-button gray outline sm label="Módulo Desativado"
                                                                icon="exclamation-triangle" class="w-full" disabled />
                                                        @else
                                                            <x-button green outline sm
                                                                wire:click="toggleModule('{{ $module->id }}')"
                                                                label="Ativar Módulo" icon="check"
                                                                class="w-full" />
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum módulo disponível
                                        </h3>
                                        <p class="text-sm text-gray-600">Não há módulos cadastrados para este
                                            app/cliente ou não foram carregados.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- TAB: USUÁRIOS --}}
                        <div x-show="activeTab === 'usuarios'">
                            {{-- Título para impressão --}}
                            <div class="print-only border-b-2 border-gray-300 pb-2 mb-4">
                                <h2 style="font-size: 16px; font-weight: bold; color: #000;">USUÁRIOS DO SISTEMA</h2>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-lg font-semibold text-gray-800">Usuários do Cliente
                                        ({{ ($customerUsers ?? collect())->count() }})</h2>
                                    <x-button primary sm label="Novo Usuário"
                                        href="{{ route('configuracoes-novo-usuario', ['customer_id' => $customerId]) }}"
                                        as="a" icon="plus" class="px-4 py-2" />
                                </div>

                                @if (($customerUsers ?? collect())->count())
                                    <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            Nome</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            E-mail</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            Papel</th>
                                                        <th
                                                            class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            Eventos</th>
                                                        <th
                                                            class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            Campanhas</th>
                                                        <th
                                                            class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            Assinaturas</th>
                                                        <th
                                                            class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach ($customerUsers as $user)
                                                        <tr wire:key="customer-user-{{ $user->id }}"
                                                            class="hover:bg-gray-50 transition-colors duration-150">
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex items-center">
                                                                    <div class="flex-shrink-0 h-10 w-10">
                                                                        <div
                                                                            class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center text-white font-semibold text-sm">
                                                                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="ml-4">
                                                                        <div class="text-sm font-medium text-gray-900">
                                                                            {{ $user->name }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="text-sm text-gray-600">{{ $user->email }}
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded-full
                                                                {{ ($user->pivot->user_role ?? '') === 'admin'
                                                                    ? 'bg-red-100 text-red-800'
                                                                    : (($user->pivot->user_role ?? '') === 'owner'
                                                                        ? 'bg-blue-100 text-blue-800'
                                                                        : 'bg-gray-100 text-gray-800') }}">
                                                                    {{ strtoupper($user->pivot->user_role ?? 'user') }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                @if (($user->pivot->can_events ?? 0) == 1)
                                                                    <span
                                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                        Sim
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                        Não
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                @if (($user->pivot->can_campaigns ?? 0) == 1)
                                                                    <span
                                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                        Sim
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                        Não
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                @if (($user->pivot->can_subscriptions ?? 0) == 1)
                                                                    <span
                                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                        Sim
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                        Não
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <a href="{{ route('configuracoes-editar-usuario', ['customer_id' => $customerId, 'user_id' => $user->id]) }}"
                                                                    class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                    <span>Editar</span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        {{-- @dump($user->toArray()) --}}
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum usuário encontrado
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-4">Este cliente ainda não possui usuários
                                            cadastrados.</p>
                                        <x-button primary sm label="Criar Primeiro Usuário"
                                            href="{{ route('configuracoes-novo-usuario', ['customer_id' => $customerId]) }}"
                                            as="a" icon="plus" />
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- TAB: GATEWAYS --}}
                        <div x-show="activeTab === 'gateways'">
                            <div class="print-only border-b-2 border-gray-300 pb-2 mb-4">
                                <h2 style="font-size: 16px; font-weight: bold; color: #000;">GATEWAYS DE PAGAMENTO</h2>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-lg font-semibold text-gray-800">Gateways de Pagamento
                                        ({{ ($customerGateways ?? collect())->count() }})</h2>
                                    <x-button primary sm label="Novo Gateway" wire:click="openNewGatewayModal"
                                        icon="plus" class="px-4 py-2" />
                                </div>

                                @if (($customerGateways ?? collect())->count() || ($customerGatewaysInactive ?? collect())->count())
                                    {{-- Painel de Filtros --}}
                                    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden mb-6"
                                        x-data="{ expanded: false }">
                                        {{-- Cabeçalho do Painel de Filtros --}}
                                        <div class="bg-gray-50 px-4 py-3 cursor-pointer hover:bg-gray-100 transition-colors border-b border-gray-300"
                                            @click="expanded = !expanded">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <svg class="w-5 h-5 text-gray-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                                    </svg>
                                                    <div>
                                                        <h3
                                                            class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                                                            Filtros
                                                            @php
                                                                $activeFiltersCount = 0;
                                                                if (!empty($filterGatewaySearch)) {
                                                                    $activeFiltersCount++;
                                                                }
                                                                if ($filterPayBoleto) {
                                                                    $activeFiltersCount++;
                                                                }
                                                                if ($filterPayPix) {
                                                                    $activeFiltersCount++;
                                                                }
                                                                if ($filterPaySlipPix) {
                                                                    $activeFiltersCount++;
                                                                }
                                                                if ($filterPayCardDebit) {
                                                                    $activeFiltersCount++;
                                                                }
                                                                if ($filterPayCardCredit) {
                                                                    $activeFiltersCount++;
                                                                }
                                                                if ($filterUseEvents) {
                                                                    $activeFiltersCount++;
                                                                }
                                                                if ($filterUseCampaigns) {
                                                                    $activeFiltersCount++;
                                                                }
                                                                if ($filterHideFees) {
                                                                    $activeFiltersCount++;
                                                                }
                                                            @endphp
                                                            @if ($activeFiltersCount > 0)
                                                                <span
                                                                    class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-gray-700 bg-gray-200 rounded">
                                                                    {{ $activeFiltersCount }}
                                                                    ativo{{ $activeFiltersCount > 1 ? 's' : '' }}
                                                                </span>
                                                            @endif
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    @if ($activeFiltersCount > 0)
                                                        <x-button flat xs negative label="Limpar" icon="x"
                                                            wire:click.stop="clearGatewayFilters" />
                                                    @endif
                                                    <svg class="w-5 h-5 text-gray-600 transition-transform"
                                                        :class="{ 'rotate-180': expanded }" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Conteúdo dos Filtros --}}
                                        <div x-show="expanded" x-cloak
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 -translate-y-1" class="bg-white">
                                            <div class="p-4 space-y-4">
                                                {{-- Busca por Texto --}}
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                                        Buscar
                                                    </label>
                                                    <x-input wire:model.live.debounce.500ms="filterGatewaySearch"
                                                        placeholder="Nome, slug ou descrição..." class="w-full" />
                                                </div>

                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                                    {{-- Parametrização de Visualização --}}
                                                    <div class="border border-gray-200 rounded p-3 lg:col-span-2">
                                                        <h4 class="text-xs font-semibold text-gray-700 mb-2.5">
                                                            Parametrização
                                                        </h4>
                                                        <label
                                                            class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded w-fit">
                                                            <x-checkbox wire:model.live="filterHideFees"
                                                                id="filter_hide_fees" />
                                                            <span class="text-sm text-gray-700">Ocultar taxas</span>
                                                        </label>
                                                    </div>

                                                    {{-- Filtros de Métodos de Pagamento --}}
                                                    <div class="border border-gray-200 rounded p-3">
                                                        <h4 class="text-xs font-semibold text-gray-700 mb-2.5">
                                                            Métodos de Pagamento
                                                        </h4>
                                                        <div class="grid grid-cols-3 gap-2">
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <x-checkbox wire:model.live="filterPayBoleto"
                                                                    id="filter_boleto" />
                                                                <span class="text-sm text-gray-700">Boleto</span>
                                                            </label>
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <x-checkbox wire:model.live="filterPayPix"
                                                                    id="filter_pix" />
                                                                <span class="text-sm text-gray-700">PIX</span>
                                                            </label>
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <x-checkbox wire:model.live="filterPaySlipPix"
                                                                    id="filter_slip_pix" />
                                                                <span class="text-sm text-gray-700">Slip PIX</span>
                                                            </label>
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <x-checkbox wire:model.live="filterPayCardDebit"
                                                                    id="filter_card_debit" />
                                                                <span class="text-sm text-gray-700">Cartão
                                                                    Débito</span>
                                                            </label>
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <x-checkbox wire:model.live="filterPayCardCredit"
                                                                    id="filter_card_credit" />
                                                                <span class="text-sm text-gray-700">Cartão
                                                                    Crédito</span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    {{-- Filtros de Uso --}}
                                                    <div class="border border-gray-200 rounded p-3">
                                                        <h4 class="text-xs font-semibold text-gray-700 mb-2.5">
                                                            Disponibilidade
                                                        </h4>
                                                        <div class="grid grid-cols-3 gap-2">
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <x-checkbox wire:model.live="filterUseEvents"
                                                                    id="filter_use_events" />
                                                                <span class="text-sm text-gray-700">Eventos</span>
                                                            </label>
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <x-checkbox wire:model.live="filterUseCampaigns"
                                                                    id="filter_use_campaigns" />
                                                                <span class="text-sm text-gray-700">Campanhas</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Resumo de Resultados --}}
                                                @if ($activeFiltersCount > 0)
                                                    <div class="bg-gray-50 border border-gray-200 rounded p-3">
                                                        <div class="text-xs text-gray-600">
                                                            <span
                                                                class="font-semibold text-gray-800">{{ ($customerGateways ?? collect())->count() }}</span>
                                                            gateway(s) encontrado(s)
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <br>

                                    {{-- Gateways Ativos --}}
                                    @if (($customerGateways ?? collect())->count())
                                        <div
                                            class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm mb-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                                        <tr>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                                Gateway</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                                Métodos</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                                Uso</th>
                                                            <th
                                                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                                Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @foreach ($customerGateways as $gateway)
                                                            <tr
                                                                class="hover:bg-gray-50 transition-colors duration-150">
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <div class="flex items-center gap-3">
                                                                        <div class="flex-shrink-0">
                                                                            <div
                                                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                                                                                <svg class="w-5 h-5" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                                                </svg>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="text-sm font-medium text-gray-900"
                                                                                title="{{ $gateway->pay_gateway_slug }}">
                                                                                {{ $gateway->pay_gateway_label }}</div>
                                                                            @if ($gateway->pay_gateway_description)
                                                                                <div
                                                                                    class="text-xs text-gray-500 line-clamp-1">
                                                                                    {{ $gateway->pay_gateway_description }}
                                                                                </div>
                                                                            @endif
                                                                            @if ($hasCodSubcontaIdColumn)
                                                                                <div
                                                                                    class="text-xs text-gray-400 mt-0.5">
                                                                                    <span
                                                                                        class="font-medium">CodSubconta:</span>
                                                                                    {{ !empty($gateway->cod_subconta_id) ? $gateway->cod_subconta_id : '----' }}
                                                                                </div>
                                                                            @endif
                                                                            @if (!empty($gateway->conta_cod) || !empty($gateway->conta_banco) || !empty($gateway->conta_numero))
                                                                                <div
                                                                                    class="text-xs text-gray-500 mt-1.5 space-y-0.5">
                                                                                    <div><span
                                                                                            class="font-medium">Conta
                                                                                            Cod:</span>
                                                                                        {{ $gateway->conta_cod ?: '----' }}
                                                                                    </div>
                                                                                    <div><span
                                                                                            class="font-medium">Banco:</span>
                                                                                        {{ $gateway->conta_banco ?: '----' }}
                                                                                        @if (!empty($gateway->conta_banco_descricao))
                                                                                            -
                                                                                            {{ $gateway->conta_banco_descricao }}
                                                                                        @endif
                                                                                    </div>
                                                                                    <div><span
                                                                                            class="font-medium">Tipo:</span>
                                                                                        {{ $gateway->conta_tipo ?: '----' }}
                                                                                    </div>
                                                                                    <div><span
                                                                                            class="font-medium">Agência:</span>
                                                                                        {{ $gateway->conta_agencia ?: '----' }}
                                                                                        @if (!empty($gateway->conta_agencia_dv))
                                                                                            -{{ $gateway->conta_agencia_dv }}
                                                                                        @endif
                                                                                    </div>
                                                                                    <div><span
                                                                                            class="font-medium">Conta:</span>
                                                                                        {{ $gateway->conta_numero ?: '----' }}
                                                                                        @if (!empty($gateway->conta_numero_dv))
                                                                                            -{{ $gateway->conta_numero_dv }}
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <div class="flex flex-wrap gap-1">
                                                                        @if ($gateway->pay_boleto)
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded">Boleto</span>
                                                                        @endif
                                                                        @if ($gateway->pay_pix)
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">PIX</span>
                                                                        @endif
                                                                        @if ($gateway->pay_slip_pix)
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-purple-100 text-purple-800 rounded">Slip
                                                                                PIX</span>
                                                                        @endif
                                                                        @if ($gateway->pay_card_debit)
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">Débito</span>
                                                                        @endif
                                                                        @if ($gateway->pay_card_credit)
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-orange-100 text-orange-800 rounded">Crédito</span>
                                                                        @endif
                                                                        @if (
                                                                            !$gateway->pay_boleto &&
                                                                                !$gateway->pay_pix &&
                                                                                !$gateway->pay_slip_pix &&
                                                                                !$gateway->pay_card_debit &&
                                                                                !$gateway->pay_card_credit)
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">Nenhum</span>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <div class="flex flex-col gap-1">
                                                                        @if ($gateway->use_events ?? 1)
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded">
                                                                                <svg class="w-3 h-3 mr-1"
                                                                                    fill="currentColor"
                                                                                    viewBox="0 0 20 20">
                                                                                    <path fill-rule="evenodd"
                                                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                                                        clip-rule="evenodd" />
                                                                                </svg>
                                                                                Eventos
                                                                            </span>
                                                                        @else
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-500 rounded">
                                                                                <svg class="w-3 h-3 mr-1"
                                                                                    fill="currentColor"
                                                                                    viewBox="0 0 20 20">
                                                                                    <path fill-rule="evenodd"
                                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                                        clip-rule="evenodd" />
                                                                                </svg>
                                                                                Eventos
                                                                            </span>
                                                                        @endif
                                                                        @if ($gateway->use_campaigns ?? 1)
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded">
                                                                                <svg class="w-3 h-3 mr-1"
                                                                                    fill="currentColor"
                                                                                    viewBox="0 0 20 20">
                                                                                    <path
                                                                                        d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                                                                </svg>
                                                                                Campanhas
                                                                            </span>
                                                                        @else
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-500 rounded">
                                                                                <svg class="w-3 h-3 mr-1"
                                                                                    fill="currentColor"
                                                                                    viewBox="0 0 20 20">
                                                                                    <path fill-rule="evenodd"
                                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                                        clip-rule="evenodd" />
                                                                                </svg>
                                                                                Campanhas
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td
                                                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                    <x-button flat primary label="Editar"
                                                                        wire:click="openEditGatewayModal('{{ $gateway->id }}')"
                                                                        class="text-sm" />
                                                                </td>
                                                            </tr>
                                                            {{-- Linha expandida com taxas --}}
                                                            @unless ($filterHideFees)
                                                                <tr class="bg-gray-50/50 border-t border-gray-200">
                                                                    <td colspan="4" class="px-6 py-3">
                                                                        @php
                                                                            $boletoFees = $gateway->pay_boleto_fees_json
                                                                                ? json_decode(
                                                                                    $gateway->pay_boleto_fees_json,
                                                                                    true,
                                                                                )
                                                                                : [];
                                                                            $pixFees = $gateway->pay_pix_fees_json
                                                                                ? json_decode(
                                                                                    $gateway->pay_pix_fees_json,
                                                                                    true,
                                                                                )
                                                                                : [];
                                                                            $slipPixFees = $gateway->pay_slip_pix_fees_json
                                                                                ? json_decode(
                                                                                    $gateway->pay_slip_pix_fees_json,
                                                                                    true,
                                                                                )
                                                                                : [];
                                                                            $creditFees = $gateway->pay_gateway_installment_fees_json
                                                                                ? json_decode(
                                                                                    $gateway->pay_gateway_installment_fees_json,
                                                                                    true,
                                                                                )
                                                                                : [];
                                                                            $allParcelas = range(1, 12);
                                                                            $formatPercent = function (
                                                                                $fees,
                                                                                $parcela,
                                                                            ) {
                                                                                if (!isset($fees[$parcela])) {
                                                                                    return '<span class="text-gray-300">--</span>';
                                                                                }

                                                                                $valor = str_replace(
                                                                                    ',',
                                                                                    '.',
                                                                                    (string) $fees[$parcela],
                                                                                );
                                                                                $valor = (float) $valor;
                                                                                return number_format(
                                                                                    $valor,
                                                                                    2,
                                                                                    ',',
                                                                                    '.',
                                                                                ) . '%';
                                                                            };
                                                                            $formatCents = function ($value) {
                                                                                if ($value === null || $value === '') {
                                                                                    return '<span class="text-gray-300">--</span>';
                                                                                }

                                                                                $amount = (int) $value;
                                                                                return 'R$ ' .
                                                                                    number_format(
                                                                                        $amount / 100,
                                                                                        2,
                                                                                        ',',
                                                                                        '.',
                                                                                    );
                                                                            };
                                                                        @endphp
                                                                        <div x-data="{ selectedFeeRow: '' }"
                                                                            class="border border-gray-200 rounded-lg overflow-hidden">
                                                                            <table
                                                                                class="min-w-full divide-y divide-gray-200">
                                                                                <thead class="bg-gray-100">
                                                                                    <tr x-on:click="selectedFeeRow = 'boleto'"
                                                                                        :class="selectedFeeRow === 'boleto' ?
                                                                                            'bg-indigo-100/80' :
                                                                                            'hover:bg-indigo-50/60'"
                                                                                        class="cursor-pointer transition-colors duration-150">
                                                                                        <th
                                                                                            class="px-3 py-2 text-xs font-semibold text-gray-700 text-left">
                                                                                            Taxa</th>
                                                                                        <th
                                                                                            class="px-3 py-2 text-xs font-semibold text-gray-700 text-center border-l border-gray-200">
                                                                                            Fixo</th>
                                                                                        @foreach ($allParcelas as $parcela)
                                                                                            <th
                                                                                                class="px-2 py-2 text-xs font-semibold text-gray-700 text-center border-l border-gray-200">
                                                                                                {{ $parcela }}x</th>
                                                                                        @endforeach
                                                                                        <th
                                                                                            class="px-3 py-2 text-xs font-semibold text-gray-700 text-center border-l border-gray-200">
                                                                                            Ações</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody
                                                                                    class="bg-white divide-y divide-gray-200">
                                                                                    <tr x-on:click="selectedFeeRow = 'pix'"
                                                                                        :class="selectedFeeRow === 'pix' ?
                                                                                            'bg-indigo-100/80' :
                                                                                            'hover:bg-indigo-50/60'"
                                                                                        class="cursor-pointer transition-colors duration-150">
                                                                                        <td
                                                                                            class="px-3 py-2 text-xs font-medium text-gray-700">
                                                                                            Boleto</td>
                                                                                        <td
                                                                                            class="px-3 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                            {!! $formatCents($gateway->fee_boleto_fixed_amount) !!}</td>
                                                                                        @foreach ($allParcelas as $parcela)
                                                                                            <td
                                                                                                class="px-2 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                                {!! $formatPercent($boletoFees, $parcela) !!}</td>
                                                                                        @endforeach
                                                                                        <td
                                                                                            class="px-3 py-2 text-center border-l border-gray-200">
                                                                                            <x-button flat primary xs
                                                                                                icon="calculator"
                                                                                                wire:click="openBoletoFeesModalForGateway('{{ $gateway->id }}')" />
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr x-on:click="selectedFeeRow = 'pix_parcelado'"
                                                                                        :class="selectedFeeRow === 'pix_parcelado'
                                                                                            ?
                                                                                            'bg-indigo-100/80' :
                                                                                            'hover:bg-indigo-50/60'"
                                                                                        class="cursor-pointer transition-colors duration-150">
                                                                                        <td
                                                                                            class="px-3 py-2 text-xs font-medium text-gray-700">
                                                                                            PIX</td>
                                                                                        <td
                                                                                            class="px-3 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                            {!! $formatCents($gateway->fee_pix_fixed_amount) !!}</td>
                                                                                        @foreach ($allParcelas as $parcela)
                                                                                            <td
                                                                                                class="px-2 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                                {!! $formatPercent($pixFees, $parcela) !!}</td>
                                                                                        @endforeach
                                                                                        <td
                                                                                            class="px-3 py-2 text-center border-l border-gray-200">
                                                                                            <x-button flat primary xs
                                                                                                icon="calculator"
                                                                                                wire:click="openPixFeesModalForGateway('{{ $gateway->id }}')" />
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr
                                                                                        class="hover:bg-indigo-50/60 transition-colors duration-150">
                                                                                        <td
                                                                                            class="px-3 py-2 text-xs font-medium text-gray-700">
                                                                                            PIX Parcelado - Adicional ao PIX
                                                                                        </td>
                                                                                        <td
                                                                                            class="px-3 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                            {!! $formatCents($gateway->fee_slip_pix_fixed_amount) !!}</td>
                                                                                        @foreach ($allParcelas as $parcela)
                                                                                            <td
                                                                                                class="px-2 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                                {!! $formatPercent($slipPixFees, $parcela) !!}</td>
                                                                                        @endforeach
                                                                                        <td
                                                                                            class="px-3 py-2 text-center border-l border-gray-200">
                                                                                            <x-button flat primary xs
                                                                                                icon="calculator"
                                                                                                wire:click="openSlipPixFeesModalForGateway('{{ $gateway->id }}')" />
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr x-on:click="selectedFeeRow = 'credito'"
                                                                                        :class="selectedFeeRow === 'credito' ?
                                                                                            'bg-indigo-100/80' :
                                                                                            'hover:bg-indigo-50/60'"
                                                                                        class="cursor-pointer transition-colors duration-150">
                                                                                        <td
                                                                                            class="px-3 py-2 text-xs font-medium text-gray-700">
                                                                                            Crédito</td>
                                                                                        <td
                                                                                            class="px-3 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                            {!! $formatCents($gateway->fee_credit_fixed_amount) !!}</td>
                                                                                        @foreach ($allParcelas as $parcela)
                                                                                            <td
                                                                                                class="px-2 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                                {!! $formatPercent($creditFees, $parcela) !!}</td>
                                                                                        @endforeach
                                                                                        <td
                                                                                            class="px-3 py-2 text-center border-l border-gray-200">
                                                                                            <x-button flat primary xs
                                                                                                icon="calculator"
                                                                                                wire:click="openInstallmentFeesModalForGateway('{{ $gateway->id }}')" />
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endunless
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Gateways Desativados - Card com Destaque --}}
                                    @if (($customerGatewaysInactive ?? collect())->count())
                                        <div class="mt-8">
                                            {{-- Card Destacado com Bordas e Background de Alerta --}}
                                            <div
                                                class="bg-gradient-to-br from-red-50 via-orange-50 to-amber-50 border-2 border-red-300 rounded-2xl shadow-lg overflow-hidden">
                                                {{-- Cabeçalho do Card Desativados --}}
                                                <div
                                                    class="bg-gradient-to-r from-red-100 to-orange-100 border-b-2 border-red-300 px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        {{-- Ícone de Alerta --}}
                                                        <div class="flex-shrink-0">
                                                            <div
                                                                class="h-12 w-12 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg">
                                                                <svg class="w-6 h-6 text-white" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2.5"
                                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        {{-- Título e Descrição --}}
                                                        <div class="flex-1">
                                                            <h3
                                                                class="text-xl font-bold text-red-800 uppercase tracking-wide flex items-center gap-2">
                                                                <span>Gateways Desativados</span>
                                                                <span
                                                                    class="inline-flex items-center justify-center px-3 py-1 text-sm font-bold leading-none text-red-100 bg-red-600 rounded-full shadow-md">
                                                                    {{ ($customerGatewaysInactive ?? collect())->count() }}
                                                                </span>
                                                            </h3>
                                                            <p class="text-sm text-red-700 mt-1 font-medium">
                                                                <svg class="w-4 h-4 inline mr-1" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                Estes gateways não estão disponíveis para uso no sistema
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tabela de Gateways Desativados --}}
                                                <div
                                                    class="overflow-hidden bg-white/80 backdrop-blur-sm border-t-2 border-red-200">
                                                    <div class="overflow-x-auto">
                                                        <table class="min-w-full divide-y divide-red-200">
                                                            <thead class="bg-gradient-to-r from-red-100 to-orange-100">
                                                                <tr>
                                                                    <th
                                                                        class="px-6 py-3 text-left text-xs font-bold text-red-800 uppercase tracking-wider">
                                                                        Gateway</th>
                                                                    <th
                                                                        class="px-6 py-3 text-left text-xs font-bold text-red-800 uppercase tracking-wider">
                                                                        Métodos</th>
                                                                    <th
                                                                        class="px-6 py-3 text-left text-xs font-bold text-red-800 uppercase tracking-wider">
                                                                        Uso</th>
                                                                    <th
                                                                        class="px-6 py-3 text-right text-xs font-bold text-red-800 uppercase tracking-wider">
                                                                        Ações</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody
                                                                class="bg-white/60 backdrop-blur-sm divide-y divide-red-200">
                                                                @foreach ($customerGatewaysInactive as $gateway)
                                                                    <tr
                                                                        class="hover:bg-red-50/70 transition-all duration-150">
                                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                                            <div class="flex items-center gap-3">
                                                                                <div class="flex-shrink-0">
                                                                                    <div
                                                                                        class="h-11 w-11 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-semibold shadow-md border-2 border-red-300 relative">
                                                                                        <svg class="w-5 h-5"
                                                                                            fill="none"
                                                                                            stroke="currentColor"
                                                                                            viewBox="0 0 24 24">
                                                                                            <path
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                                                        </svg>
                                                                                        {{-- Badge de Desativado --}}
                                                                                        <span
                                                                                            class="absolute -top-1 -right-1 flex h-4 w-4">
                                                                                            <span
                                                                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                                                            <span
                                                                                                class="relative inline-flex rounded-full h-4 w-4 bg-red-600 border-2 border-white"></span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                                <div>
                                                                                    <div class="text-sm font-bold text-gray-900 flex items-center gap-2"
                                                                                        title="{{ $gateway->pay_gateway_slug }}">
                                                                                        {{ $gateway->pay_gateway_label }}
                                                                                        <span
                                                                                            class="inline-flex items-center px-2 py-0.5 text-xs font-bold text-red-700 bg-red-100 rounded-full border border-red-300">
                                                                                            INATIVO
                                                                                        </span>
                                                                                    </div>
                                                                                    @if ($gateway->pay_gateway_description)
                                                                                        <div
                                                                                            class="text-xs text-gray-600 line-clamp-1 mt-0.5">
                                                                                            {{ $gateway->pay_gateway_description }}
                                                                                        </div>
                                                                                    @endif
                                                                                    @if ($hasCodSubcontaIdColumn)
                                                                                        <div
                                                                                            class="text-xs text-gray-400 mt-0.5">
                                                                                            <span
                                                                                                class="font-medium">CodSubconta:</span>
                                                                                            {{ !empty($gateway->cod_subconta_id) ? $gateway->cod_subconta_id : '----' }}
                                                                                        </div>
                                                                                    @endif
                                                                                    @if (!empty($gateway->conta_cod) || !empty($gateway->conta_banco) || !empty($gateway->conta_numero))
                                                                                        <div
                                                                                            class="text-xs text-gray-500 mt-1.5 space-y-0.5">
                                                                                            <div><span
                                                                                                    class="font-medium">Conta
                                                                                                    Cod:</span>
                                                                                                {{ $gateway->conta_cod ?: '----' }}
                                                                                            </div>
                                                                                            <div><span
                                                                                                    class="font-medium">Banco:</span>
                                                                                                {{ $gateway->conta_banco ?: '----' }}
                                                                                                @if (!empty($gateway->conta_banco_descricao))
                                                                                                    -
                                                                                                    {{ $gateway->conta_banco_descricao }}
                                                                                                @endif
                                                                                            </div>
                                                                                            <div><span
                                                                                                    class="font-medium">Tipo:</span>
                                                                                                {{ $gateway->conta_tipo ?: '----' }}
                                                                                            </div>
                                                                                            <div><span
                                                                                                    class="font-medium">Agência:</span>
                                                                                                {{ $gateway->conta_agencia ?: '----' }}
                                                                                                @if (!empty($gateway->conta_agencia_dv))
                                                                                                    -{{ $gateway->conta_agencia_dv }}
                                                                                                @endif
                                                                                            </div>
                                                                                            <div><span
                                                                                                    class="font-medium">Conta:</span>
                                                                                                {{ $gateway->conta_numero ?: '----' }}
                                                                                                @if (!empty($gateway->conta_numero_dv))
                                                                                                    -{{ $gateway->conta_numero_dv }}
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-6 py-4">
                                                                            <div class="flex flex-wrap gap-1">
                                                                                @if ($gateway->pay_boleto)
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded border border-blue-300 font-medium">Boleto</span>
                                                                                @endif
                                                                                @if ($gateway->pay_pix)
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded border border-green-300 font-medium">PIX</span>
                                                                                @endif
                                                                                @if ($gateway->pay_slip_pix)
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-purple-100 text-purple-700 rounded border border-purple-300 font-medium">Slip
                                                                                        PIX</span>
                                                                                @endif
                                                                                @if ($gateway->pay_card_debit)
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded border border-yellow-300 font-medium">Débito</span>
                                                                                @endif
                                                                                @if ($gateway->pay_card_credit)
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-orange-100 text-orange-700 rounded border border-orange-300 font-medium">Crédito</span>
                                                                                @endif
                                                                                @if (
                                                                                    !$gateway->pay_boleto &&
                                                                                        !$gateway->pay_pix &&
                                                                                        !$gateway->pay_slip_pix &&
                                                                                        !$gateway->pay_card_debit &&
                                                                                        !$gateway->pay_card_credit)
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded border border-gray-300">Nenhum</span>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-6 py-4">
                                                                            <div class="flex flex-col gap-1">
                                                                                @if ($gateway->use_events ?? 1)
                                                                                    <span
                                                                                        class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded border border-green-300">
                                                                                        <svg class="w-3 h-3 mr-1"
                                                                                            fill="currentColor"
                                                                                            viewBox="0 0 20 20">
                                                                                            <path fill-rule="evenodd"
                                                                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                                                                clip-rule="evenodd" />
                                                                                        </svg>
                                                                                        Eventos
                                                                                    </span>
                                                                                @else
                                                                                    <span
                                                                                        class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-500 rounded border border-gray-300">
                                                                                        <svg class="w-3 h-3 mr-1"
                                                                                            fill="currentColor"
                                                                                            viewBox="0 0 20 20">
                                                                                            <path fill-rule="evenodd"
                                                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                                                clip-rule="evenodd" />
                                                                                        </svg>
                                                                                        Eventos
                                                                                    </span>
                                                                                @endif
                                                                                @if ($gateway->use_campaigns ?? 1)
                                                                                    <span
                                                                                        class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded border border-green-300">
                                                                                        <svg class="w-3 h-3 mr-1"
                                                                                            fill="currentColor"
                                                                                            viewBox="0 0 20 20">
                                                                                            <path
                                                                                                d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                                                                        </svg>
                                                                                        Campanhas
                                                                                    </span>
                                                                                @else
                                                                                    <span
                                                                                        class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-500 rounded border border-gray-300">
                                                                                        <svg class="w-3 h-3 mr-1"
                                                                                            fill="currentColor"
                                                                                            viewBox="0 0 20 20">
                                                                                            <path fill-rule="evenodd"
                                                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                                                clip-rule="evenodd" />
                                                                                        </svg>
                                                                                        Campanhas
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                        <td
                                                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                            <x-button flat warning label="Editar"
                                                                                wire:click="openEditGatewayModal('{{ $gateway->id }}')"
                                                                                class="text-sm font-semibold" />
                                                                        </td>
                                                                    </tr>
                                                                    {{-- Linha expandida com taxas para inativos --}}
                                                                    @unless ($filterHideFees)
                                                                        <tr class="bg-red-50/30 border-t-2 border-red-200">
                                                                            <td colspan="4" class="px-6 py-3">
                                                                                @php
                                                                                    $boletoFees = $gateway->pay_boleto_fees_json
                                                                                        ? json_decode(
                                                                                            $gateway->pay_boleto_fees_json,
                                                                                            true,
                                                                                        )
                                                                                        : [];
                                                                                    $pixFees = $gateway->pay_pix_fees_json
                                                                                        ? json_decode(
                                                                                            $gateway->pay_pix_fees_json,
                                                                                            true,
                                                                                        )
                                                                                        : [];
                                                                                    $slipPixFees = $gateway->pay_slip_pix_fees_json
                                                                                        ? json_decode(
                                                                                            $gateway->pay_slip_pix_fees_json,
                                                                                            true,
                                                                                        )
                                                                                        : [];
                                                                                    $creditFees = $gateway->pay_gateway_installment_fees_json
                                                                                        ? json_decode(
                                                                                            $gateway->pay_gateway_installment_fees_json,
                                                                                            true,
                                                                                        )
                                                                                        : [];
                                                                                    $allParcelas = range(1, 12);
                                                                                    $formatPercent = function (
                                                                                        $fees,
                                                                                        $parcela,
                                                                                    ) {
                                                                                        if (!isset($fees[$parcela])) {
                                                                                            return '<span class="text-gray-300">--</span>';
                                                                                        }

                                                                                        $valor = str_replace(
                                                                                            ',',
                                                                                            '.',
                                                                                            (string) $fees[$parcela],
                                                                                        );
                                                                                        $valor = (float) $valor;
                                                                                        return number_format(
                                                                                            $valor,
                                                                                            2,
                                                                                            ',',
                                                                                            '.',
                                                                                        ) . '%';
                                                                                    };
                                                                                    $formatCents = function ($value) {
                                                                                        if (
                                                                                            $value === null ||
                                                                                            $value === ''
                                                                                        ) {
                                                                                            return '<span class="text-gray-300">--</span>';
                                                                                        }

                                                                                        $amount = (int) $value;
                                                                                        return 'R$ ' .
                                                                                            number_format(
                                                                                                $amount / 100,
                                                                                                2,
                                                                                                ',',
                                                                                                '.',
                                                                                            );
                                                                                    };
                                                                                @endphp
                                                                                <div x-data="{ selectedFeeRow: '' }"
                                                                                    class="border-2 border-red-200 rounded-lg overflow-hidden bg-white/90">
                                                                                    <table
                                                                                        class="min-w-full divide-y divide-red-200">
                                                                                        <thead class="bg-red-100/70">
                                                                                            <tr>
                                                                                                <th
                                                                                                    class="px-3 py-2 text-xs font-semibold text-red-800 text-left">
                                                                                                    Taxa</th>
                                                                                                <th
                                                                                                    class="px-3 py-2 text-xs font-semibold text-red-800 text-center border-l border-red-200">
                                                                                                    Fixo</th>
                                                                                                @foreach ($allParcelas as $parcela)
                                                                                                    <th
                                                                                                        class="px-2 py-2 text-xs font-semibold text-red-800 text-center border-l border-red-200">
                                                                                                        {{ $parcela }}x
                                                                                                    </th>
                                                                                                @endforeach
                                                                                                <th
                                                                                                    class="px-3 py-2 text-xs font-semibold text-red-800 text-center border-l border-red-200">
                                                                                                    Ações</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody
                                                                                            class="bg-white divide-y divide-red-200">
                                                                                            <tr x-on:click="selectedFeeRow = 'boleto'"
                                                                                                :class="selectedFeeRow === 'boleto'
                                                                                                    ?
                                                                                                    'bg-red-100/70' :
                                                                                                    'hover:bg-red-50/50'"
                                                                                                class="cursor-pointer transition-colors duration-150">
                                                                                                <td
                                                                                                    class="px-3 py-2 text-xs font-medium text-gray-700">
                                                                                                    Boleto</td>
                                                                                                <td
                                                                                                    class="px-3 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                    {!! $formatCents($gateway->fee_boleto_fixed_amount) !!}
                                                                                                </td>
                                                                                                @foreach ($allParcelas as $parcela)
                                                                                                    <td
                                                                                                        class="px-2 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                        {!! $formatPercent($boletoFees, $parcela) !!}
                                                                                                    </td>
                                                                                                @endforeach
                                                                                                <td
                                                                                                    class="px-3 py-2 text-center border-l border-red-200">
                                                                                                    <x-button flat warning
                                                                                                        xs
                                                                                                        icon="calculator"
                                                                                                        wire:click="openBoletoFeesModalForGateway('{{ $gateway->id }}')" />
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr x-on:click="selectedFeeRow = 'pix'"
                                                                                                :class="selectedFeeRow === 'pix'
                                                                                                    ?
                                                                                                    'bg-red-100/70' :
                                                                                                    'hover:bg-red-50/50'"
                                                                                                class="cursor-pointer transition-colors duration-150">
                                                                                                <td
                                                                                                    class="px-3 py-2 text-xs font-medium text-gray-700">
                                                                                                    PIX</td>
                                                                                                <td
                                                                                                    class="px-3 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                    {!! $formatCents($gateway->fee_pix_fixed_amount) !!}
                                                                                                </td>
                                                                                                @foreach ($allParcelas as $parcela)
                                                                                                    <td
                                                                                                        class="px-2 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                        {!! $formatPercent($pixFees, $parcela) !!}
                                                                                                    </td>
                                                                                                @endforeach
                                                                                                <td
                                                                                                    class="px-3 py-2 text-center border-l border-red-200">
                                                                                                    <x-button flat warning
                                                                                                        xs
                                                                                                        icon="calculator"
                                                                                                        wire:click="openPixFeesModalForGateway('{{ $gateway->id }}')" />
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr x-on:click="selectedFeeRow = 'pix_parcelado'"
                                                                                                :class="selectedFeeRow === 'pix_parcelado'
                                                                                                    ?
                                                                                                    'bg-red-100/70' :
                                                                                                    'hover:bg-red-50/50'"
                                                                                                class="cursor-pointer transition-colors duration-150">
                                                                                                <td
                                                                                                    class="px-3 py-2 text-xs font-medium text-gray-700">
                                                                                                    PIX Parcelado- Taxa
                                                                                                    adicional ao PIX</td>
                                                                                                <td
                                                                                                    class="px-3 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                    {!! $formatCents($gateway->fee_slip_pix_fixed_amount) !!}
                                                                                                </td>
                                                                                                @foreach ($allParcelas as $parcela)
                                                                                                    <td
                                                                                                        class="px-2 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                        {!! $formatPercent($slipPixFees, $parcela) !!}
                                                                                                    </td>
                                                                                                @endforeach
                                                                                                <td
                                                                                                    class="px-3 py-2 text-center border-l border-red-200">
                                                                                                    <x-button flat warning
                                                                                                        xs
                                                                                                        icon="calculator"
                                                                                                        wire:click="openSlipPixFeesModalForGateway('{{ $gateway->id }}')" />
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr x-on:click="selectedFeeRow = 'credito'"
                                                                                                :class="selectedFeeRow === 'credito'
                                                                                                    ?
                                                                                                    'bg-red-100/70' :
                                                                                                    'hover:bg-red-50/50'"
                                                                                                class="cursor-pointer transition-colors duration-150">
                                                                                                <td
                                                                                                    class="px-3 py-2 text-xs font-medium text-gray-700">
                                                                                                    Crédito</td>
                                                                                                <td
                                                                                                    class="px-3 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                    {!! $formatCents($gateway->fee_credit_fixed_amount) !!}
                                                                                                </td>
                                                                                                @foreach ($allParcelas as $parcela)
                                                                                                    <td
                                                                                                        class="px-2 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                        {!! $formatPercent($creditFees, $parcela) !!}
                                                                                                    </td>
                                                                                                @endforeach
                                                                                                <td
                                                                                                    class="px-3 py-2 text-center border-l border-red-200">
                                                                                                    <x-button flat warning
                                                                                                        xs
                                                                                                        icon="calculator"
                                                                                                        wire:click="openInstallmentFeesModalForGateway('{{ $gateway->id }}')" />
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endunless
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    {{-- Sem gateways inativos para este cliente --}}
                                @endif
                            </div>
                        </div>
                    </div>{{-- /p-6 --}}
                </div>{{-- /wire:key x-data --}}
            @else
                <div class="w-full max-w-7xl mx-auto bg-white shadow-sm border-x border-b rounded-b-lg p-12">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Selecione um Cliente</h3>
                        <p class="text-sm text-gray-600">Escolha um cliente no filtro acima para gerenciar suas
                            configurações.</p>
                    </div>
                </div>
            @endif
        @endif
    @endif

    {{-- Modal de Edição de Usuário --}}
    <x-modal.card wire:model="showEditModal" title="Editar Usuário" max-width="2xl">
        <div wire:key="edit-user-modal-{{ $selectedUserId ?? 'none' }}" class="space-y-6 px-6 pb-4">
            <div class="space-y-4">
                <div>
                    <x-input label="Nome" wire:model="editName" placeholder="Nome completo" class="w-full" />
                    @error('editName')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-input type="email" label="E-mail" wire:model.lazy="editEmail"
                        placeholder="email@exemplo.com" class="w-full" />
                    @error('editEmail')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-base font-light uppercase text-black dark:text-gray-400">
                        Papel <span class="text-red-500">*</span>
                    </label>
                    <x-native-select wire:model="editUserRole" class="w-full">
                        <option value="" @selected(!$editUserRole)>Selecione...</option>
                        <option value="user" @selected($editUserRole === 'user')>Usuário da Organização</option>
                        <option value="owner" @selected($editUserRole === 'owner')>Proprietário da Organização</option>
                        <option value="admin" @selected($editUserRole === 'admin')>Administrador do Sistema</option>
                        @if ($editUserRole === 'super-admin')
                            <option value="super-admin" selected>Super Administrador</option>
                        @endif
                    </x-native-select>
                    @error('editUserRole')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Permissões</label>
                    <div class="flex items-center gap-2">
                        <x-checkbox wire:model="editCanEvents" label="Pode acessar Eventos" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-checkbox wire:model="editCanCampaigns" label="Pode acessar Campanhas" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-checkbox wire:model="editCanSubscriptions" label="Pode acessar Assinaturas" />
                    </div>
                </div>
            </div>

            <div class="space-y-4 border-t pt-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Alterar Senha</h3>
                    <button type="button" wire:click="toggleUserPasswordSection"
                        class="text-sm text-indigo-600 hover:text-indigo-800">
                        {{ $showPasswordSection ? 'Ocultar' : 'Alterar Senha' }}
                    </button>
                </div>

                @if ($showPasswordSection)
                    <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <x-input type="password" label="Nova Senha" wire:model.defer="newPassword"
                                placeholder="Mínimo 8 caracteres" class="w-full" />
                            @error('newPassword')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <x-input type="password" label="Confirmar Nova Senha"
                                wire:model.defer="newPasswordConfirmation" placeholder="Confirme a nova senha"
                                class="w-full" />
                            @error('newPasswordConfirmation')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <x-button primary xs label="Salvar Senha" wire:click="updateUserPassword"
                                spinner="updateUserPassword" />
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-4 border-t pt-4">
                <h3 class="text-sm font-semibold text-red-700 uppercase tracking-wide">Zona de Perigo</h3>

                @if (!$showDeleteConfirmation)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-xs text-red-800 mb-3">Ao remover o usuário, ele perderá acesso a este
                            cliente/filial/setor. Esta ação não pode ser desfeita.</p>
                        <x-button red outline xs label="Remover Usuário" wire:click="startUserDeleteConfirmation" />
                    </div>
                @else
                    <div class="bg-red-50 border-2 border-red-300 rounded-lg p-4">
                        <p class="text-xs font-semibold text-red-900 mb-2">Tem certeza que deseja remover este usuário?
                        </p>
                        <p class="text-xs text-red-800 mb-4">O usuário <strong>{{ $editName ?? '' }}</strong> será
                            removido do cliente atual.</p>
                        <div class="flex gap-2">
                            <x-button red xs label="Confirmar Remoção" wire:click="removeUser"
                                spinner="removeUser" />
                            <x-button flat xs label="Cancelar" wire:click="cancelUserDeleteConfirmation" />
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-button flat label="Fechar" wire:click="closeEditModal" />
                <x-button primary label="Salvar Alterações" wire:click="updateUser" spinner="updateUser" />
            </div>
        </x-slot>
    </x-modal.card>

    {{-- Modal de Criação de Novo Usuário --}}
    <x-modal.card wire:model.defer="showNewUserModal" title="Novo Usuário" max-width="2xl">
        <div wire:key="new-user-modal-{{ $showNewUserModal ? 'open' : 'closed' }}" class="space-y-6 px-6 pb-4">
            {{-- Mensagens de erro/sucesso --}}
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-red-800 font-medium text-sm">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <x-input label="Nome" wire:model.defer="newUserName" placeholder="Nome completo"
                        class="w-full" />
                </div>

                <div>
                    <x-input type="email" label="E-mail" wire:model.defer="newUserEmail"
                        placeholder="email@exemplo.com" class="w-full" />
                </div>

                <div>
                    <x-input type="password" label="Senha" wire:model.defer="newUserPassword"
                        placeholder="Digite a senha (mínimo 8 caracteres)" class="w-full" />
                </div>

                <div>
                    <x-input type="password" label="Confirmar Senha"
                        wire:model.defer="newUserPasswordConfirmation" placeholder="Confirme a senha"
                        class="w-full" />
                </div>

                <div>
                    <label class="block text-base font-light uppercase text-black dark:text-gray-400">
                        Papel <span class="text-red-500">*</span>
                    </label>
                    <x-native-select wire:model.defer="newUserRole" class="w-full">
                        <option value="user">Usuário da Organização</option>
                        <option value="owner">Proprietário da Organização</option>
                        {{-- <option value="admin">Administrador geral do Sistema</option> NAO FAZ SENTIDO ENQUANTO EMPRESA --}}
                    </x-native-select>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Permissões
                    </label>
                    <div class="flex items-center gap-2">
                        <x-checkbox wire:model.defer="newUserCanEvents" label="Pode acessar Eventos" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-checkbox wire:model.defer="newUserCanCampaigns" label="Pode acessar Campanhas" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-checkbox wire:model.defer="newUserCanSubscriptions" label="Pode acessar Assinaturas" />
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-button flat label="Cancelar" wire:click="closeNewUserModal" />
                <x-button primary label="Criar Usuário" wire:click="createUser" spinner="createUser" />
            </div>
        </x-slot>
    </x-modal.card>

    {{-- ============================================================== --}}
    {{-- Modal Gateway (Novo / Editar) - Alpine.js puro --}}
    {{-- ============================================================== --}}
    <div x-data="{
        open: false,
        editing: false,
        gatewayId: '',
        recordId: '',
        label: '',
        description: '',
        codSubcontaId: '',
        contaCod: '',
        contaBanco: '',
        contaBancoDescricao: '',
        contaTipo: '',
        contaAgencia: '',
        contaAgenciaDv: '',
        contaNumero: '',
        contaNumeroDv: '',
        banks: @js($availableBanks ?? []),
        tokenLive: '',
        tokenLivePass: '',
        tokenTest: '',
        tokenTestPass: '',
        payBoleto: false,
        payPix: false,
        paySlipPix: false,
        payCardDebit: false,
        payCardCredit: false,
        installmentMax: 1,
        installmentAmountMin: 500,
        slipPixInstallmentMax: 1,
        slipPixInstallmentAmountMin: 1000,
        boletoFixedAmount: null,
        pixFixedAmount: null,
        slipPixFixedAmount: null,
        creditFixedAmount: null,
        payActive: true,
        useEvents: true,
        useCampaigns: true,
        showDeleteConfirm: false,
        saving: false,
        errors: [],
        resetForm() {
            this.gatewayId = '';
            this.recordId = '';
            this.label = '';
            this.description = '';
            this.codSubcontaId = '';
            this.contaCod = '';
            this.contaBanco = '';
            this.contaBancoDescricao = '';
            this.contaTipo = '';
            this.contaAgencia = '';
            this.contaAgenciaDv = '';
            this.contaNumero = '';
            this.contaNumeroDv = '';
            this.tokenLive = '';
            this.tokenLivePass = '';
            this.tokenTest = '';
            this.tokenTestPass = '';
            this.payBoleto = false;
            this.payPix = false;
            this.paySlipPix = false;
            this.payCardDebit = false;
            this.payCardCredit = false;
            this.installmentMax = 1;
            this.installmentAmountMin = 500;
            this.slipPixInstallmentMax = 1;
            this.slipPixInstallmentAmountMin = 1000;
            this.boletoFixedAmount = null;
            this.pixFixedAmount = null;
            this.slipPixFixedAmount = null;
            this.creditFixedAmount = null;
            this.payActive = true;
            this.useEvents = true;
            this.useCampaigns = true;
            this.showDeleteConfirm = false;
            this.saving = false;
            this.errors = [];
        },
        updateBankDescription() {
            const bank = this.banks.find((item) => item.ref_banco === this.contaBanco);
            this.contaBancoDescricao = bank ? (bank.ref_banco_descricao || '') : '';
            if (bank && !this.contaCod) {
                this.contaCod = bank.ref_cod || '';
            }
        },
        syncToLivewire() {
            $wire.set('gatewayPayGatewayId', this.gatewayId);
            $wire.set('gatewayPayGatewayLabel', this.label);
            $wire.set('gatewayPayGatewayDescription', this.description);
            $wire.set('gatewayCodSubcontaId', this.codSubcontaId);
            $wire.set('gatewayContaCod', this.contaCod);
            $wire.set('gatewayContaBanco', this.contaBanco);
            $wire.set('gatewayContaBancoDescricao', this.contaBancoDescricao);
            $wire.set('gatewayContaTipo', this.contaTipo);
            $wire.set('gatewayContaAgencia', this.contaAgencia);
            $wire.set('gatewayContaAgenciaDv', this.contaAgenciaDv);
            $wire.set('gatewayContaNumero', this.contaNumero);
            $wire.set('gatewayContaNumeroDv', this.contaNumeroDv);
            $wire.set('gatewayTokenLive', this.tokenLive);
            $wire.set('gatewayTokenLivePass', this.tokenLivePass);
            $wire.set('gatewayTokenTest', this.tokenTest);
            $wire.set('gatewayTokenTestPass', this.tokenTestPass);
            $wire.set('gatewayPayBoleto', this.payBoleto);
            $wire.set('gatewayPayPix', this.payPix);
            $wire.set('gatewayPaySlipPix', this.paySlipPix);
            $wire.set('gatewayPayCardDebit', this.payCardDebit);
            $wire.set('gatewayPayCardCredit', this.payCardCredit);
            $wire.set('gatewayPayCardCreditInstallmentMax', this.installmentMax);
            $wire.set('gatewayPayCardCreditInstallmentAmountMin', this.installmentAmountMin);
            $wire.set('gatewayPaySlipPixInstallmentMax', this.slipPixInstallmentMax);
            $wire.set('gatewayPaySlipPixInstallmentAmountMin', this.slipPixInstallmentAmountMin);
            $wire.set('gatewayBoletoFixedAmount', this.boletoFixedAmount);
            $wire.set('gatewayPixFixedAmount', this.pixFixedAmount);
            $wire.set('gatewaySlipPixFixedAmount', this.slipPixFixedAmount);
            $wire.set('gatewayCreditFixedAmount', this.creditFixedAmount);
            $wire.set('gatewayPayActive', this.payActive);
            $wire.set('gatewayUseEvents', this.useEvents);
            $wire.set('gatewayUseCampaigns', this.useCampaigns);
        },
        async save() {
            this.saving = true;
            this.errors = [];
            this.syncToLivewire();
            try {
                await $wire.call('saveGateway');
            } catch (e) {
                // errors will be set via gateway-errors event
            }
            this.saving = false;
        },
        close() {
            this.open = false;
            this.editing = false;
            $wire.call('closeGatewayModal');
        }
    }"
        x-on:open-new-gateway.window="
            resetForm();
            editing = false;
            open = true;
        "
        x-on:open-edit-gateway.window="
            resetForm();
            editing = true;
            gatewayId = $event.detail.gatewayId;
            recordId = $event.detail.recordId || '';
            label = $event.detail.label;
            description = $event.detail.description;
            codSubcontaId = $event.detail.codSubcontaId || '';
            contaCod = $event.detail.contaCod || '';
            contaBanco = $event.detail.contaBanco || '';
            contaBancoDescricao = $event.detail.contaBancoDescricao || '';
            contaTipo = $event.detail.contaTipo || '';
            contaAgencia = $event.detail.contaAgencia || '';
            contaAgenciaDv = $event.detail.contaAgenciaDv || '';
            contaNumero = $event.detail.contaNumero || '';
            contaNumeroDv = $event.detail.contaNumeroDv || '';
            if (!contaBancoDescricao) {
                updateBankDescription();
            }
            tokenLive = $event.detail.tokenLive;
            tokenLivePass = $event.detail.tokenLivePass;
            tokenTest = $event.detail.tokenTest;
            tokenTestPass = $event.detail.tokenTestPass;
            payBoleto = $event.detail.payBoleto;
            payPix = $event.detail.payPix;
            paySlipPix = $event.detail.paySlipPix;
            payCardDebit = $event.detail.payCardDebit;
            payCardCredit = $event.detail.payCardCredit;
            installmentMax = $event.detail.installmentMax;
            installmentAmountMin = $event.detail.installmentAmountMin;
            slipPixInstallmentMax = $event.detail.slipPixInstallmentMax;
            slipPixInstallmentAmountMin = $event.detail.slipPixInstallmentAmountMin;
            boletoFixedAmount = $event.detail.boletoFixedAmount;
            pixFixedAmount = $event.detail.pixFixedAmount;
            slipPixFixedAmount = $event.detail.slipPixFixedAmount;
            creditFixedAmount = $event.detail.creditFixedAmount;
            payActive = $event.detail.payActive;
            useEvents = $event.detail.useEvents;
            useCampaigns = $event.detail.useCampaigns;
            open = true;
        "
        x-on:close-gateway-modal.window="open = false; editing = false; errors = [];"
        x-on:gateway-errors.window="errors = $event.detail.errors || [];">
        {{-- Backdrop --}}
        <div x-show="open" x-cloak
            class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto p-4 sm:pt-16"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-secondary-400 dark:bg-secondary-700 bg-opacity-60 dark:bg-opacity-60"
                x-on:click="close()"></div>
            <div class="relative z-10 w-full sm:max-w-4xl bg-white rounded-xl shadow-xl"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800"
                        x-text="editing ? 'Editar Gateway' : 'Novo Gateway'"></h2>
                    <button x-on:click="close()"
                        class="p-1 rounded-full text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-secondary-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="space-y-6 px-6 py-6 max-h-[75vh] overflow-y-auto">
                    <div class="space-y-4">
                        {{-- Gateway Select --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gateway</label>
                            <select x-model="gatewayId" :disabled="editing"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm disabled:bg-gray-100"
                                x-on:change="if(!editing){ $wire.set('gatewayPayGatewayId', gatewayId); $wire.call('onGatewaySelected'); }">
                                <option value="">Selecione um gateway...</option>
                                @forelse($availableGateways ?? [] as $appGateway)
                                    <option value="{{ $appGateway->id }}">{{ $appGateway->gateway_name }}</option>
                                @empty
                                    <option value="" disabled>Nenhum gateway disponível no sistema</option>
                                @endforelse
                            </select>
                        </div>

                        {{-- Label --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rótulo (Label)</label>
                            <input type="text" x-model="label" placeholder="Ex: PagSeguro Principal"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                        </div>

                        {{-- Descrição --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                            <textarea x-model="description" rows="2" placeholder="Descrição do gateway"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>

                        {{-- Código Subconta --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Código Subconta ID</label>
                            <input type="text" x-model="codSubcontaId"
                                placeholder="Ex: ID da subconta no gateway"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <p class="mt-1 text-xs text-gray-500">Identificador da subconta/recipiente no gateway de
                                pagamento (se aplicável)</p>
                        </div>

                        <div class="space-y-4 border-t pt-4">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Conta
                                Bancária do Gateway</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta Cod
                                        (único)</label>
                                    <input type="text" x-model="contaCod" placeholder="Ex: 206"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Banco</label>
                                    <select x-model="contaBanco" x-on:change="updateBankDescription()"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Selecione um banco...</option>
                                        @foreach ($availableBanks ?? [] as $bank)
                                            <option value="{{ $bank->ref_banco }}">
                                                {{ $bank->ref_cod }} - {{ $bank->ref_banco }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição do
                                        Banco</label>
                                    <input type="text" x-model="contaBancoDescricao"
                                        placeholder="Ex: PagSeguro" readonly
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Conta</label>
                                    <select x-model="contaTipo"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Selecione...</option>
                                        <option value="corrente">Corrente</option>
                                        <option value="poupanca">Poupança</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Agência</label>
                                    <input type="text" x-model="contaAgencia" placeholder="Ex: 0001"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">DV da Agência</label>
                                    <input type="text" x-model="contaAgenciaDv" placeholder="Ex: 4"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Número da
                                        Conta</label>
                                    <input type="text" x-model="contaNumero" placeholder="Ex: 00122334455"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">DV da Conta</label>
                                    <input type="text" x-model="contaNumeroDv" placeholder="Ex: 9"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Credenciais --}}
                    <div class="space-y-4 border-t pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Credenciais</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Token Live</label>
                                <input type="password" x-model="tokenLive" placeholder="Token de produção"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Senha Token Live</label>
                                <input type="password" x-model="tokenLivePass" placeholder="Senha do token"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Token Test</label>
                                <input type="password" x-model="tokenTest" placeholder="Token de teste"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Senha Token Test</label>
                                <input type="password" x-model="tokenTestPass" placeholder="Senha do token"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                        </div>
                    </div>

                    {{-- Métodos de Pagamento --}}
                    <div class="space-y-4 border-t pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Métodos de
                            Pagamento</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="payBoleto"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Boleto</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="payPix"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">PIX</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="paySlipPix"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Slip PIX</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="payCardDebit"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Cartão Débito</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="payCardCredit"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Cartão Crédito</span>
                            </label>
                        </div>
                    </div>

                    {{-- Parcelamento Cartão Crédito --}}
                    <div class="space-y-4 border-t pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Parcelamento
                            Cartão Crédito</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Máx. Parcelas</label>
                                <input type="number" x-model.number="installmentMax" min="1"
                                    max="12"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Valor Mínimo Parcela (R$
                                    12,34 = 1234)</label>
                                <input type="number" step="1" x-model.number="installmentAmountMin"
                                    min="500"
                                    x-on:change="if(installmentAmountMin < 500) installmentAmountMin = 500"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                <p class="mt-1 text-xs text-gray-500">Mínimo: 500 (equivale a R$ 5,00)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Slip PIX --}}
                    <div class="space-y-4 border-t pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Slip PIX</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Máx. Parcelas</label>
                                <input type="number" x-model.number="slipPixInstallmentMax" min="1"
                                    max="12"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Valor Mínimo Parcela (R$
                                    12,34 = 1234)</label>
                                <input type="number" step="1" x-model.number="slipPixInstallmentAmountMin"
                                    min="1000"
                                    x-on:change="if(slipPixInstallmentAmountMin < 1000) slipPixInstallmentAmountMin = 1000"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                <p class="mt-1 text-xs text-gray-500">Mínimo: 1000 (equivale a R$ 10,00)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Valores Fixos por Transação (centavos) --}}
                    <div class="space-y-4 border-t pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Valor Fixo por
                            Transação (centavos)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Boleto (centavos)</label>
                                <input type="number" step="1" min="0"
                                    x-model.number="boletoFixedAmount"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">PIX (centavos)</label>
                                <input type="number" step="1" min="0"
                                    x-model.number="pixFixedAmount"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Slip PIX
                                    (centavos)</label>
                                <input type="number" step="1" min="0"
                                    x-model.number="slipPixFixedAmount"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Crédito (centavos)</label>
                                <input type="number" step="1" min="0"
                                    x-model.number="creditFixedAmount"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-4 border-t pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Status</h3>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Gateway Ativo</p>
                                <p class="text-xs text-gray-500 mt-1">Ative ou desative este gateway de pagamento</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="payActive" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Uso em Eventos e Campanhas --}}
                    <div class="space-y-4 border-b pb-6">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Uso do Gateway</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Usar em Eventos</p>
                                    <p class="text-xs text-gray-500 mt-1">Permitir uso deste gateway em eventos</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="useEvents" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500">
                                    </div>
                                </label>
                            </div>
                            <div
                                class="flex items-center justify-between p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Usar em Campanhas</p>
                                    <p class="text-xs text-gray-500 mt-1">Permitir uso deste gateway em campanhas</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="useCampaigns" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Zona de Perigo (apenas ao editar) --}}
                    <template x-if="editing">
                        <div class="space-y-4 border-t pt-4">
                            <h3 class="text-sm font-semibold text-red-700 uppercase tracking-wide">Zona de Perigo</h3>
                            <div x-show="!showDeleteConfirm" class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-xs text-red-800 mb-3">Ao remover o gateway, ele não poderá mais ser
                                    usado em transações. Esta ação não pode ser desfeita.</p>
                                <button x-on:click="showDeleteConfirm = true"
                                    class="px-3 py-1.5 text-xs font-medium text-red-700 border border-red-300 rounded-md hover:bg-red-100">
                                    Remover Gateway
                                </button>
                            </div>
                            <div x-show="showDeleteConfirm"
                                class="bg-red-50 border-2 border-red-300 rounded-lg p-4">
                                <p class="text-xs font-semibold text-red-900 mb-2">⚠️ Tem certeza que deseja remover
                                    este gateway?</p>
                                <p class="text-xs text-red-800 mb-4">O gateway <strong x-text="label"></strong> será
                                    removido permanentemente.</p>
                                <div class="flex gap-2">
                                    <button x-on:click="$wire.call('removeGateway', true)"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                        Confirmar Remoção do Gateway
                                    </button>
                                    <button x-on:click="showDeleteConfirm = false"
                                        class="px-3 py-1.5 text-xs font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Footer com erros --}}
                <div class="px-6 py-4 border-t">
                    {{-- Mensagens de erro --}}
                    <div x-show="errors.length > 0" x-cloak x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="mb-3 rounded-lg border border-red-300 bg-red-50 p-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="flex-1">
                                <template x-for="(err, idx) in errors" :key="idx">
                                    <p class="text-sm text-red-700" x-text="err"></p>
                                </template>
                            </div>
                            <button x-on:click="errors = []" class="text-red-400 hover:text-red-600 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    {{-- Botões --}}
                    <div class="flex items-center justify-end gap-2">
                        <button x-on:click="close()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Fechar
                        </button>
                        <button x-on:click="save()" :disabled="saving"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 disabled:opacity-50">
                            <span x-show="!saving" x-text="editing ? 'Salvar Alterações' : 'Criar Gateway'"></span>
                            <span x-show="saving">Salvando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Taxas de Parcelamento --}}
    <div>
        <x-modal.card wire:model="showInstallmentFeesModal" title="Gerenciar Taxas de Parcelamento"
            max-width="4xl"
            wire:key="installment-fees-modal-{{ $selectedGatewayForInstallmentFees->id ?? 'none' }}">
            <div class="space-y-6 px-6 pb-6">
                @if ($selectedGatewayForInstallmentFees)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-blue-900">Gateway: <span
                                class="font-semibold">{{ $selectedGatewayForInstallmentFees->pay_gateway_label }}</span>
                        </p>
                        <p class="text-xs text-blue-700 mt-1">Campo: <span
                                class="font-mono">pay_gateway_installment_fees_json</span> - Configure as taxas por
                            número de parcelas para cartão de crédito</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">JSON de Taxas de Parcelamento</label>
                    <textarea wire:model.defer="gatewayInstallmentFeesJson" rows="10"
                        class="w-full font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder='{"1":"2.20","2":"2.70","3":"2.70","4":"2.70","5":"2.70","6":"2.70","7":"3.20","8":"3.20","9":"3.20","10":"3.20","11":"3.20","12":"3.20"}'></textarea>
                    <p class="text-xs text-gray-500 mt-2">Formato JSON: objeto onde a chave é o número de parcelas e o
                        valor é a taxa percentual. Ex: {"1":"2.20","2":"2.70"}</p>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-2">
                    <button wire:click="closeInstallmentFeesModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Fechar
                    </button>
                    <button wire:click="saveInstallmentFees"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                        Salvar
                    </button>
                </div>
            </x-slot>
        </x-modal.card>
    </div>

    {{-- Modal de Taxas do Boleto --}}
    <div>
        <x-modal.card wire:model="showBoletoFeesModal" title="Gerenciar Taxas do Boleto" max-width="4xl"
            wire:key="boleto-fees-modal-{{ $selectedGatewayForBoletoFees->id ?? 'none' }}">
            <div class="space-y-6 px-6 pb-6">
                @if ($selectedGatewayForBoletoFees)
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-amber-900">Gateway: <span
                                class="font-semibold">{{ $selectedGatewayForBoletoFees->pay_gateway_label }}</span>
                        </p>
                        <p class="text-xs text-amber-700 mt-1">Campo: <span
                                class="font-mono">pay_boleto_fees_json</span> - Configure as taxas por número de
                            parcelas para boleto</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">JSON de Taxas do Boleto</label>
                    <textarea wire:model.defer="gatewayBoletoFeesJson" rows="10"
                        class="w-full font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder='{"1":"2.20","2":"2.70","3":"2.70","4":"2.70","5":"2.70","6":"2.70","7":"3.20","8":"3.20","9":"3.20","10":"3.20","11":"3.20","12":"3.20"}'></textarea>
                    <p class="text-xs text-gray-500 mt-2">Formato JSON: objeto onde a chave é o número de parcelas e o
                        valor é a taxa percentual. Ex: {"1":"2.20","2":"2.70"}</p>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-2">
                    <button wire:click="closeBoletoFeesModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Fechar
                    </button>
                    <button wire:click="saveBoletoFees"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                        Salvar
                    </button>
                </div>
            </x-slot>
        </x-modal.card>
    </div>

    {{-- Modal de Taxas do PIX --}}
    <div>
        <x-modal.card wire:model="showPixFeesModal" title="Gerenciar Taxas do PIX" max-width="4xl"
            wire:key="pix-fees-modal-{{ $selectedGatewayForPixFees->id ?? 'none' }}">
            <div class="space-y-6 px-6 pb-6">
                @if ($selectedGatewayForPixFees)
                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-cyan-900">Gateway: <span
                                class="font-semibold">{{ $selectedGatewayForPixFees->pay_gateway_label }}</span>
                        </p>
                        <p class="text-xs text-cyan-700 mt-1">Campo: <span
                                class="font-mono">pay_pix_fees_json</span> - Configure as taxas por número de
                            parcelas para PIX</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">JSON de Taxas do PIX</label>
                    <textarea wire:model.defer="gatewayPixFeesJson" rows="10"
                        class="w-full font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder='{"1":"2.20","2":"2.70","3":"2.70","4":"2.70","5":"2.70","6":"2.70","7":"3.20","8":"3.20","9":"3.20","10":"3.20","11":"3.20","12":"3.20"}'></textarea>
                    <p class="text-xs text-gray-500 mt-2">Formato JSON: objeto onde a chave é o número de parcelas e o
                        valor é a taxa percentual. Ex: {"1":"2.20","2":"2.70"}</p>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-2">
                    <button wire:click="closePixFeesModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Fechar
                    </button>
                    <button wire:click="savePixFees"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                        Salvar
                    </button>
                </div>
            </x-slot>
        </x-modal.card>
    </div>

    {{-- Modal de Taxas do Slip PIX --}}
    <div>
        <x-modal.card wire:model="showSlipPixFeesModal" title="Gerenciar Taxas do Slip PIX" max-width="4xl"
            wire:key="slippix-fees-modal-{{ $selectedGatewayForSlipPixFees->id ?? 'none' }}">
            <div class="space-y-6 px-6 pb-6">
                @if ($selectedGatewayForSlipPixFees)
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-purple-900">Gateway: <span
                                class="font-semibold">{{ $selectedGatewayForSlipPixFees->pay_gateway_label }}</span>
                        </p>
                        <p class="text-xs text-purple-700 mt-1">Campo: <span
                                class="font-mono">pay_slip_pix_fees_json</span> - Configure as taxas por número de
                            parcelas para Slip PIX</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">JSON de Taxas do Slip PIX</label>
                    <textarea wire:model.defer="gatewaySlipPixFeesJson" rows="10"
                        class="w-full font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder='{"1":"2.20","2":"2.70","3":"2.70","4":"2.70","5":"2.70","6":"2.70","7":"3.20","8":"3.20","9":"3.20","10":"3.20","11":"3.20","12":"3.20"}'></textarea>
                    <p class="text-xs text-gray-500 mt-2">Formato JSON: objeto onde a chave é o número de parcelas e o
                        valor é a taxa percentual. Ex: {"1":"2.20","2":"2.70"}</p>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-2">
                    <button wire:click="closeSlipPixFeesModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Fechar
                    </button>
                    <button wire:click="saveSlipPixFees"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                        Salvar
                    </button>
                </div>
            </x-slot>
        </x-modal.card>
    </div>

    {{-- Modal de Cliente (mantido para edição) --}}
    @if (!$standaloneCreate)
        <x-modal.card wire:model="showCustomerModal" :title="$isEditingCustomer ? 'Editar Cliente' : 'Novo Cliente'" max-width="4xl">
            <div class="space-y-6 px-6 pb-6">
                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- DADOS PRINCIPAIS --}}
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Dados Principais</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-input label="Razão Social / Nome do Cliente" wire:model.defer="customerNameCorporate"
                                placeholder="Razão social da empresa" class="w-full" />
                        </div>
                        <div>
                            <x-input label="Nome Fantasia / Nome Comercial" wire:model.blur="customerNameFantasy"
                                placeholder="Nome fantasia" class="w-full" />
                        </div>
                        <div>
                            <x-input label="Nome Curto" wire:model.defer="customerNameShort"
                                placeholder="Nome curto/abreviação" class="w-full" />
                        </div>
                        <div class="md:col-span-1">
                            <x-native-select label="Tipo de Documento" wire:model="customerDocType"
                                class="w-full">
                                <option value="cpf">CPF</option>
                                <option value="cnpj">CNPJ</option>
                            </x-native-select>
                        </div>
                        <div class="md:col-span-1">
                            <x-input label="Número do Documento" wire:model.live.debounce.500ms="customerDocNum"
                                placeholder="Digite o documento" class="w-full" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input label="Slug do Cliente" wire:model.defer="customerSlug"
                                placeholder="slug-do-cliente" class="w-full" />
                            <p class="text-xs text-gray-500 mt-1">Usado na URL ex:
                                www.proeventpay.com/{{ $customerSlug ?: '{slug}' }}</p>
                        </div>
                    </div>
                </div>

                {{-- CONTATO COMERCIAL --}}
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Contato Comercial</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input label="Nome" wire:model.defer="customerComercialContactName"
                                placeholder="Nome do contato comercial" class="w-full" />
                        </div>
                        <div>
                            <x-input type="email" label="E-mail"
                                wire:model.defer="customerComercialContactEmail" placeholder="email@exemplo.com"
                                class="w-full" />
                        </div>
                        <div class="md:col-span-1">
                            <x-input label="DDD" wire:model.defer="customerComercialContactDdd"
                                placeholder="21" class="w-full" maxlength="2" />
                        </div>
                        <div class="md:col-span-1">
                            <x-input label="Telefone" wire:model.defer="customerComercialContactNum"
                                placeholder="987654321" class="w-full" />
                        </div>
                    </div>
                </div>

                {{-- CONTATO FINANCEIRO --}}
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Contato Financeiro</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input label="Nome" wire:model.defer="customerFinancialContactName"
                                placeholder="Nome do contato financeiro" class="w-full" />
                        </div>
                        <div>
                            <x-input type="email" label="E-mail"
                                wire:model.defer="customerFinancialContactEmail" placeholder="email@exemplo.com"
                                class="w-full" />
                        </div>
                        <div class="md:col-span-1">
                            <x-input label="DDD" wire:model.defer="customerFinancialContactDdd"
                                placeholder="21" class="w-full" maxlength="2" />
                        </div>
                        <div class="md:col-span-1">
                            <x-input label="Telefone" wire:model.defer="customerFinancialContactNum"
                                placeholder="987654321" class="w-full" />
                        </div>
                    </div>
                </div>

                {{-- ENDEREÇO --}}
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Endereço</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <x-input label="Logradouro" wire:model.defer="customerAddress"
                                placeholder="Rua, Avenida, etc" class="w-full" />
                        </div>
                        <div>
                            <x-input label="Número" wire:model.defer="customerAddressNumber" placeholder="123"
                                class="w-full" />
                        </div>
                        <div>
                            <x-input label="Complemento" wire:model.defer="customerAddressComplement"
                                placeholder="Apto, Sala, etc" class="w-full" />
                        </div>
                        <div>
                            <x-input label="Bairro" wire:model.defer="customerCityNeighborhood"
                                placeholder="Nome do bairro" class="w-full" />
                        </div>
                        <div>
                            <x-input label="Cidade" wire:model.defer="customerCity" placeholder="Nome da cidade"
                                class="w-full" />
                        </div>
                        <div>
                            <x-input label="Estado (UF)" wire:model.defer="customerState" placeholder="RJ"
                                class="w-full" maxlength="2" />
                        </div>
                        <div>
                            <x-input label="CEP" wire:model.defer="customerZipCode" placeholder="20000-000"
                                class="w-full" />
                        </div>
                    </div>
                </div>

                {{-- LINKS E CONFIGURAÇÕES --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Links e Configurações</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input label="Site" wire:model.defer="customerUrlSite"
                                placeholder="https://www.exemplo.com.br" class="w-full" />
                        </div>
                        <div>
                            <x-input label="Instagram" wire:model.defer="customerUrlInstagram"
                                placeholder="https://instagram.com/exemplo" class="w-full" />
                        </div>
                        <div>
                            <x-input label="Facebook" wire:model.defer="customerUrlFacebook"
                                placeholder="https://facebook.com/exemplo" class="w-full" />
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Gerar Fatura</p>
                                <p class="text-xs text-gray-500 mt-1">Habilitar geração automática de notas fiscais
                                </p>
                            </div>
                            <x-toggle wire:model.defer="customerGenerateInvoice" lg color="green" />
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    @if ($errors->any())
                        <div class="w-full rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-700 mb-3">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($isEditingCustomer)
                        <div class="flex items-center justify-between gap-3 w-full">
                            @if ($confirmingCustomerDeletion)
                                <div class="text-xs text-red-700 bg-red-50 border border-red-200 px-3 py-2 rounded">
                                    Confirmar exclusão do cliente e todos os vínculos? Não pode haver eventos ou
                                    campanhas.
                                </div>
                                <div class="flex gap-2">
                                    <x-button flat label="Cancelar exclusão"
                                        wire:click="$set('confirmingCustomerDeletion', false)" />
                                    <x-button negative label="Confirmar exclusão"
                                        wire:click="confirmDeleteCustomer(true)" />
                                </div>
                            @else
                                <x-button flat negative label="Remover Cliente"
                                    wire:click="confirmDeleteCustomer" />
                                <div class="flex gap-2">
                                    <x-button flat label="Cancelar" wire:click="closeCustomerModal" />
                                    <x-button primary
                                        label="{{ $isEditingCustomer ? 'Salvar Alterações' : 'Criar Cliente' }}"
                                        wire:click="saveCustomer" spinner="saveCustomer" />
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="flex justify-end gap-2">
                            <x-button flat label="Cancelar" wire:click="closeCustomerModal" />
                            <x-button primary
                                label="{{ $isEditingCustomer ? 'Salvar Alterações' : 'Criar Cliente' }}"
                                wire:click="saveCustomer" spinner="saveCustomer" />
                        </div>
                    @endif
                </x-slot>
        </x-modal.card>
    @endif

</div>
