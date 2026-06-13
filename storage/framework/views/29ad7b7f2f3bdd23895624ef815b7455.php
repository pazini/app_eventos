
<?php if(session('modal_pagamento_success')): ?>
    <?php echo e(session('modal_pagamento_success')); ?>

    <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['blur' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'modal_pagamento_success']); ?>

        <div class="flex flex-col justify-center items-center px-6">

            <div>
                <img src="<?php echo e(asset('images/icones/icon-success-animate.gif')); ?>" alt="Sucesso na Conclusão" class="h-32">
            </div>

            <div class="w-full text-center text-2xl text-green-700 mx-1 mb-2 font-normal"><?php echo e(__(session('modal_pagamento_success'))); ?></div>

            <?php if(session('modal_pagamento_success_sub')): ?>
                <div class="w-full text-center text-xl text-green-600 mx-8 font-light"><?php echo e(__(session('modal_pagamento_success_sub'))); ?></div>
            <?php endif; ?>

            <div class="flex flex-col justify-center items-center gap-2 pt-2 my-2">
                <div class="text-sm font-normal">Pagamentos processados por</div>
                <img src="<?php echo e(asset('assets/safe2pay-logo.png')); ?>" alt="Sucesso na Conclusão" class="h-10">
            </div>

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


<?php if(session('modal_error')): ?>
    <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['blur' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'modal_error']); ?>

        <div class="flex flex-col justify-center items-center px-6">

            <div>
                <img src="<?php echo e(asset('images/icones/icon-error-animate.gif')); ?>" alt="Erro na Conclusão" class="h-32">
            </div>

            <div class="w-full text-center text-2xl text-red-700 mx-1 mb-2 font-normal"><?php echo e(__(session('modal_error'))); ?></div>

            <?php if(session('modal_error_sub')): ?>
                <div class="w-full text-center text-xl text-red-600 mx-8 font-light"><?php echo e(__(session('modal_error_sub'))); ?></div>
            <?php endif; ?>

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


<?php if(session('modal_info')): ?>
    <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['blur' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'modal_info']); ?>

        <div class="flex flex-col justify-center items-center px-6">

            <div>
                <img src="<?php echo e(asset('images/icones/icon-alert-animate.gif')); ?>" alt="Erro na Conclusão" class="h-32">
            </div>

            <div class="w-full text-center text-2xl text-blue-700 mx-1 mb-2 font-normal"><?php echo e(__(session('modal_info'))); ?></div>

            <?php if(session('modal_info_sub')): ?>
                <div class="w-full text-center text-xl text-blue-600 mx-8 font-light"><?php echo e(__(session('modal_info_sub'))); ?></div>
            <?php endif; ?>

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


<?php if(session('modal_error')): ?>
    <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['blur' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'modal_error']); ?>

        <div class="flex flex-col justify-center items-center px-6">

            <div>
                <img src="<?php echo e(asset('images/icones/icon-error-animate.gif')); ?>" alt="Erro na Conclusão" class="h-32">
            </div>

            <div class="w-full text-center text-2xl text-red-700 mx-1 mb-2 font-normal"><?php echo e(__(session('modal_error'))); ?></div>

            <?php if(session('modal_error_sub')): ?>
                <div class="w-full text-center text-xl text-red-600 mx-8 font-light"><?php echo e(__(session('modal_error_sub'))); ?></div>
            <?php endif; ?>

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

<?php /**PATH /home/proev836/public_html_sistemas/app_eventos/resources/views/_includes/alertas_modal.blade.php ENDPATH**/ ?>