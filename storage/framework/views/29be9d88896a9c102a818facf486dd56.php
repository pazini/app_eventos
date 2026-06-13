<?php if (isset($component)) { $__componentOriginal13b0d63336832e1ea9eb9c9bc9410b18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal13b0d63336832e1ea9eb9c9bc9410b18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.form-section','data' => ['submit' => 'updatePassword']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-form-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['submit' => 'updatePassword']); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('Update Password')); ?>

     <?php $__env->endSlot(); ?>

     <?php $__env->slot('description', null, []); ?> 
        <?php echo e(__('Ensure your account is using a long, random password to stay secure.')); ?>

     <?php $__env->endSlot(); ?>

     <?php $__env->slot('form', null, []); ?> 
        <div class="col-span-6 sm:col-span-4">
            <?php if (isset($component)) { $__componentOriginal859cd0e3ec07088107cb57b399b2d110 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal859cd0e3ec07088107cb57b399b2d110 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.label','data' => ['for' => 'current_password','value' => ''.e(__('Current Password')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'current_password','value' => ''.e(__('Current Password')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal859cd0e3ec07088107cb57b399b2d110)): ?>
<?php $attributes = $__attributesOriginal859cd0e3ec07088107cb57b399b2d110; ?>
<?php unset($__attributesOriginal859cd0e3ec07088107cb57b399b2d110); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal859cd0e3ec07088107cb57b399b2d110)): ?>
<?php $component = $__componentOriginal859cd0e3ec07088107cb57b399b2d110; ?>
<?php unset($__componentOriginal859cd0e3ec07088107cb57b399b2d110); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal9145aada0d147d1c029b2cfba77fb9a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.input','data' => ['id' => 'current_password','type' => 'password','class' => 'mt-1 block w-full','wire:model.defer' => 'state.current_password','autocomplete' => 'current-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'current_password','type' => 'password','class' => 'mt-1 block w-full','wire:model.defer' => 'state.current_password','autocomplete' => 'current-password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0)): ?>
<?php $attributes = $__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0; ?>
<?php unset($__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9145aada0d147d1c029b2cfba77fb9a0)): ?>
<?php $component = $__componentOriginal9145aada0d147d1c029b2cfba77fb9a0; ?>
<?php unset($__componentOriginal9145aada0d147d1c029b2cfba77fb9a0); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal718c6df7fe2936e053a80e743205e7b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal718c6df7fe2936e053a80e743205e7b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.input-error','data' => ['for' => 'current_password','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'current_password','class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal718c6df7fe2936e053a80e743205e7b3)): ?>
<?php $attributes = $__attributesOriginal718c6df7fe2936e053a80e743205e7b3; ?>
<?php unset($__attributesOriginal718c6df7fe2936e053a80e743205e7b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal718c6df7fe2936e053a80e743205e7b3)): ?>
<?php $component = $__componentOriginal718c6df7fe2936e053a80e743205e7b3; ?>
<?php unset($__componentOriginal718c6df7fe2936e053a80e743205e7b3); ?>
<?php endif; ?>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <?php if (isset($component)) { $__componentOriginal859cd0e3ec07088107cb57b399b2d110 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal859cd0e3ec07088107cb57b399b2d110 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.label','data' => ['for' => 'password','value' => ''.e(__('New Password')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'password','value' => ''.e(__('New Password')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal859cd0e3ec07088107cb57b399b2d110)): ?>
<?php $attributes = $__attributesOriginal859cd0e3ec07088107cb57b399b2d110; ?>
<?php unset($__attributesOriginal859cd0e3ec07088107cb57b399b2d110); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal859cd0e3ec07088107cb57b399b2d110)): ?>
<?php $component = $__componentOriginal859cd0e3ec07088107cb57b399b2d110; ?>
<?php unset($__componentOriginal859cd0e3ec07088107cb57b399b2d110); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal9145aada0d147d1c029b2cfba77fb9a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.input','data' => ['id' => 'password','type' => 'password','class' => 'mt-1 block w-full','wire:model.defer' => 'state.password','autocomplete' => 'new-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'password','type' => 'password','class' => 'mt-1 block w-full','wire:model.defer' => 'state.password','autocomplete' => 'new-password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0)): ?>
<?php $attributes = $__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0; ?>
<?php unset($__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9145aada0d147d1c029b2cfba77fb9a0)): ?>
<?php $component = $__componentOriginal9145aada0d147d1c029b2cfba77fb9a0; ?>
<?php unset($__componentOriginal9145aada0d147d1c029b2cfba77fb9a0); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal718c6df7fe2936e053a80e743205e7b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal718c6df7fe2936e053a80e743205e7b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.input-error','data' => ['for' => 'password','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'password','class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal718c6df7fe2936e053a80e743205e7b3)): ?>
<?php $attributes = $__attributesOriginal718c6df7fe2936e053a80e743205e7b3; ?>
<?php unset($__attributesOriginal718c6df7fe2936e053a80e743205e7b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal718c6df7fe2936e053a80e743205e7b3)): ?>
<?php $component = $__componentOriginal718c6df7fe2936e053a80e743205e7b3; ?>
<?php unset($__componentOriginal718c6df7fe2936e053a80e743205e7b3); ?>
<?php endif; ?>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <?php if (isset($component)) { $__componentOriginal859cd0e3ec07088107cb57b399b2d110 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal859cd0e3ec07088107cb57b399b2d110 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.label','data' => ['for' => 'password_confirmation','value' => ''.e(__('Confirm Password')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'password_confirmation','value' => ''.e(__('Confirm Password')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal859cd0e3ec07088107cb57b399b2d110)): ?>
<?php $attributes = $__attributesOriginal859cd0e3ec07088107cb57b399b2d110; ?>
<?php unset($__attributesOriginal859cd0e3ec07088107cb57b399b2d110); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal859cd0e3ec07088107cb57b399b2d110)): ?>
<?php $component = $__componentOriginal859cd0e3ec07088107cb57b399b2d110; ?>
<?php unset($__componentOriginal859cd0e3ec07088107cb57b399b2d110); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal9145aada0d147d1c029b2cfba77fb9a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.input','data' => ['id' => 'password_confirmation','type' => 'password','class' => 'mt-1 block w-full','wire:model.defer' => 'state.password_confirmation','autocomplete' => 'new-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'password_confirmation','type' => 'password','class' => 'mt-1 block w-full','wire:model.defer' => 'state.password_confirmation','autocomplete' => 'new-password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0)): ?>
<?php $attributes = $__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0; ?>
<?php unset($__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9145aada0d147d1c029b2cfba77fb9a0)): ?>
<?php $component = $__componentOriginal9145aada0d147d1c029b2cfba77fb9a0; ?>
<?php unset($__componentOriginal9145aada0d147d1c029b2cfba77fb9a0); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal718c6df7fe2936e053a80e743205e7b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal718c6df7fe2936e053a80e743205e7b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.input-error','data' => ['for' => 'password_confirmation','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'password_confirmation','class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal718c6df7fe2936e053a80e743205e7b3)): ?>
<?php $attributes = $__attributesOriginal718c6df7fe2936e053a80e743205e7b3; ?>
<?php unset($__attributesOriginal718c6df7fe2936e053a80e743205e7b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal718c6df7fe2936e053a80e743205e7b3)): ?>
<?php $component = $__componentOriginal718c6df7fe2936e053a80e743205e7b3; ?>
<?php unset($__componentOriginal718c6df7fe2936e053a80e743205e7b3); ?>
<?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

     <?php $__env->slot('actions', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginal56911401d9b528a1a2a72bcc69ad7e51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56911401d9b528a1a2a72bcc69ad7e51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.action-message','data' => ['class' => 'mr-3','on' => 'saved']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-action-message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mr-3','on' => 'saved']); ?>
            <?php echo e(__('Saved.')); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56911401d9b528a1a2a72bcc69ad7e51)): ?>
