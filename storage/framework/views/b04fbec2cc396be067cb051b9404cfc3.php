<div class="pt-2 pb-6">
    <div class="max-w-7xl mx-auto">

        
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200">
                <div>
                    <div class="pb-4 mb-4 border-b">
                        <h2 class="text-2xl font-bold text-gray-900">Dashboard Super Admin</h2>
                        <p class="mt-1 text-sm text-gray-600">Gerenciamento central do sistema white label</p>
                    </div>

                    <div class="grid grid-cols-6 gap-3">
                        <div>
                            <a
                                href="<?php echo e(route('super-administrador.apps.create')); ?>"
                                class="w-full inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Nova Aplicação
                            </a>
                        </div>
                        <div>
                            <a
                                href="<?php echo e(route('super-administrador.apps.index')); ?>"
                                class="w-full inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Aplicações
                            </a>
                        </div>
                        <div>
                            <a
                                href="<?php echo e(route('super-administrador.dashboard')); ?>"
                                class="w-full inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                                Domínios
                            </a>
                        </div>
                        <div>
                            <a
                                href="<?php echo e(route('super-administrador.listas')); ?>"
                                class="w-full inline-flex items-center px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 active:bg-sky-900 focus:outline-none focus:border-sky-900 focus:ring ring-sky-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                </svg>
                                Listas
                            </a>
                        </div>
                        <div>
                            <a
                                href="<?php echo e(route('super-administrador.sql')); ?>"
                                class="w-full inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 18l6-6-6-6M8 6l-6 6 6 6" />
                                </svg>
                                SQL
                            </a>
                        </div>
                        <div>
                            <button
                                wire:click="clearCache"
                                class="w-full inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Limpar Cache
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Aplicações
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    <?php echo e($stats['apps']['total'] ?? 0); ?>

                                    <?php if(($stats['apps']['active'] ?? 0) > 0): ?>
                                        <span class="text-xs text-green-600 block">(<?php echo e($stats['apps']['active']); ?> ativas)</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Usuários
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    <?php echo e($stats['users']['total'] ?? 0); ?>

                                    <?php if(($stats['users']['verified'] ?? 0) > 0): ?>
                                        <span class="text-xs text-green-600 block">(<?php echo e($stats['users']['verified']); ?> verificados)</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Clientes
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    <?php echo e($stats['customers']['total'] ?? 0); ?>

                                    <?php if(($stats['customers']['this_month'] ?? 0) > 0): ?>
                                        <span class="text-xs text-indigo-600 block">(+<?php echo e($stats['customers']['this_month']); ?> este mês)</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Campanhas
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    <?php echo e($stats['campaigns']['total'] ?? 0); ?>

                                    <?php if(($stats['campaigns']['this_month'] ?? 0) > 0): ?>
                                        <span class="text-xs text-blue-600 block">(+<?php echo e($stats['campaigns']['this_month']); ?> este mês)</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Storage Usado
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    <?php echo e(number_format($stats['storage']['used_mb'] ?? 0, 1)); ?> MB
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            
            <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Aplicações Recentes
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Últimas aplicações criadas no sistema
                    </p>
                </div>

                <?php if(count($apps) > 0): ?>
                    <ul class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $apps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo e(route('super-administrador.apps.edit', $app['id'])); ?>" class="block hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <div class="px-6 py-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-3 w-3 rounded-full"
                                                     style="background-color: <?php echo e($app['colors']['primary'] ?? '#1a202c'); ?>">
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        <?php echo e($app['name']); ?>

                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        <?php echo e($app['domain'] ?? 'Sem domínio'); ?>

                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-2">
                                                <?php if($app['active']): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Ativa
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Inativa
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="mt-2">
                                            <div class="sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="flex items-center text-sm text-gray-500">
                                                        <?php echo e($app['customers_count'] ?? 0); ?> clientes
                                                    </p>
                                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                        <?php echo e($app['campaigns_count'] ?? 0); ?> campanhas
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-center">
                        <a href="<?php echo e(route('super-administrador.apps.index')); ?>" class="text-sm text-blue-600 hover:text-blue-500 font-medium">
                            Ver todas as aplicações →
                        </a>
                    </div>
                <?php else: ?>
                    <div class="px-6 py-8 text-center">
                        <p class="text-gray-500">Nenhuma aplicação encontrada.</p>
                        <a href="<?php echo e(route('super-administrador.apps.create')); ?>" class="mt-2 text-blue-600 hover:text-blue-500 font-medium">
                            Criar primeira aplicação
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Atividades Recentes
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Últimas ações no sistema (7 dias)
                    </p>
                </div>

                <?php if(count($recentActivities) > 0): ?>
                    <div class="px-6 py-4">
                        <div class="flow-root">
                            <ul class="-mb-6">
                                <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <div class="relative pb-6">
                                            <?php if(!$loop->last): ?>
                                                <span class="absolute top-10 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <?php endif; ?>

                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-<?php echo e($activity['color'] ?? 'green'); ?>-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <?php if($activity['type'] === 'app_created'): ?>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                            <?php elseif($activity['type'] === 'campaign_created'): ?>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                                            <?php else: ?>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            <?php endif; ?>
                                                        </svg>
                                                    </span>
                                                </div>

                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            <?php echo e($activity['title']); ?>

                                                        </p>
                                                        <p class="text-sm text-gray-500 mt-0.5">
                                                            <?php echo e($activity['description']); ?>

                                                        </p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-400">
                                                        <?php echo e(\Carbon\Carbon::parse($activity['date'])->diffForHumans()); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="px-6 py-8 text-center">
                        <p class="text-gray-500">Nenhuma atividade recente.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

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
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/super-admin/dashboard.blade.php ENDPATH**/ ?>