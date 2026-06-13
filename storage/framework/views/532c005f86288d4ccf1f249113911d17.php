<div class="">

    <?php if(auth()->guard()->check()): ?>
        
        <?php if($faturasVencidasBloqueio->count() ?? false): ?>

            <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['blur' => true,'title' => ''.e(session('faturas_pendentes')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lg' => true,'wire:model.defer' => 'faturas_pendentes']); ?>

                <div class="flex flex-col justify-center items-center">

                    <div class="flex items-center gap-2">
                        <img src="<?php echo e(asset('images/icones/icon-alert-animate.gif')); ?>" alt="Erro na Conclusão" class="h-16">

                        <div class="">
                            <div class="text-base text-red-700">
                                <?php echo e(getNome(auth()->user()->name)); ?>, existem boletos vencidos a mais de <?php echo e($vencimentoDias); ?> dias.
                            </div>
                            <div class="hidden text-2xs text-gray-700 uppercase">
                                <span class="font-semibold">Atenção - </span> ultrapassando <?php echo e($this->vencimentoDias * 2); ?> dias as vendas serão suspensas automaticamente
                            </div>
                        </div>
                    </div>

                    <?php $__currentLoopData = $faturasVencidas ? $faturasVencidas->sortBy('pay_data_vencimento') : []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fatura_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="w-full mt-1 pt-1 border-t">
                            <div class="w-full grid grid-cols-12 items-center bg-gray-100 py-1 px-2 text-xs uppercase">
                                <div class="col-span-5">
                                    <div class="text-gray-600"><?php echo e($fatura_item->faturamento->event->event_name ?? '--'); ?></div>
                                    <div class="text-gray-600 text-3xs"><?php echo e($fatura_item->faturamento->event->organizer->organizer_name_full ?? '--'); ?></div>
                                </div>
                                <div class="col-span-3 text-center"><?php echo e($fatura_item->pay_descricao ?? '--'); ?></div>
                                <div class="col-span-2 text-center"><?php echo e(toMoney($fatura_item->pay_valor,'R$ ')); ?></div>
                                <div class="col-span-2 text-center">
                                    <div class=""><?php echo e(convertToDate($fatura_item->pay_data_vencimento ?? '--')); ?></div class="">
                                    <div class="text-2xs"><?php echo e(dateAgo($fatura_item->pay_data_vencimento ?? '--')); ?></div class="">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </div>

                <div class="w-full text-sm text-center font-normal mt-4 text-red-700">
                    Regularize os pagamentos para normalizar os acessos e evitar bloqueio total da Plataforma
                </div>

                 <?php $__env->slot('footer', null, []); ?> 
                    <div class="flex justify-center gap-x-4">
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Fechar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:click' => 'close']); ?>
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

        <?php endif; ?>
    <?php endif; ?>

    <?php if(($bloquear ?? false) && $this->faturasVencidasBloqueio->count() ?? false): ?>
        <script>
            window.location.replace("<?php echo e(route('home')); ?>");
        </script>
    <?php endif; ?>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/faturamento/validar-faturas.blade.php ENDPATH**/ ?>