<div class="pt-2 pb-6">
    <div class="max-w-7xl mx-auto">
        <div class="space-y-6">
            
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                <?php if($app->url_image_logo): ?>
                                    <img src="<?php echo e(appLogo(true)); ?>"
                                         alt="<?php echo e($app->app_name); ?>" class="h-8 w-8 object-contain">
                                <?php else: ?>
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                
                                <div class="flex items-center space-x-2">
                                    <h1 class="text-2xl font-bold text-gray-900"><?php echo e($app->app_name); ?></h1>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php echo e($app_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($app_active ? 'Ativo' : 'Inativo'); ?>

                                    </span>
                                </div>
                                <p class="text-sm text-gray-500"><?php echo e($app->domain_primary); ?></p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            
                            <a href="<?php echo e(route('super-administrador.apps.index')); ?>"
                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Voltar à Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white shadow rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">

                        <button wire:click="setActiveTab('stats')"
                                class="py-4 px-1 border-b-2 font-medium text-sm
                                <?php echo e($activeTab === 'stats' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Estatísticas
                        </button>

                        <button wire:click="setActiveTab('basic')"
                                class="py-4 px-1 border-b-2 font-medium text-sm
                                <?php echo e($activeTab === 'basic' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                            Informações Básicas
                        </button>

                        <button wire:click="setActiveTab('branding')"
                                class="py-4 px-1 border-b-2 font-medium text-sm
                                <?php echo e($activeTab === 'branding' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                            </svg>
                            Branding
                        </button>

                        <button wire:click="setActiveTab('settings')"
                                class="py-4 px-1 border-b-2 font-medium text-sm
                                <?php echo e($activeTab === 'settings' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Configurações
                        </button>

                        <button wire:click="setActiveTab('customers')"
                                class="py-4 px-1 border-b-2 font-medium text-sm
                                <?php echo e($activeTab === 'customers' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Customers
                        </button>

                        <button wire:click="setActiveTab('active-customers')"
                                class="py-4 px-1 border-b-2 font-medium text-sm
                                <?php echo e($activeTab === 'active-customers' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Clientes Ativos
                        </button>

                        <button wire:click="setActiveTab('safe2pay')"
                                class="py-4 px-1 border-b-2 font-medium text-sm
                                <?php echo e($activeTab === 'safe2pay' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Safe2Pay Master
                        </button>

                    </nav>
                </div>
            </div>

            
            <div class="bg-white shadow rounded-lg">
                <?php if($activeTab === 'stats'): ?>
                    <?php echo $__env->make('livewire.super-admin.tabs.stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($activeTab === 'basic'): ?>
                    <?php echo $__env->make('livewire.super-admin.tabs.basic-info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($activeTab === 'branding'): ?>
                    <?php echo $__env->make('livewire.super-admin.tabs.branding', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($activeTab === 'settings'): ?>
                    <?php echo $__env->make('livewire.super-admin.tabs.settings', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($activeTab === 'customers'): ?>
                    <?php echo $__env->make('livewire.super-admin.tabs.customers', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($activeTab === 'active-customers'): ?>
                    <?php echo $__env->make('livewire.super-admin.tabs.active-customers', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($activeTab === 'safe2pay'): ?>
                    <?php echo $__env->make('livewire.super-admin.tabs.safe2pay', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/super-admin/apps-edit.blade.php ENDPATH**/ ?>