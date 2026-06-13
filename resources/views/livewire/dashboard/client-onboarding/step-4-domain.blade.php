<!-- Step 4: Configuração de Domínio -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-globe text-4xl text-green-500 mb-4"></i>
        <p class="text-gray-600">
            Configure o domínio personalizado para este cliente acessar sua aplicação.
        </p>
    </div>

    <!-- Opções de Domínio -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-cog mr-2"></i>Configuração de Domínio
        </h3>

        <!-- Tipo de Domínio -->
        <div class="space-y-4 mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">
                Escolha o tipo de domínio:
            </label>

            <div class="grid md:grid-cols-2 gap-4">
                <!-- Subdomínio da Plataforma -->
                <label class="relative">
                    <input type="radio" wire:model.defer="domain_type" value="subdomain"
                           class="sr-only peer" checked>
                    <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-300 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-4 h-4 bg-white border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">Subdomínio da Plataforma</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    Use um subdomínio automaticamente configurado
                                </p>
                                <p class="text-xs text-green-600 mt-2 font-medium">
                                    ✓ Recomendado • Configuração automática • SSL incluso
                                </p>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Domínio Personalizado -->
                <label class="relative">
                    <input type="radio" wire:model.defer="domain_type" value="custom"
                           class="sr-only peer">
                    <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-4 h-4 bg-white border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">Domínio Personalizado</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    Use um domínio próprio do cliente
                                </p>
                                <p class="text-xs text-blue-600 mt-2 font-medium">
                                    ⚡ Configuração manual • DNS requerido
                                </p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Configuração de Subdomínio -->
        <div x-show="$wire.domain_type === 'subdomain'" x-transition class="space-y-4">
            <h4 class="font-medium text-gray-900">Configuração do Subdomínio</h4>

            <div class="flex items-center space-x-2">
                <div class="flex-1">
                    <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome do Subdomínio
                    </label>
                    <div class="flex">
                        <input type="text" id="subdomain" wire:model.defer="subdomain"
                               placeholder="minhempresa"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('subdomain') border-red-500 @enderror">
                        <div class="flex items-center px-4 py-3 bg-gray-50 border border-l-0 border-gray-300 rounded-r-lg text-gray-500">
                            .{{ config('app.domain', 'minhaPlataforma.com') }}
                        </div>
                    </div>
                    @error('subdomain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Apenas letras, números e hífens. Mínimo 3 caracteres.
                    </p>
                </div>
            </div>

            <!-- Preview do URL -->
            @if ($subdomain)
                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <h5 class="font-medium text-green-800 mb-2">URL da aplicação:</h5>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-globe text-green-600"></i>
                        <code class="text-green-700 font-mono">
                            https://{{ $subdomain }}.{{ config('app.domain', 'minhaPlataforma.com') }}
                        </code>
                        <button type="button" onclick="navigator.clipboard.writeText('https://{{ $subdomain }}.{{ config('app.domain', 'minhaPlataforma.com') }}')"
                                class="p-1 text-green-600 hover:text-green-700 transition-colors">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Configuração de Domínio Personalizado -->
        <div x-show="$wire.domain_type === 'custom'" x-transition class="space-y-6">
            <h4 class="font-medium text-gray-900">Configuração do Domínio Personalizado</h4>

            <!-- Campo do Domínio -->
            <div>
                <label for="custom_domain" class="block text-sm font-medium text-gray-700 mb-2">
                    Domínio Completo
                </label>
                <div class="relative">
                    <input type="text" id="custom_domain" wire:model.defer="custom_domain"
                           placeholder="campanhas.minhaempresa.com"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('custom_domain') border-red-500 @enderror">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-globe text-gray-400"></i>
                    </div>
                </div>
                @error('custom_domain')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Ex: campanhas.suaempresa.com ou app.cliente.com.br
                </p>
            </div>

            <!-- Instruções de DNS -->
            @if ($custom_domain)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h5 class="font-medium text-blue-800 mb-4">
                        <i class="fas fa-dns mr-2"></i>Configurações DNS Necessárias
                    </h5>

                    <div class="space-y-4">
                        <div class="bg-white rounded border p-4">
                            <h6 class="font-medium text-gray-900 mb-2">Registro CNAME:</h6>
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="font-medium text-gray-700">Nome:</p>
                                    <code class="text-blue-600">{{ explode('.', $custom_domain)[0] }}</code>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Tipo:</p>
                                    <code class="text-blue-600">CNAME</code>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Valor:</p>
                                    <code class="text-blue-600">{{ config('app.domain', 'minhaPlataforma.com') }}</code>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ config('app.domain', 'minhaPlataforma.com') }}')"
                                            class="ml-2 text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-blue-700">
                            <p class="font-medium mb-2">Passos para configurar:</p>
                            <ol class="list-decimal list-inside space-y-1">
                                <li>Acesse o painel DNS do provedor do domínio</li>
                                <li>Crie um novo registro CNAME com os valores acima</li>
                                <li>Aguarde a propagação DNS (pode levar até 48h)</li>
                                <li>O certificado SSL será configurado automaticamente</li>
                            </ol>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status de Verificação -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                    <span class="text-sm text-gray-600">Status: Aguardando configuração</span>
                </div>
                <button type="button" wire:click="verifyDomain"
                        class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Verificar DNS
                </button>
            </div>
        </div>
    </div>

    <!-- Configurações Avançadas -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-sliders-h mr-2"></i>Configurações Avançadas
        </h3>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- SSL/HTTPS -->
            <div class="space-y-4">
                <h4 class="font-medium text-gray-900">
                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>Segurança SSL/HTTPS
                </h4>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <div>
                                <p class="font-medium text-green-800">SSL Automático</p>
                                <p class="text-sm text-green-600">Certificado configurado automaticamente</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Forçar HTTPS</p>
                            <p class="text-sm text-gray-600">Redirecionar HTTP para HTTPS</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.defer="force_https" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Configurações de Cache -->
            <div class="space-y-4">
                <h4 class="font-medium text-gray-900">
                    <i class="fas fa-tachometer-alt mr-2 text-blue-500"></i>Performance
                </h4>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Cache de Conteúdo</p>
                            <p class="text-sm text-gray-600">Melhorar velocidade de carregamento</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.defer="enable_cache" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Compressão GZIP</p>
                            <p class="text-sm text-gray-600">Reduzir tamanho dos arquivos</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.defer="enable_compression" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview da Configuração -->
    <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-green-800 mb-4">
            <i class="fas fa-eye mr-2"></i>Preview da Configuração
        </h3>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <h4 class="font-medium text-gray-900 mb-3">Acesso Principal:</h4>

                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-globe text-green-500"></i>
                        @if ($domain_type === 'custom' && $custom_domain)
                            <code class="text-green-700">https://{{ $custom_domain }}</code>
                        @elseif ($subdomain)
                            <code class="text-green-700">https://{{ $subdomain }}.{{ config('app.domain', 'minhaPlataforma.com') }}</code>
                        @else
                            <span class="text-gray-500">Configure o domínio acima</span>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-shield-alt"></i>
                        <span>SSL {{ $force_https ? 'Forçado' : 'Opcional' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 shadow-sm">
                <h4 class="font-medium text-gray-900 mb-3">Performance:</h4>

                <div class="space-y-2 text-sm">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-{{ $enable_cache ? 'check text-green-500' : 'times text-gray-400' }}"></i>
                        <span>Cache de Conteúdo</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-{{ $enable_compression ? 'check text-green-500' : 'times text-gray-400' }}"></i>
                        <span>Compressão GZIP</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações importantes -->
    <div class="bg-amber-50 border-l-4 border-amber-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-amber-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-amber-800">Informações Importantes:</h4>
                <ul class="mt-2 text-sm text-amber-700 list-disc list-inside space-y-1">
                    <li><strong>Subdomínio:</strong> Configuração automática, disponível imediatamente</li>
                    <li><strong>Domínio personalizado:</strong> Requer configuração DNS pelo cliente</li>
                    <li><strong>Certificado SSL:</strong> Configurado automaticamente após propagação DNS</li>
                    <li><strong>Mudanças:</strong> Configurações podem ser alteradas posteriormente</li>
                </ul>
            </div>
        </div>
    </div>
</div>
