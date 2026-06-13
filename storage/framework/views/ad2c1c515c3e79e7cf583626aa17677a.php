<div>
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

    <?php if(session('message')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.$wireui.notify({
                    title: 'Sucesso!',
                    description: '<?php echo e(session('message')); ?>',
                    icon: 'success'
                });
            });
        </script>
    <?php endif; ?>

    
    <div class="<?php echo e(setClass('divContentHeader')); ?>">
        <div class="w-full flex justify-between items-center">
            <div>
                <?php echo setLabelHeader('Campanhas', 'Minhas Campanhas', 'Gerencie e acompanhe suas campanhas de arrecadação'); ?>

            </div>
            <div>
                <?php if(isAdmin() || isOwner() || ($organizers && $organizers->isNotEmpty())): ?>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Nova Campanha','icon' => 'plus'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'wire:click' => 'novaCampanha','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!$customer_id),'class' => '!text-white hover:!text-blue-200 hover:!bg-white/20 border border-white/40']); ?>
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

    
    <?php if($customers ?? false): ?>
        <div class="w-full max-w-7xl mx-auto mt-4">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm px-4 py-3 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Empresa</label>
                        <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['placeholder' => 'Selecione uma empresa'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'customer_id','class' => 'w-full rounded border-gray-300 text-sm uppercase']); ?>
                            <option value="">Selecione uma empresa...</option>
                            <?php $__currentLoopData = ($customers ?? collect())->sortBy('name_corporate'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>" class="uppercase"><?php echo e($item->name_corporate); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <label class="block text-xs font-medium text-gray-600 mb-1">Organizador</label>
                        <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'organizer_id','class' => 'w-full rounded border-gray-300 text-sm uppercase','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!$customer_id)]); ?>
                            <option value="">Selecione</option>
                            <?php if(isAdmin() || isOwner()): ?>
                                <option value="all">- TODOS -</option>
                            <?php endif; ?>
                            <?php $__currentLoopData = ($organizers ?? collect())->sortBy('organizer_name_full'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>" class="uppercase"><?php echo e($item->organizer_name_full); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <?php endif; ?>

    
    <div class="w-full max-w-7xl mx-auto">
        <?php if(($customer_id ?? false) && !isAdmin() && !isOwner() && (!$organizers || $organizers->isEmpty())): ?>
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 text-center text-gray-500 text-sm">
                NAO POSSUI ORGANIZADORES
            </div>
        <?php else: ?>
            <?php if($customer_id && (($organizer_id ?? false) || ($organizer_id ?? '') === 'all') && $campaigns->count() > 0): ?>

                <div class="flex items-center justify-between mb-3 px-1">
                    <span class="text-sm font-semibold text-gray-600">
                        <?php echo e($campaigns->count()); ?>

                        <?php echo e($campaigns->count() == 1 ? 'campanha encontrada' : 'campanhas encontradas'); ?>

                    </span>
                </div>

                <div class="space-y-3">
                    <?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $ordersTotalCount = $campaign->orders_total_count ?? 0;
                            $ordersPaidCount = $campaign->orders_paid_count ?? 0;
                            $totalDonationsPaid = $campaign->orders()->where('status', 'paid')->sum('amount_paid');
                            $goalAmount = $campaign->goal_amount ?? null;
                        ?>
                        <div
                            class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                            <div class="p-4">

                                
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <a href="<?php echo e(route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id])); ?>"
                                            class="text-base font-bold text-gray-900 hover:text-blue-600 transition-colors uppercase leading-tight">
                                            <?php echo e($campaign->name); ?>

                                        </a>

                                        <?php if($campaign->organizer): ?>
                                            <div class="flex items-center gap-1 mt-0.5 text-xs text-gray-500">
                                                <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span
                                                    class="uppercase font-medium"><?php echo e($campaign->organizer->organizer_name_full); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        <?php if($campaign->status === 'active'): ?>
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Ativa
                                            </span>
                                        <?php elseif($campaign->status === 'active_direct'): ?>
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Link Direto
                                            </span>
                                        <?php elseif($campaign->status === 'draft'): ?>
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Rascunho
                                            </span>
                                        <?php elseif($campaign->status === 'paused'): ?>
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Pausada
                                            </span>
                                        <?php elseif($campaign->status === 'finished'): ?>
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Finalizada
                                            </span>
                                        <?php elseif($campaign->status === 'cancelled'): ?>
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Cancelada
                                            </span>
                                        <?php endif; ?>

                                        <a
                                            href="<?php echo e(route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id])); ?>">
                                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Abrir','icon' => 'eye'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'class' => 'text-xs py-1 px-3 whitespace-nowrap']); ?>
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
                                        </a>
                                    </div>
                                </div>

                                
                                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-500">
                                    <?php if($campaign->visibility_public && $campaign->customer_organization_slug): ?>
                                        <span
                                            class="inline-flex items-center gap-1 text-blue-600 bg-blue-50 rounded px-2 py-0.5 font-mono text-[11px]">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            /campanha/<?php echo e($campaign->customer_organization_slug); ?>/<?php echo e($campaign->slug); ?>

                                        </span>
                                    <?php endif; ?>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <?php echo e(\Carbon\Carbon::parse($campaign->created_at)->format('d/m/Y')); ?>

                                    </span>
                                    <?php if($campaign->datetime_start): ?>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Inicio:
                                            <?php echo e(\Carbon\Carbon::parse($campaign->datetime_start)->format('d/m/Y')); ?>

                                        </span>
                                    <?php endif; ?>
                                    <?php if($campaign->datetime_finish): ?>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Termino:
                                            <?php echo e(\Carbon\Carbon::parse($campaign->datetime_finish)->format('d/m/Y')); ?>

                                        </span>
                                    <?php endif; ?>
                                    <?php if($campaign->organization): ?>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <?php echo e($campaign->organization->organization_name); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>

                                
                                <div class="flex flex-wrap items-center gap-4 mt-3 pt-3 border-t border-gray-100">
                                    <?php if($goalAmount): ?>
                                        <div class="flex items-center gap-1.5">
                                            <div
                                                class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-[10px] text-gray-500 leading-none">Meta</div>
                                                <div class="text-sm font-bold text-gray-700 leading-tight">
                                                    <?php echo e(toMoney($goalAmount, 'R$ ')); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex items-center gap-1.5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5 text-green-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-500 leading-none">Arrecadado</div>
                                            <div class="text-sm font-bold text-green-600 leading-tight">
                                                <?php echo e(toMoney($totalDonationsPaid, 'R$ ')); ?></div>
                                        </div>
                                    </div>
                                    <?php if($goalAmount): ?>
                                        <?php $goalPct = $goalAmount > 0 ? min(round(($totalDonationsPaid / $goalAmount) * 100, 1), 999.9) : 0; ?>
                                        <div class="flex items-center gap-1.5 <?php echo e($goalPct >= 100 ? 'bg-emerald-50 border border-emerald-300 rounded-lg px-2 py-1' : ''); ?>">
                                            <div class="relative flex items-center justify-center shrink-0">
                                                <?php if($goalPct >= 100): ?>
                                                    <span class="absolute inline-flex h-7 w-7 rounded-full bg-emerald-400 opacity-50 animate-ping"></span>
                                                <?php endif; ?>
                                                <div class="w-7 h-7 rounded-full <?php echo e($goalPct >= 100 ? 'bg-emerald-500' : 'bg-amber-100'); ?> flex items-center justify-center relative z-10">
                                                    <svg class="w-3.5 h-3.5 <?php echo e($goalPct >= 100 ? 'text-white' : 'text-amber-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-[10px] <?php echo e($goalPct >= 100 ? 'text-emerald-700 font-semibold' : 'text-gray-500'); ?> leading-none">Êxito da Meta</div>
                                                <div class="text-sm font-bold <?php echo e($goalPct >= 100 ? 'text-emerald-700' : 'text-amber-600'); ?> leading-tight"><?php echo e($goalPct); ?>%</div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex items-center gap-1.5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-500 leading-none">Adesoes Geradas</div>
                                            <div class="text-sm font-bold text-indigo-600 leading-tight">
                                                <?php echo e($ordersTotalCount); ?></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-500 leading-none">Adesoes Pagas</div>
                                            <div class="text-sm font-bold text-blue-600 leading-tight">
                                                <?php echo e($ordersPaidCount); ?></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php elseif($customer_id && (($organizer_id ?? false) || ($organizer_id ?? '') === 'all') && $campaigns->count() < 1): ?>
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-10 text-center">
                    <div class="w-14 h-14 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800 mb-1">Nenhuma campanha encontrada</h3>
                    <p class="text-xs text-gray-500 mb-4">Comece criando sua primeira campanha de arrecadacao</p>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Criar Primeira Campanha','icon' => 'plus'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'wire:click' => 'novaCampanha']); ?>
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
            <?php elseif($customer_id && !$organizer_id): ?>
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8 text-center">
                    <div class="w-12 h-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V6a4 4 0 018 0v1m-9 0h10a2 2 0 012 2v6a3 3 0 01-3 3H8a3 3 0 01-3-3V9a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800 mb-1 uppercase">Selecione um Organizador</h3>
                    <p class="text-xs text-gray-500">Para visualizar as campanhas, selecione um organizador no filtro
                        acima</p>
                </div>
            <?php else: ?>
                <div
                    class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200 border-dashed p-10 text-center">
                    <div class="w-14 h-14 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 mb-1">Selecione uma empresa para comecar</h3>
                    <p class="text-xs text-gray-500">Escolha uma empresa no filtro acima para visualizar suas campanhas
                    </p>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/dashboard/dashboard-campanhas.blade.php ENDPATH**/ ?>