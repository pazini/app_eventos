<?php if (isset($component)) { $__componentOriginal5f7481395b96b9f830e0adcf51b24ba2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5f7481395b96b9f830e0adcf51b24ba2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.action-section','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-action-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('Browser Sessions')); ?>

     <?php $__env->endSlot(); ?>

     <?php $__env->slot('description', null, []); ?> 
        <?php echo e(__('Manage and log out your active sessions on other browsers and devices.')); ?>

     <?php $__env->endSlot(); ?>

     <?php $__env->slot('content', null, []); ?> 
        <div class="max-w-xl text-sm text-gray-600">
            <?php echo e(__('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.')); ?>

        </div>

        <?php if(count($this->sessions) > 0): ?>
            <div class="mt-5 space-y-6">
                <!-- Other Browser Sessions -->
                <?php $__currentLoopData = $this->sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center">
                        <div>
                            <?php if($session->agent->isDesktop()): ?>
                                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-gray-500">
                                    <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-gray-500">
                                    <path d="M0 0h24v24H0z" stroke="none"></path><rect x="7" y="4" width="10" height="16" rx="1"></rect><path d="M11 5h2M12 17v.01"></path>
                                </svg>
                            <?php endif; ?>
                        </div>

                        <div class="ml-3">
                            <div class="text-sm text-gray-600">
                                <?php echo e($session->agent->platform() ? $session->agent->platform() : 'Unknown'); ?> - <?php echo e($session->agent->browser() ? $session->agent->browser() : 'Unknown'); ?>

                            </div>

                            <div>
                                <div class="text-xs text-gray-500">
                                    <?php echo e($session->ip_address); ?>,

                                    <?php if($session->is_current_device): ?>
                                        <span class="text-green-500 font-semibold"><?php echo e(__('This device')); ?></span>
                                    <?php else: ?>
                                        <?php echo e(__('Last active')); ?> <?php echo e($session->last_active); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <div class="flex items-center mt-5">
            <?php if (isset($component)) { $__componentOriginal9132372e292e016fc877b416eeae2e71 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9132372e292e016fc877b416eeae2e71 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.button','data' => ['wire:click' => 'confirmLogout','wire:loading.attr' => 'disabled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'confirmLogout','wire:loading.attr' => 'disabled']); ?>
                <?php echo e(__('Log Out Other Browser Sessions')); ?>

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

            <?php if (isset($component)) { $__componentOriginal56911401d9b528a1a2a72bcc69ad7e51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56911401d9b528a1a2a72bcc69ad7e51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.action-message','data' => ['class' => 'ml-3','on' => 'loggedOut']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-action-message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ml-3','on' => 'loggedOut']); ?>
                <?php echo e(__('Done.')); ?>

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
        </div>

        <!-- Log Out Other Devices Confirmation Modal -->
        <?php if (isset($component)) { $__componentOriginalb7c3d02ad0a9b1daf558a84e1ecad045 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb7c3d02ad0a9b1daf558a84e1ecad045 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.dialog-modal','data' => ['wire:model' => 'confirmingLogout']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-dialog-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'confirmingLogout']); ?>
             <?php $__env->slot('title', null, []); ?> 
                <?php echo e(__('Log Out Other Browser Sessions')); ?>

             <?php $__env->endSlot(); ?>

             <?php $__env->slot('content', null, []); ?> 
                <?php echo e(__('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.')); ?>


                <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <?php if (isset($component)) { $__componentOriginal9145aada0d147d1c029b2cfba77fb9a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9145aada0d147d1c029b2cfba77fb9a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.input','data' => ['type' => 'password','class' => 'mt-1 block w-3/4','placeholder' => ''.e(__('Password')).'','xRef' => 'password','wire:model.defer' => 'password','wire:keydown.enter' => 'logoutOtherBrowserSessions']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'password','class' => 'mt-1 block w-3/4','placeholder' => ''.e(__('Password')).'','x-ref' => 'password','wire:model.defer' => 'password','wire:keydown.enter' => 'logoutOtherBrowserSessions']); ?>
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
             <?php $__env->endSlot(); ?>

             <?php $__env->slot('footer', null, []); ?> 
                <?php if (isset($component)) { $__componentOriginal6909d696c10e2553c022c9e24b4bbb5d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6909d696c10e2553c022c9e24b4bbb5d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.secondary-button','data' => ['wire:click' => '$toggle(\'confirmingLogout\')','wire:loading.attr' => 'disabled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => '$toggle(\'confirmingLogout\')','wire:loading.attr' => 'disabled']); ?>
                    <?php echo e(__('Cancel')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6909d696c10e2553c022c9e24b4bbb5d)): ?>
<?php $attributes = $__attributesOriginal6909d696c10e2553c022c9e24b4bbb5d; ?>
<?php unset($__attributesOriginal6909d696c10e2553c022c9e24b4bbb5d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6909d696c10e2553c022c9e24b4bbb5d)): ?>
<?php $component = $__componentOriginal6909d696c10e2553c022c9e24b4bbb5d; ?>
<?php unset($__componentOriginal6909d696c10e2553c022c9e24b4bbb5d); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal9132372e292e016fc877b416eeae2e71 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9132372e292e016fc877b416eeae2e71 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.button','data' => ['class' => 'ml-3','wire:click' => 'logoutOtherBrowserSessions','wire:loading.attr' => 'disabled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ml-3','wire:click' => 'logoutOtherBrowserSessions','wire:loading.attr' => 'disabled']); ?>
                    <?php echo e(__('Log Out Other Browser Sessions')); ?>

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
<?php if (isset($__attributesOriginalb7c3d02ad0a9b1daf558a84e1ecad045)): ?>
<?php $attributes = $__attributesOriginalb7c3d02ad0a9b1daf558a84e1ecad045; ?>
<?php unset($__attributesOriginalb7c3d02ad0a9b1daf558a84e1ecad045); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb7c3d02ad0a9b1daf558a84e1ecad045)): ?>
<?php $component = $__componentOriginalb7c3d02ad0a9b1daf558a84e1ecad045; ?>
<?php unset($__componentOriginalb7c3d02ad0a9b1daf558a84e1ecad045); ?>
<?php endif; ?>
     <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5f7481395b96b9f830e0adcf51b24ba2)): ?>
<?php $attributes = $__attributesOriginal5f7481395b96b9f830e0adcf51b24ba2; ?>
<?php unset($__attributesOriginal5f7481395b96b9f830e0adcf51b24ba2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5f7481395b96b9f830e0adcf51b24ba2)): ?>
<?php $component = $__componentOriginal5f7481395b96b9f830e0adcf51b24ba2; ?>
<?php unset($__componentOriginal5f7481395b96b9f830e0adcf51b24ba2); ?>
<?php endif; ?>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/profile/logout-other-browser-sessions-form.blade.php ENDPATH**/ ?>