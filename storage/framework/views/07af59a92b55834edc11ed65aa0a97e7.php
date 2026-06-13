<?php if(session('success')): ?>
    <div class="w-full my-4">
        <div class="w-full flex-none md:flex justify-center items-center gap-1 mx-auto text-center bg-green-700 text-white px-1 py-2 shadow-md rounded-sm">
            <div class="font-bold uppercase">
                <?php echo e(__(session('success'))); ?>

            </div>
            <?php if(session('success_sub')): ?>
                <div class="font-normal uppercase">
                    <?php echo e(__(session('success_sub'))); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="w-full my-4">
        <div class="w-full flex-none md:flex justify-center items-center gap-1 mx-auto text-center bg-red-700 text-white px-1 py-2 shadow-md rounded-sm">
            <div class="font-bold uppercase">
                <?php echo e(__(session('error'))); ?>

            </div>
            <?php if(session('error_sub')): ?>
                <div class="font-normal uppercase">
                    <?php echo e(__(session('error_sub'))); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if(session('info')): ?>
    <div class="w-full my-4">
        <div class="w-full flex-none md:flex justify-center items-center gap-1 mx-auto text-center bg-blue-700 text-white px-1 py-2 shadow-md rounded-sm">
            <div class="font-bold uppercase">
                <?php echo e(__(session('info'))); ?>

            </div>
            <?php if(session('info_sub')): ?>
                <div class="font-normal uppercase">
                    <?php echo e(__(session('info_sub'))); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /home/proev836/public_html_sistemas/app_eventos/resources/views/_includes/alertas_exibir_compra.blade.php ENDPATH**/ ?>