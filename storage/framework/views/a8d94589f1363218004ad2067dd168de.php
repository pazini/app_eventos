<div>

    
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
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm">Ops! Erro no preenchimento. Revise os dados.</p>
        </div>
    <?php endif; ?>

    <form wire:submit.prevent="processarPagamento">

        <div class="flex flex-col md:flex-row gap-5">

            
            <div class="w-full md:w-1/2">

                
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-widest text-gray-400">Parcelamento</div>
                        <div class="text-base font-bold text-gray-800 leading-tight mt-0.5">Carnê PIX</div>
                    </div>
                    <?php if($installment_max > 1): ?>
                        <div>
                            <select wire:model="pay_installments_number_slip" required
                                class="text-sm font-semibold rounded-lg px-3 py-2 focus:outline-none transition bg-white text-gray-800"
                                style="border: 2px solid <?php echo e($colorPrimary ?? '#6366f1'); ?>; min-width: 130px;">
                                <option value="">Selecione</option>
                                <?php $__currentLoopData = range(2, $installment_max); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($parcela); ?>"><?php echo e($parcela); ?>x parcelas</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>

                
                <?php if(count($pagamento_parcelas ?? [])): ?>
                    <div class="rounded-xl overflow-hidden" style="border: 1px solid #e2e8f0;">

                        
                        <div class="grid grid-cols-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400 px-1 py-2.5 bg-gray-50" style="border-bottom: 1px solid #e2e8f0;">
                            <div>Parcela</div>
                            <div>Vencimento</div>
                            <div>Valor</div>
                        </div>

                        <?php $__currentLoopData = $pagamento_parcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela_key => $parcela_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="grid grid-cols-3 text-center items-center px-1 py-3 transition-colors hover:bg-gray-50 <?php echo e(!$loop->last ? 'border-b border-gray-100' : ''); ?>">

                                
                                <div class="flex justify-center items-center gap-1.5">
                                    <span class="text-sm font-semibold text-gray-700"><?php echo e(str_replace('Parcela ', '', $parcela_item['label'])); ?></span>
                                </div>

                                
                                <div>
                                    <?php if($parcela_item['parcela'] == 1): ?>
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-bold text-orange-600 bg-orange-50 rounded-full" style="border: 1px solid #fed7aa;">IMEDIATO</span>
                                    <?php else: ?>
                                        <span class="text-sm font-semibold text-gray-700"><?php echo e($parcela_item['vencimento']); ?></span>
                                    <?php endif; ?>
                                </div>

                                
                                <div class="text-sm font-bold text-gray-800 whitespace-nowrap">
                                    <?php echo e(toMoney($parcela_item['parcela_valor'], 'R$ ')); ?>

                                </div>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                        <div class="flex items-center justify-between px-4 py-3" style="background: <?php echo e($colorPrimary ?? '#6366f1'); ?>08; border-top: 2px solid <?php echo e($colorPrimary ?? '#6366f1'); ?>20;">
                            <span class="text-xs font-semibold uppercase tracking-widest" style="color: <?php echo e($colorPrimary ?? '#6366f1'); ?>;">Total do Carnê</span>
                            <span class="text-lg font-bold" style="color: <?php echo e($colorPrimary ?? '#6366f1'); ?>;">
                                <?php echo e(toMoney($parcela_item['parcela_valor'] * $parcela_item['parcela_qtd'], 'R$ ')); ?>

                            </span>
                        </div>

                    </div>
                <?php else: ?>
                    <div class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-sm text-gray-400 uppercase tracking-widest">
                        Nenhuma parcela disponível
                    </div>
                <?php endif; ?>

            </div>

            
            <div id="PagarParcela" class="w-full md:w-1/2 flex flex-col gap-4">

                <?php if($pagamento_parcelas ?? false): ?>

                    
                    <div class="flex items-start gap-3 rounded-xl px-4 py-3" style="background: <?php echo e($colorPrimary ?? '#6366f1'); ?>08; border: 1px solid <?php echo e($colorPrimary ?? '#6366f1'); ?>20;">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: <?php echo e($colorPrimary ?? '#6366f1'); ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-wide" style="color: <?php echo e($colorPrimary ?? '#6366f1'); ?>;">Atenção ao prazo</div>
                            <div class="text-xs text-gray-500 mt-0.5 leading-relaxed">Após gerar a chave você tem <strong>10 minutos</strong> para pagar a 1ª parcela via PIX.</div>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest text-gray-500 mb-1.5">CPF do pagador PIX</label>
                        <input type="text" wire:model.defer="pix_cpf" placeholder="000.000.000-00" required
                            maxlength="14" inputmode="numeric"
                            oninput="let v=this.value.replace(/\D/g,'').slice(0,11);this.value=v.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');"
                            class="w-full text-sm font-medium text-gray-800 bg-white rounded-lg px-3 py-2.5 focus:outline-none transition placeholder-gray-300"
                            style="border: 1px solid #d1d5db;" />
                        <span class="text-xs text-gray-400 mt-1 block">CPF de quem vai realizar o pagamento</span>
                    </div>

                    
                    <?php if(isset($aceiteTermos['slip_pix'])): ?>

                        <div>
                            
                            <div class="flex items-center gap-2 rounded-t-xl px-4 py-3 bg-amber-500">
                                <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <div class="flex flex-col leading-tight">
                                    <span class="text-xs font-bold uppercase tracking-widest text-white">Termos do Carnê PIX</span>
                                    <span class="text-xs font-black uppercase tracking-widest text-white opacity-90">⚠ Leia com atenção</span>
                                </div>
                            </div>

                            
                            <div class="rounded-b-xl overflow-hidden" style="border: 1px solid #e2e8f0; border-top: none;">
                                <?php $__currentLoopData = $aceiteTermos['slip_pix'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termosKey => $termosItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label for="<?php echo e($termosKey); ?>"
                                        class="flex items-start gap-3 px-4 py-3 cursor-pointer transition-colors hover:bg-gray-50 <?php echo e(!$loop->last ? 'border-b border-gray-100' : ''); ?>"
                                    >
                                        <input
                                            type="checkbox"
                                            id="<?php echo e($termosKey); ?>"
                                            name="<?php echo e($termosKey); ?>"
                                            wire:model.defer="aceite_termos.slip_pix.<?php echo e($termosKey); ?>.check"
                                            class="mt-0.5 rounded flex-shrink-0"
                                            required
                                        />
                                        <span class="text-xs text-gray-600 leading-relaxed"><?php echo e($termosItem['termo']); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                    <?php endif; ?>

                    
                    <div class="mt-auto">
                        <?php if($target->pay_sandbox ?? false): ?>
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'GERAR CARNÊ PIX — TESTE','spinner' => 'processarPagamentoSlip'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['positive' => true,'type' => 'submit','class' => 'w-full text-base font-bold py-3 shadow-md rounded-xl']); ?>
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
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'GERAR CARNÊ PIX','spinner' => 'processarPagamentoSlip'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['positive' => true,'type' => 'submit','class' => 'w-full text-base font-bold py-3 shadow-md rounded-xl','onclick' => 'confirm(\'Confirma a geração de carnê PIX? Após a geração, o método de pagamento não poderá ser alterado.\') || event.stopImmediatePropagation()','wire:confirm' => '']); ?>
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
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Nenhum pagamento disponível'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['red' => true,'class' => 'w-full rounded-xl']); ?>
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

    </form>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/pagamento/_includes/pay_type_slip_pix.blade.php ENDPATH**/ ?>