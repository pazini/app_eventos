<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'ProEventPay')); ?></title>

        <!-- White Label: Favicon dinâmico -->
        <link rel="icon" type="image/x-icon" href="<?php echo e(appFavicon(true)); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=albert-sans:100,200,300,400,500,600,700,800,900|inter:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <?php echo \Livewire\Livewire::styles(); ?>


        <script >window.Wireui = {hook(hook, callback) {window.addEventListener(`wireui:${hook}`, () => callback())},dispatchHook(hook) {window.dispatchEvent(new Event(`wireui:${hook}`))}}</script>
<script src="https://eventos.igrejanovoscomecos.com.br/wireui/assets/scripts?id=be97ebae74d62aa4c86689a6528b707f" defer ></script>

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    </head>
    <body class="bg-white">

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

            <div class="flex-1">

                <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('navigation.navigation-menu-pep-guest')->html();
} elseif ($_instance->childHasBeenRendered('llCinsc')) {
    $componentId = $_instance->getRenderedChildComponentId('llCinsc');
    $componentTag = $_instance->getRenderedChildComponentTagName('llCinsc');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('llCinsc');
} else {
    $response = \Livewire\Livewire::mount('navigation.navigation-menu-pep-guest');
    $html = $response->html();
    $_instance->logRenderedChild('llCinsc', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>

                <main>
                    <?php echo e($slot); ?>

                </main>
            </div>

            <footer class="mt-4 flex justify-center items-center gap-4 px-4 py-4 bg-gray-50 border-t border-gray-200">
                <div class="text-center text-xs uppercase tracking-widest text-gray-400"><?php echo e(appName()); ?> &middot; Copyright &copy; <?php echo e(now()->format('Y')); ?> &middot; Todos os direitos reservados</div>
            </footer>

        </div>

        <?php echo $__env->yieldPushContent('modals'); ?>

        <?php echo \Livewire\Livewire::scripts(); ?>

        

    </body>
</html>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/layouts/app-pep-guest.blade.php ENDPATH**/ ?>