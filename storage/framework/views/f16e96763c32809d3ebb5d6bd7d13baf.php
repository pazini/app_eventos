<div>
    <?php $__currentLoopData = $totalizador; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totalizador_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex justify-between gap-2">
            
            <div><?php echo e(toMoney($totalizador_item->total_amount,'R$ ')); ?></div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/faturamento/calcula-vendidos.blade.php ENDPATH**/ ?>