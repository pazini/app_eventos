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
    <?php if (isset($component)) { $__componentOriginal10717d162484e57a570d6d2cc4597545 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal10717d162484e57a570d6d2cc4597545 = $attributes; } ?>
<?php $component = WireUi\View\Components\Notifications::resolve(['position' => 'top-right'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('notifications'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Notifications::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal10717d162484e57a570d6d2cc4597545)): ?>
<?php $attributes = $__attributesOriginal10717d162484e57a570d6d2cc4597545; ?>
<?php unset($__attributesOriginal10717d162484e57a570d6d2cc4597545); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal10717d162484e57a570d6d2cc4597545)): ?>
<?php $component = $__componentOriginal10717d162484e57a570d6d2cc4597545; ?>
<?php unset($__componentOriginal10717d162484e57a570d6d2cc4597545); ?>
<?php endif; ?>

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

    <?php if(session('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('success', <?php echo json_encode(session('success'), 15, 512) ?>);
            });
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('error', <?php echo json_encode(session('error'), 15, 512) ?>);
            });
        </script>
    <?php endif; ?>

    <?php if($standaloneCreate || $standaloneEdit): ?>

        
        <div class="max-w-7xl mx-auto bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">
                        <?php echo e($standaloneEdit ? 'Editar Cliente' : 'Novo Cliente'); ?></h2>
                    <p class="text-sm text-gray-500">
                        <?php echo e($standaloneEdit ? 'Atualize os dados do cliente selecionado.' : 'Preencha os dados para criar um novo cliente.'); ?>

                    </p>
                </div>
                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Voltar','href' => ''.e($standaloneEdit && $customerId ? route('configuracoes-customer', ['customer_id' => $customerId]) : route('configuracoes')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'a']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
            </div>

            <div class="space-y-6">
                <?php if($errors->any()): ?>
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Dados Principais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Razão Social / Nome do Cliente'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerNameCorporate','placeholder' => 'Razão social da empresa','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Nome Fantasia / Nome Comercial'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.debounce.800ms' => 'customerNameFantasy','placeholder' => 'Nome fantasia','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Nome Curto'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerNameShort','placeholder' => 'Nome curto/abreviação','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div class="md:col-span-1">
                            <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['label' => 'Tipo de Documento'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'customerDocType','class' => 'w-full']); ?>
                                <option value="cpf">CPF</option>
                                <option value="cnpj">CNPJ</option>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $attributes = $__attributesOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__attributesOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $component = $__componentOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__componentOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
                        </div>
                        <div class="md:col-span-1">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Número do Documento'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerDocNum','placeholder' => 'Digite o documento','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <?php if($standaloneEdit): ?>
                            <div class="md:col-span-2">
                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Slug do Cliente'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerSlug','placeholder' => 'slug-do-cliente','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                <?php $__errorArgs = ['customerSlug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php else: ?>
                            <div class="md:col-span-2">
                                <label class="block text-base font-light uppercase text-black dark:text-gray-400"
                                    for="f3b7a19df077e8015b72f1e5877e0ac8">URL do Cliente</label>
                                <div
                                    class="placeholder-secondary-400 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 bg-gray-100 dark:bg-secondary-800 form-input block w-full sm:text-sm transition ease-in-out duration-100 focus:outline-none shadow-sm rounded-none cursor-not-allowed">
                                    www.sitecliente.com.br/<?php echo e($customerSlug ?? '{slug}'); ?></div>
                                <?php $__errorArgs = ['customerSlug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Contatos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Contato Comercial'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerComercialContactName','placeholder' => 'Nome do contato comercial','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                            </div>
                            <div>
                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'DDD Comercial'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerComercialContactDdd','placeholder' => 'DDD','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                            </div>
                            <div>
                                <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['label' => 'Telefone Comercial','mask' => '[\'####-####\',\'#####-####\']'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerComercialContactNum','placeholder' => 'Telefone','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $attributes = $__attributesOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__attributesOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $component = $__componentOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__componentOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
                            </div>
                            <div class="md:col-span-2">
                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'E-mail Comercial'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerComercialContactEmail','placeholder' => 'email@empresa.com','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Contato Financeiro'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerFinancialContactName','placeholder' => 'Nome do contato financeiro','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                            </div>
                            <div>
                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'DDD Financeiro'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerFinancialContactDdd','placeholder' => 'DDD','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                            </div>
                            <div>
                                <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['label' => 'Telefone Financeiro','mask' => '[\'####-####\',\'#####-####\']'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerFinancialContactNum','placeholder' => 'Telefone','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $attributes = $__attributesOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__attributesOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $component = $__componentOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__componentOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
                            </div>
                            <div class="md:col-span-2">
                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'E-mail Financeiro'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerFinancialContactEmail','placeholder' => 'financeiro@empresa.com','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Endereço</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                        <div class="lg:col-span-2">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Endereço'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerAddress','placeholder' => 'Rua / Avenida','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Número'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerAddressNumber','placeholder' => 'Número','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Complemento'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerAddressComplement','placeholder' => 'Complemento','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Bairro'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerCityNeighborhood','placeholder' => 'Bairro','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Cidade'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerCity','placeholder' => 'Cidade','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Estado'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerState','placeholder' => 'UF','class' => 'w-full uppercase']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['label' => 'CEP','mask' => '#####-###'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerZipCode','placeholder' => '_____-___','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $attributes = $__attributesOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__attributesOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $component = $__componentOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__componentOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Online</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Site'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerUrlSite','placeholder' => 'https://site.com','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Instagram'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerUrlInstagram','placeholder' => 'https://instagram.com/exemplo','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Facebook'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerUrlFacebook','placeholder' => 'https://facebook.com/exemplo','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div class="md:col-span-3">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Gerar Fatura</p>
                                    <p class="text-xs text-gray-500 mt-1">Habilitar geração automática de notas fiscais
                                    </p>
                                </div>
                                <?php if (isset($component)) { $__componentOriginale45caf11f55ea97b78a13a84cea67cba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale45caf11f55ea97b78a13a84cea67cba = $attributes; } ?>
<?php $component = WireUi\View\Components\Toggle::resolve(['lg' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Toggle::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerGenerateInvoice','color' => 'green']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale45caf11f55ea97b78a13a84cea67cba)): ?>
<?php $attributes = $__attributesOriginale45caf11f55ea97b78a13a84cea67cba; ?>
<?php unset($__attributesOriginale45caf11f55ea97b78a13a84cea67cba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale45caf11f55ea97b78a13a84cea67cba)): ?>
<?php $component = $__componentOriginale45caf11f55ea97b78a13a84cea67cba; ?>
<?php unset($__componentOriginale45caf11f55ea97b78a13a84cea67cba); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if($errors->any()): ?>
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Cancelar','href' => ''.e($standaloneEdit && $customerId ? route('configuracoes-customer', ['customer_id' => $customerId]) : route('configuracoes')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'a']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => ''.e($standaloneEdit ? 'Salvar Alterações' : 'Criar Cliente').'','spinner' => 'saveCustomer'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'wire:click' => 'saveCustomer']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php if(count($customers ?? [])): ?>

            
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

                        
                        <div class="flex items-center gap-4">
                            
                            <div class="min-w-[300px]">
                                <select id="customerSelector" onchange="forceCustomerChange(this.value)"
                                    class="w-full uppercase pt-2 pb-2 bg-white/95 backdrop-blur-sm border-white/30 focus:border-white focus:ring-2 focus:ring-white/50 rounded-lg shadow-sm transition-all duration-200">
                                    <option value="">Selecione um cliente</option>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e($customerId == $item->id || request()->route('customer_id') == $item->id || request('customer_id') == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name_corporate); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <script>
                                function forceCustomerChange(customerId) {
                                    console.log('Forçando mudança para:', customerId);
                                    const configuracoesBaseUrl = <?php echo json_encode(route('configuracoes'), 15, 512) ?>;
                                    if (customerId) {
                                        // Recarrega a página com customer_id no path
                                        window.location.href = `${configuracoesBaseUrl}/${encodeURIComponent(customerId)}`;
                                    } else {
                                        // Volta para a rota base sem cliente selecionado
                                        window.location.href = configuracoesBaseUrl;
                                    }
                                }
                            </script>

                            
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
                                    <a href="<?php echo e(route('configuracoes-novo-cliente')); ?>"
                                        class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Novo Cliente
                                    </a>

                                    

                                    
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

            
            <?php if($customerId): ?>
                <div wire:key="customer-<?php echo e($customerId); ?>"
                    class="w-full max-w-7xl mx-auto bg-white shadow-sm border-x border-b rounded-b-lg"
                    x-data="{ activeTab: '<?php echo e($activeTab); ?>' }">
                    
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
                        
                        <div x-show="activeTab === 'cliente'">
                            
                            <div class="print-only border-b-2 border-gray-300 pb-2 mb-4">
                                <h2 style="font-size: 16px; font-weight: bold; color: #000;">INFORMAÇÕES DO CLIENTE
                                </h2>
                            </div>
                            <div class="space-y-6">
                                <?php if($customerId): ?>
                                    <div class="flex justify-between items-center">
                                        <h2 class="text-lg font-semibold text-gray-800">Informações do Cliente</h2>
                                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Editar Cliente','href' => ''.e(route('configuracoes-editar-cliente', ['customer_id' => $customerId])).'','icon' => 'pencil-alt'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'sm' => true,'as' => 'a','class' => 'px-4 py-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                    </div>

                                    <?php
                                        $customer = \App\Models\Customer::find($customerId);
                                    ?>

                                    <?php if($customer): ?>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            
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
                                                            <?php echo e($customer->name_corporate ?? '--'); ?></div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-blue-600 uppercase mb-1">
                                                            Nome Fantasia</div>
                                                        <div class="text-base font-semibold text-blue-900">
                                                            <?php echo e($customer->name_fantasy ?? '--'); ?></div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-blue-600 uppercase mb-1">
                                                            Nome Curto</div>
                                                        <div class="text-base font-semibold text-blue-900">
                                                            <?php echo e($customer->name_short ?? '--'); ?></div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-blue-600 uppercase mb-1">
                                                            <?php echo e(strtoupper($customer->doc_type ?? 'Documento')); ?></div>
                                                        <div class="text-base font-semibold text-blue-900">
                                                            <?php echo e($customer->doc_num ?? '--'); ?></div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-blue-600 uppercase mb-1">
                                                            Slug</div>
                                                        <div class="text-base font-semibold text-blue-900 font-mono">
                                                            <?php echo e($customer->customer_slug ?? '--'); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            
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
                                                            <?php echo e($customer->address ?? '--'); ?>

                                                            <?php if($customer->address_number): ?>
                                                                , <?php echo e($customer->address_number); ?>

                                                            <?php endif; ?>
                                                            <?php if($customer->address_complement): ?>
                                                                - <?php echo e($customer->address_complement); ?>

                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-amber-600 uppercase mb-1">
                                                            Bairro</div>
                                                        <div class="text-base font-semibold text-amber-900">
                                                            <?php echo e($customer->city_neighborhood ?? '--'); ?></div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-amber-600 uppercase mb-1">
                                                            Cidade / Estado</div>
                                                        <div class="text-base font-semibold text-amber-900">
                                                            <?php echo e($customer->city ?? '--'); ?> /
                                                            <?php echo e($customer->state ?? '--'); ?>

                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-amber-600 uppercase mb-1">
                                                            CEP</div>
                                                        <div class="text-base font-semibold text-amber-900">
                                                            <?php echo e($customer->zip_code ?? '--'); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            
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
                                                            <?php echo e($customer->comercial_contact_name ?? '--'); ?></div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-green-600 uppercase mb-1">
                                                            E-mail</div>
                                                        <div class="text-base font-semibold text-green-900 break-all">
                                                            <?php echo e($customer->comercial_contact_email ?? '--'); ?></div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-green-600 uppercase mb-1">
                                                            Telefone</div>
                                                        <div class="text-base font-semibold text-green-900">
                                                            <?php if($customer->comercial_contact_ddd && $customer->comercial_contact_num): ?>
                                                                (<?php echo e($customer->comercial_contact_ddd); ?>)
                                                                <?php echo e($customer->comercial_contact_num); ?>

                                                            <?php else: ?>
                                                                --
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            
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
                                                            <?php echo e($customer->financial_contact_name ?? '--'); ?></div>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-purple-600 uppercase mb-1">
                                                            E-mail</div>
                                                        <div class="text-base font-semibold text-purple-900 break-all">
                                                            <?php echo e($customer->financial_contact_email ?? '--'); ?></div>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-purple-600 uppercase mb-1">
                                                            Telefone</div>
                                                        <div class="text-base font-semibold text-purple-900">
                                                            <?php if($customer->financial_contact_ddd && $customer->financial_contact_num): ?>
                                                                (<?php echo e($customer->financial_contact_ddd); ?>)
                                                                <?php echo e($customer->financial_contact_num); ?>

                                                            <?php else: ?>
                                                                --
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            
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
                                                            <?php if($customer->url_site): ?>
                                                                <a href="<?php echo e($customer->url_site); ?>" target="_blank"
                                                                    class="hover:underline"><?php echo e($customer->url_site); ?></a>
                                                            <?php else: ?>
                                                                --
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-indigo-600 uppercase mb-1">
                                                            Instagram</div>
                                                        <div class="text-sm font-semibold text-indigo-900 break-all">
                                                            <?php if($customer->url_instagram): ?>
                                                                <a href="<?php echo e($customer->url_instagram); ?>"
                                                                    target="_blank"
                                                                    class="hover:underline"><?php echo e($customer->url_instagram); ?></a>
                                                            <?php else: ?>
                                                                --
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-indigo-600 uppercase mb-1">
                                                            Facebook</div>
                                                        <div class="text-sm font-semibold text-indigo-900 break-all">
                                                            <?php if($customer->url_facebook): ?>
                                                                <a href="<?php echo e($customer->url_facebook); ?>"
                                                                    target="_blank"
                                                                    class="hover:underline"><?php echo e($customer->url_facebook); ?></a>
                                                            <?php else: ?>
                                                                --
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-xs font-medium text-indigo-600 uppercase mb-1">
                                                            Gerar Fatura</div>
                                                        <div class="text-sm font-semibold text-indigo-900">
                                                            <?php if($customer->generate_invoice): ?>
                                                                <span class="text-green-600">Sim</span>
                                                            <?php else: ?>
                                                                <span class="text-gray-500">Não</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
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
                                    <?php endif; ?>
                                <?php else: ?>
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
                                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Criar Novo Cliente','icon' => 'plus'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'sm' => true,'wire:click' => 'openNewCustomerModal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div x-show="activeTab === 'modulos'">
                            
                            <div class="print-only border-b-2 border-gray-300 pb-2 mb-4">
                                <h2 style="font-size: 16px; font-weight: bold; color: #000;">MÓDULOS DO SISTEMA</h2>
                            </div>
                            <div class="space-y-4">
                                <h2 class="text-lg font-semibold text-gray-800">Módulos do Cliente</h2>

                                <?php if(!$customerId): ?>
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
                                <?php elseif(($allModules ?? collect())->count()): ?>
                                    <?php $blockedModules = ['workshops']; ?>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                                        <?php $__currentLoopData = $allModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $slug = $module->module_slug ?? $module->slug ?? null; ?>
                                            <?php if($slug && in_array($slug, $blockedModules)): ?>
                                                <?php continue; ?>
                                            <?php endif; ?>
                                            <div
                                                class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                                <div class="p-6">
                                                    <div class="flex items-start justify-between mb-4">
                                                        <div class="flex-1">
                                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                                <?php echo e($module->module_name); ?></h3>
                                                            <p
                                                                class="text-xs text-gray-500 uppercase tracking-wide mb-2">
                                                                <?php echo e($module->slug); ?></p>
                                                            <p class="text-sm text-gray-600 line-clamp-2">
                                                                <?php echo e($module->module_description); ?></p>
                                                            
                                                            <?php if(!$module->module_active): ?>
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
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="ml-4">
                                                            <?php if(in_array($module->id, $customerModuleIds)): ?>
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
                                                            <?php else: ?>
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
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4">
                                                        <?php if(in_array($module->id, $customerModuleIds)): ?>
                                                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'Remover Módulo','icon' => 'x'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['red' => true,'sm' => true,'wire:click' => 'toggleModule(\''.e($module->id).'\')','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                                        <?php elseif(!$module->module_active): ?>
                                                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'Módulo Desativado','icon' => 'exclamation-triangle'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['gray' => true,'sm' => true,'class' => 'w-full','disabled' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                                        <?php else: ?>
                                                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'Ativar Módulo','icon' => 'check'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['green' => true,'sm' => true,'wire:click' => 'toggleModule(\''.e($module->id).'\')','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
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
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div x-show="activeTab === 'usuarios'">
                            
                            <div class="print-only border-b-2 border-gray-300 pb-2 mb-4">
                                <h2 style="font-size: 16px; font-weight: bold; color: #000;">USUÁRIOS DO SISTEMA</h2>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-lg font-semibold text-gray-800">Usuários do Cliente
                                        (<?php echo e(($customerUsers ?? collect())->count()); ?>)</h2>
                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Novo Usuário','href' => ''.e(route('configuracoes-novo-usuario', ['customer_id' => $customerId])).'','icon' => 'plus'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'sm' => true,'as' => 'a','class' => 'px-4 py-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                </div>

                                <?php if(($customerUsers ?? collect())->count()): ?>
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
                                                    <?php $__currentLoopData = $customerUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr wire:key="customer-user-<?php echo e($user->id); ?>"
                                                            class="hover:bg-gray-50 transition-colors duration-150">
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex items-center">
                                                                    <div class="flex-shrink-0 h-10 w-10">
                                                                        <div
                                                                            class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center text-white font-semibold text-sm">
                                                                            <?php echo e(strtoupper(substr($user->name ?? 'U', 0, 1))); ?>

                                                                        </div>
                                                                    </div>
                                                                    <div class="ml-4">
                                                                        <div class="text-sm font-medium text-gray-900">
                                                                            <?php echo e($user->name); ?></div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="text-sm text-gray-600"><?php echo e($user->email); ?>

                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded-full
                                                                <?php echo e(($user->pivot->user_role ?? '') === 'admin'
                                                                    ? 'bg-red-100 text-red-800'
                                                                    : (($user->pivot->user_role ?? '') === 'owner'
                                                                        ? 'bg-blue-100 text-blue-800'
                                                                        : 'bg-gray-100 text-gray-800')); ?>">
                                                                    <?php echo e(strtoupper($user->pivot->user_role ?? 'user')); ?>

                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                <?php if(($user->pivot->can_events ?? 0) == 1): ?>
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
                                                                <?php else: ?>
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
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                <?php if(($user->pivot->can_campaigns ?? 0) == 1): ?>
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
                                                                <?php else: ?>
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
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                <?php if(($user->pivot->can_subscriptions ?? 0) == 1): ?>
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
                                                                <?php else: ?>
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
                                                                <?php endif; ?>
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <a href="<?php echo e(route('configuracoes-editar-usuario', ['customer_id' => $customerId, 'user_id' => $user->id])); ?>"
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
                                                        
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php else: ?>
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
                                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Criar Primeiro Usuário','href' => ''.e(route('configuracoes-novo-usuario', ['customer_id' => $customerId])).'','icon' => 'plus'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'sm' => true,'as' => 'a']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div x-show="activeTab === 'gateways'">
                            <div class="print-only border-b-2 border-gray-300 pb-2 mb-4">
                                <h2 style="font-size: 16px; font-weight: bold; color: #000;">GATEWAYS DE PAGAMENTO</h2>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-lg font-semibold text-gray-800">Gateways de Pagamento
                                        (<?php echo e(($customerGateways ?? collect())->count()); ?>)</h2>
                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Novo Gateway','icon' => 'plus'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'sm' => true,'wire:click' => 'openNewGatewayModal','class' => 'px-4 py-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                </div>

                                <?php if(($customerGateways ?? collect())->count() || ($customerGatewaysInactive ?? collect())->count()): ?>
                                    
                                    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden mb-6"
                                        x-data="{ expanded: false }">
                                        
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
                                                            <?php
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
                                                            ?>
                                                            <?php if($activeFiltersCount > 0): ?>
                                                                <span
                                                                    class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-gray-700 bg-gray-200 rounded">
                                                                    <?php echo e($activeFiltersCount); ?>

                                                                    ativo<?php echo e($activeFiltersCount > 1 ? 's' : ''); ?>

                                                                </span>
                                                            <?php endif; ?>
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <?php if($activeFiltersCount > 0): ?>
                                                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Limpar','icon' => 'x'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['xs' => true,'negative' => true,'wire:click.stop' => 'clearGatewayFilters']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                                    <?php endif; ?>
                                                    <svg class="w-5 h-5 text-gray-600 transition-transform"
                                                        :class="{ 'rotate-180': expanded }" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div x-show="expanded" x-cloak
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 -translate-y-1" class="bg-white">
                                            <div class="p-4 space-y-4">
                                                
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                                        Buscar
                                                    </label>
                                                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.500ms' => 'filterGatewaySearch','placeholder' => 'Nome, slug ou descrição...','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                </div>

                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                                    
                                                    <div class="border border-gray-200 rounded p-3 lg:col-span-2">
                                                        <h4 class="text-xs font-semibold text-gray-700 mb-2.5">
                                                            Parametrização
                                                        </h4>
                                                        <label
                                                            class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded w-fit">
                                                            <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'filterHideFees','id' => 'filter_hide_fees']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                                                            <span class="text-sm text-gray-700">Ocultar taxas</span>
                                                        </label>
                                                    </div>

                                                    
                                                    <div class="border border-gray-200 rounded p-3">
                                                        <h4 class="text-xs font-semibold text-gray-700 mb-2.5">
                                                            Métodos de Pagamento
                                                        </h4>
                                                        <div class="grid grid-cols-3 gap-2">
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'filterPayBoleto','id' => 'filter_boleto']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                                                                <span class="text-sm text-gray-700">Boleto</span>
                                                            </label>
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'filterPayPix','id' => 'filter_pix']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                                                                <span class="text-sm text-gray-700">PIX</span>
                                                            </label>
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'filterPaySlipPix','id' => 'filter_slip_pix']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                                                                <span class="text-sm text-gray-700">Slip PIX</span>
                                                            </label>
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'filterPayCardDebit','id' => 'filter_card_debit']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                                                                <span class="text-sm text-gray-700">Cartão
                                                                    Débito</span>
                                                            </label>
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'filterPayCardCredit','id' => 'filter_card_credit']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                                                                <span class="text-sm text-gray-700">Cartão
                                                                    Crédito</span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="border border-gray-200 rounded p-3">
                                                        <h4 class="text-xs font-semibold text-gray-700 mb-2.5">
                                                            Disponibilidade
                                                        </h4>
                                                        <div class="grid grid-cols-3 gap-2">
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'filterUseEvents','id' => 'filter_use_events']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                                                                <span class="text-sm text-gray-700">Eventos</span>
                                                            </label>
                                                            <label
                                                                class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded">
                                                                <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'filterUseCampaigns','id' => 'filter_use_campaigns']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                                                                <span class="text-sm text-gray-700">Campanhas</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <?php if($activeFiltersCount > 0): ?>
                                                    <div class="bg-gray-50 border border-gray-200 rounded p-3">
                                                        <div class="text-xs text-gray-600">
                                                            <span
                                                                class="font-semibold text-gray-800"><?php echo e(($customerGateways ?? collect())->count()); ?></span>
                                                            gateway(s) encontrado(s)
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <br>

                                    
                                    <?php if(($customerGateways ?? collect())->count()): ?>
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
                                                        <?php $__currentLoopData = $customerGateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                                                                title="<?php echo e($gateway->pay_gateway_slug); ?>">
                                                                                <?php echo e($gateway->pay_gateway_label); ?></div>
                                                                            <?php if($gateway->pay_gateway_description): ?>
                                                                                <div
                                                                                    class="text-xs text-gray-500 line-clamp-1">
                                                                                    <?php echo e($gateway->pay_gateway_description); ?>

                                                                                </div>
                                                                            <?php endif; ?>
                                                                            <?php if($hasCodSubcontaIdColumn): ?>
                                                                                <div
                                                                                    class="text-xs text-gray-400 mt-0.5">
                                                                                    <span
                                                                                        class="font-medium">CodSubconta:</span>
                                                                                    <?php echo e(!empty($gateway->cod_subconta_id) ? $gateway->cod_subconta_id : '----'); ?>

                                                                                </div>
                                                                            <?php endif; ?>
                                                                            <?php if(!empty($gateway->conta_cod) || !empty($gateway->conta_banco) || !empty($gateway->conta_numero)): ?>
                                                                                <div
                                                                                    class="text-xs text-gray-500 mt-1.5 space-y-0.5">
                                                                                    <div><span
                                                                                            class="font-medium">Conta
                                                                                            Cod:</span>
                                                                                        <?php echo e($gateway->conta_cod ?: '----'); ?>

                                                                                    </div>
                                                                                    <div><span
                                                                                            class="font-medium">Banco:</span>
                                                                                        <?php echo e($gateway->conta_banco ?: '----'); ?>

                                                                                        <?php if(!empty($gateway->conta_banco_descricao)): ?>
                                                                                            -
                                                                                            <?php echo e($gateway->conta_banco_descricao); ?>

                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    <div><span
                                                                                            class="font-medium">Tipo:</span>
                                                                                        <?php echo e($gateway->conta_tipo ?: '----'); ?>

                                                                                    </div>
                                                                                    <div><span
                                                                                            class="font-medium">Agência:</span>
                                                                                        <?php echo e($gateway->conta_agencia ?: '----'); ?>

                                                                                        <?php if(!empty($gateway->conta_agencia_dv)): ?>
                                                                                            -<?php echo e($gateway->conta_agencia_dv); ?>

                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    <div><span
                                                                                            class="font-medium">Conta:</span>
                                                                                        <?php echo e($gateway->conta_numero ?: '----'); ?>

                                                                                        <?php if(!empty($gateway->conta_numero_dv)): ?>
                                                                                            -<?php echo e($gateway->conta_numero_dv); ?>

                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <div class="flex flex-wrap gap-1">
                                                                        <?php if($gateway->pay_boleto): ?>
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded">Boleto</span>
                                                                        <?php endif; ?>
                                                                        <?php if($gateway->pay_pix): ?>
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">PIX</span>
                                                                        <?php endif; ?>
                                                                        <?php if($gateway->pay_slip_pix): ?>
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-purple-100 text-purple-800 rounded">Slip
                                                                                PIX</span>
                                                                        <?php endif; ?>
                                                                        <?php if($gateway->pay_card_debit): ?>
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">Débito</span>
                                                                        <?php endif; ?>
                                                                        <?php if($gateway->pay_card_credit): ?>
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-orange-100 text-orange-800 rounded">Crédito</span>
                                                                        <?php endif; ?>
                                                                        <?php if(
                                                                            !$gateway->pay_boleto &&
                                                                                !$gateway->pay_pix &&
                                                                                !$gateway->pay_slip_pix &&
                                                                                !$gateway->pay_card_debit &&
                                                                                !$gateway->pay_card_credit): ?>
                                                                            <span
                                                                                class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">Nenhum</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <div class="flex flex-col gap-1">
                                                                        <?php if($gateway->use_events ?? 1): ?>
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
                                                                        <?php else: ?>
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
                                                                        <?php endif; ?>
                                                                        <?php if($gateway->use_campaigns ?? 1): ?>
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
                                                                        <?php else: ?>
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
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </td>
                                                                <td
                                                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Editar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'wire:click' => 'openEditGatewayModal(\''.e($gateway->id).'\')','class' => 'text-sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            
                                                            <?php if (! ($filterHideFees)): ?>
                                                                <tr class="bg-gray-50/50 border-t border-gray-200">
                                                                    <td colspan="4" class="px-6 py-3">
                                                                        <?php
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
                                                                        ?>
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
                                                                                        <?php $__currentLoopData = $allParcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <th
                                                                                                class="px-2 py-2 text-xs font-semibold text-gray-700 text-center border-l border-gray-200">
                                                                                                <?php echo e($parcela); ?>x</th>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                                                                            <?php echo $formatCents($gateway->fee_boleto_fixed_amount); ?></td>
                                                                                        <?php $__currentLoopData = $allParcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <td
                                                                                                class="px-2 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                                <?php echo $formatPercent($boletoFees, $parcela); ?></td>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        <td
                                                                                            class="px-3 py-2 text-center border-l border-gray-200">
                                                                                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'calculator'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'xs' => true,'wire:click' => 'openBoletoFeesModalForGateway(\''.e($gateway->id).'\')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
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
                                                                                            <?php echo $formatCents($gateway->fee_pix_fixed_amount); ?></td>
                                                                                        <?php $__currentLoopData = $allParcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <td
                                                                                                class="px-2 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                                <?php echo $formatPercent($pixFees, $parcela); ?></td>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        <td
                                                                                            class="px-3 py-2 text-center border-l border-gray-200">
                                                                                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'calculator'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'xs' => true,'wire:click' => 'openPixFeesModalForGateway(\''.e($gateway->id).'\')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
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
                                                                                            <?php echo $formatCents($gateway->fee_slip_pix_fixed_amount); ?></td>
                                                                                        <?php $__currentLoopData = $allParcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <td
                                                                                                class="px-2 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                                <?php echo $formatPercent($slipPixFees, $parcela); ?></td>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        <td
                                                                                            class="px-3 py-2 text-center border-l border-gray-200">
                                                                                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'calculator'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'xs' => true,'wire:click' => 'openSlipPixFeesModalForGateway(\''.e($gateway->id).'\')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
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
                                                                                            <?php echo $formatCents($gateway->fee_credit_fixed_amount); ?></td>
                                                                                        <?php $__currentLoopData = $allParcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <td
                                                                                                class="px-2 py-2 text-xs text-gray-900 text-center border-l border-gray-200">
                                                                                                <?php echo $formatPercent($creditFees, $parcela); ?></td>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        <td
                                                                                            class="px-3 py-2 text-center border-l border-gray-200">
                                                                                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'calculator'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'xs' => true,'wire:click' => 'openInstallmentFeesModalForGateway(\''.e($gateway->id).'\')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    
                                    <?php if(($customerGatewaysInactive ?? collect())->count()): ?>
                                        <div class="mt-8">
                                            
                                            <div
                                                class="bg-gradient-to-br from-red-50 via-orange-50 to-amber-50 border-2 border-red-300 rounded-2xl shadow-lg overflow-hidden">
                                                
                                                <div
                                                    class="bg-gradient-to-r from-red-100 to-orange-100 border-b-2 border-red-300 px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        
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
                                                        
                                                        <div class="flex-1">
                                                            <h3
                                                                class="text-xl font-bold text-red-800 uppercase tracking-wide flex items-center gap-2">
                                                                <span>Gateways Desativados</span>
                                                                <span
                                                                    class="inline-flex items-center justify-center px-3 py-1 text-sm font-bold leading-none text-red-100 bg-red-600 rounded-full shadow-md">
                                                                    <?php echo e(($customerGatewaysInactive ?? collect())->count()); ?>

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
                                                                <?php $__currentLoopData = $customerGatewaysInactive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                                                                        title="<?php echo e($gateway->pay_gateway_slug); ?>">
                                                                                        <?php echo e($gateway->pay_gateway_label); ?>

                                                                                        <span
                                                                                            class="inline-flex items-center px-2 py-0.5 text-xs font-bold text-red-700 bg-red-100 rounded-full border border-red-300">
                                                                                            INATIVO
                                                                                        </span>
                                                                                    </div>
                                                                                    <?php if($gateway->pay_gateway_description): ?>
                                                                                        <div
                                                                                            class="text-xs text-gray-600 line-clamp-1 mt-0.5">
                                                                                            <?php echo e($gateway->pay_gateway_description); ?>

                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                    <?php if($hasCodSubcontaIdColumn): ?>
                                                                                        <div
                                                                                            class="text-xs text-gray-400 mt-0.5">
                                                                                            <span
                                                                                                class="font-medium">CodSubconta:</span>
                                                                                            <?php echo e(!empty($gateway->cod_subconta_id) ? $gateway->cod_subconta_id : '----'); ?>

                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                    <?php if(!empty($gateway->conta_cod) || !empty($gateway->conta_banco) || !empty($gateway->conta_numero)): ?>
                                                                                        <div
                                                                                            class="text-xs text-gray-500 mt-1.5 space-y-0.5">
                                                                                            <div><span
                                                                                                    class="font-medium">Conta
                                                                                                    Cod:</span>
                                                                                                <?php echo e($gateway->conta_cod ?: '----'); ?>

                                                                                            </div>
                                                                                            <div><span
                                                                                                    class="font-medium">Banco:</span>
                                                                                                <?php echo e($gateway->conta_banco ?: '----'); ?>

                                                                                                <?php if(!empty($gateway->conta_banco_descricao)): ?>
                                                                                                    -
                                                                                                    <?php echo e($gateway->conta_banco_descricao); ?>

                                                                                                <?php endif; ?>
                                                                                            </div>
                                                                                            <div><span
                                                                                                    class="font-medium">Tipo:</span>
                                                                                                <?php echo e($gateway->conta_tipo ?: '----'); ?>

                                                                                            </div>
                                                                                            <div><span
                                                                                                    class="font-medium">Agência:</span>
                                                                                                <?php echo e($gateway->conta_agencia ?: '----'); ?>

                                                                                                <?php if(!empty($gateway->conta_agencia_dv)): ?>
                                                                                                    -<?php echo e($gateway->conta_agencia_dv); ?>

                                                                                                <?php endif; ?>
                                                                                            </div>
                                                                                            <div><span
                                                                                                    class="font-medium">Conta:</span>
                                                                                                <?php echo e($gateway->conta_numero ?: '----'); ?>

                                                                                                <?php if(!empty($gateway->conta_numero_dv)): ?>
                                                                                                    -<?php echo e($gateway->conta_numero_dv); ?>

                                                                                                <?php endif; ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-6 py-4">
                                                                            <div class="flex flex-wrap gap-1">
                                                                                <?php if($gateway->pay_boleto): ?>
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded border border-blue-300 font-medium">Boleto</span>
                                                                                <?php endif; ?>
                                                                                <?php if($gateway->pay_pix): ?>
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded border border-green-300 font-medium">PIX</span>
                                                                                <?php endif; ?>
                                                                                <?php if($gateway->pay_slip_pix): ?>
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-purple-100 text-purple-700 rounded border border-purple-300 font-medium">Slip
                                                                                        PIX</span>
                                                                                <?php endif; ?>
                                                                                <?php if($gateway->pay_card_debit): ?>
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded border border-yellow-300 font-medium">Débito</span>
                                                                                <?php endif; ?>
                                                                                <?php if($gateway->pay_card_credit): ?>
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-orange-100 text-orange-700 rounded border border-orange-300 font-medium">Crédito</span>
                                                                                <?php endif; ?>
                                                                                <?php if(
                                                                                    !$gateway->pay_boleto &&
                                                                                        !$gateway->pay_pix &&
                                                                                        !$gateway->pay_slip_pix &&
                                                                                        !$gateway->pay_card_debit &&
                                                                                        !$gateway->pay_card_credit): ?>
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded border border-gray-300">Nenhum</span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-6 py-4">
                                                                            <div class="flex flex-col gap-1">
                                                                                <?php if($gateway->use_events ?? 1): ?>
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
                                                                                <?php else: ?>
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
                                                                                <?php endif; ?>
                                                                                <?php if($gateway->use_campaigns ?? 1): ?>
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
                                                                                <?php else: ?>
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
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </td>
                                                                        <td
                                                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Editar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['warning' => true,'wire:click' => 'openEditGatewayModal(\''.e($gateway->id).'\')','class' => 'text-sm font-semibold']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <?php if (! ($filterHideFees)): ?>
                                                                        <tr class="bg-red-50/30 border-t-2 border-red-200">
                                                                            <td colspan="4" class="px-6 py-3">
                                                                                <?php
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
                                                                                ?>
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
                                                                                                <?php $__currentLoopData = $allParcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <th
                                                                                                        class="px-2 py-2 text-xs font-semibold text-red-800 text-center border-l border-red-200">
                                                                                                        <?php echo e($parcela); ?>x
                                                                                                    </th>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                                                                                    <?php echo $formatCents($gateway->fee_boleto_fixed_amount); ?>

                                                                                                </td>
                                                                                                <?php $__currentLoopData = $allParcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <td
                                                                                                        class="px-2 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                        <?php echo $formatPercent($boletoFees, $parcela); ?>

                                                                                                    </td>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                <td
                                                                                                    class="px-3 py-2 text-center border-l border-red-200">
                                                                                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'calculator'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['warning' => true,'xs' => true,'wire:click' => 'openBoletoFeesModalForGateway(\''.e($gateway->id).'\')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
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
                                                                                                    <?php echo $formatCents($gateway->fee_pix_fixed_amount); ?>

                                                                                                </td>
                                                                                                <?php $__currentLoopData = $allParcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <td
                                                                                                        class="px-2 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                        <?php echo $formatPercent($pixFees, $parcela); ?>

                                                                                                    </td>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                <td
                                                                                                    class="px-3 py-2 text-center border-l border-red-200">
                                                                                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'calculator'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['warning' => true,'xs' => true,'wire:click' => 'openPixFeesModalForGateway(\''.e($gateway->id).'\')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
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
                                                                                                    <?php echo $formatCents($gateway->fee_slip_pix_fixed_amount); ?>

                                                                                                </td>
                                                                                                <?php $__currentLoopData = $allParcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <td
                                                                                                        class="px-2 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                        <?php echo $formatPercent($slipPixFees, $parcela); ?>

                                                                                                    </td>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                <td
                                                                                                    class="px-3 py-2 text-center border-l border-red-200">
                                                                                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'calculator'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['warning' => true,'xs' => true,'wire:click' => 'openSlipPixFeesModalForGateway(\''.e($gateway->id).'\')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
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
                                                                                                    <?php echo $formatCents($gateway->fee_credit_fixed_amount); ?>

                                                                                                </td>
                                                                                                <?php $__currentLoopData = $allParcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <td
                                                                                                        class="px-2 py-2 text-xs text-gray-900 text-center border-l border-red-200">
                                                                                                        <?php echo $formatPercent($creditFees, $parcela); ?>

                                                                                                    </td>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                <td
                                                                                                    class="px-3 py-2 text-center border-l border-red-200">
                                                                                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'calculator'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['warning' => true,'xs' => true,'wire:click' => 'openInstallmentFeesModalForGateway(\''.e($gateway->id).'\')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
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
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => 'Editar Usuário','maxWidth' => '2xl'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'showEditModal']); ?>
        <div wire:key="edit-user-modal-<?php echo e($selectedUserId ?? 'none'); ?>" class="space-y-6 px-6 pb-4">
            <div class="space-y-4">
                <div>
                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Nome'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'editName','placeholder' => 'Nome completo','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                    <?php $__errorArgs = ['editName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'E-mail'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'email','wire:model.lazy' => 'editEmail','placeholder' => 'email@exemplo.com','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                    <?php $__errorArgs = ['editEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-base font-light uppercase text-black dark:text-gray-400">
                        Papel <span class="text-red-500">*</span>
                    </label>
                    <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'editUserRole','class' => 'w-full']); ?>
                        <option value="" <?php if(!$editUserRole): echo 'selected'; endif; ?>>Selecione...</option>
                        <option value="user" <?php if($editUserRole === 'user'): echo 'selected'; endif; ?>>Usuário da Organização</option>
                        <option value="owner" <?php if($editUserRole === 'owner'): echo 'selected'; endif; ?>>Proprietário da Organização</option>
                        <option value="admin" <?php if($editUserRole === 'admin'): echo 'selected'; endif; ?>>Administrador do Sistema</option>
                        <?php if($editUserRole === 'super-admin'): ?>
                            <option value="super-admin" selected>Super Administrador</option>
                        <?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $attributes = $__attributesOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__attributesOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $component = $__componentOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__componentOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
                    <?php $__errorArgs = ['editUserRole'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Permissões</label>
                    <div class="flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve(['label' => 'Pode acessar Eventos'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'editCanEvents']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve(['label' => 'Pode acessar Campanhas'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'editCanCampaigns']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve(['label' => 'Pode acessar Assinaturas'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'editCanSubscriptions']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="space-y-4 border-t pt-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Alterar Senha</h3>
                    <button type="button" wire:click="toggleUserPasswordSection"
                        class="text-sm text-indigo-600 hover:text-indigo-800">
                        <?php echo e($showPasswordSection ? 'Ocultar' : 'Alterar Senha'); ?>

                    </button>
                </div>

                <?php if($showPasswordSection): ?>
                    <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Nova Senha'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'password','wire:model.defer' => 'newPassword','placeholder' => 'Mínimo 8 caracteres','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                            <?php $__errorArgs = ['newPassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Confirmar Nova Senha'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'password','wire:model.defer' => 'newPasswordConfirmation','placeholder' => 'Confirme a nova senha','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                            <?php $__errorArgs = ['newPasswordConfirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Salvar Senha','spinner' => 'updateUserPassword'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'xs' => true,'wire:click' => 'updateUserPassword']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="space-y-4 border-t pt-4">
                <h3 class="text-sm font-semibold text-red-700 uppercase tracking-wide">Zona de Perigo</h3>

                <?php if(!$showDeleteConfirmation): ?>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-xs text-red-800 mb-3">Ao remover o usuário, ele perderá acesso a este
                            cliente/filial/setor. Esta ação não pode ser desfeita.</p>
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'Remover Usuário'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['red' => true,'xs' => true,'wire:click' => 'startUserDeleteConfirmation']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-red-50 border-2 border-red-300 rounded-lg p-4">
                        <p class="text-xs font-semibold text-red-900 mb-2">Tem certeza que deseja remover este usuário?
                        </p>
                        <p class="text-xs text-red-800 mb-4">O usuário <strong><?php echo e($editName ?? ''); ?></strong> será
                            removido do cliente atual.</p>
                        <div class="flex gap-2">
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Confirmar Remoção','spinner' => 'removeUser'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['red' => true,'xs' => true,'wire:click' => 'removeUser']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Cancelar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['xs' => true,'wire:click' => 'cancelUserDeleteConfirmation']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

         <?php $__env->slot('footer', null, []); ?> 
            <div class="flex justify-end gap-2">
                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Fechar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'closeEditModal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Salvar Alterações','spinner' => 'updateUser'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'wire:click' => 'updateUser']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
            </div>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => 'Novo Usuário','maxWidth' => '2xl'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'showNewUserModal']); ?>
        <div wire:key="new-user-modal-<?php echo e($showNewUserModal ? 'open' : 'closed'); ?>" class="space-y-6 px-6 pb-4">
            
            <?php if(session('error')): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-red-800 font-medium text-sm"><?php echo e(session('error')); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="space-y-4">
                <div>
                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Nome'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'newUserName','placeholder' => 'Nome completo','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                </div>

                <div>
                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'E-mail'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'email','wire:model.defer' => 'newUserEmail','placeholder' => 'email@exemplo.com','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                </div>

                <div>
                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Senha'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'password','wire:model.defer' => 'newUserPassword','placeholder' => 'Digite a senha (mínimo 8 caracteres)','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                </div>

                <div>
                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Confirmar Senha'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'password','wire:model.defer' => 'newUserPasswordConfirmation','placeholder' => 'Confirme a senha','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                </div>

                <div>
                    <label class="block text-base font-light uppercase text-black dark:text-gray-400">
                        Papel <span class="text-red-500">*</span>
                    </label>
                    <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'newUserRole','class' => 'w-full']); ?>
                        <option value="user">Usuário da Organização</option>
                        <option value="owner">Proprietário da Organização</option>
                        
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $attributes = $__attributesOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__attributesOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $component = $__componentOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__componentOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Permissões
                    </label>
                    <div class="flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve(['label' => 'Pode acessar Eventos'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'newUserCanEvents']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve(['label' => 'Pode acessar Campanhas'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'newUserCanCampaigns']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530 = $attributes; } ?>
