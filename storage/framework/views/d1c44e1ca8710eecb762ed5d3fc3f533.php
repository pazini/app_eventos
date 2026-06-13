<?php extract(collect($attributes->getAttributes())->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['class']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['class']); ?>
<?php foreach (array_filter((['class']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<?php if (isset($component)) { $__componentOriginal621a27ff0cf4f77a67c46c6b2be573c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal621a27ff0cf4f77a67c46c6b2be573c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'wireui::components.icons.outline.ban','data' => ['class' => $class]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('wireui::icons.outline.ban'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($class)]); ?>

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
<?php endif; ?><?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/storage/framework/views/16cb59e1e7d01a8f3df4d3c9a4f4f108.blade.php ENDPATH**/ ?>