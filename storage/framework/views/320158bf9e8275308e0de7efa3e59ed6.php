<div class="sticky top-0 z-50" style="background: rgba(255,255,255,0.97); backdrop-filter: blur(14px); border-bottom: 1px solid #e5e7eb;">
    
    <div class="max-w-7xl mx-auto px-4 md:px-10 py-3 md:py-4">
        <?php if($isCampanhasPage || $isEventosPage): ?>
            
            <div class="md:hidden space-y-3">
                
                <div class="flex items-center justify-between">
                    <a href="<?php echo e(route('home')); ?>">
                        <img src="<?php echo e(appLogo(true)); ?>" alt="<?php echo e(appName()); ?>" class="h-8">
                    </a>

                    
                    <?php if(!auth()->user() || (!$isParticipantesPage && !$isCheckinPage)): ?>
                    <?php if(request()->routeIs('minhas-doacoes')): ?>
                    <a
                        href="<?php echo e(route('campanhas-home')); ?>"
                        class="px-3 py-2 text-xs border border-blue-500 rounded-lg font-semibold transition-colors flex items-center gap-1.5"
                        style="background: rgba(59,130,246,0.08); color: #2563eb;"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Campanhas</span>
                    </a>
                    <?php else: ?>
                    <a
                        href="<?php echo e($isCampanhasPage ? route('minhas-doacoes') : route('minhas-compras')); ?>"
                        class="px-3 py-2 text-xs border border-green-500 rounded-lg font-semibold transition-colors flex items-center gap-1.5"
                        style="background: rgba(34,197,94,0.08); color: #16a34a;"
                    >
                        <?php if($isCampanhasPage): ?>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <?php else: ?>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        <?php endif; ?>
                        <span><?php echo e($isCampanhasPage ? 'Doações' : 'Compras'); ?></span>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>

                
                <div class="flex gap-2">
                    
                    <div class="relative w-1/2" x-data="{ open: <?php if ((object) ('showCustomerDropdown') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showCustomerDropdown'->value()); ?>')<?php echo e('showCustomerDropdown'->hasModifier('defer') ? '.defer' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showCustomerDropdown'); ?>')<?php endif; ?> }">
                        <button
                            @click="open = !open"
                            class="w-full flex items-center justify-start text-left gap-2 px-3 py-2.5 rounded-lg transition-colors h-[42px]"
                            style="background: #f9fafb; border: 1px solid #e5e7eb;"
                        >
                            <div class="flex flex-col items-start flex-1 min-w-0">
                                <span class="text-[10px] uppercase leading-none tracking-wide" style="color: #9ca3af;">Parceiro</span>
                                <span class="text-xs font-semibold leading-snug truncate w-full uppercase" style="color: #374151;">
                                    <?php if($activeCustomerId): ?>
                                        <?php echo e(Str::limit($customers->firstWhere('id', $activeCustomerId)->name_corporate ?? 'Todos', 12)); ?>

                                    <?php else: ?>
                                        Todos
                                    <?php endif; ?>
                                </span>
                            </div>
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #9ca3af;" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute left-0 mt-2 w-56 rounded-xl py-1 z-50 max-h-80 overflow-y-auto"
                            style="background: #ffffff; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.08); display: none;"
                        >
                            <button
                                wire:click="$set('filterCustomer', ''); $set('filterCustomerSlug', '')"
                                class="w-full text-left px-4 py-2.5 text-xs transition-colors <?php echo e(!$activeCustomerId ? 'font-semibold' : ''); ?>"
                                style="color: <?php echo e(!$activeCustomerId ? '#1f2937' : '#6b7280'); ?>;"
                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                            >
                                Todos os Parceiros
                            </button>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button
                                    wire:click="$set('filterCustomer', '<?php echo e($customer->id); ?>'); $set('filterCustomerSlug', '')"
                                    class="w-full text-left px-4 py-2.5 text-xs transition-colors <?php echo e($activeCustomerId == $customer->id ? 'font-semibold' : ''); ?>"
                                    style="color: <?php echo e($activeCustomerId == $customer->id ? '#1f2937' : '#6b7280'); ?>;"
                                    onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                                >
                                    <?php echo e($customer->name_corporate); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div class="relative w-1/2" x-data="{ open: <?php if ((object) ('showOrganizerDropdown') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showOrganizerDropdown'->value()); ?>')<?php echo e('showOrganizerDropdown'->hasModifier('defer') ? '.defer' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showOrganizerDropdown'); ?>')<?php endif; ?> }">
                        <button
                            @click="open = !open"
                            class="w-full flex items-center justify-start text-left gap-2 px-3 py-2.5 rounded-lg transition-colors h-[42px]"
                            style="background: #f9fafb; border: 1px solid #e5e7eb;"
                        >
                            <div class="flex flex-col items-start flex-1 min-w-0">
                                <span class="text-[10px] uppercase leading-none tracking-wide" style="color: #9ca3af;">Organizador</span>
                                <span class="text-xs font-semibold leading-snug truncate w-full uppercase" style="color: #374151;">
                                    <?php if($filterOrganizer): ?>
                                        <?php echo e(Str::limit($organizers->firstWhere('id', $filterOrganizer)->organizer_name ?? 'Todos', 11)); ?>

                                    <?php else: ?>
                                        Todos
                                    <?php endif; ?>
                                </span>
                            </div>
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #9ca3af;" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-56 rounded-xl py-1 z-50 max-h-80 overflow-y-auto"
                            style="background: #ffffff; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.08); display: none;"
                        >
                            <button
                                wire:click="$set('filterOrganizer', '')"
                                class="w-full text-left px-4 py-2.5 text-xs transition-colors <?php echo e(!$filterOrganizer ? 'font-semibold' : ''); ?>"
                                style="color: <?php echo e(!$filterOrganizer ? '#1f2937' : '#6b7280'); ?>;"
                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                            >
                                Todos os Organizadores
                            </button>
                            <?php $__currentLoopData = $organizers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organizer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button
                                    wire:click="$set('filterOrganizer', '<?php echo e($organizer->id); ?>')"
                                    title="<?php echo e($organizer->organizer_name_full); ?>"
                                    class="w-full text-left px-3 py-2 transition-colors <?php echo e($filterOrganizer == $organizer->id ? 'font-semibold' : ''); ?>"
                                    style="border-bottom: 1px solid #f3f4f6; color: <?php echo e($filterOrganizer == $organizer->id ? '#1f2937' : '#6b7280'); ?>;"
                                    onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                                >
                                    
                                    <p class="text-[10px] uppercase truncate leading-tight" style="color: #9ca3af;">
                                        <?php echo e(Str::limit($organizer->customer->name_corporate ?? '', 18)); ?>

                                        <?php if($organizer->organization): ?>
                                            <span class="mx-0.5">|</span><?php echo e(Str::limit($organizer->organization->organization_name, 18)); ?>

                                        <?php endif; ?>
                                    </p>
                                    
                                    <p class="text-xs font-bold uppercase truncate leading-tight mt-0.5" style="color: #374151;">
                                        <?php echo e($organizer->organizer_name); ?>

                                    </p>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div class="grid grid-cols-6 gap-2">
                    
                    <div class="col-span-4">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4" style="color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input
                                type="text"
                                wire:model.debounce.300ms="search"
                                placeholder="Buscar..."
                                class="w-full pl-9 pr-3 py-2.5 rounded-lg text-sm h-[42px] focus:outline-none transition-colors"
                                style="background: #f9fafb; border: 1px solid #e5e7eb; color: #374151;"
                            />
                        </div>
                    </div>

                    
                    <div class="relative col-span-2" x-data="{ open: <?php if ((object) ('showStatusDropdown') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showStatusDropdown'->value()); ?>')<?php echo e('showStatusDropdown'->hasModifier('defer') ? '.defer' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showStatusDropdown'); ?>')<?php endif; ?> }">
                        <button
                            @click="open = !open"
                            class="w-full flex items-center justify-start text-left gap-2 px-3 py-2.5 rounded-lg transition-colors h-[42px]"
                            style="background: #f9fafb; border: 1px solid #e5e7eb;"
                        >
                            <div class="flex flex-col items-start flex-1">
                                <span class="text-[10px] uppercase leading-none tracking-wide" style="color: #9ca3af;">Status</span>
                                <span class="text-xs font-semibold leading-snug uppercase" style="color: #374151;">
                                    <?php if($filterStatus === 'ativas'): ?> Ativas
                                    <?php elseif($filterStatus === 'todas'): ?> Todas
                                    <?php elseif($filterStatus === 'finalizadas'): ?> Finalizadas
                                    <?php endif; ?>
                                </span>
                            </div>
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #9ca3af;" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-40 rounded-xl py-1 z-50"
                            style="background: #ffffff; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.08); display: none;"
                        >
                            <button
                                wire:click="$set('filterStatus', 'ativas')"
                                class="w-full text-left px-4 py-2 text-xs transition-colors <?php echo e($filterStatus === 'ativas' ? 'font-semibold' : ''); ?>"
                                style="color: <?php echo e($filterStatus === 'ativas' ? '#1f2937' : '#6b7280'); ?>;"
                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                            >
                                Ativas
                            </button>
                            <button
                                wire:click="$set('filterStatus', 'todas')"
                                class="w-full text-left px-4 py-2 text-xs transition-colors <?php echo e($filterStatus === 'todas' ? 'font-semibold' : ''); ?>"
                                style="color: <?php echo e($filterStatus === 'todas' ? '#1f2937' : '#6b7280'); ?>;"
                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                            >
                                Todas
                            </button>
                            <button
                                wire:click="$set('filterStatus', 'finalizadas')"
                                class="w-full text-left px-4 py-2 text-xs transition-colors <?php echo e($filterStatus === 'finalizadas' ? 'font-semibold' : ''); ?>"
                                style="color: <?php echo e($filterStatus === 'finalizadas' ? '#1f2937' : '#6b7280'); ?>;"
                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                            >
                                Finalizadas
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="hidden md:flex items-center gap-3">
                <!-- Logo -->
                <a href="<?php echo e(route('home')); ?>" class="shrink-0 flex items-center mr-2 md:mr-8">
                    <img src="<?php echo e(appLogo(true)); ?>" alt="<?php echo e(appName()); ?>" class="h-10 max-w-[180px] object-contain">
                </a>

                
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4" style="color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input
                            type="text"
                            wire:model.debounce.300ms="search"
                            placeholder="<?php echo e($isEventosPage ? 'Buscar eventos...' : 'Buscar campanhas...'); ?>"
                            class="w-full pl-9 pr-4 py-2 rounded-lg text-sm focus:outline-none transition-colors"
                            style="background: #f9fafb; border: 1px solid #e5e7eb; color: #374151;"
                        />
                    </div>
                </div>

                    
                    <div class="relative" x-data="{ open: <?php if ((object) ('showStatusDropdown') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showStatusDropdown'->value()); ?>')<?php echo e('showStatusDropdown'->hasModifier('defer') ? '.defer' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showStatusDropdown'); ?>')<?php endif; ?> }">
                        <button
                            @click="open = !open"
                            class="flex items-center justify-start text-left gap-2 pl-3 pr-2.5 py-1.5 rounded-lg transition-colors"
                            style="background: #f9fafb; border: 1px solid #e5e7eb;"
                        >
                            <div class="flex flex-col items-start w-full">
                                <span class="text-[10px] uppercase leading-none tracking-wide" style="color: #9ca3af;">Status</span>
                                <span class="text-sm font-semibold leading-snug uppercase" style="color: #374151;">
                                    <?php if($filterStatus === 'ativas'): ?> Ativas
                                    <?php elseif($filterStatus === 'todas'): ?> Todas
                                    <?php elseif($filterStatus === 'finalizadas'): ?> Finalizadas
                                    <?php endif; ?>
                                </span>
                            </div>
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #9ca3af;" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-48 rounded-xl py-1 z-50"
                            style="background: #ffffff; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.08); display: none;"
                        >
                            <button
                                wire:click="$set('filterStatus', 'ativas')"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors <?php echo e($filterStatus === 'ativas' ? 'font-semibold' : ''); ?>"
                                style="color: <?php echo e($filterStatus === 'ativas' ? '#1f2937' : '#6b7280'); ?>;"
                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                            >
                                Ativas
                            </button>
                            <button
                                wire:click="$set('filterStatus', 'todas')"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors <?php echo e($filterStatus === 'todas' ? 'font-semibold' : ''); ?>"
                                style="color: <?php echo e($filterStatus === 'todas' ? '#1f2937' : '#6b7280'); ?>;"
                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                            >
                                Todas
                            </button>
                            <button
                                wire:click="$set('filterStatus', 'finalizadas')"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors <?php echo e($filterStatus === 'finalizadas' ? 'font-semibold' : ''); ?>"
                                style="color: <?php echo e($filterStatus === 'finalizadas' ? '#1f2937' : '#6b7280'); ?>;"
                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                            >
                                Finalizadas
                            </button>
                        </div>
                    </div>

                    
                    <div class="relative" x-data="{ open: <?php if ((object) ('showCustomerDropdown') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showCustomerDropdown'->value()); ?>')<?php echo e('showCustomerDropdown'->hasModifier('defer') ? '.defer' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showCustomerDropdown'); ?>')<?php endif; ?> }">
                        <button
                            @click="open = !open"
                            class="flex items-center justify-start text-left gap-2 pl-3 pr-2.5 py-1.5 rounded-lg transition-colors min-w-[160px]"
                            style="background: #f9fafb; border: 1px solid #e5e7eb;"
                        >
                            <div class="flex flex-col items-start flex-1 min-w-0">
                                <span class="text-[10px] uppercase leading-none tracking-wide" style="color: #9ca3af;">Parceiro</span>
                                <span class="text-sm font-semibold leading-snug truncate w-full uppercase" style="color: #374151;">
                                    <?php if($activeCustomerId): ?>
                                        <?php echo e(Str::limit($customers->firstWhere('id', $activeCustomerId)->name_corporate ?? 'Todos', 20)); ?>

                                    <?php else: ?>
                                        Todos
                                    <?php endif; ?>
                                </span>
                            </div>
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #9ca3af;" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-64 rounded-xl py-1 z-50 max-h-96 overflow-y-auto"
                            style="background: #ffffff; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.08); display: none;"
                        >
                            <button
                                wire:click="$set('filterCustomer', ''); $set('filterCustomerSlug', '')"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors <?php echo e(!$activeCustomerId ? 'font-semibold' : ''); ?>"
                                style="color: <?php echo e(!$activeCustomerId ? '#1f2937' : '#6b7280'); ?>;"
                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                            >
                                Todos os Parceiros
                            </button>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button
                                    wire:click="$set('filterCustomer', '<?php echo e($customer->id); ?>'); $set('filterCustomerSlug', '')"
                                    class="w-full capitalize text-left px-4 py-2.5 text-sm transition-colors <?php echo e($activeCustomerId == $customer->id ? 'font-semibold' : ''); ?>"
                                    style="color: <?php echo e($activeCustomerId == $customer->id ? '#1f2937' : '#6b7280'); ?>;"
                                    onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                                >
                                    <?php echo e($customer->name_corporate); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div class="relative" x-data="{ open: <?php if ((object) ('showOrganizerDropdown') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showOrganizerDropdown'->value()); ?>')<?php echo e('showOrganizerDropdown'->hasModifier('defer') ? '.defer' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showOrganizerDropdown'); ?>')<?php endif; ?> }">
                        <button
                            @click="open = !open"
                            class="flex items-center justify-start text-left gap-2 pl-3 pr-2.5 py-1.5 rounded-lg transition-colors min-w-[160px]"
                            style="background: #f9fafb; border: 1px solid #e5e7eb;"
                        >
                            <div class="flex flex-col items-start flex-1 min-w-0">
                                <span class="text-[10px] uppercase leading-none tracking-wide" style="color: #9ca3af;">Organizador</span>
                                <span class="text-sm font-semibold leading-snug truncate w-full uppercase" style="color: #374151;">
                                    <?php if($filterOrganizer): ?>
                                        <?php echo e(Str::limit($organizers->firstWhere('id', $filterOrganizer)->organizer_name ?? 'Todos', 20)); ?>

                                    <?php else: ?>
                                        Todos
                                    <?php endif; ?>
                                </span>
                            </div>
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #9ca3af;" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="capitalize absolute right-0 mt-2 w-64 rounded-xl py-1 z-50 max-h-96 overflow-y-auto"
                            style="background: #ffffff; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.08); display: none;"
                        >
                            <button
                                wire:click="$set('filterOrganizer', '')"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors <?php echo e(!$filterOrganizer ? 'font-semibold' : ''); ?>"
                                style="color: <?php echo e(!$filterOrganizer ? '#1f2937' : '#6b7280'); ?>;"
                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                            >
                                Todos os Organizadores
                            </button>
                            <?php $__currentLoopData = $organizers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organizer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button
                                    wire:click="$set('filterOrganizer', '<?php echo e($organizer->id); ?>')"
                                    title="<?php echo e($organizer->organizer_name_full); ?>"
                                    class="w-full text-left px-4 py-2.5 transition-colors <?php echo e($filterOrganizer == $organizer->id ? 'font-semibold' : ''); ?>"
                                    style="border-bottom: 1px solid #f3f4f6; color: <?php echo e($filterOrganizer == $organizer->id ? '#1f2937' : '#6b7280'); ?>;"
                                    onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''"
                                >
                                    
                                    <p class="text-[10px] uppercase truncate leading-tight" style="color: #9ca3af;">
                                        <?php echo e(Str::limit($organizer->customer->name_corporate ?? '', 22)); ?>

                                        <?php if($organizer->organization): ?>
                                            <span class="mx-0.5">|</span><?php echo e(Str::limit($organizer->organization->organization_name, 22)); ?>

                                        <?php endif; ?>
                                    </p>
                                    
                                    <p class="text-sm font-bold uppercase truncate leading-tight mt-0.5" style="color: #374151;">
                                        <?php echo e($organizer->organizer_name); ?>

                                    </p>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <?php if(!auth()->user() || (!$isParticipantesPage && !$isCheckinPage)): ?>
                    <?php if(request()->routeIs('minhas-doacoes')): ?>
                    <a
                        href="<?php echo e(route('campanhas-home')); ?>"
                        class="px-4 py-2 text-sm border border-blue-500 rounded-lg font-semibold hover:shadow-md focus:outline-none transition-colors flex items-center gap-2"
                        style="background: rgba(59,130,246,0.08); color: #2563eb;"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div>Voltar às Campanhas</div>
                    </a>
                    <?php else: ?>
                    <a
                        href="<?php echo e($isCampanhasPage ? route('minhas-doacoes') : route('minhas-compras')); ?>"
                        class="px-4 py-2 text-sm border border-green-500 rounded-lg font-semibold hover:shadow-md focus:outline-none transition-colors flex items-center gap-2"
                        style="background: rgba(34,197,94,0.08); color: #16a34a;"
                    >
                        <?php if($isCampanhasPage): ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <?php else: ?>
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        <?php endif; ?>
                        <div><?php echo e($isCampanhasPage ? 'MINHAS DOAÇÕES' : 'MINHAS COMPRAS'); ?></div>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
            </div>
        <?php else: ?>
            
            <div class="flex justify-between items-center gap-4">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="<?php echo e(route('home')); ?>">
                        
                        <img src="<?php echo e(appLogo(true)); ?>" alt="<?php echo e(appName()); ?>" class="h-6 md:h-10">
                    </a>
                </div>

                <!-- Botão Minhas Compras / Voltar às Campanhas -->
                <?php if(!auth()->user() || (!$isParticipantesPage && !$isCheckinPage)): ?>
                <?php if(request()->routeIs('minhas-doacoes')): ?>
                <a
                    href="<?php echo e(route('campanhas-home')); ?>"
                    class="px-3 md:px-4 py-2 text-xs md:text-sm border border-blue-500 rounded-lg font-semibold hover:shadow-md focus:outline-none transition-colors flex items-center gap-1.5 md:gap-2"
                    style="background: rgba(59,130,246,0.08); color: #2563eb;"
                >
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Voltar às Campanhas</span>
                </a>
                <?php else: ?>
                <a
                    href="<?php echo e(route('minhas-compras')); ?>"
                    class="px-3 md:px-4 py-2 text-xs md:text-sm border border-green-500 rounded-lg font-semibold hover:shadow-md focus:outline-none transition-colors flex items-center gap-1.5 md:gap-2"
                    style="background: rgba(34,197,94,0.08); color: #16a34a;"
                >
                    <svg class="w-4 h-4 md:w-5 md:h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <span class="hidden md:inline">MINHAS COMPRAS</span>
                    <span class="md:hidden">COMPRAS</span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/navigation/navigation-menu-pep-guest.blade.php ENDPATH**/ ?>