<?php $attributes = $__attributesOriginal56911401d9b528a1a2a72bcc69ad7e51; ?>
<?php unset($__attributesOriginal56911401d9b528a1a2a72bcc69ad7e51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56911401d9b528a1a2a72bcc69ad7e51)): ?>
<?php $component = $__componentOriginal56911401d9b528a1a2a72bcc69ad7e51; ?>
<?php unset($__componentOriginal56911401d9b528a1a2a72bcc69ad7e51); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal9132372e292e016fc877b416eeae2e71 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9132372e292e016fc877b416eeae2e71 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php echo e(__('Save')); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9132372e292e016fc877b416eeae2e71)): ?>
<?php $attributes = $__attributesOriginal9132372e292e016fc877b416eeae2e71; ?>
<?php unset($__attributesOriginal9132372e292e016fc877b416eeae2e71); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9132372e292e016fc877b416eeae2e71)): ?>
<?php $component = $__componentOriginal9132372e292e016fc877b416eeae2e71; ?>
<?php unset($__componentOriginal9132372e292e016fc877b416eeae2e71); ?>
<?php endif; ?>
     <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal13b0d63336832e1ea9eb9c9bc9410b18)): ?>
<?php $attributes = $__attributesOriginal13b0d63336832e1ea9eb9c9bc9410b18; ?>
<?php unset($__attributesOriginal13b0d63336832e1ea9eb9c9bc9410b18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal13b0d63336832e1ea9eb9c9bc9410b18)): ?>
<?php $component = $__componentOriginal13b0d63336832e1ea9eb9c9bc9410b18; ?>
<?php unset($__componentOriginal13b0d63336832e1ea9eb9c9bc9410b18); ?>
<?php endif; ?>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/profile/update-password-form.blade.php ENDPATH**/ ?>