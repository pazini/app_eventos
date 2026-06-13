<div>

    
    <div wire:loading.class.remove="hidden"
         wire:target="processarPagamento,paymentReset"
         class="hidden fixed inset-0 z-[999] flex items-center justify-center" style="background: rgba(255,255,255,0.80); backdrop-filter: blur(6px);">
        <div class="flex flex-col items-center gap-4">
            <img src="<?php echo e(asset('/assets/loader.v2.svg')); ?>" alt="" class="w-16 h-16">
            <span class="text-gray-500 text-sm uppercase tracking-widest font-light">Aguarde…</span>
        </div>
    </div>
    

    <?php echo $__env->make('_includes.alertas_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if($order ?? false): ?>

        <?php
            $colorPrimary   = $order->event->color_primary   ?? $order->event->color_default ?? '#6366f1';
            $colorSecondary = $order->event->color_secondary  ?? $order->event->color_default ?? '#8b5cf6';
            $colorDefault   = $order->event->color_default    ?? '#6366f1';
            $colorInverse   = $order->event->color_default_inverse ?? '#ffffff';
        ?>

        <div id="formasPagamento" class="w-full max-w-4xl mx-auto px-4 md:px-10">

            <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid <?php echo e($colorPrimary); ?>18;">

                
                <div class="px-5 md:px-8 py-4" style="background: <?php echo e($colorPrimary); ?>08; border-bottom: 1px solid <?php echo e($colorPrimary); ?>15;">
                    <div class="uppercase text-sm tracking-widest font-semibold text-gray-700">PAGAMENTO</div>
                </div>

                <div class="px-5 md:px-8 py-5">

                <div class="flex flex-col gap-2 mb-4">

                    <div class="col-span-full flex flex-col gap-2">

                        <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 bg-gray-50" style="border: 1px solid #e2e8f0;">
                            <div class="text-xs uppercase tracking-widest font-semibold text-gray-500">VALOR</div>
                            <div class="text-lg md:text-2xl uppercase text-right font-bold text-gray-900 whitespace-nowrap"><?php echo e(toMoney($this->order_amount_payment ?? 0, 'R$ ')); ?></div>
                        </div>

                    </div>

                    
                    <?php if($order->code_promo_id ?? false): ?>

                        <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 bg-red-50 border border-red-200">
                            <div>
                                <div class="text-sm md:text-lg uppercase text-left font-semibold text-red-600"><?php echo e($order->code_promo_label ?? 'CUPOM APLICADO'); ?></div>
                            </div>
                            <div>
                                <div class="text-base md:text-xl uppercase text-right font-bold text-red-600 whitespace-nowrap">
                                    <?php if($order->code_promo_price_less ?? false): ?>
                                        <span><?php echo e(toMoney($order->code_promo_price_less,'- R$ ')); ?></span>
                                    <?php elseif($order->code_promo_discount_amount ?? false): ?>
                                        <span><?php echo e(toMoney($order->code_promo_discount_amount,'- R$ ')); ?></span>
                                    <?php else: ?>
                                        --
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if($order->code_promo_price_new ?? false): ?>

                            <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 bg-gray-50" style="border: 1px solid #e2e8f0;">
                                <div>
                                    <div class="text-xs uppercase tracking-widest font-semibold text-gray-500">TOTAL PARA PAGAMENTO</div>
                                </div>
                                <div>
                                    <div class="text-lg md:text-2xl uppercase text-right font-bold text-gray-900 whitespace-nowrap">
                                        <span><?php echo e(toMoney($order->code_promo_price_new,'R$ ')); ?></span>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                    <?php endif; ?>

                    
                    <?php
                        $valueOrderAmount  = (($order->code_promo_id ?? false) && ($order->code_promo_price_new)) ? ($order->code_promo_price_new ?? 0) : ($order->order_amount ?? 0);
                        $valueOrderPaid    = $order->order_amount_pay ?? 0;
                        $valueOrderPending = $valueOrderAmount - $valueOrderPaid;
                    ?>
                    <?php if(($order->order_amount_pay > 0) && ($valueOrderPending > 0)): ?>

                        <div x-data="{ open: false }" class="rounded-xl px-4 md:px-5 py-3 bg-green-50 border border-green-200">
                            <div class="w-full flex justify-between items-center gap-2">
                                <div class="text-sm md:text-lg uppercase text-left font-semibold text-green-700">PAGAMENTOS REALIZADOS <button @click="open = !open" class="text-xs text-blue-500 font-light hover:underline ml-1">Exibir</button></div>
                                <div class="text-base md:text-xl uppercase text-right font-bold text-green-700 whitespace-nowrap">- <?php echo e(toMoney($valueOrderPaid ?? 0, 'R$ ')); ?></div>
                            </div>
                            <div x-show="open" x-transition.duration.500ms.opacity.scale class="mt-2">

                                
                                <?php $__empty_1 = true; $__currentLoopData = $order->payments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderPaymentKey => $orderPaymentItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="mt-1 py-1.5 px-4 bg-white rounded-lg border border-gray-100 shadow-sm" title="<?php echo e($orderPaymentKey + 1); ?> // <?php echo e($orderPaymentItem->id); ?>">
                                        <div class="w-full flex justify-between items-center">
                                            <div class="flex-none md:flex items-center gap-2 text-sm">
                                                <div class="uppercase font-light text-gray-500"><?php echo e(dataData($orderPaymentItem->pay_datetime)); ?></div>
                                                <div class="uppercase font-medium text-gray-700"><?php echo e(__($orderPaymentItem->pay_type ?? '---')); ?></div>
                                                <div class="uppercase text-gray-400 text-xs"><?php echo e($orderPaymentItem->status ?? null); ?></div>
                                                <div class="uppercase text-gray-400 text-xs font-mono"><?php echo e($orderPaymentItem->pay_nsu ?? '---'); ?></div>
                                            </div>
                                            <div class="text-sm font-semibold text-gray-800">
                                                <?php if($orderPaymentItem->pay_value_paid < $orderPaymentItem->value_liquid): ?>
                                                    <?php echo e(toMoney($orderPaymentItem->pay_value_paid ?? 0,'R$ ')); ?>

                                                <?php else: ?>
                                                    <?php echo e(toMoney($orderPaymentItem->value_liquid ?? 0,'R$ ')); ?>

                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="mt-1 py-1.5 px-4 bg-white rounded-lg border border-gray-100 shadow-sm text-sm text-gray-500">PAGAMENTOS NÃO LOCALIZADOS</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-span-full flex flex-col gap-2">
                            <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 bg-gray-50" style="border: 1px solid #e2e8f0;">
                                <div class="text-xs uppercase tracking-widest font-semibold text-gray-500">PENDENTE</div>
                                <div class="text-lg md:text-2xl uppercase text-right font-bold text-gray-900 whitespace-nowrap"><?php echo e(toMoney($valueOrderPending ?? 0, 'R$ ')); ?></div>
                            </div>
                        </div>

                    
                    <?php elseif(!$order->code_promo_id ?? false): ?>

                        
                        <div class="w-full grid grid-cols-1 md:grid-cols-12 mt-4 md:mt-6 px-5 py-4 rounded-xl bg-gray-50" style="border: 1px solid #e2e8f0;">

                            <div class="col-span-full md:col-span-6">
                                <div class="uppercase text-sm md:text-base font-semibold text-gray-700">POSSUI UM CUPOM?</div>
                                <div class="uppercase text-xs font-light -mt-0.5 mb-2 text-gray-400">Informe aqui e clique em aplicar</div>
                            </div>

                            <div class="col-span-full md:col-span-6">

                                <?php if($code_promo_label ?? false): ?>
                                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'ticket_code_promo','class' => 'w-full bg-gray-100 cursor-not-allowed uppercase','readonly' => true]); ?>
                                         <?php $__env->slot('append', null, []); ?> 
                                            <div class="absolute inset-y-0 right-0 flex items-center p-0.5">
                                                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Remover','squared' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'removeCupom','class' => 'h-full shadow uppercase','negative' => true]); ?>
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
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                <?php else: ?>
                                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'CUPOM','wire:model.defer' => 'ticket_code_promo','class' => 'w-full py-1 md:py-2 px-2 text-sm md:text-base uppercase']); ?>
                                         <?php $__env->slot('append', null, []); ?> 
                                            <div class="absolute inset-y-0 right-0 flex items-center p-0.5">
                                                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'APLICAR','squared' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'aplicarCupom','class' => 'h-full uppercase text-sm md:text-base p-1','primary' => true]); ?>
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
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                <?php endif; ?>

                            </div>

                            <?php if(session('ticket_code_promo_erro')): ?>
                                <div class="col-span-full mt-3 py-1.5 text-center bg-red-50 text-red-700 border border-red-200 rounded-lg">
                                    <span class="text-sm font-bold uppercase"><?php echo e(__(session('ticket_code_promo_erro'))); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if(session('ticket_code_promo_sucesso')): ?>
                                <div class="col-span-full mt-3 py-1.5 text-center bg-green-50 text-green-700 border border-green-200 rounded-lg">
                                    <span class="text-sm font-bold uppercase"><?php echo e(__(session('ticket_code_promo_sucesso'))); ?></span>
                                </div>
                            <?php endif; ?>

                            
                            <?php if($this->code_promo_discount_amount ?? false): ?>
                                <div class="col-span-full mt-3 flex justify-between items-center px-4 py-2 bg-white rounded-lg border border-gray-100 text-gray-600">
                                    <div class="uppercase text-sm font-semibold flex items-center gap-2">
                                        <div>DESCONTO</div>
                                        <?php if($code_promo_label ?? false): ?>
                                            <div class="font-normal text-green-700 uppercase"><?php echo e(__($this->code_promo_label)); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="font-bold text-lg"><?php echo e(toMoney($this->code_promo_discount_amount ?? 0 ,'- ')); ?></div>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="text-gray-400 text-xs font-normal mx-2 mt-2 mb-4">* Se você não possuir nenhum cupom de desconto, simplesmente deixe em branco.</div>

                    <?php endif; ?>

                </div>

                
                <?php if((!$payment ?? false) || (($payment ?? false) && !in_array($payment->status,listPaymentStatusPaidCanceled()) )): ?>

                    <div class="w-full mt-4 pt-4" style="border-top: 1px solid #e2e8f0;">

                        <?php echo $__env->make('_includes.alertas_forma_pagamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <?php if($formaPagamentoDisponivel ?? false): ?>

                            <div class="w-full text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3 mt-2">ESCOLHA UMA FORMA DE PAGAMENTO</div>

                            
                            <div
                                x-data="{ openTab: '<?php echo e($this->payType ?? ''); ?>' }"
                                class="flex flex-col gap-2"
                                id="formaPagamentoSelecionada"
                            >
                                <?php $__currentLoopData = $this->formaPagamentoDisponivel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formaPagamentoItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $slug = $formaPagamentoItem['slug'];
                                        $payConfig = match($slug) {
                                            'boleto'      => ['label' => 'BOLETO',            'sub' => null,        'icon' => asset('images/icones/logo-boleto.png')],
                                            'card_credit' => ['label' => 'CARTÃO DE CRÉDITO', 'sub' => null,        'icon' => asset('images/icones/logo-credit.png')],
                                            'pix'         => ['label' => 'PIX',               'sub' => null,        'icon' => asset('images/icones/logo-pix.png')],
                                            'slip_pix'    => ['label' => 'CARNÊ PIX',          'sub' => 'COM JUROS', 'icon' => asset('images/icones/logo-slip-pix.png')],
                                            'slip_boleto' => ['label' => 'CARNÊ BOLETO',       'sub' => 'COM JUROS', 'icon' => asset('images/icones/logo-slip-pix.png')],
                                            default       => null,
                                        };
                                    ?>

                                    <?php if($payConfig !== null): ?>
                                        <div
                                            class="rounded-xl overflow-hidden transition-all duration-200"
                                            :style="openTab === '<?php echo e($slug); ?>'
                                                ? 'border: 1px solid <?php echo e($colorPrimary); ?>; box-shadow: 0 2px 8px rgba(0,0,0,0.08);'
                                                : 'border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.04);'"
                                        >

                                            
                                            <button
                                                type="button"
                                                wire:click="$set('payType','<?php echo e($slug); ?>')"
                                                x-on:click="openTab = openTab === '<?php echo e($slug); ?>' ? '' : '<?php echo e($slug); ?>'"
                                                class="w-full flex items-center justify-between gap-3 px-4 py-3 md:py-4 focus:outline-none transition-colors duration-200"
                                                :style="openTab === '<?php echo e($slug); ?>'
                                                    ? 'background: <?php echo e($colorPrimary); ?>0d; border-left: 4px solid <?php echo e($colorPrimary); ?>;'
                                                    : 'background: white; border-left: 4px solid transparent;'"
                                            >
                                                <div class="flex items-center gap-3">
                                                    <img
                                                        src="<?php echo e($payConfig['icon']); ?>"
                                                        alt="<?php echo e($payConfig['label']); ?>"
                                                        class="h-6 md:h-8 w-auto object-contain flex-shrink-0"
                                                    >
                                                    <div class="text-left leading-tight">
                                                        <div
                                                            class="text-sm md:text-base font-bold uppercase tracking-wide transition-colors duration-200"
                                                            :style="openTab === '<?php echo e($slug); ?>' ? 'color: <?php echo e($colorPrimary); ?>;' : 'color: #1f2937;'"
                                                        ><?php echo e($payConfig['label']); ?></div>
                                                        <?php if($payConfig['sub']): ?>
                                                            <div class="text-xs font-light text-gray-400 mt-0.5"><?php echo e($payConfig['sub']); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2 flex-shrink-0">
                                                    
                                                    <svg
                                                        x-show="openTab === '<?php echo e($slug); ?>'"
                                                        class="w-4 h-4"
                                                        :style="'color: <?php echo e($colorPrimary); ?>;'"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        style="display:none;"
                                                    >
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    
                                                    <svg
                                                        x-show="openTab !== '<?php echo e($slug); ?>'"
                                                        class="w-4 h-4 text-gray-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    >
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </div>
                                            </button>

                                            
                                            <div
                                                x-show="openTab === '<?php echo e($slug); ?>'"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                style="display: none; border-top: 1px solid #e2e8f0;"
                                            >
                                                <div class="p-4 md:p-6 bg-white">
                                                    <?php switch($slug):
                                                        case ('card_credit'): ?>
                                                            <?php if($this->payType === 'card_credit'): ?>
                                                                <?php echo $__env->make('livewire.pagamento._includes.pay_type_card_credit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php endif; ?>
                                                            <?php break; ?>
                                                        <?php case ('boleto'): ?>
                                                            <?php if($this->payType === 'boleto'): ?>
                                                                <?php echo $__env->make('livewire.pagamento._includes.pay_type_boleto', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php endif; ?>
                                                            <?php break; ?>
                                                        <?php case ('pix'): ?>
                                                            <?php if($this->payType === 'pix'): ?>
                                                                
                                                                <?php if($this->pixValido ?? false): ?>
                                                                    <div wire:poll.10s="paymentCheckProcessed" class="hidden"></div>
                                                                    <div wire:loading wire:target="paymentCheckProcessed" class="flex items-center justify-center gap-1 text-xs text-gray-400 pb-2">
                                                                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                                        <span>Verificando pagamento...</span>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php echo $__env->make('livewire.pagamento._includes.pay_type_pix', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php endif; ?>
                                                            <?php break; ?>
                                                        <?php case ('slip_pix'): ?>
                                                            <?php if($this->payType === 'slip_pix'): ?>
                                                                <?php echo $__env->make('livewire.pagamento._includes.pay_type_slip_pix', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php endif; ?>
                                                            <?php break; ?>
                                                        <?php default: ?>
                                                    <?php endswitch; ?>
                                                </div>
                                            </div>

                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>

                        <?php else: ?>
                            <div class="w-full text-center font-bold text-red-600 border border-red-300 bg-red-50 p-4 rounded-xl">FORMAS DE PAGAMENTO INDISPONÍVEIS</div>
                        <?php endif; ?>

                    </div>

                <?php endif; ?>

                <?php if($order->buyer_email == "proeventpay@gmail.com"): ?>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'DEBUG - VALIDAR ORDER'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['warning' => true,'wire:click' => 'validaOrder(\''.e($order->id).'\',false)','class' => 'w-full mt-4']); ?>
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

                
                <div class="mt-6 pt-4" style="border-top: 1px solid <?php echo e($colorPrimary); ?>10;">
                    <div class="text-center text-xs text-gray-400 space-y-1">
                        <div class="flex items-center justify-center gap-2">
                            <span>Localizador: <span class="font-mono font-semibold text-gray-500"><?php echo e($order->order_control ?? 'N/A'); ?></span></span>
                        </div>
                        <div class="text-[10px] text-gray-400">
                            <span class="font-mono"><?php echo e($order->id ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </div>

            </div>
            </div>

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
<?php $component->withAttributes(['lg' => true,'orange' => true,'class' => 'w-full uppercase font-bold rounded-xl']); ?>
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
            <div class="border border-red-300 bg-red-50 p-4 text-center rounded-xl">
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

        if ('<?php echo e($payType); ?>' !== false)
        {
            // ROLA TELA ATE FRAME PAGAMENTO
            scrolToFormasPagamentoSelecionada();
        }
        else
        {
            // ROLA TELA ATE FRAME PAGAMENTO
            scrollToFormasPagamento();
        }

    </script>

</div>

<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/compras/modulo-pagamento.blade.php ENDPATH**/ ?>