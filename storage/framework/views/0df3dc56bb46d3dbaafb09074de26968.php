<?php extract(collect($attributes->getAttributes())->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['class','wire:loading.remove']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['class','wire:loading.remove']); ?>
<?php foreach (array_filter((['class','wire:loading.remove']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<?php if (isset($component)) { $__componentOriginal621a27ff0cf4f77a67c46c6b2be573c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal621a27ff0cf4f77a67c46c6b2be573c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'wireui::components.icons.outline.ban','data' => ['class' => $class,'wire:loading.remove' => $wireLoadingRemove]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('wireui::icons.outline.ban'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($class),'wire:loading.remove' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wireLoadingRemove)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal621a27ff0cf4f77a67c46c6b2be573c3)): ?>
<?php $attributes = $__attributesOriginal621a27ff0cf4f77a67c46c6b2be573c3; ?>
<?php unset($__attributesOriginal621a27ff0cf4f77a67c46c6b2be573c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal621a27ff0cf4f77a67c46c6b2be573c3)): ?>
<?php $component = $__componentOriginal621a27ff0cf4f77a67c46c6b2be573c3; ?>
<?php unset($__componentOriginal621a27ff0cf4f77a67c46c6b2be573c3); ?>
<?php endif; ?><?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/storage/framework/views/c0015c926b55846a6fc597a122ba4a24.blade.php ENDPATH**/ ?>