<div>

    
    
    <div class="max-w-7xl mx-auto py-4 md:py-4 z-50">

        <div class="flex justify-between items-center min-h-14">

            <!-- Logo 2 -->
            <div class="shrink-0 flex items-center">
                <a href="<?php echo e(route('dashboard')); ?>">
                    
                    <img src="<?php echo e(appLogo(true)); ?>" alt="<?php echo e(appName()); ?>" class="h-10">
                </a>
            </div>

            <!-- Menu / Usuário -->
            <div class="flex items-center gap-4">
                <?php if(auth()->guard()->check()): ?>
                    <style>
                        body {
                            margin: 0;
                            font-family: Arial, sans-serif;
                        }

                        /* Estilos básicos do Offcanvas */
                        .offcanvas {
                            position: fixed;
                            top: 0;
                            right: 0;
                            min-width: 300px;
                            max-width: 50%;
                            height: 100%;
                            background: white;
                            visibility: hidden;
                            opacity: 0;
                            z-index: 1000;
                            transition: visibility 0.3s, opacity 0.3s, transform 0.3s;
                            transform: translateX(100%);
                            /* Inicia fora da tela */
                            box-shadow: -2px 0px 10px rgba(0, 0, 0, 0.1);
                        }

                        .offcanvas.active {
                            visibility: visible;
                            opacity: 1;
                            transform: translateX(0);
                            /* Entra na tela */
                        }

                        .offcanvas-content {
                            padding: 20px;
                            height: 100%;
                            overflow-y: auto;
                        }

                        .offcanvas-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            border-bottom: 1px solid #ddd;
                        }

                        .offcanvas-body {
                            margin-top: 10px;
                        }

                        .close-btn {
                            cursor: pointer;
                            font-size: 1.5rem;
                            border: none;
                            background: none;
                        }

                        /* Fundo com Blur */
                        .overlay {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: rgba(0, 0, 0, 0.5);
                            visibility: hidden;
                            opacity: 0;
                            z-index: 999;
                            transition: visibility 0.3s, opacity 0.3s, filter 0.3s;
                            backdrop-filter: blur(5px);
                            /* Aplica o blur */
                        }

                        .overlay.active {
                            visibility: visible;
                            opacity: 1;
                        }
                    </style>

                    <!-- Botão para abrir o Offcanvas -->
                    <button id="openOffcanvas">
                        <div class="shadow border111 border-gray-300 rounded-full py-1 px-1 bg-white">
                            <div class="flex items-center gap-x-1 text-gray-500">
                                <div class="flex justify-center pl-2 pr-1">
                                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                                        width="18" height="18">
                                        <path d="M2.5 10h15m-15-5h15m-15 10h15" stroke="#717680" stroke-width="1"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                                <div class="rounded-full bg-gray-100 p-2 shadow">
                                    <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'user'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $attributes = $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $component = $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </button>
                    <!-- Botão para abrir o Offcanvas fim -->

                    <!-- Fundo com Blur -->
                    <div id="overlay" class="overlay"></div>

                    <!-- Offcanvas -->
                    <div id="offcanvas" class="offcanvas">
                        <div class="offcanvas-content">
                            <div class="flex justify-between items-center gap-2">
                                <div>
                                    <div class="text-lg font-bold truncate capitalize" title="<?php echo e(Auth::user()->name); ?>">
                                        <?php echo e(Auth::user()->name); ?></div>
                                    <div class="-mt-1 text-base truncate lowercase" title="<?php echo e(Auth::user()->email); ?>">
                                        <?php echo e(Auth::user()->email); ?></div>
                                </div>
                                <button id="closeOffcanvas" class="close-btn">&times;</button>
                            </div>

                            <div class="py-4">
                                <hr>
                            </div>

                            <div class="flex flex-col gap-4">

                                <?php
                                    $user = Auth::user();
                                    $customer = sessionCustomer();

                                    $canEvents = false;
                                    $canCampaigns = false;

                                    if ($user) {
                                        // Admin global enxerga sempre os módulos principais
                                        if (\App\Services\ModuleAccessService::userIsAppAdmin($user)) {
                                            $canEvents = true;
                                            $canCampaigns = true;
                                        } elseif ($customer) {
                                            $canEvents = \App\Services\ModuleAccessService::userCanAccessEvents(
                                                $user,
                                                $customer,
                                            );
                                            $canCampaigns = \App\Services\ModuleAccessService::userCanAccessCampaigns(
                                                $user,
                                                $customer,
                                            );
                                        }
                                    }
                                ?>

                                
                                <?php if(isAdmin() || $canEvents): ?>
                                    <div class="space-y-3 border-b pb-6 mb-1">
                                        <div class="text-[14px] font-light uppercase tracking-wide text-gray-500">
                                            Módulo Eventos
                                        </div>

                                        <div>
                                            <a href="<?php echo e(route('dashboard-eventos')); ?>"
                                                class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'calendar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $attributes = $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $component = $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
                                                <span class="">Painel</span>
                                            </a>
                                        </div>

                                        
                                        <?php if(isAdmin() || isOwner()): ?>
                                            <div>
                                                <a href="<?php echo e(route('eventos-organizadores')); ?>"
                                                    class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                    <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'users'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $attributes = $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $component = $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
                                                    <span class="">Organizadores</span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(isAdmin()): ?>
                                            <div>
                                                <a href="<?php echo e(route('ultimas-vendas')); ?>"
                                                    class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                    <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'cash'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $attributes = $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $component = $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
                                                    <span class="">Últimas Vendas</span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if(isAdmin() || $canCampaigns): ?>
                                    <div class="space-y-3 border-b pb-6 mb-1">
                                        <div class="text-[14px] font-light uppercase tracking-wide text-gray-500">
                                            Módulo Campanhas
                                        </div>

                                        <div>
                                            <a href="<?php echo e(route('dashboard-campanhas')); ?>"
                                                class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'view-grid'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $attributes = $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $component = $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
                                                <span class="">Painel</span>
                                            </a>
                                        </div>

                                        
                                        <?php if(isAdmin() || isOwner()): ?>
                                            <div>
                                                <a href="<?php echo e(route('campanhas-organizadores')); ?>"
                                                    class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                    <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'users'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $attributes = $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $component = $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
                                                    <span class="">Organizadores</span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(isAdmin()): ?>
                                            <div>
                                                <a href="<?php echo e(route('ultimas-vendas-campanhas')); ?>"
                                                    class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                    <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'cash'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $attributes = $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2)): ?>
