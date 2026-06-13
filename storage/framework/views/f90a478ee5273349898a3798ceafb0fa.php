<div class="min-h-screen bg-white">

    
    <?php if($isAppVersion): ?>
    <div class="bg-white sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
        <div class="max-w-[480px] mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                
                <a
                    href="<?php echo e(route('app-version-home')); ?>"
                    class="flex items-center gap-1 text-sm text-gray-600 active:text-gray-900 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="font-medium">Voltar</span>
                </a>

                
                <span class="text-sm font-bold text-gray-900 uppercase truncate mx-3 flex-1 text-center">
                    <?php echo e($appCustomerName ?? ''); ?>

                </span>

                
                <?php if($authenticated): ?>
                    <button
                        wire:click="sair"
                        class="px-3 py-2 text-xs border border-red-400 rounded-lg bg-white text-red-500 font-semibold active:bg-red-50 transition-colors flex items-center gap-1.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Sair</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if(!$isAppVersion && !$isCampanhasPage): ?>
    <div class="bg-white sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 md:px-10 py-3 md:py-4">
            <div class="flex items-center justify-between">
                
                <a href="<?php echo e($isCampanhasPage ? route('campanhas-home') : route('eventos-home')); ?>" class="flex items-center gap-3 hover:opacity-80 transition">
                    <img src="<?php echo e(appLogo(true)); ?>" alt="<?php echo e(appName()); ?>" class="h-8 md:h-10">
                </a>

                
                <div class="flex items-center gap-3">
                    <a href="<?php echo e($isCampanhasPage ? route('campanhas-home') : route('eventos-home')); ?>" class="px-4 py-2 text-sm font-medium text-blue-600 bg-white border-2 border-blue-600 hover:bg-blue-50 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class=""><span class="hidden sm:inline">Voltar às </span><?php echo e($isCampanhasPage ? 'Campanhas' : 'Eventos'); ?></span>
                    </a>

                    <?php if($authenticated): ?>
                        <button
                            wire:click="sair"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="hidden sm:inline">Sair</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

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

    <main class="<?php echo e($isAppVersion ? 'px-4 pt-4 pb-8' : 'max-w-4xl mx-auto px-6 md:px-8 pt-6 pb-12'); ?>">

        <?php if(!$authenticated): ?>
            
            <div class="max-w-xl mx-auto">
                <div class="text-center mb-6">
                    <h1 class="<?php echo e($isAppVersion ? 'text-xl' : 'text-2xl md:text-3xl'); ?> font-bold text-gray-900 mb-1">
                        <?php echo e($isCampanhasPage ? 'Minhas Doações' : 'Minhas Compras'); ?>

                    </h1>
                    <p class="text-sm text-gray-600">
                        <?php echo e($isCampanhasPage ? 'Consulte suas adesões e contribuições em campanhas' : 'Consulte suas compras de eventos'); ?>

                    </p>
                </div>

                <?php if($errorMessage): ?>
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm text-red-700"><?php echo e($errorMessage); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                    <form wire:submit.prevent="consultar" class="space-y-4">
                        
                        <div>
                            <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['label' => 'CPF','mask' => '###.###.###-##'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'doc_num','placeholder' => '000.000.000-00']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $attributes = $__attributesOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__attributesOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $component = $__componentOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__componentOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
                        </div>

                        
                        <div>
                            <?php
                                foreach (range(1,31) as $v) { $listaDd[str_pad($v,2,'0',STR_PAD_LEFT)] = str_pad($v,2,'0',STR_PAD_LEFT); }
                                foreach (range(1,12) as $v) { $listaMm[str_pad($v,2,'0',STR_PAD_LEFT)] = str_pad($v,2,'0',STR_PAD_LEFT); }
                                foreach (range(now()->format('Y'), now()->subYears(100)->format('Y')) as $aaaa) { $listaAaaa[$aaaa] = $aaaa; }
                            ?>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Data de Nascimento</label>
                            <div class="grid grid-cols-3 gap-2">
                                <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['placeholder' => 'Dia','options' => $listaDd] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'birth_date_dd']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $attributes = $__attributesOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__attributesOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $component = $__componentOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__componentOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['placeholder' => 'Mês','options' => $listaMm] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'birth_date_mm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $attributes = $__attributesOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__attributesOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $component = $__componentOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__componentOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['placeholder' => 'Ano','options' => $listaAaaa] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'birth_date_aaaa']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $attributes = $__attributesOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__attributesOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $component = $__componentOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__componentOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
                            </div>
                        </div>

                        
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg wire:loading.remove wire:target="consultar" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <svg wire:loading wire:target="consultar" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="consultar">Consultar</span>
                            <span wire:loading wire:target="consultar">Consultando...</span>
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            

            
            <div class="mb-8">
                <h1 class="<?php echo e($isAppVersion ? 'text-xl' : 'text-3xl md:text-4xl'); ?> font-bold text-gray-900 mb-2">
                    <?php echo e($isCampanhasPage ? 'Minhas Doações' : 'Minhas Compras'); ?>

                </h1>
                <p class="text-gray-600">
                    Olá, <span class="font-semibold text-gray-900"><?php echo e($buyer->name); ?></span>!
                    <?php echo e($isCampanhasPage ? 'Aqui estão todas as suas contribuições em campanhas.' : 'Aqui estão todas as suas compras de eventos.'); ?>

                </p>
            </div>

            
            <?php if($orders->count() > 0): ?>
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        <?php echo e($isCampanhasPage ? 'Suas Contribuições' : 'Suas Compras'); ?>

                        <span class="text-gray-400 font-normal text-base ml-2">(<?php echo e($orders->count()); ?>)</span>
                    </h2>
                </div>

                <div class="space-y-4">
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden">

                            <?php if($isCampanhasPage): ?>
                                
                                <div class="<?php echo e($isAppVersion ? 'p-4' : 'p-6'); ?>">
                                    <div class="flex flex-col <?php echo e($isAppVersion ? '' : 'md:flex-row md:items-center md:justify-between'); ?> gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-start gap-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                                                        <?php
                                                            $campImg = null;
                                                            if (!empty($order->campaign->url_image_thumb)) {
                                                                $campImg = tenantAsset($order->campaign->url_image_thumb, true);
                                                            } elseif (!empty($order->campaign->url_image_banner)) {
                                                                $campImg = tenantAsset($order->campaign->url_image_banner, true);
                                                            }
                                                        ?>
                                                        <?php if($campImg): ?>
                                                            <img src="<?php echo e($campImg); ?>" alt="<?php echo e($order->campaign->name ?? ''); ?>" class="w-full h-full object-cover">
                                                        <?php else: ?>
                                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                            </svg>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                                        <?php echo e($order->campaign->name ?? 'Campanha não encontrada'); ?>

                                                    </h3>
                                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span><?php echo e($order->created_at->format('d/m/Y H:i')); ?></span>
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                            </svg>
                                                            <span class="font-mono font-semibold"><?php echo e($order->order_control); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col <?php echo e($isAppVersion ? '' : 'md:items-end'); ?> gap-2">
                                            <div class="text-right">
                                                <div class="text-sm text-gray-600">Valor Contribuído</div>
                                                <div class="text-2xl font-bold text-green-600">
                                                    <?php echo e(toMoney($order->amount_total, 'R$ ')); ?>

                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                    <?php echo e($order->status === 'paid' ? 'bg-green-100 text-green-700' :
                                                       ($order->status === 'pending' ? 'bg-orange-100 text-orange-700' :
                                                       'bg-gray-100 text-gray-700')); ?>">
                                                    <?php if($order->status === 'paid'): ?> PAGO
                                                    <?php elseif($order->status === 'pending'): ?> PENDENTE
                                                    <?php else: ?> <?php echo e(strtoupper($order->status)); ?>

                                                    <?php endif; ?>
                                                </span>
                                                <?php if($order->campaign): ?>
                                                    <a href="<?php echo e(campanhaUrl($order->campaign->customer_organization_slug, $order->campaign->slug, $order->id)); ?>"
                                                       class="px-3 py-1 text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                                        Ver Detalhes
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php else: ?>
                                
                                <a href="<?php echo e($isAppVersion ? route('app-version-minhas-compras-detalhes', $order->id) : route('minhas-compras-detalhes', $order->id)); ?>" class="block <?php echo e($isAppVersion ? 'p-4' : 'p-6'); ?> hover:bg-gray-50 transition-colors">
                                    <div class="flex flex-col <?php echo e($isAppVersion ? '' : 'md:flex-row md:items-center md:justify-between'); ?> gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-start gap-4">
                                                <div class="flex-shrink-0">
                                                    <?php if($order->event): ?>
                                                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                                            <?php
                                                                $eventImage = null;
                                                                $isExternalEvent = !empty($order->event->referer_url)
                                                                    && rtrim($order->event->referer_url, '/') !== rtrim(config('domains.eventos'), '/');

                                                                $isInternalMediaPath = function ($path) {
                                                                    if (!is_string($path) || $path === '') {
                                                                        return false;
                                                                    }

                                                                    $internalPrefixes = [
                                                                        '/storage/',
                                                                        'storage/',
                                                                        'events/',
                                                                        'campaigns/',
                                                                        'customers/',
                                                                        'images_eventos/',
                                                                        'images_patrocinadores/',
                                                                        'images_customers_logo/',
                                                                    ];

                                                                    foreach ($internalPrefixes as $prefix) {
                                                                        if (str_starts_with($path, $prefix)) {
                                                                            return true;
                                                                        }
                                                                    }

                                                                    return false;
                                                                };

                                                                if ($isExternalEvent && ($order->event->url_image ?? false) && !$isInternalMediaPath($order->event->url_image)) {
                                                                    $eventImage = $order->event->referer_url . '/' . $order->event->url_image;
                                                                } elseif ($order->event->url_image) {
                                                                    $eventImage = tenantAsset($order->event->url_image, true);
                                                                }
                                                            ?>
                                                            <?php if($eventImage): ?>
                                                                <img src="<?php echo e($eventImage); ?>" alt="<?php echo e($order->event->event_name); ?>" class="w-full h-full object-cover">
                                                            <?php else: ?>
                                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                                </svg>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-1">
                                                    <?php if($order->event && $order->event->organizer): ?>
                                                        <div class="text-xs font-medium text-gray-500 mb-0.5">
                                                            <?php echo e($order->event->organizer->organizer_name_full); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                                        <?php echo e($order->event->event_name ?? 'Evento não encontrado'); ?>

                                                    </h3>
                                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span><?php echo e($order->created_at->format('d/m/Y H:i')); ?></span>
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                            </svg>
                                                            <span class="font-mono font-semibold"><?php echo e($order->order_control); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col <?php echo e($isAppVersion ? '' : 'md:items-end'); ?> gap-2">
                                            <div class="text-right">
                                                <div class="text-sm text-gray-600">Valor Total</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    <?php echo e(toMoney($order->order_amount, 'R$ ')); ?>

                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                    <?php echo e(in_array($order->status, ['paid', 'approved']) ? 'bg-green-100 text-green-700' :
                                                       ($order->status === 'pending' ? 'bg-orange-100 text-orange-700' :
                                                       'bg-gray-100 text-gray-700')); ?>">
                                                    <?php if(in_array($order->status, ['paid', 'approved'])): ?> PAGO
                                                    <?php elseif($order->status === 'pending'): ?> PENDENTE
                                                    <?php else: ?> <?php echo e(strtoupper($order->status)); ?>

                                                    <?php endif; ?>
                                                </span>
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        <?php echo e($isCampanhasPage ? 'Nenhuma contribuição encontrada' : 'Nenhuma compra encontrada'); ?>

                    </h3>
                    <p class="text-gray-600">
                        <?php echo e($isCampanhasPage ? 'Você ainda não realizou nenhuma contribuição em campanhas.' : 'Você ainda não realizou nenhuma compra de ingressos.'); ?>

                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </main>
</div>

<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/minhas-compras.blade.php ENDPATH**/ ?>