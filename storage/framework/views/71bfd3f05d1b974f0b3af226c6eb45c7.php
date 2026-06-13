<div class="<?php echo e($cardClasses); ?>">
    <?php if($header): ?>
        <?php echo e($header); ?>

    <?php elseif($title || $action): ?>
        <div class="<?php echo e($headerClasses); ?>">
            <h3 class="font-medium whitespace-normal text-md text-secondary-700 dark:text-secondary-400"><?php echo e($title); ?></h3>

            <?php if($action): ?>
                <?php echo e($action); ?>

            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div <?php echo e($attributes->merge(['class' => "{$padding} text-secondary-700 grow dark:text-secondary-400"])); ?>>
        <?php echo e($slot); ?>

    </div>

    <?php if($footer): ?>
        <div class="<?php echo e($footerClasses); ?>">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/vendor/wireui/components/card.blade.php ENDPATH**/ ?>