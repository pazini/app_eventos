<?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <p <?php echo e($attributes->merge(['class' => 'mt-2 text-sm text-negative-600'])); ?>>
        <?php echo e($message); ?>

    </p>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
<?php /**PATH /home/proev836/public_html_sistemas/app_eventos/resources/views/vendor/wireui/components/error.blade.php ENDPATH**/ ?>