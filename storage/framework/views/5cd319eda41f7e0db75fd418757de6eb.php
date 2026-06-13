<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['dark' => false]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['dark' => false]); ?>
<?php foreach (array_filter((['dark' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<a href="/" class="px-4 block">
    <?php
        // Prioridade: Logo do customer (se detectado pelo domínio), senão logo do app
        // Para páginas de autenticação, usa logo squared
        $customerLogoUrl = customerLogo();
        $logoUrl = $customerLogoUrl ?: ($dark ? appLogoDark(false, null, true) : appLogo(false, null, true));
    ?>
    <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e(appName()); ?>" style="min-height: 70px; max-width:300px; height:auto;">
</a>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/vendor/jetstream/components/authentication-card-logo.blade.php ENDPATH**/ ?>