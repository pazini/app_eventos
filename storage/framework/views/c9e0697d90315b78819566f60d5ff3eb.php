<?php if(session('forma_pagamento_success')): ?>
    <div class="w-full my-4">
        <div class="w-full mx-auto text-center bg-green-700 text-white px-2 py-2 shadow-lg rounded-md">
            <div class="font-bold uppercase">
                <?php echo e(__(session('forma_pagamento_success'))); ?>

            </div>
            <?php if(session('forma_pagamento_success_sub')): ?>
                <div class="font-normal uppercase">
                    <?php echo e(__(session('forma_pagamento_success_sub'))); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if(session('forma_pagamento_error')): ?>
    <div class="w-full my-4">
        <div class="mx-auto text-center bg-red-700 text-white px-2 py-2 shadow-lg rounded-md">
            <div class="w-full">
                <div class="font-bold uppercase">
                    <?php echo e(__(session('forma_pagamento_error'))); ?>

                </div>
                <div class="uppercase">
                    <?php if(session('forma_pagamento_error_sub')): ?>
                        <span class="font-normal"><?php echo e(__(session('forma_pagamento_error_sub'))); ?></span>    </span>
                    <?php endif; ?>
                    <span class="font-light"><?php echo e(now()->format('d/m/Y H:i:s')); ?></span>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(session('forma_pagamento_info')): ?>
    <div class="w-full my-4">
        <div class="w-full mx-auto text-center bg-blue-700 text-white px-2 py-2 shadow-lg rounded-md">
            <div class="font-bold uppercase">
                <?php echo e(__(session('forma_pagamento_info'))); ?>

            </div>
            <?php if(session('forma_pagamento_info_sub')): ?>
                <div class="font-normal uppercase">
                    <?php echo e(__(session('forma_pagamento_info_sub'))); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if(session('forma_pagamento_warning')): ?>
    <div class="w-full my-4">
        <div class="w-full mx-auto text-center bg-yellow-700 text-white px-2 py-2 shadow-lg rounded-md">
            <div class="font-bold uppercase">ATENÇÃO</div>
            <div class="font-normal uppercase">
                <?php echo e(__(session('forma_pagamento_warning'))); ?>

            </div>
            <?php if(session('forma_pagamento_warning_sub')): ?>
                <div class="font-light uppercase">
                    <?php echo e(__(session('forma_pagamento_warning_sub'))); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if(session('forma_pagamento_pix_alert')): ?>
    <div class="w-full my-4">
        <div class="w-full flex justify-center items-center gap-1 mx-auto text-center bg-blue-700 text-white px-2 py-2 shadow-lg rounded-md">
            <div class="font-bold uppercase">
                <?php echo e(__(session('forma_pagamento_pix_alert'))); ?>

            </div>
            <?php if(session('forma_pagamento_pix_alert_sub')): ?>
                <div class="font-normal uppercase">
                    <?php echo e(__(session('forma_pagamento_pix_alert_sub'))); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/_includes/alertas_forma_pagamento.blade.php ENDPATH**/ ?>