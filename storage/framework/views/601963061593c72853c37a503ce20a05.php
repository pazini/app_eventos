<tr>
<td class="header">
<a href="<?php echo e($url); ?>" style="display: inline-block;">
<?php if(trim($slot) === 'ProEventPay' || trim($slot) === appName()): ?>
<img src="<?php echo e(appUrl()); ?>/<?php echo e(appLogo()); ?>" class="logo" alt="<?php echo e(appName()); ?>">
<?php else: ?>
<?php echo e($slot); ?>

<?php endif; ?>
</a>
</td>
</tr>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/vendor/mail/html/header.blade.php ENDPATH**/ ?>