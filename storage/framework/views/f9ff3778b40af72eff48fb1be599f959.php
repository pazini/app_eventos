<div <?php echo e($attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6'])); ?>>
    <?php if (isset($component)) { $__componentOriginal051a28c9acc4e2881f216f1dce9d55e5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal051a28c9acc4e2881f216f1dce9d55e5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.section-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-section-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
         <?php $__env->slot('title', null, []); ?> <?php echo e($title); ?> <?php $__env->endSlot(); ?>
         <?php $__env->slot('description', null, []); ?> <?php echo e($description); ?> <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal051a28c9acc4e2881f216f1dce9d55e5)): ?>
<?php $attributes = $__attributesOriginal051a28c9acc4e2881f216f1dce9d55e5; ?>
<?php unset($__attributesOriginal051a28c9acc4e2881f216f1dce9d55e5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal051a28c9acc4e2881f216f1dce9d55e5)): ?>
<?php $component = $__componentOriginal051a28c9acc4e2881f216f1dce9d55e5; ?>
<?php unset($__componentOriginal051a28c9acc4e2881f216f1dce9d55e5); ?>
<?php endif; ?>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
            <?php echo e($content); ?>

        </div>
    </div>
</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/vendor/jetstream/components/action-section.blade.php ENDPATH**/ ?>