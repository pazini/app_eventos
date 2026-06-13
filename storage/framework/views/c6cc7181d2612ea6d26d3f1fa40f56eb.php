<label <?php echo e($attributes->class([
        'block text-base font-light uppercase',
        'text-negative-600'  => $hasError,
        'opacity-60'         => $attributes->get('disabled'),
        'text-black dark:text-gray-400' => !$hasError,
    ])); ?>>
    <?php echo e($label ?? $slot); ?>

</label>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/vendor/wireui/components/label.blade.php ENDPATH**/ ?>