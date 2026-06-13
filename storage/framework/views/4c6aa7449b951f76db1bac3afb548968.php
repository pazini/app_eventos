<div class="block" <?php echo e($attributes ?? false); ?>>
    <?php if($errors->any()): ?>
        <div class="<?php echo e(setClass('divContentErros')); ?>">
            <?php if(count($errors->all()) > 1): ?>
                <div class="flex justify-center w-full mx-auto bg-red-600 text-white p-2 my-1 rounded-none shadow-md">
                    <div class="w-full m-0 px-2 text-white uppercase"><?php echo e(count($errors->all())); ?> erros foram encontrados</p>
                </div>
            <?php else: ?>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex w-full mx-auto bg-red-600 text-white p-2 my-1 rounded shadow-md">
                        <p class="w-full m-0 px-2 text-sm text-white uppercase"><?php echo e($error); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    
    <?php if(session('errorLw')): ?>
    <div class="w-full mx-auto text-center bg-red-700 text-white px-1 py-2 rounded-sm shadow-md">
        <h3 class="text-xl font-bold uppercase">
            <?php echo e(__(session('errorLw'))); ?>

        </h3>
        <?php if(session('error_sub')): ?>
        <h5 class="text-sm font-normal py-0 uppercase">
            <?php echo e(__(session('error_sub_lw'))); ?>

        </h5>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if(session('successLw')): ?>
    <div class="w-full mx-auto text-center bg-green-700 text-white px-1 py-2 rounded-sm shadow-md">
        <h3 class="text-xl font-bold uppercase">
            <?php echo e(__(session('successLw'))); ?>

        </h3>
        <?php if(session('success_sub_lw')): ?>
        <h5 class="text-sm font-normal py-0 uppercase">
            <?php echo e(__(session('success_sub_lw'))); ?>

        </h5>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if(session('error')): ?>
    <div class="w-full mx-auto text-center bg-red-700 text-white px-1 py-2 rounded-sm shadow-md">
        <h3 class="text-xl font-bold uppercase">
            <?php echo e(__(session('error'))); ?>

        </h3>
        <?php if(session('error_sub')): ?>
        <h5 class="text-sm font-normal py-0 uppercase">
            <?php echo e(__(session('error_sub'))); ?>

        </h5>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if(session('success')): ?>
    <div class="w-full mx-auto text-center bg-green-700 text-white px-1 py-2 rounded-sm shadow-md">
        <h3 class="text-xl font-bold uppercase">
            <?php echo e(__(session('success'))); ?>

        </h3>
        <?php if(session('success_sub')): ?>
            <h5 class="text-sm font-normal py-0 uppercase">
                <?php echo e(__(session('success_sub'))); ?>

            </h5>
        <?php endif; ?>
        <?php if(session('success_sub_lc')): ?>
            <h5 class="text-sm font-normal py-0">
                <?php echo e(__(session('success_sub_lc'))); ?>

            </h5>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if(session('warning')): ?>
        <div class="w-full mx-auto text-center bg-yellow-500 text-white px-1 py-2 rounded-sm shadow-md">
            <h3 class="text-xl font-bold uppercase">
                <?php echo e(__(session('warning'))); ?>

            </h3>
            <?php if(session('warning_sub')): ?>
            <h5 class="text-sm font-normal py-0 uppercase">
                <?php echo e(__(session('warning_sub'))); ?>

            </h5>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    
    <?php if(session('msg')): ?>
    <div class="w-full mx-auto text-center px-1 py-2 bg-gray-700 text-white">
        <h3 class="text-xl font-bold uppercase">
            <?php echo e(__(session('msg'))); ?>

        </h3>
        <?php if(session('msg_sub')): ?>
        <h5 class="text-sm font-normal py-0 uppercase">
            <?php echo e(__(session('msg_sub'))); ?>

        </h5>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if(session('status')): ?>
    <div class="w-full mx-auto text-center px-1 py-2 bg-gray-700 text-white">
        <h3 class="text-xl font-bold uppercase">
            <?php echo e(__(session('status'))); ?>

        </h3>
        <?php if(session('status_sub')): ?>
        <h5 class="text-sm font-normal py-0 uppercase">
            <?php echo e(__(session('status_sub'))); ?>

        </h5>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/_includes/alertas.blade.php ENDPATH**/ ?>