<?php $component = $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2; ?>
<?php unset($__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2); ?>
<?php endif; ?>
                                                    <span class="">Últimas Adesões</span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if(isAdmin()): ?>
                                    <div><?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'CONFIGURAÇÕES','href' => ''.e(route('configuracoes')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blue' => true,'class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?></div>

                                    <div><?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'FATURAS','href' => ''.e(route('plataforma-faturamento')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blue' => true,'class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?></div>

                                    <div>
                                        <hr>
                                    </div>
                                <?php endif; ?>

                                
                                <?php if(App\Http\Middleware\EnsureSuperAdmin::check()): ?>
                                    <div><?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'SUPER-ADMINISTRADOR','href' => ''.e(route('super-administrador.dashboard')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['purple' => true,'class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?></div>
                                    <div>
                                        <hr>
                                    </div>
                                <?php endif; ?>

                                
                                <div><?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'PERFIL','href' => ''.e(route('dashboard-user-profile')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blue' => true,'class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?></div>

                                <div>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>" x-data>
                                        <?php echo csrf_field(); ?>
                                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'outline' => true,'label' => 'SAIR','href' => ''.e(route('logout')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['red' => true,'@click.prevent' => '$root.submit();','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <script>
                        // Seleção dos elementos
                        const openBtn = document.getElementById('openOffcanvas');
                        const closeBtn = document.getElementById('closeOffcanvas');
                        const offcanvas = document.getElementById('offcanvas');
                        const overlay = document.getElementById('overlay');

                        // Abrir o Offcanvas e ativar o Blur
                        openBtn.addEventListener('click', () => {
                            offcanvas.classList.add('active');
                            overlay.classList.add('active');
                        });

                        // Fechar o Offcanvas e remover o Blur
                        closeBtn.addEventListener('click', () => {
                            offcanvas.classList.remove('active');
                            overlay.classList.remove('active');
                        });

                        // Fechar ao clicar fora
                        overlay.addEventListener('click', () => {
                            offcanvas.classList.remove('active');
                            overlay.classList.remove('active');
                        });
                    </script>

                <?php endif; ?>
            </div>

        </div>
    </div>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/navigation/navigation-menu-pep-auth.blade.php ENDPATH**/ ?>