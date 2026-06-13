<?php $__env->startComponent('mail::layout'); ?>
    
    <?php $__env->slot('header'); ?>
        <?php $__env->startComponent('mail::header', ['url' => config('app.url')]); ?>
            <div style="text-transform: uppercase;"><?php echo e(config('app.name')); ?></div>
        <?php echo $__env->renderComponent(); ?>
    <?php $__env->endSlot(); ?>

    
    <?php echo e($slot); ?>


    
    <?php if(isset($subcopy)): ?>
        <?php $__env->slot('subcopy'); ?>
            <?php $__env->startComponent('mail::subcopy'); ?>
                <?php echo e($subcopy); ?>

            <?php echo $__env->renderComponent(); ?>
        <?php $__env->endSlot(); ?>
    <?php endif; ?>

    
    <?php $__env->slot('footer'); ?>
        <?php $__env->startComponent('mail::footer'); ?>
            Copyright © <?php echo e(now()->format('Y')); ?> - <?php echo e(config('app.name')); ?> . ' - ' . <?php echo app('translator')->get('Todos os direitos reservados.'); ?></div>
        <?php echo $__env->renderComponent(); ?>
    <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/vendor/mail/text/message.blade.php ENDPATH**/ ?>