<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        
        <title><?php echo e(config('app.name', appName())); ?> | <?php echo e(appName()); ?></title>

        
        <meta name="description" content="<?php echo e(appMeta('description')); ?>">
        <meta name="keywords" content="<?php echo e(appMeta('keywords')); ?>">

        
        <meta property="og:type" content="website">
        <meta property="og:title" content="<?php echo e(appMeta('title')); ?>">
        <meta property="og:description" content="<?php echo e(appMeta('description')); ?>">
        <meta property="og:image" content="<?php echo e(appMeta('image')); ?>">

        
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo e(appMeta('title')); ?>">
        <meta name="twitter:description" content="<?php echo e(appMeta('description')); ?>">
        <meta name="twitter:image" content="<?php echo e(appMeta('image')); ?>">

        
        <link rel="icon" type="image/x-icon" href="<?php echo e(appFavicon(true)); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=albert-sans:100,200,300,400,500,600,700,800,900|inter:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        
        <?php echo appColorsCss(); ?>


        <!-- Styles -->
        <?php echo \Livewire\Livewire::styles(); ?>

        <script >window.Wireui = {hook(hook, callback) {window.addEventListener(`wireui:${hook}`, () => callback())},dispatchHook(hook) {window.dispatchEvent(new Event(`wireui:${hook}`))}}</script>
<script src="https://painel.igrejanovoscomecos.com.br/wireui/assets/scripts?id=be97ebae74d62aa4c86689a6528b707f" defer ></script>

        <!-- CKEditor 5 (Mesmo do sistema de eventos) -->
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/decoupled-document/ckeditor.js"></script>

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    
    <body class="font-sans antialiased">

        <?php if (isset($component)) { $__componentOriginal10717d162484e57a570d6d2cc4597545 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal10717d162484e57a570d6d2cc4597545 = $attributes; } ?>
<?php $component = WireUi\View\Components\Notifications::resolve(['position' => 'top-right'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('notifications'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Notifications::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal10717d162484e57a570d6d2cc4597545)): ?>
<?php $attributes = $__attributesOriginal10717d162484e57a570d6d2cc4597545; ?>
<?php unset($__attributesOriginal10717d162484e57a570d6d2cc4597545); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal10717d162484e57a570d6d2cc4597545)): ?>
<?php $component = $__componentOriginal10717d162484e57a570d6d2cc4597545; ?>
<?php unset($__componentOriginal10717d162484e57a570d6d2cc4597545); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal2451dfd9df7c01154a83baa9ef28b9d5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2451dfd9df7c01154a83baa9ef28b9d5 = $attributes; } ?>
<?php $component = WireUi\View\Components\Dialog::resolve(['zIndex' => 'z-50','blur' => 'md','align' => 'center'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Dialog::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2451dfd9df7c01154a83baa9ef28b9d5)): ?>
<?php $attributes = $__attributesOriginal2451dfd9df7c01154a83baa9ef28b9d5; ?>
<?php unset($__attributesOriginal2451dfd9df7c01154a83baa9ef28b9d5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2451dfd9df7c01154a83baa9ef28b9d5)): ?>
<?php $component = $__componentOriginal2451dfd9df7c01154a83baa9ef28b9d5; ?>
<?php unset($__componentOriginal2451dfd9df7c01154a83baa9ef28b9d5); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal83705052b1154c3727876992a9432ade = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal83705052b1154c3727876992a9432ade = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.banner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal83705052b1154c3727876992a9432ade)): ?>
<?php $attributes = $__attributesOriginal83705052b1154c3727876992a9432ade; ?>
<?php unset($__attributesOriginal83705052b1154c3727876992a9432ade); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal83705052b1154c3727876992a9432ade)): ?>
<?php $component = $__componentOriginal83705052b1154c3727876992a9432ade; ?>
<?php unset($__componentOriginal83705052b1154c3727876992a9432ade); ?>
<?php endif; ?>

        <div class="flex flex-col min-h-screen">

            <div class="flex-grow px-6">

                <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('navigation.navigation-menu-pep-auth')->html();
} elseif ($_instance->childHasBeenRendered('9OwZX4j')) {
    $componentId = $_instance->getRenderedChildComponentId('9OwZX4j');
    $componentTag = $_instance->getRenderedChildComponentTagName('9OwZX4j');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('9OwZX4j');
} else {
    $response = \Livewire\Livewire::mount('navigation.navigation-menu-pep-auth');
    $html = $response->html();
    $_instance->logRenderedChild('9OwZX4j', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>

                <main >
                    <?php echo e($slot); ?>

                </main>

            </div>

            <footer class="flex justify-center items-center gap-4 px-4 py-4 bg-gray-100 mt-auto">
                <div class="text-center text-sm text-gray-600"><?php echo e(appName()); ?> - Copyright © <?php echo e(now()->format('Y')); ?> - Todos os direitos reservados!</div>
            </footer>

        </div>

        <?php echo $__env->yieldPushContent('modals'); ?>

        <?php echo \Livewire\Livewire::scripts(); ?>

        

    </body>
</html>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/layouts/app-pep-auth.blade.php ENDPATH**/ ?>