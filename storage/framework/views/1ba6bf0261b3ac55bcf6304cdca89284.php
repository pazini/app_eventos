<div class="w-full max-w-7xl mx-auto mb-10 space-y-6">

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

    
    <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-0">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Meus Eventos
                    </h1>
                    <p class="mt-2 text-blue-100 text-sm">Gerencie e acompanhe todos os seus eventos</p>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($owner ?? false): ?>
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Filtros</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 uppercase">Empresa</label>
                        <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'customer_id','class' => 'uppercase']); ?>
                            <option value="">-- Selecione --</option>
                            <?php if($customers ?? false): ?>
                                <?php $__currentLoopData = $customers->sortBy('name_corporate'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer_item->id); ?>"><?php echo e($customer_item->name_corporate); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 uppercase">Filial</label>
                        <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'organization_id','class' => 'uppercase']); ?>
                            <option value="">-- Selecione --</option>
                            <?php if($organizations ?? false): ?>
                                <?php $__currentLoopData = $organizations->sortBy('organization_name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organization_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($organization_item->id); ?>"><?php echo e($organization_item->organization_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 uppercase">Organizador</label>
                        <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'organizer_id','class' => 'uppercase']); ?>
                            <option value="">-- Selecione --</option>
                            <?php if($organizers ?? false): ?>
                                <?php $__currentLoopData = $organizers->sortBy('organizer_name_full'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organizer_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($organizer_item->id); ?>"><?php echo e($organizer_item->organizer_name_full); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
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
            </div>
        </div>
    <?php else: ?>
        
        <?php if($organizers->count() == 1): ?>
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Organizador</h2>
                </div>
                <div class="p-6">
                    <div class="text-xl font-bold text-gray-900 uppercase"><?php echo e($organizers->first()->organizer_name_full); ?></div>
                </div>
            </div>
        <?php elseif($organizers->count() > 1): ?>
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Selecione o Organizador</h2>
                </div>
                <div class="p-6">
                    <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'organizer_id','class' => 'uppercase']); ?>
                        <option value="">-- Selecione --</option>
                        <?php if($organizers ?? false): ?>
                            <?php $__currentLoopData = $organizers->sortBy('organizer_name_full'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organizerItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($organizerItem->id); ?>"><?php echo e($organizerItem->organizer_name_full); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
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
        <?php else: ?>
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
                <div class="p-6 text-center">
                    <p class="text-gray-500">NÃO POSSUI ORGANIZADORES</p>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    
    <?php if($organizer ?? false): ?>
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <h2 class="text-lg font-semibold text-gray-800">Eventos</h2>
                    <span class="text-sm text-gray-500">
                        <?php if($targetList && $targetList->count()): ?>
                            <?php if($targetList->count() == 1): ?>
                                <?php echo e($targetList->count()); ?> encontrado
                            <?php else: ?>
                                <?php echo e($targetList->count()); ?> encontrados
                            <?php endif; ?>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button
                            type="button"
                            wire:click="$set('exibir','evento_exibir_todos')"
                            class="px-3 py-1.5 text-xs font-semibold rounded-md transition <?php echo e($exibir === 'evento_exibir_todos' ? 'bg-white text-blue-600 shadow-sm border border-blue-200' : 'text-gray-600 hover:text-blue-600'); ?>"
                        >
                            Todos
                        </button>
                        <button
                            type="button"
                            wire:click="$set('exibir','evento_exibir_andamento')"
                            class="px-3 py-1.5 text-xs font-semibold rounded-md transition <?php echo e($exibir === 'evento_exibir_andamento' ? 'bg-white text-blue-600 shadow-sm border border-blue-200' : 'text-gray-600 hover:text-blue-600'); ?>"
                        >
                            Em andamento
                        </button>
                        <button
                            type="button"
                            wire:click="$set('exibir','evento_exibir_realizado')"
                            class="px-3 py-1.5 text-xs font-semibold rounded-md transition <?php echo e($exibir === 'evento_exibir_realizado' ? 'bg-white text-blue-600 shadow-sm border border-blue-200' : 'text-gray-600 hover:text-blue-600'); ?>"
                        >
                            Realizados
                        </button>
                    </div>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'NOVO EVENTO','rightIcon' => 'ticket','href' => ''.e(route('novo-evento')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['positive' => true]); ?>
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
                <?php if($targetList && $targetList->count()): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $targetList->sortByDesc('event_datetime_start') ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $now = now();
                                $eventStart = \Carbon\Carbon::parse($evento->event_datetime_start);
                                $eventFinish = $evento->event_datetime_finish ? \Carbon\Carbon::parse($evento->event_datetime_finish) : null;

                                // Determinar status do evento
                                $isFuturo = $eventStart->isFuture();

                                if ($eventFinish) {
                                    // Realizados apenas se a data de término já passou
                                    $isRealizado = $eventFinish->isPast();
                                    $isEmAndamento = $eventStart->isPast() && !$eventFinish->isPast();
                                } else {
                                    // Sem data fim não entra como realizado; apenas futuro ou em andamento pelo início
                                    $isRealizado = false;
                                    $isEmAndamento = $eventStart->isPast();
                                }

                                // Determinar cor e ícone
                                if ($isRealizado) {
                                    $iconBg = 'bg-gradient-to-br from-green-500 to-emerald-600';
                                    $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
                                } elseif ($isEmAndamento) {
                                    $iconBg = 'bg-gradient-to-br from-orange-500 to-amber-600';
                                    $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
                                } else {
                                    $iconBg = 'bg-gradient-to-br from-blue-500 to-cyan-500';
                                    $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
                                }
                            ?>
                            <a href="<?php echo e(route('evento-by-uuid', $evento->id)); ?>" class="block bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-blue-300 transition-all cursor-pointer group">
                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 <?php echo e($iconBg); ?> rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <?php echo $iconSvg; ?>

                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors truncate uppercase"><?php echo e($evento->event_name); ?></h3>
                                                <div class="flex items-center gap-4 mt-1">
                                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        <span><?php echo e(formatDateStartFinish($evento->event_datetime_start, $evento->event_datetime_finish) ?? 'Sem data definida'); ?></span>
                                                    </div>
                                                    <div class="text-xs text-gray-400 font-mono truncate" title="<?php echo e($evento->event_slug); ?>"><?php echo e($evento->event_slug); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ml-4">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-900">Nada por aqui ainda</p>
                        <p class="mt-1 text-sm text-gray-500">Crie seu primeiro evento para começar</p>
                        <div class="mt-6">
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'NOVO EVENTO','rightIcon' => 'ticket','href' => ''.e(route('novo-evento')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['positive' => true]); ?>
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
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="mt-2 text-sm font-medium text-gray-900">Selecione um organizador</p>
                <p class="mt-1 text-sm text-gray-500">Para visualizar os eventos, é necessário selecionar um organizador</p>
            </div>
        </div>
    <?php endif; ?>

    <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('faturamento.validar-fatura')->html();
} elseif ($_instance->childHasBeenRendered('l2161370643-0')) {
    $componentId = $_instance->getRenderedChildComponentId('l2161370643-0');
    $componentTag = $_instance->getRenderedChildComponentTagName('l2161370643-0');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l2161370643-0');
} else {
    $response = \Livewire\Livewire::mount('faturamento.validar-fatura');
    $html = $response->html();
    $_instance->logRenderedChild('l2161370643-0', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/dashboard/dashboard.blade.php ENDPATH**/ ?>