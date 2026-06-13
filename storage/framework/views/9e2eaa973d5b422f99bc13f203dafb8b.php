<div class="mb-10">

    <div class="<?php echo e(setClass('divContentHeader')); ?> ">
        <div class="w-full">
            <div class="flex justify-between items-center">
                <div>
                    <?php echo setLabelHeader('Evento', $target->event_name, formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? null); ?>

                </div>
                <div class="p-0">
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'reply','label' => 'VOLTAR','href' => ''.e(route('dashboard-evento')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'class' => 'hover:text-sky-500']); ?>
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
            <div class="border-t border-white mt-2 mb-4"></div>
            <div class="w-full flex flex-col md:flex-row justify-between">
                <div class="flex justify-start gap-4">
                    <div class="text-2xl font-semibold">NOFITICAÇÕES</div>
                </div>
                <div class="flex justify-end gap-4">

                    <?php if($this->notificacao ?? false): ?>
                        <?php if(!in_array($this->notificacao->status,['concluido'])): ?>
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'ALTERAR','href' => ''.e(route('notifica-alterar',['notificacao_id' => $this->notificacao->id])).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'class' => 'hover:text-blue-700']); ?>
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
                    <?php else: ?>
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'Nova Notificação','href' => ''.e(route('notifica-nova')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'class' => 'hover:text-blue-700']); ?>
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
    </div>

    <div class="<?php echo e(setClass('divContentErros')); ?>">
        <div class="w-full my-2">
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
    </div>

    
    <?php if($this->notificacao ?? false): ?>

        <div class="<?php echo e(setClass('divContent')); ?> bg-white py-8">

            <div class="w-full">

                <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4 items-center">

                    <div class="col-span-full md:col-span-5">
                        <?php echo setLabelHeader(false, $this->notificacao->envio_nome,$this->notificacao->envio_descricao); ?>

                    </div>

                    <div class="col-span-full md:col-span-2">
                        <?php echo setLabel('TIPO', __($this->notificacao->envio_tipo ?? '---')); ?>

                    </div>

                    <div class="col-span-full md:col-span-3">
                        <?php echo setLabel('STATUS', $this->notificacao->status ?? '---'); ?>

                    </div>

                    <div class="col-span-full md:col-span-2">
                        <?php echo setLabel('QTD ENVIOS', $this->notificacao->notificacaoEnvio->count() ?? 0); ?>

                    </div>

                    <div class="col-span-full md:col-span-full">
                        <hr>
                    </div>

                    <?php if(!in_array($this->notificacao->status,['concluido'])): ?>

                        <div class="col-span-full">

                            <div class="flex justify-between items-center">

                                <div class="w-9/12 grid grid-cols-1 md:grid-cols-5 items-end gap-4">

                                    <div class="text-sm font-normal text-gray-700 uppercase">
                                        STATUS ENVIOS
                                    </div>

                                    <?php $__currentLoopData = $this->notificacao->notificacaoEnvio->groupBy('status'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $envioStatus => $envioItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <div class="w-full text-sm font-normal px-4 bg-gray-700 text-white rounded-full shadow-sm uppercase">
                                            <div class="flex justify-between items-center gap-1">
                                                <div class="truncate"><?php echo e(__($envioStatus ?? '---')); ?></div>
                                                <div class="font-bold ml-2"><?php echo e($envioItem->count() ?? 0); ?></div>
                                            </div>
                                        </div>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </div>

                                <div class="w-3/12">

                                    <div class="flex flex-col gap-2">

                                        <?php if(($processar ?? 0) > 0): ?>


                                            <div wire:poll.3s="processarEnvio" class="flex gap-4">
                                                <span>PROCESSANDO ...</span>
                                                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'spinner' => true,'label' => 'Cancelar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sm' => true,'negative' => true,'wire:click' => '$set(\'processar\',false)','class' => 'py-1']); ?>
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
<?php $component = WireUi\View\Components\Button::resolve(['spinner' => true,'label' => 'PROCESSAR ENVIO'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sm' => true,'positive' => true,'wire:click' => '$set(\'processar\',true)','class' => 'py-1']); ?>
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

                        </div>

                        <div class="col-span-full md:col-span-full">
                            <hr>
                        </div>

                    <?php endif; ?>

                    <div class="col-span-full">
                        <?php echo setLabel('ASSUNTO', $this->notificacao->envio_assunto ?? '---'); ?>

                    </div>

                    <div class="col-span-full md:col-span-full">
                        <hr>
                    </div>

                    <div class="col-span-full -mt-4">

                        <?php $__empty_1 = true; $__currentLoopData = $this->notificacao->notificacaoEnvio ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $envio_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4 rounded shadow <?php echo e(in_array($envio_item->status,['ok']) ? 'bg-green-100' : 'bg-gray-100'); ?> py-2 px-4 mb-2 items-center">

                                <div class="col-span-full md:col-span-5 uppercase">
                                    <?php echo e($envio_item->destino_nome); ?>

                                </div>

                                <div class="col-span-full md:col-span-5">
                                    <?php echo e($envio_item->destino); ?>

                                </div>

                                <div class="col-span-full md:col-span-2 text-center">
                                    <?php if($envio_item->datahora ?? false): ?>
                                        <div><?php echo e($envio_item->datahora->format('d/m/Y H:i')); ?></div>
                                    <?php else: ?>
                                        <div><?php echo e($envio_item->status); ?></div>
                                    <?php endif; ?>
                                </div>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <div class="col-span-full">
                                <div>NENHUM ENVIO CADASTRADO</div>
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="col-span-full md:col-span-full mb-4">
                        <hr>
                    </div>

                </div>

            </div>

        </div>

    <?php else: ?>

        <?php $__empty_1 = true; $__currentLoopData = $this->notificacoes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notificacao_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="<?php echo e(setClass('divContent')); ?> bg-white py-2">

                <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4 items-center">

                    <div class="col-span-full md:col-span-5">
                        <?php echo setLabelHeader(false, $notificacao_item->envio_nome,$notificacao_item->envio_descricao); ?>

                    </div>

                    <div class="col-span-full md:col-span-2">
                        <?php echo setLabel('TIPO', __($notificacao_item->envio_tipo ?? '---')); ?>

                    </div>

                    <div class="col-span-full md:col-span-2">
                        <?php echo setLabel('STATUS', $notificacao_item->status ?? '---'); ?>

                    </div>

                    <div class="col-span-full md:col-span-2">
                        <?php echo setLabel('QTD ENVIOS', $notificacao_item->notificacaoEnvio->count() ?? 0); ?>

                    </div>

                    <div class="col-span-full md:col-span-1 text-center">

                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'exibir','href' => ''.e(route('notifica-exibir',['notificacao_id' => $notificacao_item->id])).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['negative' => true,'class' => 'py-1']); ?>
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

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="<?php echo e(setClass('divContent')); ?> bg-white py-8">

                <div class="w-full">NENHUMA NOTIFICAÇÃO ENCONTRADA</div>

            </div>

        <?php endif; ?>

    <?php endif; ?>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/notifica/notifica-dashboard.blade.php ENDPATH**/ ?>