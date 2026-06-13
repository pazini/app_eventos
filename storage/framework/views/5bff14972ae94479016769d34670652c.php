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
<?php if (isset($component)) { $__componentOriginalf7edb4a44b5c7acf7af8f344751b8ab2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf7edb4a44b5c7acf7af8f344751b8ab2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'wireui::components.icons.outline.chevron-down','data' => ['class' => $class]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('wireui::icons.outline.chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($class)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf7edb4a44b5c7acf7af8f344751b8ab2)): ?>
<?php $attributes = $__attributesOriginalf7edb4a44b5c7acf7af8f344751b8ab2; ?>
<?php unset($__attributesOriginalf7edb4a44b5c7acf7af8f344751b8ab2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf7edb4a44b5c7acf7af8f344751b8ab2)): ?>
<?php $component = $__componentOriginalf7edb4a44b5c7acf7af8f344751b8ab2; ?>
<?php unset($__componentOriginalf7edb4a44b5c7acf7af8f344751b8ab2); ?>
<?php endif; ?><?php /**PATH /home/proev836/public_html_sistemas/app_eventos/storage/framework/views/5d224cc8fbe8546763014c9a82a243e6.blade.php ENDPATH**/ ?>