<?php $component = WireUi\View\Components\Checkbox::resolve(['label' => 'Pode acessar Assinaturas'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Checkbox::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'newUserCanSubscriptions']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $attributes = $__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__attributesOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530)): ?>
<?php $component = $__componentOriginal82b947c12c0b8a4cfc71f282aadb8530; ?>
<?php unset($__componentOriginal82b947c12c0b8a4cfc71f282aadb8530); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

         <?php $__env->slot('footer', null, []); ?> 
            <div class="flex justify-end gap-2">
                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Cancelar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'closeNewUserModal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Criar Usuário','spinner' => 'createUser'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'wire:click' => 'createUser']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
            </div>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>

    
    
    
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
        banks: <?php echo \Illuminate\Support\Js::from($availableBanks ?? [])->toHtml() ?>,
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

                
                <div class="space-y-6 px-6 py-6 max-h-[75vh] overflow-y-auto">
                    <div class="space-y-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gateway</label>
                            <select x-model="gatewayId" :disabled="editing"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm disabled:bg-gray-100"
                                x-on:change="if(!editing){ $wire.set('gatewayPayGatewayId', gatewayId); $wire.call('onGatewaySelected'); }">
                                <option value="">Selecione um gateway...</option>
                                <?php $__empty_1 = true; $__currentLoopData = $availableGateways ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appGateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($appGateway->id); ?>"><?php echo e($appGateway->gateway_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <option value="" disabled>Nenhum gateway disponível no sistema</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rótulo (Label)</label>
                            <input type="text" x-model="label" placeholder="Ex: PagSeguro Principal"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                        </div>

                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                            <textarea x-model="description" rows="2" placeholder="Descrição do gateway"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>

                        
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
                                        <?php $__currentLoopData = $availableBanks ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($bank->ref_banco); ?>">
                                                <?php echo e($bank->ref_cod); ?> - <?php echo e($bank->ref_banco); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                
                <div class="px-6 py-4 border-t">
                    
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

    
    <div>
        <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => 'Gerenciar Taxas de Parcelamento','maxWidth' => '4xl'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'showInstallmentFeesModal','wire:key' => 'installment-fees-modal-'.e($selectedGatewayForInstallmentFees->id ?? 'none').'']); ?>
            <div class="space-y-6 px-6 pb-6">
                <?php if($selectedGatewayForInstallmentFees): ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-blue-900">Gateway: <span
                                class="font-semibold"><?php echo e($selectedGatewayForInstallmentFees->pay_gateway_label); ?></span>
                        </p>
                        <p class="text-xs text-blue-700 mt-1">Campo: <span
                                class="font-mono">pay_gateway_installment_fees_json</span> - Configure as taxas por
                            número de parcelas para cartão de crédito</p>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">JSON de Taxas de Parcelamento</label>
                    <textarea wire:model.defer="gatewayInstallmentFeesJson" rows="10"
                        class="w-full font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder='{"1":"2.20","2":"2.70","3":"2.70","4":"2.70","5":"2.70","6":"2.70","7":"3.20","8":"3.20","9":"3.20","10":"3.20","11":"3.20","12":"3.20"}'></textarea>
                    <p class="text-xs text-gray-500 mt-2">Formato JSON: objeto onde a chave é o número de parcelas e o
                        valor é a taxa percentual. Ex: {"1":"2.20","2":"2.70"}</p>
                </div>
            </div>

             <?php $__env->slot('footer', null, []); ?> 
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
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
    </div>

    
    <div>
        <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => 'Gerenciar Taxas do Boleto','maxWidth' => '4xl'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'showBoletoFeesModal','wire:key' => 'boleto-fees-modal-'.e($selectedGatewayForBoletoFees->id ?? 'none').'']); ?>
            <div class="space-y-6 px-6 pb-6">
                <?php if($selectedGatewayForBoletoFees): ?>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-amber-900">Gateway: <span
                                class="font-semibold"><?php echo e($selectedGatewayForBoletoFees->pay_gateway_label); ?></span>
                        </p>
                        <p class="text-xs text-amber-700 mt-1">Campo: <span
                                class="font-mono">pay_boleto_fees_json</span> - Configure as taxas por número de
                            parcelas para boleto</p>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">JSON de Taxas do Boleto</label>
                    <textarea wire:model.defer="gatewayBoletoFeesJson" rows="10"
                        class="w-full font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder='{"1":"2.20","2":"2.70","3":"2.70","4":"2.70","5":"2.70","6":"2.70","7":"3.20","8":"3.20","9":"3.20","10":"3.20","11":"3.20","12":"3.20"}'></textarea>
                    <p class="text-xs text-gray-500 mt-2">Formato JSON: objeto onde a chave é o número de parcelas e o
                        valor é a taxa percentual. Ex: {"1":"2.20","2":"2.70"}</p>
                </div>
            </div>

             <?php $__env->slot('footer', null, []); ?> 
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
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
    </div>

    
    <div>
        <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => 'Gerenciar Taxas do PIX','maxWidth' => '4xl'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'showPixFeesModal','wire:key' => 'pix-fees-modal-'.e($selectedGatewayForPixFees->id ?? 'none').'']); ?>
            <div class="space-y-6 px-6 pb-6">
                <?php if($selectedGatewayForPixFees): ?>
                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-cyan-900">Gateway: <span
                                class="font-semibold"><?php echo e($selectedGatewayForPixFees->pay_gateway_label); ?></span>
                        </p>
                        <p class="text-xs text-cyan-700 mt-1">Campo: <span
                                class="font-mono">pay_pix_fees_json</span> - Configure as taxas por número de
                            parcelas para PIX</p>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">JSON de Taxas do PIX</label>
                    <textarea wire:model.defer="gatewayPixFeesJson" rows="10"
                        class="w-full font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder='{"1":"2.20","2":"2.70","3":"2.70","4":"2.70","5":"2.70","6":"2.70","7":"3.20","8":"3.20","9":"3.20","10":"3.20","11":"3.20","12":"3.20"}'></textarea>
                    <p class="text-xs text-gray-500 mt-2">Formato JSON: objeto onde a chave é o número de parcelas e o
                        valor é a taxa percentual. Ex: {"1":"2.20","2":"2.70"}</p>
                </div>
            </div>

             <?php $__env->slot('footer', null, []); ?> 
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
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
    </div>

    
    <div>
        <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => 'Gerenciar Taxas do Slip PIX','maxWidth' => '4xl'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'showSlipPixFeesModal','wire:key' => 'slippix-fees-modal-'.e($selectedGatewayForSlipPixFees->id ?? 'none').'']); ?>
            <div class="space-y-6 px-6 pb-6">
                <?php if($selectedGatewayForSlipPixFees): ?>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-purple-900">Gateway: <span
                                class="font-semibold"><?php echo e($selectedGatewayForSlipPixFees->pay_gateway_label); ?></span>
                        </p>
                        <p class="text-xs text-purple-700 mt-1">Campo: <span
                                class="font-mono">pay_slip_pix_fees_json</span> - Configure as taxas por número de
                            parcelas para Slip PIX</p>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">JSON de Taxas do Slip PIX</label>
                    <textarea wire:model.defer="gatewaySlipPixFeesJson" rows="10"
                        class="w-full font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder='{"1":"2.20","2":"2.70","3":"2.70","4":"2.70","5":"2.70","6":"2.70","7":"3.20","8":"3.20","9":"3.20","10":"3.20","11":"3.20","12":"3.20"}'></textarea>
                    <p class="text-xs text-gray-500 mt-2">Formato JSON: objeto onde a chave é o número de parcelas e o
                        valor é a taxa percentual. Ex: {"1":"2.20","2":"2.70"}</p>
                </div>
            </div>

             <?php $__env->slot('footer', null, []); ?> 
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
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
    </div>

    
    <?php if(!$standaloneCreate): ?>
        <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => $isEditingCustomer ? 'Editar Cliente' : 'Novo Cliente','maxWidth' => '4xl'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'showCustomerModal']); ?>
            <div class="space-y-6 px-6 pb-6">
                <?php if($errors->any()): ?>
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Dados Principais</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Razão Social / Nome do Cliente'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerNameCorporate','placeholder' => 'Razão social da empresa','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Nome Fantasia / Nome Comercial'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.blur' => 'customerNameFantasy','placeholder' => 'Nome fantasia','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Nome Curto'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerNameShort','placeholder' => 'Nome curto/abreviação','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div class="md:col-span-1">
                            <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['label' => 'Tipo de Documento'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'customerDocType','class' => 'w-full']); ?>
                                <option value="cpf">CPF</option>
                                <option value="cnpj">CNPJ</option>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $attributes = $__attributesOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__attributesOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $component = $__componentOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__componentOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
                        </div>
                        <div class="md:col-span-1">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Número do Documento'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.500ms' => 'customerDocNum','placeholder' => 'Digite o documento','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div class="md:col-span-2">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Slug do Cliente'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerSlug','placeholder' => 'slug-do-cliente','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">Usado na URL ex:
                                www.proeventpay.com/<?php echo e($customerSlug ?: '{slug}'); ?></p>
                        </div>
                    </div>
                </div>

                
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Contato Comercial</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Nome'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerComercialContactName','placeholder' => 'Nome do contato comercial','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'E-mail'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'email','wire:model.defer' => 'customerComercialContactEmail','placeholder' => 'email@exemplo.com','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div class="md:col-span-1">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'DDD'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerComercialContactDdd','placeholder' => '21','class' => 'w-full','maxlength' => '2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div class="md:col-span-1">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Telefone'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerComercialContactNum','placeholder' => '987654321','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Contato Financeiro</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Nome'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerFinancialContactName','placeholder' => 'Nome do contato financeiro','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'E-mail'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'email','wire:model.defer' => 'customerFinancialContactEmail','placeholder' => 'email@exemplo.com','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div class="md:col-span-1">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'DDD'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerFinancialContactDdd','placeholder' => '21','class' => 'w-full','maxlength' => '2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div class="md:col-span-1">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Telefone'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerFinancialContactNum','placeholder' => '987654321','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Endereço</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Logradouro'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerAddress','placeholder' => 'Rua, Avenida, etc','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Número'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerAddressNumber','placeholder' => '123','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Complemento'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerAddressComplement','placeholder' => 'Apto, Sala, etc','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Bairro'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerCityNeighborhood','placeholder' => 'Nome do bairro','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Cidade'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerCity','placeholder' => 'Nome da cidade','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Estado (UF)'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerState','placeholder' => 'RJ','class' => 'w-full','maxlength' => '2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'CEP'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerZipCode','placeholder' => '20000-000','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Links e Configurações</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Site'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerUrlSite','placeholder' => 'https://www.exemplo.com.br','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Instagram'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerUrlInstagram','placeholder' => 'https://instagram.com/exemplo','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Facebook'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerUrlFacebook','placeholder' => 'https://facebook.com/exemplo','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Gerar Fatura</p>
                                <p class="text-xs text-gray-500 mt-1">Habilitar geração automática de notas fiscais
                                </p>
                            </div>
                            <?php if (isset($component)) { $__componentOriginale45caf11f55ea97b78a13a84cea67cba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale45caf11f55ea97b78a13a84cea67cba = $attributes; } ?>
