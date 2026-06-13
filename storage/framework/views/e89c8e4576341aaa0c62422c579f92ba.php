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
<?php if (isset($component)) { $__componentOriginal169a86cf8c6b53ff27648274cbfad002 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal169a86cf8c6b53ff27648274cbfad002 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'wireui::components.icons.solid.check-circle','data' => ['class' => $class]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('wireui::icons.solid.check-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($class)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal169a86cf8c6b53ff27648274cbfad002)): ?>
<?php $attributes = $__attributesOriginal169a86cf8c6b53ff27648274cbfad002; ?>
<?php unset($__attributesOriginal169a86cf8c6b53ff27648274cbfad002); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal169a86cf8c6b53ff27648274cbfad002)): ?>
<?php $component = $__componentOriginal169a86cf8c6b53ff27648274cbfad002; ?>
<?php unset($__componentOriginal169a86cf8c6b53ff27648274cbfad002); ?>
<?php endif; ?><?php /**PATH /home/proev836/public_html_sistemas/app_eventos/storage/framework/views/3307f19056f9912394ced0321e53b3a3.blade.php ENDPATH**/ ?>