<div>

    <?php
        $colorPrimary   = $order->event->color_primary   ?? $order->event->color_default ?? '#6366f1';
        $colorSecondary = $order->event->color_secondary  ?? $order->event->color_default ?? '#8b5cf6';
        $colorDefault   = $order->event->color_default    ?? '#6366f1';
        $colorInverse   = $order->event->color_default_inverse ?? '#ffffff';
    ?>

    
    <div wire:loading.class.remove="hidden" class="hidden fixed inset-0 z-[999] flex items-center justify-center" style="background: rgba(255,255,255,0.80); backdrop-filter: blur(6px);">
        <div class="flex flex-col items-center gap-4">
            <img src="<?php echo e(asset('/assets/loader.v2.svg')); ?>" alt="" class="w-16 h-16">
            <span class="text-gray-500 text-sm uppercase tracking-widest font-light">Aguarde…</span>
        </div>
    </div>
    

    <?php echo $__env->make('_includes.alertas_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if($order ?? false): ?>

        <div id="formasPagamento" class="w-full max-w-4xl mx-auto px-4 md:px-10 mb-6">

            <?php if($order->paymentsSlip->count()): ?>

                <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid <?php echo e($colorPrimary); ?>18;">

                    <?php if($slipPayment ?? false): ?>

                        
                        <div class="px-5 md:px-8 py-4" style="background: <?php echo e($colorPrimary); ?>08; border-bottom: 1px solid <?php echo e($colorPrimary); ?>15;">
                            <div class="uppercase text-xs tracking-widest font-light text-gray-400">REALIZAR</div>
                            <div class="text-xl md:text-2xl font-bold text-gray-800 -mt-0.5 uppercase">PAGAMENTO</div>
                        </div>

                        
                        <div wire:key="slip-<?php echo e($slipPayment->id); ?>" class="px-5 md:px-8 py-5">

                            <div class="w-full rounded-xl px-4 md:px-5 py-4 mb-4" style="background: <?php echo e($colorPrimary); ?>06; border: 1px solid <?php echo e($colorPrimary); ?>15;">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                                    <div>
                                        <div class="uppercase text-lg md:text-xl font-semibold text-gray-800"><?php echo e($slipPayment->installment_description ?? ('# PARCELA ' . ($slipKey + 1))); ?></div>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <?php if($slipPayment->paid_datetime ?? false): ?>
                                                <div class="uppercase text-sm font-light text-gray-500"><?php echo e($slipPayment->installment_date_due ? dataData($slipPayment->installment_date_due,ago:true) : 'SEM DATA VENCIMENTO'); ?></div>
                                            <?php else: ?>
                                                <div class="uppercase text-sm font-light text-gray-500"><?php echo e($slipPayment->installment_date_due ? dataData($slipPayment->installment_date_due,ago:true) : 'SEM DATA VENCIMENTO'); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0" title="<?php echo e($slipPayment->status); ?>">
                                        <div class="<?php echo e(setClass('payment_' . $slipPayment->status)); ?> text-sm md:text-base font-semibold"><?php echo e(__($slipPayment->status)); ?></div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 mb-4" style="background: <?php echo e($colorPrimary); ?>06; border: 1px solid <?php echo e($colorPrimary); ?>15;">
                                <div class="text-sm md:text-base uppercase font-semibold text-gray-700">VALOR PARCELA</div>
                                <div class="text-base md:text-xl uppercase font-bold text-gray-800 whitespace-nowrap"><?php echo e(toMoney($slipPayment->installment_value ?? 0,'R$ ')); ?></div>
                            </div>

                            
                            <div class="w-full rounded-xl bg-white border p-4 md:p-5 shadow-sm" style="border-color: <?php echo e($colorPrimary); ?>20;">

                                <?php echo $__env->make('_includes.alertas_forma_pagamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                
                                <?php if(in_array($slipPayment->status,listPaymentStatusPaid())): ?>
                                    <div class="flex items-center gap-2 text-green-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div class="font-medium uppercase text-sm">ESTE PAGAMENTO JÁ FOI REALIZADO EM</div>
                                        <div class="font-light text-sm"><?php echo e(dataData($slipPayment->paid_datetime)); ?></div>
                                    </div>
                                
                                <?php elseif(in_array(($slipPayment->installment_pay_type ?? false),['pix','slip_pix'])): ?>
                                    <?php echo $__env->make('livewire.pagamento._includes.pay_type_pix', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                
                                <?php elseif(in_array(($slipPayment->installment_pay_type ?? false),['boleto'])): ?>
                                    <?php echo $__env->make('livewire.pagamento._includes.pay_type_boleto', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php else: ?>
                                    <div class="text-gray-500 text-sm uppercase">NÃO POSSUI MÉTODO PAGAMENTO ASSOCIADO</div>
                                <?php endif; ?>

                            </div>

                        </div>

                        
                        <div class="mx-5 md:mx-8" style="border-top: 1px solid <?php echo e($colorPrimary); ?>15;"></div>

                    <?php endif; ?>

                    
                    <div class="px-5 md:px-8 py-4" style="background: <?php echo e($colorPrimary); ?>08; border-bottom: 1px solid <?php echo e($colorPrimary); ?>15;">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span class="text-lg md:text-xl font-bold text-gray-800 uppercase">CARNÊ ONLINE</span>
                        </div>
                    </div>

                    <div class="px-5 md:px-8 py-5">

                        <?php if(!$slipPayment ?? false): ?>
                            <?php echo $__env->make('_includes.alertas_forma_pagamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>

                        
                        <?php
                            $orderPaymentsSlip = $order->paymentsSlip->sortBy('slip_installment');
                        ?>
                        <div class="flex flex-col gap-3">
                        <?php $__currentLoopData = $orderPaymentsSlip ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slipKey => $slipItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                //
                                $slipStatus       = null;
                                $slipStatusSufixo = null;
                                $classStatus      = null;
                                $classBorder      = null;
                                $classIcon        = null;
                                //
                                $slipStatus = $slipItem->status;
                                $diasAtraso = (now()->format('Ymd') - dataCarbon($slipItem->installment_date_due,'Ymd'));

                                //
                                if(in_array($slipItem->status,listPaymentStatusPaid()))
                                {
                                    $classStatus = 'bg-green-50';
                                    $classBorder = 'border-green-300';
                                    $classIcon   = 'text-green-500';
                                }
                                elseif(($slipItem->installment_date_due ?? false) && ($diasAtraso > 0))
                                {
                                    $slipStatus       = 'em_atraso';
                                    $slipStatusSufixo = $diasAtraso . ' dia(s)';
                                    $classStatus      = 'bg-red-50';
                                    $classBorder      = 'border-red-300';
                                    $classIcon        = 'text-red-500';
                                }
                                elseif(in_array($slipItem->status,['aguardando_pagamento']))
                                {
                                    $classStatus = 'bg-blue-50';
                                    $classBorder = 'border-blue-300';
                                    $classIcon   = 'text-blue-500';
                                }
                                else
                                {
                                    $classStatus = 'bg-gray-50';
                                    $classBorder = 'border-gray-200';
                                    $classIcon   = 'text-gray-400';
                                }
                            ?>
                            <div x-data="{ open: false }" class="w-full rounded-xl border shadow-sm overflow-hidden transition-all duration-200 <?php echo e($classStatus); ?> <?php echo e($classBorder); ?>">
                                <button @click="open = !open" class="w-full px-4 md:px-5 py-3">
                                    <div class="w-full flex flex-col md:flex-row justify-between items-start md:items-center gap-1">
                                        <div class="text-left">
                                            <div class="uppercase text-sm md:text-base font-semibold text-gray-800"><?php echo e($slipItem->installment_description ?? 'SEM DESCRIÇÃO'); ?></div>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <svg class="w-3.5 h-3.5 <?php echo e($classIcon); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <?php if($slipItem->paid_datetime ?? false): ?>
                                                    <div class="uppercase text-xs font-light text-green-600"><?php echo e(dataData($slipItem->paid_datetime,ago:true)); ?></div>
                                                <?php else: ?>
                                                    <div class="uppercase text-xs font-light text-gray-500"><?php echo e($slipItem->installment_date_due ? dataData($slipItem->installment_date_due,ago:true) : 'SEM DATA VENCIMENTO'); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2" title="<?php echo e($slipStatus); ?>">
                                            <div class="<?php echo e(setClass('payment_' . $slipStatus)); ?> text-xs md:text-sm font-semibold"><?php echo e(__($slipStatus)); ?> <?php echo e($slipStatusSufixo ?? null); ?></div>
                                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </button>

                                <div x-show="open" x-transition.duration.500ms.opacity.scale class="px-4 md:px-5 pb-4">
                                    <?php if(in_array($slipItem->status,listPaymentStatusPaid())): ?>
                                        <?php $__empty_1 = true; $__currentLoopData = $slipItem->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <?php echo $__env->make('livewire.compras._includes.exibir-pagamentos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="text-sm text-gray-500 uppercase">NÃO ENCONTRAMOS PAGAMENTOS PARA ESSA MENSALIDADE</div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="flex flex-col gap-0 rounded-xl bg-white border shadow-sm overflow-hidden" style="border-color: <?php echo e($colorPrimary); ?>20;">
                                            <div class="w-full px-4 py-3 uppercase flex justify-between items-center text-sm md:text-base" style="border-bottom: 1px solid <?php echo e($colorPrimary); ?>10;">
                                                <div class="font-semibold text-gray-700">VALOR PARCELA</div>
                                                <div class="font-bold text-gray-800"><?php echo e(toMoney($slipItem->installment_value ?? 0,'R$ ')); ?></div>
                                            </div>

                                            <div class="w-full px-4 py-3 uppercase flex justify-between items-center text-sm md:text-base" style="border-bottom: 1px solid <?php echo e($colorPrimary); ?>10;">
                                                <div class="font-semibold text-gray-700">VENCIMENTO</div>
                                                <div class="font-bold text-gray-800"><?php echo e($slipItem->installment_date_due ? dataData($slipItem->installment_date_due) : 'SEM DATA VENCIMENTO'); ?></div>
                                            </div>

                                            <div class="w-full px-4 py-3 uppercase flex justify-between items-center text-sm md:text-base">
                                                <div class="font-semibold text-gray-700">SITUAÇÃO</div>
                                                <div class="font-bold text-gray-800"><?php echo e(__($slipItem->status)); ?></div>
                                            </div>
                                        </div>
                                        <div class="w-full flex justify-center md:justify-end items-center gap-4 mt-4">
                                            <?php if(!in_array($slipItem->status,listPaymentStatusPaid())): ?>
                                                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'SELECIONAR PARA PAGAR'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blue' => true,'wire:click' => 'selecionaSlipPayment(\''.e($slipItem->id).'\')','class' => 'font-semibold px-6 shadow-md rounded-xl','onclick' => 'scrollToFormasPagamento()']); ?>
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
                                            <?php elseif(($slipPayment ?? FALSE) && $slipItem->id != $slipPayment->id): ?>
                                                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'SELECIONAR PARA PAGAR'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blue' => true,'wire:click' => 'selecionaSlipPayment(\''.e($slipItem->id).'\')','class' => 'font-semibold px-6 shadow-md rounded-xl','onclick' => 'scrollToFormasPagamento()']); ?>
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
                                    <?php endif; ?>
                                </div>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                    </div>

                </div>

            <?php else: ?>

                <div class="w-full rounded-xl bg-red-50 border border-red-200 p-4 text-center animate-bounce">
                    <span class="text-red-700 font-normal">Este carnê não possui parcelas cadastradas. Procure o organizador do evento informe o localizador</span>
                    <span class="text-red-700 font-bold"><?php echo e($order->order_control); ?></span>
                </div>

            <?php endif; ?>

        </div>

        
        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 mb-6">
            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['spinner' => true,'label' => 'VALIDAR PAGAMENTO FORÇADO'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['green' => true,'wire:click' => 'paymentCheckProcessed(true)','class' => 'w-full rounded-xl shadow-md','title' => 'VALIDAR PAGAMENTO FORÇADO']); ?>
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

    <?php elseif($orderControl ?? false): ?>

        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 text-center">
            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['icon' => 'refresh','href' => ''.e(route('compra-exibir', ['localizador' => $orderControl])).'','label' => 'Clique aqui para atualizar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lg' => true,'blue' => true,'class' => 'w-full uppercase font-semibold rounded-xl shadow-md']); ?>
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

        <div class="w-full max-w-4xl mx-auto px-4 md:px-10">
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-center">
                <span class="text-red-600 font-semibold">PEDIDO NÃO LOCALIZADO</span>
            </div>
        </div>

    <?php endif; ?>

    
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script>
        function scrollToFormasPagamento() {
            document.getElementById('formasPagamento').scrollIntoView({
                behavior: 'smooth'
            });
        }
        function scrolToFormasPagamentoSelecionada() {
            document.getElementById('formaPagamentoSelecionada').scrollIntoView({
                behavior: 'smooth'
            });
        }
        function copyToClipboard(id,msg=false)
        {
            var Clipboard = new ClipboardJS('#' + id);
            if(msg) {alert(msg)}
        }

        // ROLA TELA ATE FRAME PAGAMENTO
        scrollToFormasPagamento();
    </script>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/compras/modulo-pagamento-slip.blade.php ENDPATH**/ ?>