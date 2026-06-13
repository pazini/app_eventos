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
<?php if (isset($component)) { $__componentOriginala4392271d709cfb3b2f39f73f538d4a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala4392271d709cfb3b2f39f73f538d4a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'wireui::components.icons.outline.ticket','data' => ['class' => $class]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('wireui::icons.outline.ticket'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($class)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala4392271d709cfb3b2f39f73f538d4a5)): ?>
<?php $attributes = $__attributesOriginala4392271d709cfb3b2f39f73f538d4a5; ?>
<?php unset($__attributesOriginala4392271d709cfb3b2f39f73f538d4a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala4392271d709cfb3b2f39f73f538d4a5)): ?>
<?php $component = $__componentOriginala4392271d709cfb3b2f39f73f538d4a5; ?>
<?php unset($__componentOriginala4392271d709cfb3b2f39f73f538d4a5); ?>
<?php endif; ?><?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/storage/framework/views/0d4fa9756305388f8366b6db7712eb9a.blade.php ENDPATH**/ ?>