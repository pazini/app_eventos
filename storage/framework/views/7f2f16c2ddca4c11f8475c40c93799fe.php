<div>

    <div class="<?php echo e(setClass('divContentHeader')); ?> ">
        <div class="w-full flex justify-between items-center">
            <div>
                <?php echo setLabelHeader('Meu', 'Perfil'); ?>

            </div>
            <div class="p-0">
            </div>
        </div>
    </div>

    <div class="w-full max-w-7xl mx-auto">
        <?php if (isset($component)) { $__componentOriginal522a59481d8bfd4d44478643bc3270fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal522a59481d8bfd4d44478643bc3270fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.validation-errors','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $attributes = $__attributesOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $component = $__componentOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__componentOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
    </div>

    
    <div class="<?php echo e(setClass('divContentTitleDiv')); ?>">

        <div>

            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                <?php if(Laravel\Fortify\Features::canUpdateProfileInformation()): ?>
                    <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('profile.update-profile-information-form')->html();
} elseif ($_instance->childHasBeenRendered('l2118232583-0')) {
    $componentId = $_instance->getRenderedChildComponentId('l2118232583-0');
    $componentTag = $_instance->getRenderedChildComponentTagName('l2118232583-0');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l2118232583-0');
} else {
    $response = \Livewire\Livewire::mount('profile.update-profile-information-form');
    $html = $response->html();
    $_instance->logRenderedChild('l2118232583-0', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>

                    <?php if (isset($component)) { $__componentOriginal09509d6fce29dc8ff0a88f45d775b71d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal09509d6fce29dc8ff0a88f45d775b71d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.section-border','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-section-border'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal09509d6fce29dc8ff0a88f45d775b71d)): ?>
<?php $attributes = $__attributesOriginal09509d6fce29dc8ff0a88f45d775b71d; ?>
<?php unset($__attributesOriginal09509d6fce29dc8ff0a88f45d775b71d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal09509d6fce29dc8ff0a88f45d775b71d)): ?>
<?php $component = $__componentOriginal09509d6fce29dc8ff0a88f45d775b71d; ?>
<?php unset($__componentOriginal09509d6fce29dc8ff0a88f45d775b71d); ?>
<?php endif; ?>
                <?php endif; ?>

                <?php if(Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords())): ?>
                    <div class="mt-10 sm:mt-0">
                        <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('profile.update-password-form')->html();
} elseif ($_instance->childHasBeenRendered('l2118232583-1')) {
    $componentId = $_instance->getRenderedChildComponentId('l2118232583-1');
    $componentTag = $_instance->getRenderedChildComponentTagName('l2118232583-1');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l2118232583-1');
} else {
    $response = \Livewire\Livewire::mount('profile.update-password-form');
    $html = $response->html();
    $_instance->logRenderedChild('l2118232583-1', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
                    </div>
                    <?php if (isset($component)) { $__componentOriginal09509d6fce29dc8ff0a88f45d775b71d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal09509d6fce29dc8ff0a88f45d775b71d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.section-border','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-section-border'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal09509d6fce29dc8ff0a88f45d775b71d)): ?>
<?php $attributes = $__attributesOriginal09509d6fce29dc8ff0a88f45d775b71d; ?>
<?php unset($__attributesOriginal09509d6fce29dc8ff0a88f45d775b71d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal09509d6fce29dc8ff0a88f45d775b71d)): ?>
<?php $component = $__componentOriginal09509d6fce29dc8ff0a88f45d775b71d; ?>
<?php unset($__componentOriginal09509d6fce29dc8ff0a88f45d775b71d); ?>
<?php endif; ?>
                <?php endif; ?>


                <div class="mt-10 sm:mt-0">
                    <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('profile.logout-other-browser-sessions-form')->html();
} elseif ($_instance->childHasBeenRendered('l2118232583-2')) {
    $componentId = $_instance->getRenderedChildComponentId('l2118232583-2');
    $componentTag = $_instance->getRenderedChildComponentTagName('l2118232583-2');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l2118232583-2');
} else {
    $response = \Livewire\Livewire::mount('profile.logout-other-browser-sessions-form');
    $html = $response->html();
    $_instance->logRenderedChild('l2118232583-2', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
                </div>

            </div>
        </div>

    </div>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/user-profile.blade.php ENDPATH**/ ?>