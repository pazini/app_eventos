
    <?php
        foreach (listMes() as $MM => $mes) { $listaMM[$MM] = $MM; }
        foreach (range(now()->format('Y'), now()->addYears(15)->format('Y')) as $AAAA) { $listaAAAA[$AAAA] = $AAAA; }
    ?>

    
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

    <form wire:submit.prevent="processarPagamento(<?php echo e($target->pay_sandbox ?? false); ?>)">

        <div class="flex flex-col gap-5">

            
            <div class="flex items-center gap-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: <?php echo e($colorPrimary ?? '#6366f1'); ?>15;">
                    <svg class="w-4 h-4" style="color: <?php echo e($colorPrimary ?? '#6366f1'); ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-widest text-gray-400">Pagamento</div>
                    <div class="text-base font-bold text-gray-800 leading-tight">Dados do Cartão de Crédito</div>
                </div>
            </div>

            
            <?php
                $inputClass = 'w-full text-sm font-medium text-gray-800 bg-white rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-offset-0 transition placeholder-gray-300';
                $inputStyle = 'border: 1px solid #d1d5db;';
                $labelClass = 'block text-xs font-semibold uppercase tracking-widest text-gray-500 mb-1.5';
            ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                
                <div>
                    <label class="<?php echo e($labelClass); ?>">CPF do titular do cartão</label>
                    <input type="text" wire:model.defer="card_credit_cpf" placeholder="000.000.000-00" required
                        maxlength="14" inputmode="numeric"
                        oninput="let v=this.value.replace(/\D/g,'').slice(0,11);this.value=v.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');"
                        class="<?php echo e($inputClass); ?>" style="<?php echo e($inputStyle); ?>" />
                </div>

                
                <div>
                    <label class="<?php echo e($labelClass); ?>">Número do cartão</label>
                    <input type="text" wire:model.defer="card_credit_num" placeholder="0000 0000 0000 0000" required
                        maxlength="19" inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').slice(0,16).replace(/(\d{4})(?=\d)/g,'$1 ');"
                        class="<?php echo e($inputClass); ?>" style="<?php echo e($inputStyle); ?>" />
                </div>

                
                <div>
                    <label class="<?php echo e($labelClass); ?>">Nome impresso no cartão</label>
                    <input type="text" wire:model.defer="card_credit_nome" placeholder="Como está no cartão" required
                        oninput="this.value=this.value.toUpperCase();"
                        class="<?php echo e($inputClass); ?>" style="<?php echo e($inputStyle); ?>" />
                </div>

                
                <div>
                    <label class="<?php echo e($labelClass); ?>">Validade</label>
                    <div class="flex gap-2">
                        <select wire:model.defer="card_credit_validade_mm" required
                            class="<?php echo e($inputClass); ?> w-1/2"
                            style="<?php echo e($inputStyle); ?>">
                            <option value="">MM</option>
                            <?php $__currentLoopData = $listaMM; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($mm); ?>"><?php echo e($mm); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <select wire:model.defer="card_credit_validade_aaaa" required
                            class="<?php echo e($inputClass); ?> w-1/2"
                            style="<?php echo e($inputStyle); ?>">
                            <option value="">AAAA</option>
                            <?php $__currentLoopData = $listaAAAA; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aaaa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($aaaa); ?>"><?php echo e($aaaa); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                
                <div>
                    <label class="<?php echo e($labelClass); ?>">Código de segurança (CVV)</label>
                    <input type="text" wire:model.defer="card_credit_cvv" placeholder="CVV" required
                        maxlength="4" inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').slice(0,4);"
                        class="<?php echo e($inputClass); ?>" style="<?php echo e($inputStyle); ?>" />
                </div>

                
                <div>
                    <label class="<?php echo e($labelClass); ?>">Parcelamento</label>
                    <select wire:model.defer="pay_installments_number" required
                        class="<?php echo e($inputClass); ?>"
                        style="<?php echo e($inputStyle); ?>">
                        <?php $__currentLoopData = $pagamento_parcelas ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcelaKey => $parcelamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($parcelaKey); ?>"><?php echo e($parcelamento['label']); ?> <?php echo e($parcelamento['encargos'] ? '— COM JUROS' : '— SEM ACRÉSCIMO'); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="text-xs text-gray-400 mt-1 block">Juros conforme operadora do cartão</span>
                </div>

            </div>

            
            <div>
                <?php if($target->pay_sandbox ?? false): ?>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'PAGAR COM CARTÃO — TESTE','spinner' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','positive' => true,'class' => 'w-full text-base font-bold py-3 shadow-md rounded-xl']); ?>
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
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'PAGAR COM CARTÃO','spinner' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','positive' => true,'class' => 'w-full text-base font-bold py-3 shadow-md rounded-xl','onclick' => 'confirm(\'Confirma o pagamento com cartão de crédito?\') || event.stopImmediatePropagation()']); ?>
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


<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/pagamento/_includes/pay_type_card_credit.blade.php ENDPATH**/ ?>