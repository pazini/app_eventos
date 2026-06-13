<div class="w-full max-w-7xl mx-auto mb-10">

    <?php if($target ?? false): ?>

        
        <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-evento" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-evento)"/>
                </svg>
            </div>
            <div class="relative z-10 p-6 space-y-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white"><?php echo e($target->event_name); ?></h1>
                                <p class="text-white/90 text-sm"><?php echo e(formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'switch-horizontal'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'wire:click' => 'alterarTarget','class' => 'hover:bg-white/20']); ?>
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
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['icon' => 'cog','label' => 'Layout Página','href' => ''.e(route('evento-layout-pagina-uuid', $target->id)).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'sm' => true,'class' => 'hover:bg-white/20']); ?>
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
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['icon' => 'cog','label' => 'Gestão Orçamentária','href' => ''.e(route('dashboard-financeiro-gestao-orcamentaria-uuid', $target->id)).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'sm' => true,'class' => 'hover:bg-white/20']); ?>
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
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['icon' => 'cog','label' => 'Sumário de Vendas','href' => ''.e(route('dashboard-evento-vendas-sumario-uuid', $target->id)).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'sm' => true,'class' => 'hover:bg-white/20']); ?>
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
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['icon' => 'cog','label' => 'Notificações','href' => ''.e(route('notifica-uuid', $target->id)).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'sm' => true,'class' => 'hover:bg-white/20']); ?>
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
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'icon' => 'users','label' => 'Participantes','href' => ''.e(route('dashboard-vendas',['target_ref' => 'evento', 'target_slug' => $target->event_slug, 'target_id' => $target->id, 'view_status' => 'participantes'])).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'sm' => true,'class' => 'hover:bg-white/20']); ?>
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
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'icon' => 'currency-dollar','label' => 'Vendas','href' => ''.e(route('dashboard-evento-vendas-uuid', $target->id)).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'sm' => true,'class' => 'hover:bg-white/20']); ?>
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
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'icon' => 'desktop-computer','rightIcon' => 'external-link','label' => 'Página Evento','href' => ''.e(eventoUrl($target->event_slug)).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'sm' => true,'class' => 'hover:bg-white/20','target' => '_blank']); ?>
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
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'icon' => 'desktop-computer','rightIcon' => 'external-link','label' => 'Página Patrocínio','href' => ''.e(eventoPatrocinarUrl($target->event_slug)).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'sm' => true,'class' => 'hover:bg-white/20','target' => '_blank']); ?>
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
                </div>
            </div>
        </div>

        <div class="mb-6">
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

        
        <div class="mb-8 bg-white shadow-md border border-gray-200 rounded-lg">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Dados do Evento</h2>
                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'ALTERAR','href' => ''.e(route('altera-evento')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lime' => true]); ?>
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
            </div>

            <?php
                $ticketStatus                 = $target->tickets->whereIn('ticket_status',ticketStatusCapacidade()) ?? 0;
                $ticketStatusCount            = $ticketStatus->count() ?? 0;
                $ticketStatusCountReservaTemp = $ticketStatus->whereIn('ticket_status',ticketStatusTemp())->count() ?? 0;
                //
                $ticketStatusCount = $ticketStatusCount - $ticketStatusCountReservaTemp;
                //
                $ticketDisponiveisCount = ($target->sales_amount_max ?? 0) - $ticketStatusCount;
            ?>

            <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-2 items-start p-6">

                <div class="col-span-full md:col-span-4 break-words" title="<?php echo e($target->event_name ?? null); ?>">
                    <?php echo setLabel('event_name', $target->event_name ?? null); ?>

                </div>

                <div class="col-span-full md:col-span-8 break-words" title="<?php echo e($target->event_description ?? null); ?>">
                    <?php echo setLabel('event_description', $target->event_description ?? null); ?>

                </div>

                <div class="col-span-full md:col-span-full mb-2">
                    <hr>
                </div>

                <div class="col-span-full md:col-span-3">
                    <?php echo setLabel('Total de Vagas',$target->sales_amount_max ?? 0, translate:false); ?>

                </div>

                <div class="col-span-full md:col-span-3">
                    <?php echo setLabel('Ocupadas',$ticketStatusCount ?? 0, translate:false); ?>

                </div>

                <div class="col-span-full md:col-span-3">
                    <?php echo setLabel('Disponíveis',$ticketDisponiveisCount ?? 0, translate:false); ?>

                </div>

                <div class="col-span-full md:col-span-3 flex flex-col justify-end md:justify-center items-end">

                    <div class="flex justify-center items-center">
                        <div class="text-3xl font-semibold"><?php echo e($ticketStatusCount); ?></div>
                        <div class="text-4xl font-light">/</div>
                        <div class="text-xl ml-0 mt-2 mx-1 font-semibold"><?php echo e($target->sales_amount_max ?? 0); ?></div>
                        <div class="ml-1 mt-2">
                            <?php if($ticketStatusCountReservaTemp ?? false): ?>
                                <div class="text-sm bg-white rounded-full border-b shadow-sm uppercase flex justify-center items-center mb-0.5" title="Reservas temporárias">
                                    <div><?php echo e($ticketStatusCountReservaTemp); ?></div>
                                    <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'clock'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ml-1 w-3 h-3']); ?>
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
                            <?php endif; ?>
                            <div class="text-sm font-normal">(<?php echo e(calculaPorcentagem($target->sales_amount_max, $ticketStatusCount,'%')); ?>)</div>
                        </div>
                    </div>
                </div>

                <div class="col-span-full md:col-span-full mb-2">
                    <hr>
                </div>

                <div class="col-span-full md:col-span-3">
                    <?php echo setLabel('event_datetime_start',$target->event_datetime_start ? \Carbon\Carbon::parse($target->event_datetime_start)->format('d/m/Y H:i') : '--' ?? null); ?>

                </div>

                <div class="col-span-full md:col-span-3">
                    <?php echo setLabel('event_datetime_finish',$target->event_datetime_finish ? \Carbon\Carbon::parse($target->event_datetime_finish)->format('d/m/Y H:i') : '--' ?? null); ?>

                </div>

                <div class="col-span-full md:col-span-3">
                    <?php echo setLabel('type', ucfirst($target->type) ?? null); ?>

                </div>

                <div class="col-span-full md:col-span-3">
                    <?php if($target->active ?? false): ?>
                        <?php echo setLabel('Busca', "<span class='text-green-600 font-bold'>Evento Público</span>"); ?>

                    <?php else: ?>
                        <?php echo setLabel('Busca', "<span class='text-blue-600 font-bold'>Apenas Link Direto </span>"); ?>

                    <?php endif; ?>
                </div>

                <div class="col-span-full md:col-span-3">
                    <?php echo setLabel('category',  ucfirst($target->category) ?? null); ?>

                </div>

                <div class="col-span-full md:col-span-3">
                    <?php echo setLabel('sales_label',$target->sales_label ?? null); ?>

                </div>

                <div class="col-span-full md:col-span-3">
                    <?php echo setLabel('sales_btn',$target->sales_btn ?? null); ?>

                </div>

                <div class="col-span-full md:col-span-3">
                    <?php echo setLabel('event_tickets_nomenclature',$target->event_tickets_nomenclature ?? null); ?>

                </div>

                <div class="col-span-full md:col-span-6">
                    <?php echo setLabel('Utilizador Tipo', ($target->sales_label_item ?? '---') . ' / ' . ($target->sales_label_item_multiple ?? '---')); ?>

                </div>

            </div>

        </div>

        
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Método Pagamento</h2>
                <?php if($target->pay_gateway_id ?? false): ?>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'ALTERAR','href' => ''.e(route('evento-metodo-pagamento')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lime' => true]); ?>
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
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'CADASTRAR','href' => ''.e(route('evento-metodo-pagamento')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lime' => true]); ?>
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
                <?php endif; ?>
            </div>

            <div class="p-6">
                <div class="w-full grid grid-cols-12 gap-2">

                <?php if($target->pay_gateway_id ?? false): ?>

                    <div class="col-span-full md:col-span-10">
                        <?php echo setLabel($target->gatewayPay->appGateway->gateway_name ?? 'Conta Pagamentos', $target->gatewayPay->pay_gateway_label ?? null); ?>

                    </div>

                    <div class="col-span-full md:col-span-2">
                        <?php if($target->pay_sandbox ?? false): ?>
                            <?php echo setLabel('Processamento', "<span class='text-blue-600 font-bold'>EM MODO TESTE</span>"); ?>

                        <?php else: ?>
                            <?php echo setLabel('Processamento', "<span class='text-green-600 font-bold'>ATIVADO</span>"); ?>

                        <?php endif; ?>
                    </div>

                    <?php if($target->pay_boleto ?? false): ?>
                        <div class="col-span-full md:col-span-4 bg-green-50 p-2 border border-gray-300 rounded shadow">
                            <div class="w-full flex justify-between items-center gap-1 font-semibold">
                                <div class="text-xl">BOLETO</div>
                                <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'check-circle','solid' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-green-700']); ?>
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
                            <div class="w-full flex justify-start gap-1 mt-1">
                                <div class="text-sm font-light">DATA LIMITE <span class="font-semibold"><?php echo e($target->pay_boleto_date_end ? $target->pay_boleto_date_end->format('d/m/Y') : ''); ?></span></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($target->pay_pix ?? false): ?>
                        <div class="col-span-full md:col-span-4 bg-green-50 p-2 border border-gray-300 rounded shadow">
                            <div class="w-full flex justify-between items-center gap-1 font-semibold">
                                <?php if($target->pay_slip_pix ?? false): ?>
                                    <div class="text-xl">PIX + CARNÊ ONLINE</div>
                                <?php else: ?>
                                    <div class="text-xl">PIX</div>
                                <?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'check-circle','solid' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-green-700']); ?>
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
                            <?php if($target->pay_slip_pix ?? false): ?>
                                <div class="w-full flex justify-start gap-1 mt-1">
                                    <div class="text-sm font-light">MÁXIMO <span class="font-semibold"><?php echo e($target->pay_slip_pix_installment_max . 'x'); ?></span></div>
                                    <div class="text-sm font-light">PARCELA MÍNIMA <span class="font-semibold"><?php echo e(toMoney($target->pay_slip_pix_installment_amount_min ?? 0, 'R$ ')); ?></span></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($target->pay_card_credit ?? false): ?>
                        <div class="col-span-full md:col-span-4 bg-green-50 p-2 border border-gray-300 rounded shadow">

                            <div class="w-full flex justify-between items-center gap-1 font-semibold">
                                <div class="text-xl">CARTÃO DE CRÉDITO</div>
                                <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'check-circle','solid' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-green-700']); ?>
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

                            <div class="w-full flex justify-start gap-1 mt-1">
                                <div class="text-sm font-light">MÁXIMO <span class="font-semibold"><?php echo e($target->pay_card_credit_installment_max . 'x'); ?></span></div>
                                <div class="text-sm font-light">PARCELA MÍNIMA <span class="font-semibold"><?php echo e(toMoney($target->pay_card_credit_installment_amount_min ?? 0, 'R$ ')); ?></span></div>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>

                    <div class="col-span-full flex justify-between">
                        <div class="text-lg font-light">NÃO POSSUI</div>
                    </div>

                <?php endif; ?>

                </div>
            </div>
        </div>

        
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Lotes</h2>
                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'CADASTRAR','href' => ''.e(route('evento-lote')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lime' => true]); ?>
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
            </div>
            <div class="p-6">

            <div class="w-full flex flex-col gap-6">

                <?php $__empty_1 = true; $__currentLoopData = $target->ticketsTypes->sortBy('created_at') ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticketsType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="border border-gray-300 rounded-r">

                        <?php if($ticketsType->visible ?? false): ?>
                            <div class="<?php echo e(setClass('divForItem')); ?> bg-green-50 hover:bg-gray-100">
                        <?php else: ?>
                            <div class="<?php echo e(setClass('divForItem')); ?> bg-red-200 opacity-70">
                        <?php endif; ?>
                            <div class="w-full grid grid-cols-12 items-center gap-4">

                                <div class="col-span-full md:col-span-10">
                                    <div class="text-xl font-semibold break-words flex items-center gap-2">
                                        <div class="break-words"><?php echo e($ticketsType->ticket_name ?? '---'); ?></div>
                                        <div>
                                            <?php if($ticketsType->lote_publico ?? false): ?>
                                                <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'eye'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5']); ?>
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
                                            <?php else: ?>
                                                <?php if (isset($component)) { $__componentOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b1ecc3bb8af1d000115fef1c04cca2 = $attributes; } ?>
<?php $component = WireUi\View\Components\Icon::resolve(['name' => 'eye-off'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5']); ?>
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
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="text-base font-normal break-words"><?php echo e($ticketsType->ticket_description ?? '---'); ?></div>
                                </div>

                                <div class="col-span-full md:col-span-2">
                                    <?php if($ticketsType->visible ?? false): ?>
                                        <div class="w-full flex justify-center md:justify-end">
                                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'ALTERAR','href' => ''.e(route('evento-lote',['ticket_type_id' => $ticketsType->id])).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'class' => 'w-auto -mt-4']); ?>
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
                                        </div>
                                    <?php else: ?>
                                        <div class="w-full bg-white text-red-800 rounded-sm shadow-sm px-2 py-1">
                                            <div class="text-sm font-semibold">REMOVIDO EM</div>
                                            <div class="text-sm font-normal"><?php echo e($ticketsType->updated_at->format('d/m/Y H:i')); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-span-full md:col-span-full mb-2">
                                    <hr>
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('sale_start_datetime', $ticketsType->sale_start_datetime ? $ticketsType->sale_start_datetime->format('d/m/Y H:i') : '---' ?? null); ?>

                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('sale_finish_datetime', $ticketsType->sale_finish_datetime ? $ticketsType->sale_finish_datetime->format('d/m/Y H:i') : '---' ?? null); ?>

                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('Inscritos x Qtd.Lote',  ($ticketsType->tickets->whereIn('ticket_status',['utilizado','disponivel'])->count()  ?? null) . ' de ' . ($ticketsType->amount ?? '---'), bodyU:false); ?>

                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('price', toMoney($ticketsType->price, 'R$ ') ?? null); ?>

                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('sale_period_type', $ticketsType->sale_period_type ?? null); ?>

                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('sale_ticket_availability', $ticketsType->sale_ticket_availability ?? null); ?>

                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('sale_label_title', $ticketsType->sale_label_title ?? null); ?>

                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('sale_label_btn', $ticketsType->sale_label_btn ?? null); ?>

                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex justify-between">
                        <div class="text-lg font-light">NÃO POSSUI</div>
                    </div>
                <?php endif; ?>

            </div>
            </div>
        </div>

        
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Local</h2>
            </div>
            <div class="p-6">
                <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4">

                    <div class="col-span-full md:col-span-6">
                        <?php echo setLabel('address', $target->address ?? null); ?>

                    </div>

                    <div class="col-span-full md:col-span-3">
                        <?php echo setLabel('address_number', $target->address_number ?? null); ?>

                    </div>

                    <div class="col-span-full md:col-span-3">
                        <?php echo setLabel('address_complement', $target->address_complement ?? null); ?>

                    </div>

                    <div class="col-span-full md:col-span-3">
                        <?php echo setLabel('city_neighborhood', $target->city_neighborhood ?? null); ?>

                    </div>

                    <div class="col-span-full md:col-span-3">
                        <?php echo setLabel('city', $target->city ?? null); ?>

                    </div>

                    <div class="col-span-full md:col-span-3">
                        <?php echo setLabel('state', $target->state ?? null); ?>

                    </div>

                    <div class="col-span-full md:col-span-3">
                        <?php echo setLabel('zip_code', $target->zip_code ?? null); ?>

                    </div>

                    <div class="col-span-full md:col-span-3">
                        <?php echo setLabel('address_reference', $target->address_reference ?? null); ?>

                    </div>

                    <?php if($target->google_maps_iframe ?? false): ?>

                        <div class="col-span-full md:col-span-full mb-2">
                            <hr>
                        </div>
                        <div class="col-span-full">
                            <?php echo setLabel('iframe_google_maps', ' '); ?>

                            <?php
                                // $iframe_google_maps = str_replace('width="600"','width="100%"', $target->place->iframe_google_maps);
                                $iframe_google_maps = str_replace('width="600"','width="100%"', $target->google_maps_iframe);
                            ?>
                            <?php echo $iframe_google_maps; ?>

                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>

        
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Campos Adicionais Formulário</h2>
                <?php if( $target->questions_user_json ?? false ): ?>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'ALTERAR','href' => ''.e(route('evento-campo-adicional')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lime' => true]); ?>
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
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'CADASTRAR','href' => ''.e(route('evento-campo-adicional')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lime' => true]); ?>
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
                <?php endif; ?>
            </div>
            <div class="p-6">
                <div class="w-full flex flex-col gap-6">

                    

                <?php $__empty_1 = true; $__currentLoopData = collect($questions_user_json ?? [])->sortBy('input_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question_values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="border border-gray-200">

                        <div class="<?php echo e(setClass('divForItem')); ?> bg-green-50 hover:bg-gray-100">

                            <div class="w-full grid grid-cols-12 gap-4">

                                <div class="col-span-full md:col-span-full">
                                    <div class="text-xl font-semibold break-words"><?php echo e($question_values['input_label'] ?? '---'); ?></div>
                                    <div class="text-base font-normal break-words"><?php echo e($question_values['input_placeholder'] ?? '---'); ?></div>
                                </div>

                                <div class="col-span-full md:col-span-full">
                                    <hr>
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('input_type', $question_values['input_type'] ?? null); ?>

                                </div>

                                <div class="col-span-full md:col-span-1">
                                    <?php echo setLabel('input_required', boolSimNao($question_values['input_required'] ?? false)); ?>

                                </div>

                            </div>

                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex justify-between">
                        <div class="text-lg font-light">NÃO POSSUI</div>
                    </div>
                <?php endif; ?>

                </div>
            </div>
        </div>


        <?php if(isAdmin()): ?>

            <div class="mb-6 font-semibold px-4">
                <h2>SOMENTE ADMINISTRADORES PODEM VER ESSE CONTEÚDO</h2>
            </div>

            
            <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Planos de Patrocínios</h2>
                    <div class="flex justify-between">
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'CADASTRAR','href' => ''.e(route('evento-plano-patrocinio')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lime' => true]); ?>
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
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'external-link','href' => ''.e(route('evento-patrocinios')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lg' => true,'primary' => true,'title' => 'Ver Patrocínios']); ?>
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
                    </div>
                </div>
                <div class="p-6">

                    <?php $__empty_1 = true; $__currentLoopData = $target->sponsorshipPlans->sortBy('slug') ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <div class="border border-gray-200">

                            <?php if($plan_item->plan_active ?? false): ?>
                                <div class="<?php echo e(setClass('divForItem')); ?> bg-green-50 hover:bg-gray-100">
                            <?php else: ?>
                                <div class="<?php echo e(setClass('divForItem')); ?> bg-red-200 opacity-70">
                            <?php endif; ?>

                            <div class="w-full grid grid-cols-12">

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('name', $plan_item->name ?? null); ?>

                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('price', toMoney($plan_item->price ?? null,'R$ ')); ?>

                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('data_finish', convertToDate($plan_item->data_finish ?? false)); ?>

                                </div>

                                <div class="col-span-full md:col-span-1">
                                    <?php echo setLabel('adesões', $plan_item->orders->count() ?? null); ?>

                                </div>

                                <div class="col-span-full md:col-span-2">
                                    <div class="w-full flex justify-center md:justify-end">
                                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'ALTERAR','href' => ''.e(route('evento-plano-patrocinio',['patrocinio_id' => $plan_item->id])).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'class' => 'w-auto']); ?>
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
                                    </div>
                                </div>

                                <div class="col-span-full md:col-span-full mb-2">
                                    <hr>
                                </div>

                                <div class="col-span-full md:col-span-2">
                                    <?php echo setLabel('installments_fees_pay', boolSimNao($plan_item->installments_fees_pay ?? false)); ?>

                                </div>

                                <div class="col-span-full md:col-span-2">
                                    <?php echo setLabel('installments_max', $plan_item->installments_max ?? null); ?>

                                </div>

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('pay_credit', boolSimNao($plan_item->pay_credit ?? false)); ?>

                                </div>

                                

                                <div class="col-span-full md:col-span-3">
                                    <?php echo setLabel('pay_boleto', boolSimNao($plan_item->pay_boleto ?? false)); ?>

                                </div>

                                <div class="col-span-full md:col-span-2">
                                    <?php echo setLabel('pay_pix', boolSimNao($plan_item->pay_pix ?? false)); ?>

                                </div>

                                <div class="col-span-full md:col-span-full mb-2">
                                    <hr>
                                </div>

                                <div class="col-span-full">
                                    <?php echo setLabel('description', $plan_item->description ?? null); ?>

                                </div>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="flex justify-between">
                            <div class="text-lg font-light">NÃO POSSUI</div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            
            <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Sumário</h2>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'external-link','href' => ''.e(route('dashboard-evento-vendas-sumario')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lg' => true,'primary' => true,'title' => 'SUMÁRIO']); ?>
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
                </div>
                <div class="p-6">
                    <div class="w-full flex flex-col gap-4">

                        <div class="w-full grid grid-cols-12">

                        <div class="col-span-full md:col-span-3">
                            <?php echo setLabel('preview_summary_update', $target->preview_summary_update ? $target->preview_summary_update->format('d/m/Y H:i') : '---' ?? null); ?>

                        </div>

                        <div class="col-span-full md:col-span-6">
                            <?php echo setLabel('preview_summary', toMoney($target->preview_summary, 'R$ ') ?? null); ?>

                        </div>

                        <?php if($target->preview_summary_json ?? false): ?>

                            <div class="col-span-full md:col-span-full mb-2">
                                <hr>
                            </div>

                            <div class="col-span-full">
                                <div class="w-full grid grid-cols-12">
                                    <?php
                                        $preview_summary_json = json_decode($target->preview_summary_json, true)
                                    ?>
                                    <div class="<?php echo e(setClass('divForItem')); ?> col-span-full">
                                        <?php echo setLabel('json', 'dados' ?? null); ?>

                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Gestão Orçamentária</h2>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'external-link','href' => ''.e(route('dashboard-financeiro-gestao-orcamentaria')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lg' => true,'primary' => true,'title' => 'GESTÃO ORÇAMENTÁRIA']); ?>
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
                </div>
                <div class="p-6">
                    <div class="w-full flex flex-col gap-4">

                        <div class="w-full grid grid-cols-12">
                        <div class="col-span-full md:col-span-3">
                            <?php echo setLabel('preview_summary_update', $target->preview_summary_update ? $target->preview_summary_update->format('d/m/Y H:i') : '---' ?? null); ?>

                        </div>

                        <div class="col-span-full md:col-span-3">
                            <?php echo setLabel('Saldo Previsto', toMoney(($target->preview_budget_management_entries - $target->preview_budget_management_outputs), 'R$ ') ?? null); ?>

                        </div>

                        <div class="col-span-full md:col-span-3">
                            <?php echo setLabel('preview_budget_management_entries', toMoney($target->preview_budget_management_entries, 'R$ ') ?? null); ?>

                        </div>

                        <div class="col-span-full md:col-span-3">
                            <?php echo setLabel('preview_budget_management_outputs', toMoney($target->preview_budget_management_outputs, 'R$ ') ?? null); ?>

                        </div>

                        <?php if($target->preview_budget_management_json ?? false): ?>
                            <div class="col-span-full md:col-span-full mb-2">
                                <hr>
                            </div>

                            <div class="col-span-full">
                                <div class="w-full grid grid-cols-12">
                                    <?php
                                        $preview_budget_management_json = json_decode($target->preview_budget_management_json, true)
                                    ?>
                                    <div class="<?php echo e(setClass('divForItem')); ?> col-span-full">
                                        <?php echo setLabel('json', 'dados' ?? null); ?>

                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8 p-4">
            <div class="text-xs text-gray-500">
                <?php echo e($target->event_slug); ?> : <?php echo e($target->id); ?>

            </div>
        </div>
    <?php else: ?>
        <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="relative z-10 p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Ops!</h1>
                                <p class="text-white/90 text-sm">É preciso selecionar um evento</p>
                                <div class="mt-2"><a href="<?php echo e(route('dashboard')); ?>" class="text-white/90 text-sm hover:text-white/70 border border-white mt-4 p-2 rounded shadow hover:bg-gray-50 hover:text-blue-500">Página Principal</a></div>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'icon' => 'switch-horizontal'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'wire:click' => 'alterarTarget','class' => 'hover:bg-white/20']); ?>
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
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/dashboard/dashboard-evento.blade.php ENDPATH**/ ?>