<!-- Step 6: Confirmação e Ativação -->
<div class="space-y-6">
    <div class="text-center mb-8">
        <i class="fas fa-check-circle text-5xl text-green-500 mb-4"></i>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Confirmação Final</h2>
        <p class="text-gray-600">
            Revise todas as configurações antes de criar a aplicação white label.
        </p>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Informações Básicas -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Informações Básicas
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nome:</span>
                        <span class="font-medium text-gray-900">{{ $name ?: 'Não informado' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Descrição:</span>
                        <span class="font-medium text-gray-900">{{ Str::limit($description, 30) ?: 'Não informada' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Admin Email:</span>
                        <span class="font-medium text-gray-900">{{ $admin_email ?: 'Não informado' }}</span>
                    </div>
                </div>
            </div>

            <!-- Branding -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-palette text-purple-500 mr-2"></i>
                    Branding
                </h3>
                <div class="space-y-4">
                    <!-- Logos -->
                    <div class="flex space-x-4">
                        <div class="text-center">
                            @if ($logo_preview ?? false)
                                <img src="{{ $logo_preview }}" alt="Logo" class="w-12 h-12 object-contain mx-auto mb-1">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center mx-auto mb-1">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <p class="text-xs text-gray-500">Logo</p>
                        </div>
                        <div class="text-center">
                            @if ($logo_dark_preview ?? false)
                                <img src="{{ $logo_dark_preview }}" alt="Logo Dark" class="w-12 h-12 object-contain mx-auto mb-1">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center mx-auto mb-1">
                                    <i class="fas fa-moon text-gray-400"></i>
                                </div>
                            @endif
                            <p class="text-xs text-gray-500">Dark</p>
                        </div>
                        <div class="text-center">
                            @if ($favicon_preview ?? false)
                                <img src="{{ $favicon_preview }}" alt="Favicon" class="w-12 h-12 object-contain mx-auto mb-1">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center mx-auto mb-1">
                                    <i class="fas fa-bookmark text-gray-400"></i>
                                </div>
                            @endif
                            <p class="text-xs text-gray-500">Favicon</p>
                        </div>
                    </div>

                    <!-- Cores -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-center">
                            <div class="w-full h-8 rounded" style="background-color: {{ $color_primary }}"></div>
                            <p class="text-xs text-gray-500 mt-1">Primária</p>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-8 rounded" style="background-color: {{ $color_secondary }}"></div>
                            <p class="text-xs text-gray-500 mt-1">Secundária</p>
                        </div>
                        <div class="text-center">
                            <div class="w-full h-8 rounded" style="background-color: {{ $color_accent }}"></div>
                            <p class="text-xs text-gray-500 mt-1">Destaque</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Domínios -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-globe text-green-500 mr-2"></i>
                    Domínios
                </h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-600">Principal:</span>
                        <span class="font-medium text-green-600 ml-2">{{ $domain_primary ?: 'Não configurado' }}</span>
                    </div>
                    @if ($domain_aliases && trim($domain_aliases))
                        @php
                            $aliases = array_filter(explode("\n", trim($domain_aliases)));
                        @endphp
                        <div>
                            <span class="text-gray-600">Aliases ({{ count($aliases) }}):</span>
                            <div class="mt-1 space-y-1">
                                @foreach (array_slice($aliases, 0, 3) as $alias)
                                    <div class="text-sm text-blue-600 font-mono">{{ trim($alias) }}</div>
                                @endforeach
                                @if (count($aliases) > 3)
                                    <div class="text-sm text-gray-500">... e mais {{ count($aliases) - 3 }}</div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div>
                            <span class="text-gray-600">Aliases:</span>
                            <span class="text-gray-400 ml-2">Nenhum configurado</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Módulos -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-puzzle-piece text-indigo-500 mr-2"></i>
                    Módulos Habilitados
                </h3>
                @php
                    $selectedFeatures = array_filter($features);
                    $featureNames = [
                        'campaigns' => 'Campanhas',
                        'events' => 'Eventos',
                        'subscriptions' => 'Assinaturas',
                        'analytics' => 'Analytics',
                        'reports' => 'Relatórios',
                        'integrations' => 'Integrações'
                    ];
                    $featureIcons = [
                        'campaigns' => 'fas fa-bullhorn',
                        'events' => 'fas fa-calendar-alt',
                        'subscriptions' => 'fas fa-sync-alt',
                        'analytics' => 'fas fa-chart-line',
                        'reports' => 'fas fa-file-alt',
                        'integrations' => 'fas fa-plug'
                    ];
                @endphp

                @if (count($selectedFeatures) > 0)
                    <div class="space-y-2">
                        @foreach ($selectedFeatures as $feature => $enabled)
                            @if ($enabled)
                                <div class="flex items-center">
                                    <i class="{{ $featureIcons[$feature] ?? 'fas fa-check' }} text-green-500 mr-2"></i>
                                    <span class="text-gray-900">{{ $featureNames[$feature] ?? $feature }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Nenhum módulo selecionado</p>
                @endif
            </div>
        </div>

        <!-- Administrador e Limites -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-user-shield text-teal-500 mr-2"></i>
                Administrador e Configurações
            </h3>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Dados do Admin -->
                <div class="space-y-3">
                    <h4 class="font-medium text-gray-800 mb-3">Primeiro Administrador:</h4>
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ $user_name ? strtoupper(substr($user_name, 0, 1)) : '?' }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $user_name ?: 'Não informado' }}</p>
                            <p class="text-sm text-gray-600">{{ $user_email ?: 'Não informado' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Limites -->
                <div class="space-y-3">
                    <h4 class="font-medium text-gray-800 mb-3">Limites Configurados:</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="bg-gray-50 rounded p-2">
                            <span class="text-gray-600">Storage:</span>
                            <span class="font-medium block">{{ number_format($storage_mb / 1024, 1) }} GB</span>
                        </div>
                        <div class="bg-gray-50 rounded p-2">
                            <span class="text-gray-600">Campanhas/Cliente:</span>
                            <span class="font-medium block">{{ $campaigns_per_customer == 999999 ? 'Ilimitado' : $campaigns_per_customer }}</span>
                        </div>
                        <div class="bg-gray-50 rounded p-2">
                            <span class="text-gray-600">Eventos/Cliente:</span>
                            <span class="font-medium block">{{ $events_per_customer == 999999 ? 'Ilimitado' : $events_per_customer }}</span>
                        </div>
                        <div class="bg-gray-50 rounded p-2">
                            <span class="text-gray-600">Expira em:</span>
                            <span class="font-medium block">{{ Carbon\Carbon::parse($app_limit_date)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- O que Acontecerá -->
        <div class="bg-green-50 border-l-4 border-green-400 p-6 mt-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-rocket text-green-400"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-lg font-medium text-green-800 mb-3">O que acontecerá ao criar a aplicação:</h4>
                    <ol class="text-sm text-green-700 list-decimal list-inside space-y-2">
                        <li>Nova aplicação será criada no banco de dados</li>
                        <li>Estrutura de diretórios será criada no storage</li>
                        <li>Logos serão transferidos para o storage isolado</li>
                        <li>Cache de domínios será configurado</li>
                        <li>Primeiro usuário administrador será criado</li>
                        <li>Configurações de módulos e limites serão aplicadas</li>
                        <li>E-mails padrão serão configurados</li>
                        <li>Aplicação estará pronta para uso</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Próximos Passos -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-6 mt-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-list-check text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-lg font-medium text-blue-800 mb-3">Próximos passos após criação:</h4>
                    <ol class="text-sm text-blue-700 list-decimal list-inside space-y-1">
                        <li>Configurar DNS dos domínios para apontar para este servidor</li>
                        <li>Instalar certificados SSL para os domínios</li>
                        <li>Testar acesso através dos domínios configurados</li>
                        <li>Fazer login com o usuário administrador criado</li>
                        <li>Configurar textos personalizados (se necessário)</li>
                        <li>Criar primeiros clientes e usuários</li>
                        <li>Configurar gateway de pagamento</li>
                        <li>Iniciar operação da aplicação</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Aviso Final -->
        <div class="bg-red-50 border-l-4 border-red-400 p-6 mt-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-lg font-medium text-red-800">Atenção:</h4>
                    <p class="text-sm text-red-700 mt-1">
                        Após criar a aplicação, algumas configurações não poderão ser alteradas facilmente (como domínio principal).
                        Certifique-se de que todas as informações estão corretas antes de prosseguir.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Confirmação antes de criar a aplicação
document.addEventListener('livewire:load', function () {
    window.confirmWizardCompletion = function() {
        return confirm('Tem certeza que deseja criar esta aplicação white label? Esta ação não pode ser desfeita.');
    };
});
</script>
@endpush
