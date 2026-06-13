<div class="p-6">
    <div class="space-y-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total de Clientes</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">
                                        <?php echo e(number_format($stats['customers']['total'] ?? 0)); ?>

                                    </div>
                                    <?php if(($stats['customers']['total'] ?? 0) > 0): ?>
                                        <div class="ml-2 flex items-baseline text-sm text-green-600">
                                            <?php echo e(number_format($stats['customers']['active'] ?? 0)); ?> ativos
                                        </div>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Campanhas</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">
                                        <?php echo e(number_format($stats['campaigns']['total'] ?? 0)); ?>

                                    </div>
                                    <?php if(($stats['campaigns']['total'] ?? 0) > 0): ?>
                                        <div class="ml-2 flex items-baseline text-sm text-green-600">
                                            <?php echo e(number_format($stats['campaigns']['active'] ?? 0)); ?> ativas
                                        </div>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Storage</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">
                                        <?php echo e(number_format($stats['storage']['used_mb'] ?? 0, 1)); ?> MB
                                    </div>
                                    <div class="ml-2 flex items-baseline text-sm text-gray-500">
                                        / <?php echo e(number_format($stats['storage']['limit_mb'] ?? 0)); ?> MB
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    
                    <?php if(($stats['storage']['limit_mb'] ?? 0) > 0): ?>
                        <?php
                            $usage_percentage = min(100, ($stats['storage']['used_mb'] / $stats['storage']['limit_mb']) * 100);
                            $color_class = $usage_percentage > 90 ? 'bg-red-500' : ($usage_percentage > 70 ? 'bg-yellow-500' : 'bg-green-500');
                        ?>
                        <div class="mt-3">
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="<?php echo e($color_class); ?> h-2 rounded-full" style="width: <?php echo e($usage_percentage); ?>%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e(number_format($usage_percentage, 1)); ?>% usado</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Última Atividade</dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    <?php if($stats['last_activity'] ?? null): ?>
                                        <?php echo e(\Carbon\Carbon::parse($stats['last_activity'])->diffForHumans()); ?>

                                    <?php else: ?>
                                        Nenhuma
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Detalhes da Aplicação</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Informações Técnicas</h4>
                        <dl class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500">ID da Aplicação</dt>
                                <dd class="text-sm text-gray-900 font-mono"><?php echo e(Str::limit($app->id, 8)); ?>...</dd>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500">Criada em</dt>
                                <dd class="text-sm text-gray-900"><?php echo e($app->created_at->format('d/m/Y H:i')); ?></dd>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                                <dd class="text-sm text-gray-900"><?php echo e($app->updated_at->format('d/m/Y H:i')); ?></dd>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500">Branding atualizado</dt>
                                <dd class="text-sm text-gray-900">
                                    <?php echo e($app->branding_updated_at ? $app->branding_updated_at->format('d/m/Y H:i') : 'Nunca'); ?>

                                </dd>
                            </div>
                            <?php if($app->app_limit_date): ?>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500">Expira em</dt>
                                    <dd class="text-sm <?php echo e($app->app_limit_date->isPast() ? 'text-red-600' : 'text-gray-900'); ?>">
                                        <?php echo e($app->app_limit_date->format('d/m/Y H:i')); ?>

                                        <?php if($app->app_limit_date->isPast()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                                Expirado
                                            </span>
                                        <?php endif; ?>
                                    </dd>
                                </div>
                            <?php endif; ?>
                        </dl>
                    </div>

                    
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Módulos Habilitados</h4>
                        <div class="space-y-2">
                            <?php $__currentLoopData = ['campaigns' => 'Campanhas', 'events' => 'Eventos', 'subscriptions' => 'Assinaturas', 'analytics' => 'Analytics', 'reports' => 'Relatórios', 'integrations' => 'Integrações']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between py-1">
                                    <span class="text-sm text-gray-700"><?php echo e($label); ?></span>
                                    <?php if($features[$key] ?? false): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Ativo
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Inativo
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Ações de Manutenção</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <button wire:click="clearAppCache"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Limpar Cache
                    </button>

                    <button wire:click="loadStats"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Atualizar Estatísticas
                    </button>

                    <button wire:click="duplicateApp"
                            wire:confirm="Confirma duplicação desta aplicação?"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Duplicar Aplicação
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/super-admin/tabs/stats.blade.php ENDPATH**/ ?>