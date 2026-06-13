<div class="pt-2 pb-6">
    <div class="max-w-7xl mx-auto">
        <div class="space-y-6">

            
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-6">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-2xl font-bold text-gray-900">Aplicações White Label</h1>
                            <p class="mt-2 text-sm text-gray-600">Gerenciar todas as aplicações do sistema white label.</p>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none flex space-x-3">
                            
                            <?php if(count($selectedApps) > 0): ?>
                                <div class="flex items-center space-x-2 mr-4">
                                    <span class="text-sm text-gray-600"><?php echo e(count($selectedApps)); ?> selecionadas</span>
                                    <button wire:click="confirmBulkAction('activate')"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200">
                                        Ativar
                                    </button>
                                    <button wire:click="confirmBulkAction('deactivate')"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200">
                                        Desativar
                                    </button>
                                    <button wire:click="confirmBulkAction('extend_30_days')"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                        +30 dias
                                    </button>
                                    <button wire:click="cancelBulkAction"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                        Cancelar
                                    </button>
                                </div>
                            <?php endif; ?>

                            
                            <button wire:click="exportData('csv')"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                </svg>
                                Exportar CSV
                            </button>

                            
                            <a href="<?php echo e(route('super-administrador.apps.create')); ?>"
                               class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Nova Aplicação
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="p-5">
                        <div class="text-sm font-medium text-gray-500">Total</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e($total); ?></div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="p-5">
                        <div class="text-sm font-medium text-green-500">Ativas</div>
                        <div class="mt-1 text-3xl font-semibold text-green-900"><?php echo e($totalActive); ?></div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="p-5">
                        <div class="text-sm font-medium text-red-500">Inativas</div>
                        <div class="mt-1 text-3xl font-semibold text-red-900"><?php echo e($totalInactive); ?></div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="p-5">
                        <div class="text-sm font-medium text-yellow-500">Expiradas</div>
                        <div class="mt-1 text-3xl font-semibold text-yellow-900"><?php echo e($totalExpired); ?></div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input
                                type="text"
                                wire:model="search"
                                id="search"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="Nome ou domínio..."
                            >
                        </div>

                        
                        <div>
                            <label for="statusFilter" class="block text-sm font-medium text-gray-700">Status</label>
                            <select
                                wire:model="statusFilter"
                                id="statusFilter"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="all">Todos</option>
                                <option value="active">Ativas</option>
                                <option value="inactive">Inativas</option>
                                <option value="expired">Expiradas</option>
                            </select>
                        </div>

                        
                        <div>
                            <label for="moduleFilter" class="block text-sm font-medium text-gray-700">Módulos</label>
                            <select
                                wire:model="moduleFilter"
                                id="moduleFilter"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="all">Todos</option>
                                <option value="campaigns">Campanhas</option>
                                <option value="events">Eventos</option>
                                <option value="subscriptions">Assinaturas</option>
                                <option value="analytics">Analytics</option>
                                <option value="reports">Relatórios</option>
                                <option value="integrations">Integrações</option>
                            </select>
                        </div>

                        
                        <div>
                            <label for="perPage" class="block text-sm font-medium text-gray-700">Por página</label>
                            <select
                                wire:model="perPage"
                                id="perPage"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        
                        <div>
                            <label for="sortBy" class="block text-sm font-medium text-gray-700">Ordenar por</label>
                            <select
                                wire:model="sortBy"
                                id="sortBy"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="created_at">Data de criação</option>
                                <option value="app_name">Nome</option>
                                <option value="domain_primary">Domínio</option>
                                <option value="app_limit_date">Data limite</option>
                                <option value="app_active">Status</option>
                            </select>
                        </div>
                    </div>

                    <?php if($search || $statusFilter !== 'all' || $moduleFilter !== 'all'): ?>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <button wire:click="clearFilters"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Limpar Filtros
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white shadow-sm overflow-hidden rounded-lg border border-gray-200">
                <?php if($apps->count() > 0): ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            
                                            <th scope="col" class="w-10 px-4 py-3 text-left">
                                                <input type="checkbox" wire:model="selectAll"
                                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </th>

                                            
                                            <th wire:click="sortBy('app_name')"
                                                scope="col"
                                                class="min-w-[200px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                                Nome
                                                <?php if($sortBy === 'app_name'): ?>
                                                    <?php if($sortDirection === 'asc'): ?>
                                                        <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                                    <?php else: ?>
                                                        <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </th>

                                            
                                            <th wire:click="sortBy('domain_primary')"
                                                scope="col"
                                                class="min-w-[180px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                                Domínio
                                                <?php if($sortBy === 'domain_primary'): ?>
                                                    <?php if($sortDirection === 'asc'): ?>
                                                        <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                                    <?php else: ?>
                                                        <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </th>

                                            
                                            <th scope="col" class="w-24 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>

                                            
                                            <th scope="col" class="w-32 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Dados
                                            </th>

                                            
                                            <th wire:click="sortBy('created_at')"
                                                scope="col"
                                                class="w-36 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                                Criado em
                                                <?php if($sortBy === 'created_at'): ?>
                                                    <?php if($sortDirection === 'asc'): ?>
                                                        <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                                    <?php else: ?>
                                                        <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </th>

                                            
                                            <th scope="col" class="w-32 px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ações
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $apps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50">
                                        
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input type="checkbox"
                                                wire:click="toggleAppSelection('<?php echo e($app->id); ?>')"
                                                <?php if(in_array($app->id, $selectedApps)): ?> checked <?php endif; ?>
                                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        </td>

                                        
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-4 w-4 rounded-full mr-3"
                                                    style="background-color: <?php echo e($app->color_primary ?? '#1a202c'); ?>">
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo e($app->app_name); ?>

                                                    </div>
                                                    <?php if($app->app_limit_date && $app->app_limit_date->isPast()): ?>
                                                        <div class="text-sm text-red-500">
                                                            Expirada em <?php echo e($app->app_limit_date->format('d/m/Y')); ?>

                                                        </div>
                                                    <?php elseif($app->app_limit_date): ?>
                                                        <div class="text-sm text-gray-500">
                                                            Expira em <?php echo e($app->app_limit_date->format('d/m/Y')); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>

                                        
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo e($app->domain_primary ?: 'Não definido'); ?></div>
                                            <?php if($app->domain_aliases): ?>
                                                <div class="text-sm text-gray-500">
                                                    +<?php echo e(count(is_array($app->domain_aliases) ? $app->domain_aliases : json_decode($app->domain_aliases, true))); ?> aliases
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <?php if($app->app_active && (!$app->app_limit_date || $app->app_limit_date->isFuture())): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Ativa
                                                </span>
                                            <?php elseif($app->app_limit_date && $app->app_limit_date->isPast()): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Expirada
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Inativa
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="text-xs">
                                                <div><?php echo e($app->customers_count); ?> clientes</div>
                                                <div><?php echo e($app->campaigns_count); ?> campanhas</div>
                                            </div>
                                        </td>

                                        
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($app->created_at->format('d/m/Y H:i')); ?>

                                        </td>

                                        
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center space-x-2">
                                                
                                                <a href="<?php echo e(route('super-administrador.apps.edit', $app->id)); ?>"
                                                class="text-blue-600 hover:text-blue-900 p-1"
                                                title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                        <?php echo e($apps->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma aplicação encontrada</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            <?php if(!empty($search) || $statusFilter !== 'all'): ?>
                                Tente ajustar os filtros ou criar uma nova aplicação.
                            <?php else: ?>
                                Comece criando sua primeira aplicação.
                            <?php endif; ?>
                        </p>
                        <div class="mt-6">
                            <a href="<?php echo e(route('super-administrador.apps.create')); ?>"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Nova Aplicação
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($showDeleteModal && $appToDelete): ?>
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="delete-modal">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mt-5">Confirmar exclusão</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">
                                Tem certeza que deseja excluir a aplicação <strong><?php echo e($appToDelete->app_name); ?></strong>?
                                Esta ação não pode ser desfeita.
                            </p>
                        </div>
                        <div class="flex items-center px-4 py-3">
                            <button
                                wire:click="deleteApp"
                                class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300"
                            >
                                Confirmar Exclusão
                            </button>
                            <button
                                wire:click="cancelDelete"
                                class="ml-3 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            >
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if($showBulkActionModal): ?>
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="bulk-action-modal">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full
                            <?php echo e($bulkAction === 'activate' ? 'bg-green-100' : ($bulkAction === 'deactivate' ? 'bg-red-100' : 'bg-blue-100')); ?>">
                            <?php if($bulkAction === 'activate'): ?>
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            <?php elseif($bulkAction === 'deactivate'): ?>
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mt-5">Confirmar Ação em Lote</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">
                                Tem certeza que deseja
                                <?php if($bulkAction === 'activate'): ?>
                                    <strong>ativar</strong>
                                <?php elseif($bulkAction === 'deactivate'): ?>
                                    <strong>desativar</strong>
                                <?php else: ?>
                                    <strong>estender por 30 dias</strong>
                                <?php endif; ?>
                                <strong><?php echo e(count($selectedApps)); ?></strong> aplicação(ões) selecionada(s)?
                            </p>
                        </div>
                        <div class="flex items-center px-4 py-3">
                            <button
                                wire:click="executeBulkAction"
                                class="px-4 py-2 <?php echo e($bulkAction === 'activate' ? 'bg-green-500 hover:bg-green-700' : ($bulkAction === 'deactivate' ? 'bg-red-500 hover:bg-red-700' : 'bg-blue-500 hover:bg-blue-700')); ?> text-white text-base font-medium rounded-md w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2"
                            >
                                Confirmar
                            </button>
                            <button
                                wire:click="cancelBulkAction"
                                class="ml-3 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            >
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>


<script>
    document.addEventListener('livewire:load', function () {
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
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/super-admin/apps-index.blade.php ENDPATH**/ ?>