<div class="w-full max-w-7xl mx-auto mb-10">

    <div class="mb-6">
        <?php if (isset($component)) { $__componentOriginal522a59481d8bfd4d44478643bc3270fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal522a59481d8bfd4d44478643bc3270fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.validation-errors','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $attributes = $__attributesOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $component = $__componentOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__componentOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
    </div>

    <?php if($order ?? false && $target ?? false): ?>

        
        <div class="mb-6 bg-gradient-to-r from-red-500 via-rose-500 to-pink-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-cancelar" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-cancelar)"/>
                </svg>
            </div>
            <div class="relative z-10 p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Cancelamento da Compra</h1>
                                <p class="text-white/90 text-sm"><?php echo e($target->event_name); ?> - <?php echo e($order->order_control ?? '--'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'x','label' => 'FECHAR'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'wire:click' => '$set(\'cancelarOrder\', false)','class' => 'hover:bg-white/20']); ?>
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
        </div>

        
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Informações da Compra</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">Localizador</div>
                        <div class="text-xl font-bold text-gray-900"><?php echo e($order->order_control ?? '--'); ?></div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">Data da Compra</div>
                        <div class="text-lg text-gray-900"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-gray-500 mb-2">Status</div>
                        <?php
                            $statusClass = in_array($order->status ?? '--', listOrderStatusPaid())
                                ? 'bg-green-100 text-green-800'
                                : 'bg-yellow-100 text-yellow-800';
                        ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($statusClass); ?>">
                            <?php echo e(__($order->status ?? '--')); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Dados do Comprador</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">Nome</div>
                        <div class="text-base font-semibold text-gray-900 uppercase"><?php echo e($order->buyer_name ?? '--'); ?></div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">Email</div>
                        <div class="text-base text-gray-900 lowercase"><?php echo e($order->buyer_email ?? '--'); ?></div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">Nascimento</div>
                        <div class="text-base text-gray-900">
                            <?php if($order->buyer_birth_date ?? false): ?>
                                <?php echo e($order->buyer_birth_date->format('d/m/Y')); ?> (<?php echo e($order->buyer_birth_date->age); ?> anos)
                            <?php else: ?>
                                --
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">Documento</div>
                        <div class="text-base text-gray-900">
                            <span class="uppercase"><?php echo e($order->buyer_doc_type ?? null); ?>:</span>
                            <span><?php echo e(putMask($order->buyer_doc_num ?? '--', $order->buyer_doc_type ?? null)); ?></span>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">Contato</div>
                        <div class="text-base text-gray-900">(<?php echo e($order->buyer_contact_ddd ?? '--'); ?>) <?php echo e($order->buyer_contact_num ?? '--'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    <?php if(($order->itens ?? false) && $order->itens->count() > 1): ?>
                        Itens Comprados
                    <?php else: ?>
                        Item Comprado
                    <?php endif; ?>
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $order->itens ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderKey => $orderItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <div class="text-base font-medium text-gray-600"><?php echo e($target->event_tickets_nomenclature ?? 'INGRESSO'); ?></div>
                                    <div class="text-lg font-semibold text-gray-900 uppercase"><?php echo e($orderItem->item_description ?? 'ND'); ?></div>
                                    <div class="text-sm text-gray-600 mt-1 uppercase">- <?php echo e($orderItem->user_name ?? 'PARTICIPANTE #' . ($orderKey + 1)); ?></div>
                                </div>
                                <div class="text-xl font-bold text-gray-900"><?php echo e(toMoney($orderItem->item_amount ?? 0)); ?></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8 text-gray-500">Não possui itens</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Cancelamento</h2>
            </div>
            <div class="p-6">
                <?php if(in_array($order->status,['canceled'])): ?>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <div class="text-lg font-bold text-red-800">COMPRA JÁ CANCELADA</div>
                                <?php if($order->order_cancel_datetime ?? false): ?>
                                    <div class="text-sm text-red-600 mt-1">Cancelada em: <?php echo e($order->order_cancel_datetime->format('d/m/Y H:i')); ?></div>
                                <?php endif; ?>
                                <?php if($order->order_cancel_description ?? false): ?>
                                    <div class="text-sm text-red-700 mt-2"><?php echo e($order->order_cancel_description); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <div class="mb-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span class="text-sm font-medium text-yellow-800">Atenção: Esta ação é irreversível!</span>
                            </div>
                            <p class="text-sm text-yellow-700">Ao cancelar esta compra, todos os ingressos serão cancelados e não poderão ser utilizados.</p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => '* Motivo do Cancelamento'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Descreva o motivo do cancelamento','wire:model.defer' => 'order_cancel_description']); ?>
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
                            <div class="flex justify-end">
                                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Confirmar Cancelamento','rightIcon' => 'ban','spinner' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['negative' => true,'onclick' => 'confirm(\'Confirma o cancelamento da compra? Será irreversível!\') || event.stopImmediatePropagation()','wire:click' => 'cancelarOrderSubmit(\''.e($order->id).'\')']); ?>
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
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/dashboard/dashboard-financeiro-transacoes-cancelarOrder.blade.php ENDPATH**/ ?>