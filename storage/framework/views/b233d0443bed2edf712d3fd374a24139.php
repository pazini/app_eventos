    
    <?php if(session('conclusao_error')): ?>
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm"><?php echo e(__(session('conclusao_error'))); ?></p>
            <?php if(session('conclusao_error_sub')): ?>
                <p class="text-xs font-normal uppercase mt-1"><?php echo e(__(session('conclusao_error_sub'))); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm"><?php echo e(__(session('error'))); ?></p>
        </div>
    <?php endif; ?>
    <?php if(session('info')): ?>
        <div class="mb-4 bg-blue-50 text-blue-700 border border-blue-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm"><?php echo e(__(session('info'))); ?></p>
            <?php if(session('info_sub')): ?>
                <p class="text-xs font-normal mt-1"><?php echo e(__(session('info_sub'))); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if(session('pix_alert')): ?>
        <div class="mb-4 bg-blue-50 text-blue-700 border border-blue-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm"><?php echo e(__(session('pix_alert'))); ?></p>
            <?php if(session('pix_alert_sub')): ?>
                <p class="text-xs font-normal mt-1"><?php echo e(__(session('pix_alert_sub'))); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm">Ops! Erro no preenchimento. Revise os dados.</p>
        </div>
    <?php endif; ?>

    
    <?php
        $pixAtivo = ($currentPayment ?? false)
            || (($pixValido ?? false) && ($payment ?? false) && !in_array($payment->status, listPaymentStatusPaidCanceled()));
        $pixData  = $currentPayment ?? $payment ?? null;
        $isLegado = (bool)($currentPayment ?? false);
    ?>

    <?php if($pixAtivo && $pixData): ?>

        <div class="flex flex-col gap-4">

            
            <div class="flex items-center gap-3 rounded-xl px-4 py-3" style="background: <?php echo e($colorPrimary ?? '#6366f1'); ?>08; border: 1px solid <?php echo e($colorPrimary ?? '#6366f1'); ?>20;">
                <svg class="w-5 h-5 flex-shrink-0" style="color: <?php echo e($colorPrimary ?? '#6366f1'); ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-widest" style="color: <?php echo e($colorPrimary ?? '#6366f1'); ?>;"><?php echo e(__($pixData->status)); ?></div>
                    <div class="text-xs text-gray-500 mt-0.5"><?php echo e(__($pixData->description)); ?></div>
                </div>
            </div>

            
            <div class="flex flex-col md:flex-row gap-5 items-start">

                
                <div class="w-full md:flex-1 flex flex-col gap-3">

                    
                    <div class="flex justify-between items-center py-2.5" style="border-bottom: 1px solid #e2e8f0;">
                        <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Valor</span>
                        <span class="text-lg font-bold text-gray-800">
                            <?php echo e(toMoney($pixData->value_paid ?: ($pixData->pay_installment_value ?? 0), 'R$ ')); ?>

                        </span>
                    </div>

                    
                    <div class="flex justify-between items-center py-2.5" style="border-bottom: 1px solid #e2e8f0;">
                        <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Válido até</span>
                        <span class="text-sm font-semibold text-gray-700"><?php echo e(dataCarbon($pixData->pay_pix_expires_at, 'd/m/Y H:i')); ?></span>
                    </div>

                    
                    <div class="flex flex-col gap-1.5">
                        <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">PIX Copia e Cola</span>
                        <div class="flex items-center gap-2 rounded-xl px-3 py-2.5" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <span class="flex-1 text-xs font-mono text-gray-600 break-all leading-relaxed"><?php echo e($pixData->pay_pix_key ?? '---'); ?></span>
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'rightIcon' => 'clipboard'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blue' => true,'class' => 'flex-shrink-0 p-1','title' => 'Copiar','id' => 'pay_pix_key_btn','onclick' => 'copyToClipboard(\'pay_pix_key_btn\',\'Código PIX copiado!\')','data-clipboard-text' => ''.e($pixData->pay_pix_key ?? '---').'']); ?>
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

                    
                    <div class="flex flex-col md:flex-row gap-2 mt-1">
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rightIcon' => 'clipboard','label' => 'COPIAR CHAVE PIX'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blue' => true,'class' => 'w-full','id' => 'pay_pix_key','onclick' => 'copyToClipboard(\'pay_pix_key\',\'Código PIX copiado!\')','data-clipboard-text' => ''.e($pixData->pay_pix_key ?? '---').'']); ?>
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
                        <?php if($isLegado): ?>
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'VALIDAR PAGAMENTO','spinner' => 'validarPagamento'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['green' => true,'class' => 'w-full','wire:click' => 'validarPagamento']); ?>
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
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'VALIDAR PAGAMENTO','spinner' => 'paymentCheckProcessed'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['green' => true,'class' => 'w-full','wire:click' => 'paymentCheckProcessed']); ?>
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

                
                <?php if($pixData->pay_pix_qr_code_url ?? false): ?>
                    <div class="w-full md:w-auto flex-shrink-0 flex justify-center">
                        <img src="<?php echo e($pixData->pay_pix_qr_code_url); ?>" alt="QR Code PIX" class="w-full md:w-52 object-contain">
                    </div>
                <?php endif; ?>

            </div>

            
            <div class="text-center rounded px-4 py-2.5 bg-yellow-50 text-yellow-800 text-xs font-medium" style="border: 1px solid #fde68a;">
                <?php echo e(__('pending_pix_sub')); ?>

            </div>

            <?php if(!$isLegado && ($order->buyer_email == "proeventpay@gmail.com")): ?>
                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['icon' => 'trash','label' => 'REMOVER PAGAMENTO ATUAL '.e($payment->id).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['red' => true,'class' => 'w-full','wire:click' => 'paymentReset(\'pagamento_cancelado\')']); ?>
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

    <?php else: ?>

        
        <div class="flex flex-col gap-5">

            
            <div class="flex items-start gap-3 rounded-xl px-4 py-3" style="background: <?php echo e($colorPrimary ?? '#6366f1'); ?>08; border: 1px solid <?php echo e($colorPrimary ?? '#6366f1'); ?>20;">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: <?php echo e($colorPrimary ?? '#6366f1'); ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <div class="text-xs font-bold uppercase tracking-wide" style="color: <?php echo e($colorPrimary ?? '#6366f1'); ?>;">Atenção ao prazo</div>
                    <div class="text-xs text-gray-500 mt-0.5 leading-relaxed">Após gerar a chave você tem <strong>10 minutos</strong> para realizar o pagamento via PIX.</div>
                </div>
            </div>

            
            <div class="flex flex-col gap-3">
                <div class="w-full">
                    <label class="block text-xs font-semibold uppercase tracking-widest text-gray-500 mb-1.5">CPF do pagador PIX</label>
                    <input type="text" wire:model.defer="pix_cpf" placeholder="000.000.000-00" required
                        maxlength="14" inputmode="numeric"
                        oninput="let v=this.value.replace(/\D/g,'').slice(0,11);this.value=v.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');"
                        class="w-full text-sm font-medium text-gray-800 bg-white rounded-lg px-3 py-2.5 focus:outline-none transition placeholder-gray-300"
                        style="border: 1px solid #d1d5db;" />
                    <span class="text-xs text-gray-400 mt-1 block">CPF de quem vai realizar o pagamento</span>
                </div>
                <div class="w-full">
                    <?php if($target->pay_sandbox ?? false): ?>
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'GERAR PIX '.e(toMoney($slipPayment->installment_value ?? $order_amount, 'R$ ')).' — TESTE','spinner' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['positive' => true,'class' => 'w-full text-base font-bold py-3 shadow-md rounded-xl','wire:click' => 'processarPagamento(true)']); ?>
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
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'GERAR PIX '.e(toMoney($slipPayment->installment_value ?? $order_amount, 'R$ ')).'','spinner' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['positive' => true,'class' => 'w-full text-base font-bold py-3 shadow-md rounded-xl','onclick' => 'confirm(\'Confirma o pagamento com PIX?\') || event.stopImmediatePropagation()','wire:click' => 'processarPagamento()']); ?>
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

    <?php endif; ?>


<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/pagamento/_includes/pay_type_pix.blade.php ENDPATH**/ ?>