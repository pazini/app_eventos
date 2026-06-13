<!-- Step 3: Configuração de Domínio -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-globe text-4xl text-green-500 mb-4"></i>
        <p class="text-gray-600">
            Configure o domínio principal e aliases para sua aplicação.
        </p>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Domínio Principal -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                <i class="fas fa-link mr-2"></i>Domínio Principal
            </h3>

            <div>
                <label for="domain_primary" class="block text-sm font-medium text-gray-700 mb-2">
                    Domínio Principal *
                </label>
                <input type="text" id="domain_primary" wire:model="domain_primary"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('domain_primary') border-red-500 @enderror"
                       placeholder="exemplo: meuapp.com.br">
                @error('domain_primary')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Este será o domínio principal da aplicação. Não inclua "https://" ou "www".
                </p>
            </div>

            <!-- Informações do Domínio -->
            @if ($domain_primary)
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <h4 class="font-medium text-green-800 mb-2">URLs Geradas:</h4>
                    <div class="space-y-1 text-sm">
                        <p class="text-green-700">
                            <i class="fas fa-home mr-2"></i>
                            <strong>Principal:</strong> https://{{ $domain_primary }}
                        </p>
                        <p class="text-green-700">
                            <i class="fas fa-envelope mr-2"></i>
                            <strong>E-mail padrão:</strong> noreply@{{ $domain_primary }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Validações -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-800">Requisitos do Domínio:</h4>
                        <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                            <li>Deve ser um domínio válido (ex: exemplo.com.br)</li>
                            <li>Não pode estar sendo usado por outra aplicação</li>
                            <li>Deve estar apontado para este servidor</li>
                            <li>Certificado SSL recomendado</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Domínios Alternativos -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                <i class="fas fa-sitemap mr-2"></i>Domínios Alternativos
            </h3>

            <div>
                <label for="domain_aliases" class="block text-sm font-medium text-gray-700 mb-2">
                    Aliases (Opcional)
                </label>
                <textarea id="domain_aliases" wire:model="domain_aliases" rows="5"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('domain_aliases') border-red-500 @enderror"
                          placeholder="www.meuapp.com.br&#10;app.exemplo.com&#10;meuapp.exemplo.com.br&#10;&#10;(um domínio por linha)"></textarea>
                @error('domain_aliases')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Domínios alternativos que também levarão para esta aplicação. Um por linha.
                </p>
            </div>

            <!-- Preview dos Aliases -->
            @if ($domain_aliases)
                @php
                    $aliases = array_filter(explode("\n", trim($domain_aliases)));
                @endphp
                @if (count($aliases) > 0)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h4 class="font-medium text-gray-800 mb-2">
                            Aliases Configurados ({{ count($aliases) }}):
                        </h4>
                        <div class="space-y-1">
                            @foreach ($aliases as $alias)
                                @php $alias = trim($alias); @endphp
                                @if ($alias)
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i class="fas fa-arrow-right text-green-500 mr-2"></i>
                                        <span class="font-mono">https://{{ $alias }}</span>
                                        <i class="fas fa-long-arrow-alt-right mx-2 text-gray-400"></i>
                                        <span class="text-gray-600">{{ $domain_primary ?: 'domínio-principal' }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <!-- Dicas de Aliases -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lightbulb text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-yellow-800">Exemplos de Aliases:</h4>
                        <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                            <li><strong>www.seudominio.com</strong> - Versão com www</li>
                            <li><strong>app.empresa.com</strong> - Subdomínio da empresa</li>
                            <li><strong>eventos.marca.com.br</strong> - Subdomínio específico</li>
                            <li><strong>seudominio.org</strong> - Extensão alternativa</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumo Final -->
    @if ($domain_primary)
        <div class="bg-white rounded-lg border-2 border-green-200 p-6">
            <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                Resumo da Configuração de Domínios
            </h4>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Domínio Principal -->
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div>
                            <p class="font-medium text-gray-900">Domínio Principal:</p>
                            <p class="text-lg text-green-600 font-mono">{{ $domain_primary }}</p>
                            <p class="text-sm text-gray-600">Este será o domínio principal da aplicação</p>
                        </div>
                    </div>
                </div>

                <!-- Aliases -->
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div>
                            <p class="font-medium text-gray-900">Aliases:</p>
                            @if ($domain_aliases && trim($domain_aliases))
                                @php
                                    $aliases = array_filter(explode("\n", trim($domain_aliases)));
                                @endphp
                                <div class="space-y-1">
                                    @foreach (array_slice($aliases, 0, 3) as $alias)
                                        <p class="text-sm text-blue-600 font-mono">{{ trim($alias) }}</p>
                                    @endforeach
                                    @if (count($aliases) > 3)
                                        <p class="text-sm text-gray-500">... e mais {{ count($aliases) - 3 }}</p>
                                    @endif
                                </div>
                            @else
                                <p class="text-sm text-gray-500">Nenhum alias configurado</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Próximos Passos -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h5 class="font-medium text-gray-800 mb-2">Próximos Passos (Após Criação):</h5>
                <ol class="text-sm text-gray-600 list-decimal list-inside space-y-1">
                    <li>Apontar DNS dos domínios para este servidor</li>
                    <li>Configurar certificados SSL</li>
                    <li>Testar acesso pelos domínios configurados</li>
                    <li>Configurar redirects necessários</li>
                </ol>
            </div>
        </div>
    @endif

    <!-- Aviso Importante -->
    <div class="bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-red-800">Importante:</h4>
                <p class="text-sm text-red-700 mt-1">
                    Certifique-se de que os domínios estejam apontando para este servidor antes de ativar a aplicação.
                    Domínios mal configurados podem causar problemas de acesso.
                </p>
            </div>
        </div>
    </div>
</div>