<?php $component = WireUi\View\Components\Toggle::resolve(['lg' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Toggle::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'customerGenerateInvoice','color' => 'green']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale45caf11f55ea97b78a13a84cea67cba)): ?>
<?php $attributes = $__attributesOriginale45caf11f55ea97b78a13a84cea67cba; ?>
<?php unset($__attributesOriginale45caf11f55ea97b78a13a84cea67cba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale45caf11f55ea97b78a13a84cea67cba)): ?>
<?php $component = $__componentOriginale45caf11f55ea97b78a13a84cea67cba; ?>
<?php unset($__componentOriginale45caf11f55ea97b78a13a84cea67cba); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>

                 <?php $__env->slot('footer', null, []); ?> 
                    <?php if($errors->any()): ?>
                        <div class="w-full rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-700 mb-3">
                            <ul class="list-disc list-inside space-y-1">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if($isEditingCustomer): ?>
                        <div class="flex items-center justify-between gap-3 w-full">
                            <?php if($confirmingCustomerDeletion): ?>
                                <div class="text-xs text-red-700 bg-red-50 border border-red-200 px-3 py-2 rounded">
                                    Confirmar exclusão do cliente e todos os vínculos? Não pode haver eventos ou
                                    campanhas.
                                </div>
                                <div class="flex gap-2">
                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Cancelar exclusão'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => '$set(\'confirmingCustomerDeletion\', false)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Confirmar exclusão'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['negative' => true,'wire:click' => 'confirmDeleteCustomer(true)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                </div>
                            <?php else: ?>
                                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Remover Cliente'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['negative' => true,'wire:click' => 'confirmDeleteCustomer']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                <div class="flex gap-2">
                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Cancelar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'closeCustomerModal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => ''.e($isEditingCustomer ? 'Salvar Alterações' : 'Criar Cliente').'','spinner' => 'saveCustomer'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'wire:click' => 'saveCustomer']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="flex justify-end gap-2">
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Cancelar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'closeCustomerModal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => ''.e($isEditingCustomer ? 'Salvar Alterações' : 'Criar Cliente').'','spinner' => 'saveCustomer'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'wire:click' => 'saveCustomer']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                        </div>
                    <?php endif; ?>
                 <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
    <?php endif; ?>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/modules/module-configuracoes.blade.php ENDPATH**/ ?>