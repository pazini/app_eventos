<?php
    use Illuminate\Support\Str;
?>

<div>
    <style>
        /* Compatibilidade visual do conteúdo rico do CKEditor nesta tela */
        .campaign-editor-content .text-tiny {
            font-size: 0.7em;
        }

        .campaign-editor-content .text-small {
            font-size: 0.85em;
        }

        .campaign-editor-content .text-big {
            font-size: 1.4em;
        }

        .campaign-editor-content .text-huge {
            font-size: 1.8em;
        }

        .campaign-editor-content span[style*="color"] strong,
        .campaign-editor-content span[style*="color"] b {
            color: inherit;
        }
    </style>

    <div class="<?php echo e(setClass('divContentHeader')); ?>">
        <div class="w-full flex justify-between items-center">
            <div>
                <?php echo setLabelHeader(
                    'Campanha',
                    $campaign->name,
                    $campaign->organizer->organizer_name_full ?? ($campaign->organizer->organizer_name ?? 'Organizador'),
                ); ?>

            </div>
            <div class="flex items-center gap-2">
                <?php if($activeTab === 'detalhes'): ?>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'VOLTAR','href' => ''.e(route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id])).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true]); ?>
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
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'DETALHES','href' => ''.e(route('dashboard-campanhas-detalhes-detalhes', ['campaign_id' => $campaign->id])).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true]); ?>
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
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'FECHAR','href' => ''.e(route('dashboard-campanhas')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true]); ?>
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

                <!-- <?php if($campaign->status !== 'cancelled'): ?>
<?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'ARQUIVAR'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['red' => true,'wire:click' => 'arquivar','wire:confirm' => 'Tem certeza que deseja arquivar esta campanha?']); ?>
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
<?php endif; ?> -->
            </div>
        </div>
    </div>

    <?php if(!$showManualOrderModal && !$showOrderEditModal): ?>
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
    <?php endif; ?>

    <div class="w-full max-w-7xl mx-auto mt-4 space-y-4">
        <?php if($activeTab !== 'detalhes'): ?>

            
            <div class="bg-white border border-green-200 rounded-xl shadow-md px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-[10px] font-semibold text-gray-600 uppercase mb-1">URL da Campanha</div>
                        <div class="flex items-center gap-2">
                            <input type="text"
                                value="<?php echo e(campanhaUrl($campaign->customer_organization_slug, $campaign->slug)); ?>"
                                id="campaign-url" readonly
                                class="flex-1 px-0 py-1 text-sm font-mono bg-white border border-white rounded text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <button onclick="copiarURL()"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Copiar
                            </button>
                            <a href="<?php echo e(campanhaUrl($campaign->customer_organization_slug, $campaign->slug)); ?>"
                                target="_blank"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                                Abrir
                            </a>
                            <button wire:click="openQrCodeModal"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                    </path>
                                </svg>
                                QR Code
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white border rounded-xl shadow-md px-4 md:px-6 py-4">
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase">Organizador</div>
                        <div class="text-lg text-gray-900 uppercase">
                            <?php echo e($campaign->organizer->organizer_name_full ?? ($campaign->organizer->organizer_name ?? '-')); ?>

                        </div>
                    </div>
                    
                    <div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase">Status</div>
                        <div class="text-lg font-bold uppercase">
                            <?php if($campaign->status === 'active'): ?>
                                <span class="text-green-600">Ativa</span>
                            <?php elseif($campaign->status === 'active_direct'): ?>
                                <span class="text-blue-600">Ativa - Link Direto</span>
                            <?php elseif($campaign->status === 'draft'): ?>
                                <span class="text-gray-600">Rascunho</span>
                            <?php elseif($campaign->status === 'paused'): ?>
                                <span class="text-orange-600">Pausada</span>
                            <?php elseif($campaign->status === 'finished'): ?>
                                <span class="text-blue-600">Finalizada</span>
                            <?php else: ?>
                                <span class="text-red-600">Arquivada</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase">A partir de</div>
                        <div class="text-lg text-gray-900">
                            <?php echo e($campaign->datetime_start ? \Carbon\Carbon::parse($campaign->datetime_start)->format('d/m/Y') : 'Sem data início'); ?>

                        </div>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase">Até</div>
                        <div
                            class="text-lg <?php echo e($campaign->datetime_finish ? 'text-gray-900' : 'text-gray-400 italic'); ?>">
                            <?php echo e($campaign->datetime_finish ? \Carbon\Carbon::parse($campaign->datetime_finish)->format('d/m/Y') : 'não definida'); ?>

                        </div>
                    </div>
                </div>
            </div>

            
            <?php if(!$selectedOrderId && $activeTab !== 'detalhes'): ?>
                <div class="bg-white border rounded-xl shadow-md">
                    <div class="flex border-b">
                        <button wire:click="setTab('analiticos')"
                            class="flex-1 px-6 py-4 text-sm font-semibold transition-colors <?php echo e($activeTab === 'analiticos' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'); ?>">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                <span>ANALÍTICO</span>
                            </div>
                        </button>
                        <button wire:click="setTab('adesoes')"
                            class="flex-1 px-6 py-4 text-sm font-semibold transition-colors <?php echo e($activeTab === 'adesoes' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'); ?>">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <span>ADESÕES</span>
                            </div>
                        </button>
                        <button wire:click="setTab('participantes')"
                            class="flex-1 px-6 py-4 text-sm font-semibold transition-colors <?php echo e($activeTab === 'participantes' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'); ?>">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                                <span>PARTICIPANTES</span>
                            </div>
                        </button>
                        <button wire:click="setTab('questionarios')"
                            class="flex-1 px-6 py-4 text-sm font-semibold transition-colors <?php echo e($activeTab === 'questionarios' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'); ?>">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <span>QUESTIONÁRIOS</span>
                            </div>
                        </button>
                        <button wire:click="setTab('transacoes')"
                            class="flex-1 px-6 py-4 text-sm font-semibold transition-colors <?php echo e($activeTab === 'transacoes' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'); ?>">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                                <span>TRANSAÇÕES</span>
                            </div>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php if($activeTab === 'analiticos'): ?>
            <div wire:key="tab-analiticos" class="space-y-6">
                <?php
                    $metrics = $metricsLast30Days ?? [];
                    $period = $periodComparison ?? [];
                    $hasGoalAmount = !is_null($campaign->goal_amount) && (int) $campaign->goal_amount > 0;
                    $hasGoalLeads = !is_null($campaign->goal_leads) && (int) $campaign->goal_leads > 0;
                ?>
                
                <div class="grid grid-cols-4 gap-4">
                    
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Receita (30 dias)</div>
                        <div class="text-2xl font-bold text-green-600">
                            <?php echo e(toMoney(data_get($metrics, 'revenue', 0), 'R$ ')); ?>

                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">
                                Meta: <?php echo e($hasGoalAmount ? toMoney($campaign->goal_amount, 'R$ ') : '-'); ?>

                            </div>
                            <?php if(!is_null(data_get($period, 'revenue'))): ?>
                                <div
                                    class="text-[10px] font-bold <?php echo e(data_get($period, 'revenue.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e(data_get($period, 'revenue.percent', 0) >= 0 ? '▲' : '▼'); ?>

                                    <?php echo e(abs(data_get($period, 'revenue.percent', 0))); ?>%
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(!is_null(data_get($period, 'revenue'))): ?>
                            <div class="mt-2 text-[9px] text-gray-400">
                                Período anterior: <?php echo e(toMoney(data_get($period, 'revenue.previous', 0), 'R$ ')); ?>

                            </div>
                        <?php endif; ?>
                        
                        <?php
                            $revenuePercent = $hasGoalAmount
                                ? min(100, (data_get($metrics, 'revenue', 0) / $campaign->goal_amount) * 100)
                                : 0;
                        ?>
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full transition-all"
                                    style="width: <?php echo e($revenuePercent); ?>%"></div>
                            </div>
                            <div class="text-[9px] text-gray-500 mt-1">
                                <?php echo e($hasGoalAmount ? number_format($revenuePercent, 1) . '% da meta alcançada' : 'Meta não definida'); ?>

                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Leads (30 dias)</div>
                        <div class="text-2xl font-bold text-purple-600">
                            <?php echo e(data_get($metrics, 'leads', 0)); ?>

                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">
                                Meta: <?php echo e($hasGoalLeads ? number_format($campaign->goal_leads, 0, ',', '.') : '-'); ?>

                            </div>
                            <?php if(!is_null(data_get($period, 'leads'))): ?>
                                <div
                                    class="text-[10px] font-bold <?php echo e(data_get($period, 'leads.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e(data_get($period, 'leads.percent', 0) >= 0 ? '▲' : '▼'); ?>

                                    <?php echo e(abs(data_get($period, 'leads.percent', 0))); ?>%
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(!is_null(data_get($period, 'leads'))): ?>
                            <div class="mt-2 text-[9px] text-gray-400">
                                Período anterior: <?php echo e(data_get($period, 'leads.previous', 0)); ?>

                            </div>
                        <?php endif; ?>
                        
                        <?php
                            $leadsPercent = $hasGoalLeads
                                ? min(100, (data_get($metrics, 'leads', 0) / $campaign->goal_leads) * 100)
                                : 0;
                        ?>
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full transition-all"
                                    style="width: <?php echo e($leadsPercent); ?>%"></div>
                            </div>
                            <div class="text-[9px] text-gray-500 mt-1">
                                <?php echo e($hasGoalLeads ? number_format($leadsPercent, 1) . '% da meta alcançada' : 'Meta não definida'); ?>

                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Adesões (30 dias)</div>
                        <div class="text-2xl font-bold text-blue-600">
                            <?php echo e(data_get($metrics, 'orders', 0)); ?>

                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">
                                Pagas: <?php echo e(data_get($metrics, 'paid_orders', 0)); ?>

                            </div>
                            <?php if(!is_null(data_get($period, 'orders'))): ?>
                                <div
                                    class="text-[10px] font-bold <?php echo e(data_get($period, 'orders.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e(data_get($period, 'orders.percent', 0) >= 0 ? '▲' : '▼'); ?>

                                    <?php echo e(abs(data_get($period, 'orders.percent', 0))); ?>%
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(!is_null(data_get($period, 'orders'))): ?>
                            <div class="mt-2 text-[9px] text-gray-400">
                                Período anterior: <?php echo e(data_get($period, 'orders.previous', 0)); ?>

                            </div>
                        <?php endif; ?>
                        
                        <?php
                            $ordersTotal = data_get($metrics, 'orders', 0);
                            $ordersPaid = data_get($metrics, 'paid_orders', 0);
                            $conversionRate = $ordersTotal > 0 ? ($ordersPaid / $ordersTotal) * 100 : 0;
                        ?>
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all"
                                    style="width: <?php echo e($conversionRate); ?>%"></div>
                            </div>
                            <div class="text-[9px] text-gray-500 mt-1">
                                <?php echo e(number_format($conversionRate, 1)); ?>% de conversão de pagamento
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Conversões (30 dias)</div>
                        <div class="text-2xl font-bold text-orange-600">
                            <?php echo e(data_get($metrics, 'conversions', 0)); ?>

                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">
                                Meta: <?php echo e(number_format($campaign->goal_conversions ?? 0, 0, ',', '.')); ?>

                            </div>
                            <?php if(!is_null(data_get($period, 'conversions'))): ?>
                                <div
                                    class="text-[10px] font-bold <?php echo e(data_get($period, 'conversions.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e(data_get($period, 'conversions.percent', 0) >= 0 ? '▲' : '▼'); ?>

                                    <?php echo e(abs(data_get($period, 'conversions.percent', 0))); ?>%
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(!is_null(data_get($period, 'conversions'))): ?>
                            <div class="mt-2 text-[9px] text-gray-400">
                                Período anterior: <?php echo e(data_get($period, 'conversions.previous', 0)); ?>

                            </div>
                        <?php endif; ?>
                        
                        <?php
                            $conversionsPercent =
                                $campaign->goal_conversions > 0
                                    ? min(
                                        100,
                                        (data_get($metrics, 'conversions', 0) / $campaign->goal_conversions) * 100,
                                    )
                                    : 0;
                        ?>
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-600 h-2 rounded-full transition-all"
                                    style="width: <?php echo e($conversionsPercent); ?>%"></div>
                            </div>
                            <div class="text-[9px] text-gray-500 mt-1">
                                <?php echo e(number_format($conversionsPercent, 1)); ?>% da meta alcançada
                            </div>
                        </div>
                    </div>
                </div>

                
                
                <div class="mt-6 bg-white border rounded-sm shadow px-4 py-4 w-full">
                    <div class="text-sm font-semibold text-gray-800 uppercase border-b pb-2 mb-3">
                        <div class="flex justify-between items-center">
                            <div>Receita Diária (Últimos 30 Dias)</div>
                            <div><?php echo e(now()->subDays(30)->format('d/m/Y')); ?> até <?php echo e(now()->format('d/m/Y')); ?></div>
                        </div>
                    </div>
                    <div class="w-full" style="position: relative; height: 300px;">
                        <canvas id="revenueChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>

                
                <div class="mt-6 bg-white border rounded-sm shadow px-4 py-4 w-full">
                    <div class="text-sm font-semibold text-gray-800 uppercase border-b pb-2 mb-3">
                        Adesões Diárias (Últimos 30 Dias)
                    </div>
                    <div class="w-full" style="position: relative; height: 300px;">
                        <canvas id="transactionsChart"
                            style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>

            </div>
        <?php endif; ?>
        

        
        <?php if($activeTab === 'detalhes'): ?>
            <div wire:key="tab-detalhes" class="space-y-6">

                
                

                
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informações Básicas
                            </h3>
                            
                            <div class="relative inline-block" x-data="{ open: false }" @click.away="open = false"
                                @close.stop="open = false">
                                <button type="button" @click.stop="open = ! open"
                                    class="inline-flex items-center justify-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 min-w-[36px] min-h-[36px]"
                                    aria-label="Menu de opções" aria-expanded="false" x-bind:aria-expanded="open">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                        </path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 mt-2 w-52 rounded-md shadow-lg origin-top-right right-0"
                                    style="display: none;" @click.stop="open = false">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a href="<?php echo e(route('dashboard-campanhas-editar', ['campaign_id' => $campaign->id])); ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Editar
                                        </a>
                                        <?php if($campaign->status === 'draft' || $campaign->status === 'paused'): ?>
                                            <button wire:click="ativar"
                                                class="w-full text-left block px-4 py-2 text-sm text-green-700 hover:bg-green-50 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                </svg>
                                                Ativar
                                            </button>
                                        <?php endif; ?>
                                        <?php if($campaign->status === 'active' || $campaign->status === 'active_direct'): ?>
                                            <button wire:click="pausar"
                                                class="w-full text-left block px-4 py-2 text-sm text-orange-700 hover:bg-orange-50 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Pausar
                                            </button>
                                        <?php endif; ?>
                                        <?php if(isAdmin()): ?>
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <button wire:click="abrirModalClonar"
                                                class="w-full text-left block px-4 py-2 text-sm text-indigo-700 hover:bg-indigo-50 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Clonar Campanha
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Nome da
                                    Campanha</label>
                                <div class="text-base font-bold text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    <?php echo e($campaign->name); ?>

                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Nome
                                    Curto</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    <?php echo e($campaign->name_short ?: '-'); ?>

                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Slug
                                    (URL)</label>
                                <div
                                    class="text-base font-mono text-blue-600 bg-blue-50 px-4 py-3 rounded-lg border border-blue-200">
                                    <?php echo e($campaign->slug); ?>

                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Status</label>
                                <div class="flex items-center gap-2">
                                    <?php if($campaign->status === 'active'): ?>
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-green-100 text-green-700 border border-green-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA ATIVA
                                        </span>
                                    <?php elseif($campaign->status === 'active_direct'): ?>
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-blue-100 text-blue-700 border border-blue-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA ATIVA - LINK DIRETO
                                        </span>
                                    <?php elseif($campaign->status === 'draft'): ?>
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-gray-100 text-gray-700 border border-gray-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA RASCUNHO
                                        </span>
                                    <?php elseif($campaign->status === 'paused'): ?>
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-orange-100 text-orange-700 border border-orange-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA PAUSADA
                                        </span>
                                    <?php elseif($campaign->status === 'finished'): ?>
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-blue-100 text-blue-700 border border-blue-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA FINALIZADA
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-red-100 text-red-700 border border-red-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA CANCELADA
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Data de
                                    Início</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    <?php echo e($campaign->datetime_start ? \Carbon\Carbon::parse($campaign->datetime_start)->format('d/m/Y H:i') : 'Não definida'); ?>

                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Data de
                                    Término</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    <?php echo e($campaign->datetime_finish ? \Carbon\Carbon::parse($campaign->datetime_finish)->format('d/m/Y H:i') : 'Não definida'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 md:px-6 py-3 border-b">
                        <h3 class="text-base md:text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            Metas e Valores
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="flex flex-col">
                                <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1.5">Meta de
                                    Receita</label>
                                <div
                                    class="text-lg md:text-xl font-bold text-green-700 bg-green-50 px-3 py-2.5 rounded-lg border border-green-200 break-words">
                                    <?php echo e(!is_null($campaign->goal_amount) ? toMoney($campaign->goal_amount, 'R$ ') : '-'); ?>

                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1.5">Meta de
                                    Leads</label>
                                <div
                                    class="text-lg md:text-xl font-bold text-purple-700 bg-purple-50 px-3 py-2.5 rounded-lg border border-purple-200 break-words">
                                    <?php echo e(!is_null($campaign->goal_leads) ? number_format($campaign->goal_leads, 0, ',', '.') : '-'); ?>

                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1.5">Meta de
                                    Conversões</label>
                                <div
                                    class="text-lg md:text-xl font-bold text-orange-700 bg-orange-50 px-3 py-2.5 rounded-lg border border-orange-200 break-words">
                                    <?php echo e(number_format($campaign->goal_conversions ?? 0, 0, ',', '.')); ?>

                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1.5">Valor
                                    Mínimo</label>
                                <div
                                    class="text-lg md:text-xl font-bold text-blue-700 bg-blue-50 px-3 py-2.5 rounded-lg border border-blue-200 break-words">
                                    <?php echo e(toMoney($campaign->amount_min ?? 1000, 'R$ ')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="pt-4 mt-4 border-t"></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg border transition-all <?php echo e($campaign->show_goal_amount ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200'); ?>">
                                <?php if($campaign->show_goal_amount): ?>
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                <?php else: ?>
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <span
                                    class="text-xs font-medium pl-2 <?php echo e($campaign->show_goal_amount ? 'text-green-700' : 'text-gray-500'); ?>">Meta
                                    de receita</span>
                            </div>
                            <div
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg border transition-all <?php echo e($campaign->show_goal_leads ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200'); ?>">
                                <?php if($campaign->show_goal_leads): ?>
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                <?php else: ?>
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <span
                                    class="text-xs font-medium pl-2 <?php echo e($campaign->show_goal_leads ? 'text-green-700' : 'text-gray-500'); ?>">Meta
                                    de leads</span>
                            </div>
                            <div
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg border transition-all <?php echo e($campaign->show_goal_conversions ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200'); ?>">
                                <?php if($campaign->show_goal_conversions): ?>
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                <?php else: ?>
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <span
                                    class="text-xs font-medium pl-2 <?php echo e($campaign->show_goal_conversions ? 'text-green-700' : 'text-gray-500'); ?>">Meta
                                    de conversões</span>
                            </div>
                            <div
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg border transition-all <?php echo e($campaign->show_progress ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200'); ?>">
                                <?php if($campaign->show_progress): ?>
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                <?php else: ?>
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <span
                                    class="text-xs font-medium pl-2 <?php echo e($campaign->show_progress ? 'text-green-700' : 'text-gray-500'); ?>">Exibir
                                    progresso</span>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            Conteúdo Descritivo
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Descrição</label>
                            <div
                                class="prose max-w-none bg-gray-50 px-4 py-3 rounded-lg border min-h-[100px] campaign-editor-content">
                                <?php if($campaign->description): ?>
                                    <?php echo $campaign->description; ?>

                                <?php else: ?>
                                    <span class="text-gray-400 italic">Sem descrição</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Sobre
                                (Detalhes)</label>
                            <div
                                class="prose max-w-none bg-gray-50 px-4 py-3 rounded-lg border min-h-[100px] campaign-editor-content">
                                <?php if($campaign->about): ?>
                                    <?php echo $campaign->about; ?>

                                <?php else: ?>
                                    <span class="text-gray-400 italic">Sem informações detalhadas</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Privacidade e Configurações
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Perguntas do Quiz
                                    </label>
                                    <?php if($campaign->enable_questions): ?>
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            ATIVO
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">
                                            INATIVO
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-gray-600 mt-2">
                                    <?php if($campaign->enable_questions): ?>
                                        Os doadores devem responder perguntas personalizadas antes de contribuir
                                    <?php else: ?>
                                        As perguntas do quiz estão desabilitadas para esta campanha
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Exigir CPF/CNPJ
                                    </label>
                                    <?php if($campaign->require_doc): ?>
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            OBRIGATÓRIO
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-bold">
                                            OPCIONAL
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-gray-600 mt-2">
                                    <?php if($campaign->require_doc): ?>
                                        O preenchimento do documento é obrigatório para todos os doadores
                                    <?php else: ?>
                                        O documento do doador é opcional
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Doação Anônima
                                    </label>
                                    <?php if($campaign->allow_anonymous): ?>
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            PERMITIDO
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">
                                            NÃO PERMITIDO
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-gray-600 mt-2">
                                    <?php if($campaign->allow_anonymous): ?>
                                        Os doadores podem optar por fazer contribuições anônimas
                                    <?php else: ?>
                                        Todas as doações devem ser identificadas
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Recorrência Mensal
                                    </label>
                                    <?php if($campaign->allow_recurring): ?>
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            ATIVO
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">
                                            INATIVO
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-gray-600 mt-2">
                                    <?php if($campaign->allow_recurring): ?>
                                        Permite doações recorrentes mensais via cartão de crédito
                                    <?php else: ?>
                                        Doações recorrentes não estão habilitadas para esta campanha
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Perguntas do Quiz (<?php echo e($campaign->questions->count()); ?>)
                        </h3>
                    </div>
                    <div class="p-6">
                        <?php if($campaign->questions->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $campaign->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                        <div class="flex items-start gap-3">
                                            <span class="bg-purple-600 text-white px-2 py-1 rounded font-bold text-xs">
                                                #<?php echo e($question->order + 1); ?>

                                            </span>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="text-lg font-semibold text-purple-700 uppercase">
                                                        <?php echo e($question->question_text); ?>

                                                    </span>
                                                    <?php if($question->placeholder): ?>
                                                        <span
                                                            class="text-basetext-blue-700 font-light"><?php echo e($question->placeholder); ?></span>
                                                    <?php endif; ?>
                                                    <?php if($question->is_required): ?>
                                                        <span
                                                            class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded font-bold">OBRIGATÓRIA</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="text-sm font-bold text-gray-900">
                                                        <?php echo e([
                                                            'text' => 'Texto Curto',
                                                            'textarea' => 'Texto Longo',
                                                            'select' => 'Lista Suspensa',
                                                            'radio' => 'Botão Escolha Única',
                                                            'checkbox' => 'Botão Seleção Múltipla',
                                                            'number' => 'Campo Número',
                                                            'date' => 'Campo Data',
                                                        ][$question->question_type] ?? $question->question_type); ?>

                                                    </div>
                                                    <?php if($question->question_options && in_array($question->question_type, ['select', 'radio', 'checkbox'])): ?>
                                                        <div class="flex flex-wrap gap-1">
                                                            <?php $__currentLoopData = $question->question_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <span
                                                                    class="text-xs bg-white border border-purple-300 text-purple-700 px-2 py-1 rounded">
                                                                    <?php echo e($option); ?>

                                                                </span>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if($question->help_text): ?>
                                                    <p class="text-xs text-gray-600 mt-1">💡
                                                        <?php echo e($question->help_text); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 font-medium">Nenhuma pergunta configurada</p>
                                <p class="text-xs text-gray-400 mt-1">Adicione perguntas na edição da campanha</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                </path>
                            </svg>
                            Personalização Visual
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Cor
                                    Primária</label>
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-16 rounded-lg border-2 border-gray-300 shadow-inner"
                                        style="background-color: <?php echo e($campaign->color_primary ?: '#3B82F6'); ?>"></div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm font-mono font-semibold text-gray-900 bg-gray-50 px-3 py-2 rounded border">
                                            <?php echo e($campaign->color_primary ?: '#3B82F6'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Cor
                                    Secundária</label>
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-16 rounded-lg border-2 border-gray-300 shadow-inner"
                                        style="background-color: <?php echo e($campaign->color_secondary ?: '#6366F1'); ?>"></div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm font-mono font-semibold text-gray-900 bg-gray-50 px-3 py-2 rounded border">
                                            <?php echo e($campaign->color_secondary ?: '#6366F1'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Banner</label>
                                <?php
                                    // Função helper para gerar URL completa da imagem (storage isolado por tenant)
                                    $getImageUrl = function ($url) {
                                        if (empty($url)) {
                                            return null;
                                        }
                                        // Verifica se já é URL completa
                                        if (substr($url, 0, 7) === 'http://' || substr($url, 0, 8) === 'https://') {
                                            return $url;
                                        }
                                        // Se começa com /storage/, usa asset diretamente (compatibilidade)
                                        if (substr($url, 0, 9) === '/storage/') {
                                            return asset($url);
                                        }
                                        // Se começa com storage/, adiciona / (compatibilidade)
                                        if (substr($url, 0, 8) === 'storage/') {
                                            return asset('/' . $url);
                                        }
                                        // Caso padrão: usa tenantAsset para storage isolado
                                        return tenantAsset($url, true);
                                    };
                                ?>
                                <?php if($campaign->url_image_banner): ?>
                                    <div class="relative group">
                                        <img src="<?php echo e($getImageUrl($campaign->url_image_banner)); ?>" alt="Banner"
                                            class="w-full h-40 object-cover bg-gray-50 rounded-lg border-2 border-gray-300" />
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center">
                                            <a href="<?php echo e($getImageUrl($campaign->url_image_banner)); ?>" target="_blank"
                                                class="opacity-0 group-hover:opacity-100 px-4 py-2 bg-white text-gray-900 rounded-lg font-semibold text-sm">
                                                Ver Imagem
                                            </a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div
                                        class="w-full h-40 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">Sem banner</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Miniatura
                                    (Thumbnail)</label>
                                <?php if($campaign->url_image_thumb): ?>
                                    <div class="relative group">
                                        <img src="<?php echo e($getImageUrl($campaign->url_image_thumb)); ?>" alt="Thumbnail"
                                            class="w-full h-40 object-cover bg-gray-50 rounded-lg border-2 border-gray-300" />
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center">
                                            <a href="<?php echo e($getImageUrl($campaign->url_image_thumb)); ?>" target="_blank"
                                                class="opacity-0 group-hover:opacity-100 px-4 py-2 bg-white text-gray-900 rounded-lg font-semibold text-sm">
                                                Ver Imagem
                                            </a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div
                                        class="w-full h-40 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">Sem miniatura</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php endif; ?>
        

        
        <?php if($activeTab === 'transacoes'): ?>
            <div wire:key="tab-transacoes">

                
                <?php if(!$selectedTransaction): ?>
                    <div class="bg-white border rounded-lg shadow-md overflow-hidden mb-4">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                    Gateway de Pagamento
                                </h3>
                                
                                <div class="relative inline-block" x-data="{ open: false }" @click.away="open = false"
                                    @close.stop="open = false">
                                    <button type="button" @click.stop="open = ! open"
                                        class="inline-flex items-center justify-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 min-w-[36px] min-h-[36px]"
                                        aria-label="Menu de opções" aria-expanded="false"
                                        x-bind:aria-expanded="open">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                            </path>
                                        </svg>
                                    </button>

                                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg origin-top-right right-0"
                                        style="display: none;" @click.stop="open = false">
                                        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                            <a href="<?php echo e(route('dashboard-campanhas-metodo-pagamento', ['campaign_id' => $campaign->id])); ?>"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                    </path>
                                                </svg>
                                                Editar Método
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                <div>
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase mb-2">Gateway
                                        Configurado</div>
                                    <div class="text-sm font-bold text-gray-900">
                                        <?php echo e($campaign->gateway->pay_gateway_label ?? 'Não configurado'); ?>

                                    </div>
                                    <?php if($campaign->gateway): ?>
                                        <div class="text-[10px] text-gray-500">
                                            <?php echo e($campaign->gateway->pay_gateway_description ?? ''); ?></div>
                                        <div class="text-[10px] mt-4">
                                            <span
                                                class="px-2 py-1 rounded text-white <?php echo e($campaign->pay_sandbox ? 'bg-orange-500' : 'bg-green-600'); ?>">
                                                <?php echo e($campaign->pay_sandbox ? 'MODO TESTE' : 'ATIVADO'); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                
                                <div>
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase mb-2">Formas de
                                        Pagamento</div>
                                    <div class="space-y-2">
                                        
                                        <div
                                            class="flex items-center gap-2 text-xs <?php echo e($campaign->pay_pix ? '' : 'opacity-40'); ?>">
                                            <svg class="w-4 h-4 <?php echo e($campaign->pay_pix ? 'text-green-600' : 'text-gray-400'); ?>"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <?php if($campaign->pay_pix): ?>
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                <?php else: ?>
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                <?php endif; ?>
                                            </svg>
                                            <span
                                                class="font-medium <?php echo e($campaign->pay_pix ? '' : 'line-through'); ?>">PIX</span>
                                        </div>

                                        
                                        <div
                                            class="flex items-center gap-2 text-xs <?php echo e($campaign->pay_boleto ? '' : 'opacity-40'); ?>">
                                            <svg class="w-4 h-4 <?php echo e($campaign->pay_boleto ? 'text-green-600' : 'text-gray-400'); ?>"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <?php if($campaign->pay_boleto): ?>
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                <?php else: ?>
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                <?php endif; ?>
                                            </svg>
                                            <span
                                                class="font-medium <?php echo e($campaign->pay_boleto ? '' : 'line-through'); ?>">Boleto
                                                Bancário</span>
                                        </div>

                                        
                                        <div
                                            class="flex items-center justify-between text-xs <?php echo e($campaign->pay_card_credit ? '' : 'opacity-40'); ?>">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <svg class="w-4 h-4 <?php echo e($campaign->pay_card_credit ? 'text-green-600' : 'text-gray-400'); ?>"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <?php if($campaign->pay_card_credit): ?>
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    <?php else: ?>
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd"></path>
                                                    <?php endif; ?>
                                                </svg>
                                                <span
                                                    class="font-medium <?php echo e($campaign->pay_card_credit ? '' : 'line-through'); ?>">Cartão
                                                    de Crédito</span>
                                                <?php if($campaign->pay_card_credit && $campaign->pay_card_credit_installment_max > 1): ?>
                                                    <span
                                                        class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                                                        Até <?php echo e($campaign->pay_card_credit_installment_max); ?>x
                                                    </span>
                                                    
                                                    <span
                                                        class="text-[9px] px-2 py-0.5 rounded <?php echo e($campaign->pay_card_credit_installment_fee_payer === 'customer' ? 'bg-orange-50 text-orange-700' : 'bg-green-50 text-green-700'); ?>">
                                                        <?php if($campaign->pay_card_credit_installment_fee_payer === 'customer'): ?>
                                                            Juros para o Cliente
                                                        <?php else: ?>
                                                            Sem juros (a campanha absorve)
                                                        <?php endif; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    
                                    <div>
                                        <div class="text-[10px] font-semibold text-gray-500 uppercase">Valor da Meta
                                        </div>
                                        <div class="text-xl font-bold text-gray-900">
                                            <?php echo e(!is_null($campaign->goal_amount) ? toMoney($campaign->goal_amount, 'R$ ') : '-'); ?>

                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <?php if($campaign->pay_card_credit ?? false): ?>
                                            
                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">Valor
                                                    Mínimo Crédito</div>
                                                <div class="text-xl font-bold text-gray-900">
                                                    <?php echo e(toMoney($campaign->pay_card_credit_installment_amount_min ?? 0, 'R$ ')); ?>

                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Valor Mínimo
                                                Campanha</div>
                                            <div class="text-xl font-bold text-gray-900">
                                                <?php echo e(toMoney($campaign->amount_min ?? 0, 'R$ ')); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php
                    $filteredTransactions = $this->getFilteredTransactions();
                ?>

                <?php if(!$selectedTransactionId): ?>
                    <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Transações
                                (<?php echo e($filteredTransactions->total()); ?>)</h3>
                            <div class="flex gap-2">
                                <button wire:click="refreshTransacoes"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Atualizar
                                </button>
                                <a href="<?php echo e(route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id])); ?>?export=transacoes"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Exportar CSV
                                </a>
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status</label>
                                <select wire:model="filterTransactionStatus"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos</option>
                                    <option value="paid">Pago</option>
                                    <option value="processing">Processando</option>
                                    <option value="pending">Pendente</option>
                                    <option value="error">Erro</option>
                                    <option value="cancelled">Cancelado</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Início</label>
                                <input type="date" wire:model="filterTransactionDateFrom"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Fim</label>
                                <input type="date" wire:model="filterTransactionDateTo"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Buscar</label>
                                <input type="text" wire:model="filterTransactionSearch"
                                    placeholder="Localizador, Nome, E-mail, NSU..."
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Por
                                    página</label>
                                <select wire:model="transactionPerPage"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="10">10</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="500">500</option>
                                </select>
                            </div>
                        </div>

                        
                        <div id="transaction-table-top" x-data="{}" x-init="window.addEventListener('transactionPageChanged', () => {
                            const el = document.getElementById('transaction-table-top');
                            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        });">

                            
                            <?php if($filteredTransactions->hasPages()): ?>
                                <div
                                    class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-3 pb-3 border-b border-gray-100">
                                    <div class="text-xs text-gray-500">
                                        Exibindo <span
                                            class="font-semibold"><?php echo e($filteredTransactions->firstItem()); ?></span>
                                        a <span class="font-semibold"><?php echo e($filteredTransactions->lastItem()); ?></span>
                                        de <span class="font-semibold"><?php echo e($filteredTransactions->total()); ?></span>
                                        transações
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <?php if($filteredTransactions->onFirstPage()): ?>
                                            <span
                                                class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 rounded cursor-not-allowed">&laquo;</span>
                                        <?php else: ?>
                                            <button
                                                wire:click="gotoPageAndScroll(<?php echo e($filteredTransactions->currentPage() - 1); ?>)"
                                                class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">&laquo;</button>
                                        <?php endif; ?>
                                        <?php if($filteredTransactions->lastPage() <= 10): ?>
                                            <?php $__currentLoopData = $filteredTransactions->getUrlRange(1, $filteredTransactions->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($page == $filteredTransactions->currentPage()): ?>
                                                    <span
                                                        class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 border border-blue-600 rounded"><?php echo e($page); ?></span>
                                                <?php else: ?>
                                                    <button wire:click="gotoPageAndScroll(<?php echo e($page); ?>)"
                                                        class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100"><?php echo e($page); ?></button>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <?php
                                                $cur = $filteredTransactions->currentPage();
                                                $last = $filteredTransactions->lastPage();
                                            ?>
                                            <?php if($cur > 5): ?>
                                                <button wire:click="gotoPageAndScroll(1)"
                                                    class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">1</button>
                                                <span class="px-2 py-1.5 text-xs text-gray-400">...</span>
                                            <?php endif; ?>
                                            <?php $__currentLoopData = $filteredTransactions->getUrlRange(max(1, $cur - 4), min($last, $cur + 4)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($page == $cur): ?>
                                                    <span
                                                        class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 border border-blue-600 rounded"><?php echo e($page); ?></span>
                                                <?php else: ?>
                                                    <button wire:click="gotoPageAndScroll(<?php echo e($page); ?>)"
                                                        class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100"><?php echo e($page); ?></button>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($cur < $last - 4): ?>
                                                <span class="px-2 py-1.5 text-xs text-gray-400">...</span>
                                                <button wire:click="gotoPageAndScroll(<?php echo e($last); ?>)"
                                                    class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100"><?php echo e($last); ?></button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if($filteredTransactions->hasMorePages()): ?>
                                            <button
                                                wire:click="gotoPageAndScroll(<?php echo e($filteredTransactions->currentPage() + 1); ?>)"
                                                class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">&raquo;</button>
                                        <?php else: ?>
                                            <span
                                                class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 rounded cursor-not-allowed">&raquo;</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="w-full overflow-hidden">

                                <div class="w-full overflow-hidden">
                                    <table class="w-full table-fixed divide-y divide-gray-200">
                                        <colgroup>
                                            <col style="width:10%">
                                            <col style="width:10%">
                                            <col style="width:22%">
                                            <col style="width:20%">
                                            <col style="width:9%">
                                            <col style="width:13%">
                                            <col style="width:9%">
                                            <col style="width:7%">
                                        </colgroup>
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Data/Hora</th>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Adesão</th>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Doador</th>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Gateway</th>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Método</th>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    NSU</th>
                                                <th
                                                    class="text-center px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Valor</th>
                                                <th
                                                    class="text-center px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php $__empty_1 = true; $__currentLoopData = $filteredTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php
                                                    $statusClass = match ($transaction->status) {
                                                        'paid', 'approved' => 'bg-green-100 text-green-700',
                                                        'processing' => 'bg-blue-100 text-blue-700',
                                                        'pending' => 'bg-orange-100 text-orange-700',
                                                        'error', 'refused' => 'bg-red-100 text-red-700',
                                                        default => 'bg-gray-100 text-gray-700',
                                                    };

                                                    $statusLabel = match ($transaction->status) {
                                                        'paid' => 'PAGO',
                                                        'approved' => 'APROVADO',
                                                        'processing' => 'PROCESSANDO',
                                                        'pending' => 'PENDENTE',
                                                        'error' => 'ERRO',
                                                        'refused' => 'RECUSADO',
                                                        default => strtoupper($transaction->status ?? 'N/D'),
                                                    };
                                                ?>
                                                <tr class="hover:bg-gray-50 cursor-pointer"
                                                    wire:key="transaction-<?php echo e($transaction->id); ?>"
                                                    wire:click="goToTransaction('<?php echo e($transaction->id); ?>')">
                                                    <td class="px-4 py-3 whitespace-nowrap"
                                                        title="<?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s')); ?>">
                                                        <div class="text-xs font-semibold text-gray-900">
                                                            <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y')); ?>

                                                        </div>
                                                        <div class="text-[10px] text-gray-500">
                                                            <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('H:i:s')); ?>

                                                        </div>
                                                        <?php if(
                                                            ($transaction->pay_type === 'pix' || $transaction->pay_type === 'slip_pix') &&
                                                                !empty($transaction->pay_pix_expires_at) &&
                                                                in_array($transaction->status, ['pending', 'processing', 'pix_expired'])): ?>
                                                            <?php
                                                                $pixExpiresAt = \Carbon\Carbon::parse(
                                                                    $transaction->pay_pix_expires_at,
                                                                );
                                                                $pixIsExpired =
                                                                    $pixExpiresAt->isPast() ||
                                                                    $transaction->status === 'pix_expired';
                                                            ?>
                                                            <div
                                                                class="text-[10px] font-semibold mt-1 <?php echo e($pixIsExpired ? 'text-red-600' : 'text-orange-600'); ?>">
                                                                <?php echo e($pixIsExpired ? '⚠️ Expirado' : '⏰ Expira: ' . $pixExpiresAt->format('d/m H:i')); ?>

                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap"
                                                        title="Adesão: <?php echo e($transaction->order_control ?? '-'); ?>">
                                                        <div class="text-xs font-mono font-semibold text-blue-600">
                                                            <?php echo e($transaction->order_control ?? '-'); ?></div>
                                                        <div class="text-[10px] text-gray-500">
                                                            <?php echo e(substr($transaction->id, 0, 8)); ?></div>
                                                    </td>
                                                    <td class="px-4 py-3 overflow-hidden"
                                                        title="<?php echo e(($transaction->buyer_name ?? '-') . ' — ' . ($transaction->buyer_email ?? '')); ?>">
                                                        <div class="text-xs font-semibold text-gray-900 truncate">
                                                            <?php echo e($transaction->buyer_name ?? '-'); ?></div>
                                                        <div class="text-[10px] text-gray-500 truncate">
                                                            <?php echo e($transaction->buyer_email ?? '-'); ?></div>
                                                    </td>
                                                    <td class="px-4 py-3 overflow-hidden"
                                                        title="<?php echo e($transaction->pay_gateway_label ?? '-'); ?>">
                                                        <div class="text-xs text-gray-900 truncate">
                                                            <?php echo e($transaction->pay_gateway_label ?? '-'); ?></div>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap"
                                                        title="Método: <?php echo e(strtoupper($transaction->pay_type ?? '-')); ?>">
                                                        <div class="text-xs font-semibold text-gray-900">
                                                            <?php echo e(strtoupper($transaction->pay_type ?? '-')); ?></div>
                                                    </td>
                                                    <td class="px-4 py-3 overflow-hidden"
                                                        title="NSU: <?php echo e($transaction->pay_nsu ?? ($transaction->external_payment_id ?? '-')); ?>">
                                                        <div class="text-xs font-mono text-gray-600 truncate">
                                                            <?php echo e($transaction->pay_nsu ?? ($transaction->external_payment_id ? substr($transaction->external_payment_id, 0, 16) : '-')); ?>

                                                        </div>
                                                    </td>
                                                    <td class="text-center px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900"
                                                        title="Valor: <?php echo e(toMoney($transaction->amount, 'R$ ')); ?>">
                                                        <?php echo e(toMoney($transaction->amount, 'R$ ')); ?>

                                                    </td>
                                                    <td class="text-center px-4 py-3 whitespace-nowrap"
                                                        title="Status: <?php echo e($statusLabel); ?>">
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold rounded <?php echo e($statusClass); ?>">
                                                            <?php echo e($statusLabel); ?>

                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="9"
                                                        class="px-4 py-8 text-center text-sm text-gray-500">
                                                        Nenhuma transação encontrada com os filtros aplicados.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                
                                <?php if($filteredTransactions->hasPages()): ?>
                                    <div
                                        class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4 pt-4 border-t border-gray-100">
                                        <div class="text-xs text-gray-500">
                                            Exibindo <span
                                                class="font-semibold"><?php echo e($filteredTransactions->firstItem()); ?></span>
                                            a <span
                                                class="font-semibold"><?php echo e($filteredTransactions->lastItem()); ?></span>
                                            de <span
                                                class="font-semibold"><?php echo e($filteredTransactions->total()); ?></span>
                                            transações
                                        </div>
                                        <div class="flex items-center gap-1">
                                            
                                            <?php if($filteredTransactions->onFirstPage()): ?>
                                                <span
                                                    class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 rounded cursor-not-allowed">&laquo;</span>
                                            <?php else: ?>
                                                <button
                                                    wire:click="gotoPageAndScroll(<?php echo e($filteredTransactions->currentPage() - 1); ?>)"
                                                    class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">&laquo;</button>
                                            <?php endif; ?>

                                            
                                            <?php if($filteredTransactions->lastPage() <= 10): ?>
                                                <?php $__currentLoopData = $filteredTransactions->getUrlRange(1, $filteredTransactions->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($page == $filteredTransactions->currentPage()): ?>
                                                        <span
                                                            class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 border border-blue-600 rounded"><?php echo e($page); ?></span>
                                                    <?php else: ?>
                                                        <button wire:click="gotoPageAndScroll(<?php echo e($page); ?>)"
                                                            class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100"><?php echo e($page); ?></button>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <?php
                                                    $cur = $filteredTransactions->currentPage();
                                                    $last = $filteredTransactions->lastPage();
                                                ?>
                                                <?php if($cur > 5): ?>
                                                    <button wire:click="gotoPageAndScroll(1)"
                                                        class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">1</button>
                                                    <span class="px-2 py-1.5 text-xs text-gray-400">...</span>
                                                <?php endif; ?>
                                                <?php $__currentLoopData = $filteredTransactions->getUrlRange(max(1, $cur - 4), min($last, $cur + 4)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($page == $cur): ?>
                                                        <span
                                                            class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 border border-blue-600 rounded"><?php echo e($page); ?></span>
                                                    <?php else: ?>
                                                        <button wire:click="gotoPageAndScroll(<?php echo e($page); ?>)"
                                                            class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100"><?php echo e($page); ?></button>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($cur < $last - 4): ?>
                                                    <span class="px-2 py-1.5 text-xs text-gray-400">...</span>
                                                    <button wire:click="gotoPageAndScroll(<?php echo e($last); ?>)"
                                                        class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100"><?php echo e($last); ?></button>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            
                                            <?php if($filteredTransactions->hasMorePages()): ?>
                                                <button
                                                    wire:click="gotoPageAndScroll(<?php echo e($filteredTransactions->currentPage() + 1); ?>)"
                                                    class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">&raquo;</button>
                                            <?php else: ?>
                                                <span
                                                    class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 rounded cursor-not-allowed">&raquo;</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                <?php endif; ?>

                
                <?php if($selectedTransaction): ?>
                    <div class="space-y-4">
                        
                        <button wire:click="goToTransactionList"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Voltar para Transações
                        </button>

                        
                        <?php if(session()->has('success')): ?>
                            <div
                                class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium"><?php echo e(session('success')); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if(session()->has('error')): ?>
                            <div
                                class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium"><?php echo e(session('error')); ?></span>
                            </div>
                        <?php endif; ?>

                        
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Transação</h3>
                                        <p class="text-sm text-gray-600 mt-1">ID: <?php echo e($selectedTransaction->id); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-blue-600">
                                            <?php echo e(toMoney($selectedTransaction->value_paid, 'R$ ')); ?></div>
                                        <?php
                                            $txStatusClass = match ($selectedTransaction->status) {
                                                'paid', 'approved' => 'bg-green-100 text-green-700',
                                                'processing' => 'bg-blue-100 text-blue-700',
                                                'pending' => 'bg-orange-100 text-orange-700',
                                                'error', 'refused' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        ?>
                                        <span
                                            class="inline-block mt-2 px-3 py-1 text-sm font-semibold rounded <?php echo e($txStatusClass); ?>">
                                            <?php echo e(strtoupper($selectedTransaction->status)); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    
                                    <div class="space-y-4">
                                        <h4 class="text-sm font-bold text-gray-800 uppercase border-b pb-2">Dados da
                                            Transação</h4>

                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Data/Hora
                                            </div>
                                            <div class="text-sm text-gray-900">
                                                <?php echo e($selectedTransaction->created_at->format('d/m/Y H:i:s')); ?></div>
                                        </div>

                                        <?php if(
                                            ($selectedTransaction->pay_type === 'pix' || $selectedTransaction->pay_type === 'slip_pix') &&
                                                !empty($selectedTransaction->pay_pix_expires_at) &&
                                                in_array($selectedTransaction->status, ['pending', 'processing', 'pix_expired'])): ?>
                                            <?php
                                                $pixExpiresAt = \Carbon\Carbon::parse(
                                                    $selectedTransaction->pay_pix_expires_at,
                                                );
                                                $pixIsExpired =
                                                    $pixExpiresAt->isPast() ||
                                                    $selectedTransaction->status === 'pix_expired';
                                            ?>
                                            <div
                                                class="p-3 rounded-lg <?php echo e($pixIsExpired ? 'bg-red-50 border border-red-200' : 'bg-orange-50 border border-orange-200'); ?>">
                                                <div
                                                    class="text-[10px] font-semibold <?php echo e($pixIsExpired ? 'text-red-700' : 'text-orange-700'); ?> uppercase">
                                                    <?php echo e($pixIsExpired ? '⚠️ PIX Expirado' : '⏰ PIX Expira em'); ?>

                                                </div>
                                                <div
                                                    class="text-sm font-semibold <?php echo e($pixIsExpired ? 'text-red-900' : 'text-orange-900'); ?>">
                                                    <?php echo e($pixExpiresAt->format('d/m/Y H:i:s')); ?>

                                                </div>
                                                <?php if(!$pixIsExpired): ?>
                                                    <div
                                                        class="text-xs <?php echo e($pixIsExpired ? 'text-red-600' : 'text-orange-600'); ?> mt-1">
                                                        <?php echo e($pixExpiresAt->diffForHumans()); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Gateway
                                            </div>
                                            <div class="text-sm text-gray-900">
                                                <?php echo e($selectedTransaction->gateway->pay_gateway_label ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Método</div>
                                            <div class="text-sm text-gray-900">
                                                <?php echo e(strtoupper($selectedTransaction->pay_type ?? '-')); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">NSU</div>
                                            <div class="text-sm font-mono text-gray-900">
                                                <?php echo e($selectedTransaction->pay_nsu ?? '-'); ?></div>
                                        </div>
                                    </div>

                                    
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between border-b pb-2">
                                            <h4 class="text-sm font-bold text-gray-800 uppercase">Dados do Comprador
                                            </h4>
                                            <?php if($selectedTransaction->order): ?>
                                                <button
                                                    wire:click="viewOrderFromTransaction('<?php echo e($selectedTransaction->order->id); ?>')"
                                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Ver Adesão
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <?php if($selectedTransaction->order): ?>
                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">Adesão
                                                </div>
                                                <div class="text-sm font-mono text-blue-600">
                                                    <?php echo e($selectedTransaction->order->order_control ?? '-'); ?></div>
                                            </div>

                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">Nome
                                                </div>
                                                <div class="text-sm text-gray-900">
                                                    <?php echo e($selectedTransaction->order->buyer_name ?? '-'); ?></div>
                                            </div>

                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">E-mail
                                                </div>
                                                <div class="text-sm text-gray-900 lowercase">
                                                    <?php echo e($selectedTransaction->order->buyer_email ?? '-'); ?></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <?php if($selectedTransaction->pay_json_request || $selectedTransaction->pay_json_response): ?>
                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        <h4 class="text-sm font-bold text-gray-800 uppercase mb-4">Dados Técnicos</h4>
                                        <div class="space-y-3">
                                            
                                            <?php if($selectedTransaction->pay_json_request): ?>
                                                <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden"
                                                    x-data="{ open: false }">
                                                    
                                                    <button @click="open = !open"
                                                        class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-100 transition">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                            </svg>
                                                            <span
                                                                class="text-sm font-semibold text-gray-800 uppercase">Request
                                                                (Enviado)</span>
                                                        </div>
                                                        <svg class="w-5 h-5 text-gray-500 transition-transform"
                                                            :class="{ 'rotate-180': open }" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </button>

                                                    
                                                    <div x-show="open" x-collapse
                                                        class="border-t border-gray-200 bg-white">
                                                        <div class="p-4">
                                                            <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200 max-h-96"><?php echo e(json_encode($selectedTransaction->pay_json_request, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            
                                            <?php if($selectedTransaction->pay_json_response): ?>
                                                <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden"
                                                    x-data="{ open: false }">
                                                    
                                                    <button @click="open = !open"
                                                        class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-100 transition">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-green-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                            </svg>
                                                            <span
                                                                class="text-sm font-semibold text-gray-800 uppercase">Response
                                                                (Retornado)</span>
                                                        </div>
                                                        <svg class="w-5 h-5 text-gray-500 transition-transform"
                                                            :class="{ 'rotate-180': open }" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </button>

                                                    
                                                    <div x-show="open" x-collapse
                                                        class="border-t border-gray-200 bg-white">
                                                        <div class="p-4">
                                                            <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200 max-h-96"><?php echo e(json_encode($selectedTransaction->pay_json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                
                                <?php if(isAdmin()): ?>
                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        <h4 class="text-sm font-bold text-gray-800 uppercase mb-4">Webhooks Recebidos
                                        </h4>

                                        <?php
                                            // Carrega webhooks através do relacionamento
                                            $callbacks = $selectedTransaction->webhooks ?? collect();
                                        ?>

                                        <?php if($callbacks->count() > 0): ?>
                                            <div class="space-y-3">
                                                <?php $__currentLoopData = $callbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $callback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden"
                                                        x-data="{ open: false }">
                                                        
                                                        <button @click="open = !open"
                                                            class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-100 transition">
                                                            <div class="flex items-center gap-3 flex-1">
                                                                <div class="flex-1 text-left">
                                                                    <div
                                                                        class="flex gap-2 items-centertext-sm font-semibold text-gray-900">
                                                                        <span>Webhook
                                                                            #<?php echo e($callbacks->count() - $index); ?></span>
                                                                        <span
                                                                            class="px-2 py-1 rounded text-xs font-semibold
                                                            <?php if(in_array($callback->status, ['paid', 'pago', 'autorizado'])): ?> bg-green-100 text-green-800
                                                            <?php elseif(in_array($callback->status, ['canceled', 'cancelado', 'estornado'])): ?> bg-red-100 text-red-800
                                                            <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                                                            <?php echo e(strtoupper($callback->status)); ?>

                                                                        </span>
                                                                    </div>
                                                                    <div class="text-xs text-gray-600">
                                                                        <span>
                                                                            <?php echo e(dataDataHora($callback->created_at)); ?>

                                                                        </span>
                                                                        <span>-</span>
                                                                        <span>
                                                                            <?php echo e($callback->id); ?>

                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <?php if($callback->event_type): ?>
                                                                    <div class="hidden md:block">
                                                                        <span
                                                                            class="text-xs text-gray-600 bg-white px-2 py-1 rounded border border-gray-300">
                                                                            <?php echo e($callback->event_type); ?>

                                                                        </span>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <svg class="w-5 h-5 text-gray-500 transition-transform"
                                                                :class="{ 'rotate-180': open }" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </button>

                                                        
                                                        <div x-show="open" x-collapse
                                                            class="border-t border-gray-200 bg-white">
                                                            <div class="p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                                                                
                                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                                    <?php if($callback->gateway_slug): ?>
                                                                        <div>
                                                                            <div
                                                                                class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                Gateway</div>
                                                                            <div class="text-sm text-gray-900">
                                                                                <?php echo e($callback->gateway_slug); ?></div>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                    <?php if($callback->webhook_id): ?>
                                                                        <div>
                                                                            <div
                                                                                class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                Webhook ID</div>
                                                                            <div
                                                                                class="text-xs font-mono text-gray-900">
                                                                                <?php echo e($callback->webhook_id); ?></div>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                    <?php if($callback->received_at): ?>
                                                                        <div>
                                                                            <div
                                                                                class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                Recebido em</div>
                                                                            <div class="text-sm text-gray-900">
                                                                                <?php echo e(dataDataHora($callback->received_at)); ?>

                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>

                                                                
                                                                <?php if($callback->transaction_id || $callback->external_id || $callback->amount): ?>
                                                                    <div class="pt-3 border-t border-gray-100">
                                                                        <div
                                                                            class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                                            <?php if($callback->transaction_id): ?>
                                                                                <div>
                                                                                    <div
                                                                                        class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                        Transaction ID</div>
                                                                                    <div
                                                                                        class="text-xs font-mono text-gray-900">
                                                                                        <?php echo e($callback->transaction_id); ?>

                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>

                                                                            <?php if($callback->external_id): ?>
                                                                                <div>
                                                                                    <div
                                                                                        class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                        External ID</div>
                                                                                    <div
                                                                                        class="text-xs font-mono text-gray-900">
                                                                                        <?php echo e($callback->external_id); ?>

                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>

                                                                            <?php if($callback->amount): ?>
                                                                                <div>
                                                                                    <div
                                                                                        class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                        Valor</div>
                                                                                    <div
                                                                                        class="text-sm font-semibold text-gray-900">
                                                                                        <?php echo e(convertMoney($callback->amount, 'R$ ')); ?>

                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                
                                                                <?php if($callback->processed_at || $callback->error_message): ?>
                                                                    <div class="pt-3 border-t border-gray-100">
                                                                        <div
                                                                            class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                            <?php if($callback->processed_at): ?>
                                                                                <div>
                                                                                    <div
                                                                                        class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                        Processado em</div>
                                                                                    <div class="text-sm text-gray-900">
                                                                                        <?php echo e(dataDataHora($callback->processed_at)); ?>

                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>

                                                                            <?php if($callback->error_message): ?>
                                                                                <div class="md:col-span-2">
                                                                                    <div
                                                                                        class="text-[10px] font-semibold text-red-600 uppercase mb-1">
                                                                                        Erro</div>
                                                                                    <div
                                                                                        class="text-sm text-red-700 bg-red-50 p-2 rounded border border-red-200">
                                                                                        <?php echo e($callback->error_message); ?>

                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <div class="flex-none md:flex justify-end">
                                                                    <button
                                                                        wire:click="reprocessWebhook('<?php echo e($callback->id); ?>')"
                                                                        wire:loading.attr="disabled"
                                                                        wire:target="reprocessWebhook"
                                                                        class="mt-4 w-auto flex justify-center items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white text-xs font-semibold rounded transition-colors"
                                                                        title="Reprocessar webhook ID: <?php echo e($callback->id); ?>">
                                                                        <svg wire:loading.remove
                                                                            wire:target="reprocessWebhook"
                                                                            class="w-4 h-4" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                                            </path>
                                                                        </svg>
                                                                        <svg wire:loading
                                                                            wire:target="reprocessWebhook"
                                                                            class="animate-spin w-4 h-4"
                                                                            fill="none" viewBox="0 0 24 24">
                                                                            <circle class="opacity-25" cx="12"
                                                                                cy="12" r="10"
                                                                                stroke="currentColor"
                                                                                stroke-width="4"></circle>
                                                                            <path class="opacity-75"
                                                                                fill="currentColor"
                                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                            </path>
                                                                        </svg>
                                                                        <span>Reprocessar</span>
                                                                    </button>
                                                                </div>

                                                                
                                                                <?php if($callback->payload): ?>
                                                                    <div
                                                                        class="col-span-full pt-3 border-t border-gray-100">
                                                                        <div
                                                                            class="text-xs font-semibold text-gray-700 uppercase mb-2 flex items-center gap-2">
                                                                            <svg class="w-4 h-4 text-blue-600"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                                </path>
                                                                            </svg>
                                                                            Payload Recebido
                                                                        </div>
                                                                        <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200 max-h-64"><?php echo e(json_encode($callback->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                                    </div>
                                                                <?php endif; ?>

                                                                
                                                                <?php if($callback->response): ?>
                                                                    <div
                                                                        class="col-span-full pt-3 border-t border-gray-100">
                                                                        <div
                                                                            class="text-xs font-semibold text-gray-700 uppercase mb-2 flex items-center gap-2">
                                                                            <svg class="w-4 h-4 text-green-600"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                                </path>
                                                                            </svg>
                                                                            Response Enviada
                                                                        </div>
                                                                        <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200 max-h-64"><?php echo e(json_encode($callback->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                                <p class="text-sm text-gray-500 font-medium">Não possui webhooks
                                                    registrados</p>
                                                <p class="text-xs text-gray-400 mt-1">Nenhum callback foi recebido do
                                                    gateway para esta transação</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>
        

        
        <?php if($activeTab === 'adesoes'): ?>
            <div wire:key="tab-adesoes">

                <?php
                    $filteredOrders = $this->getFilteredOrders();
                ?>

                <?php if(!$selectedOrderId): ?>
                    <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-lg font-bold text-gray-800">Adesões (<?php echo e($filteredOrders->count()); ?>)
                                </h3>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" wire:click="openManualOrderModal"
                                    class="px-4 py-2 border border-green-600 text-green-700 hover:bg-green-50 text-xs font-semibold rounded transition-colors inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Cadastrar
                                </button>
                                <button type="button" wire:click="refreshAdesoes" wire:target="refreshAdesoes"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <span class="flex items-center gap-2" wire:loading.remove
                                        wire:target="refreshAdesoes" wire:loading.class="hidden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        <span>Atualizar</span>
                                    </span>

                                    <span class="flex justify-center items-center gap-2 hidden" wire:loading
                                        wire:target="refreshAdesoes" wire:loading.class.remove="hidden">
                                        <span>Atualizando...</span>
                                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                                <a href="<?php echo e(route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id])); ?>?export=adesoes"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Exportar CSV
                                </a>
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status</label>
                                <select wire:model="filterStatus"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos</option>
                                    <option value="pending">Pendente</option>
                                    <option value="paid">Pago</option>
                                    <option value="cancelled">Cancelado</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Início</label>
                                <input type="date" wire:model="filterDateFrom"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Fim</label>
                                <input type="date" wire:model="filterDateTo"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Buscar</label>
                                <input type="text" wire:model.debounce.300ms="filterSearch"
                                    placeholder="Nome, email, localizador..."
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(!$selectedOrderId): ?>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Data/Hora / Localizador</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Nome / Documento</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        E-mail / Telefone</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Valor Total</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Valor Pago</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Forma de Pagamento</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $filteredOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr wire:key="order-<?php echo e($order->id); ?>"
                                        wire:click="goToOrder('<?php echo e($order->id); ?>')"
                                        class="hover:bg-blue-50 cursor-pointer transition">
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="text-xs text-gray-600">
                                                <?php echo e($order->created_at->format('d/m/Y H:i')); ?></div>
                                            <div class="font-mono font-bold text-sm text-blue-600">
                                                <?php echo e($order->order_control); ?>

                                            </div>
                                        </td>
                                        <td
                                            class="text-center px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 capitalize">
                                            <div><?php echo e($order->buyer_name); ?></div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <?php echo e($order->buyer_doc_num ?? '-'); ?></div>
                                        </td>
                                        <td class="text-center px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            <div><?php echo e($order->buyer_email ?? '-'); ?></div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <?php
                                                    $orderPhone = trim(
                                                        implode(
                                                            ' ',
                                                            array_filter([
                                                                !empty($order->buyer_contact_country)
                                                                    ? '+' . $order->buyer_contact_country
                                                                    : '',
                                                                $order->buyer_contact_ddd ?? '',
                                                                $order->buyer_contact_num ?? '',
                                                            ]),
                                                        ),
                                                    );
                                                ?>
                                                <?php echo e($orderPhone !== '' ? $orderPhone : '-'); ?>

                                            </div>
                                        </td>
                                        <td
                                            class="text-center px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            <?php echo e(toMoney($order->amount_total, 'R$ ')); ?>

                                        </td>
                                        <td
                                            class="text-center px-4 py-3 whitespace-nowrap text-sm font-semibold <?php echo e($order->amount_paid > 0 ? 'text-green-600' : 'text-gray-600'); ?>">
                                            <?php echo e(toMoney($order->amount_paid, 'R$ ')); ?>

                                        </td>
                                        <td class="text-center px-4 py-3 whitespace-nowrap">
                                            <?php
                                                $currentPayment = $order->campaignPayments
                                                    ->sortByDesc('created_at')
                                                    ->first();
                                                $paymentType = $currentPayment
                                                    ? strtoupper($currentPayment->pay_type ?? '-')
                                                    : '-';
                                                $gatewayLabel = $currentPayment
                                                    ? strtoupper($currentPayment->gateway_slug ?? '-')
                                                    : '-';
                                                $isManualGateway =
                                                    $gatewayLabel === 'MANUAL' ||
                                                    \Illuminate\Support\Str::endsWith(
                                                        (string) $order->order_control,
                                                        '-M',
                                                    );
                                                $orderStatusNormalized = strtolower((string) ($order->status ?? ''));
                                                $paidOrderStatuses = function_exists('listOrderStatusPaid')
                                                    ? array_map(
                                                        fn($status) => strtolower((string) $status),
                                                        listOrderStatusPaid(),
                                                    )
                                                    : ['paid'];
                                                $isOrderPaid = in_array(
                                                    $orderStatusNormalized,
                                                    $paidOrderStatuses,
                                                    true,
                                                );

                                                // Verifica se há carnê (múltiplos PaymentSlips)
                                                $hasCarne = false;
                                                $carneInfo = null;
                                                if ($order->paymentSlips && $order->paymentSlips->count() > 1) {
                                                    // Tem mais de um slip = é carnê
                                                    $hasCarne = true;
                                                    $totalCarne = $order->paymentSlips->count();
                                                    $paidCarne = $order->paymentSlips->whereNotNull('paid_at')->count();
                                                    $carneInfo = [
                                                        'total' => $totalCarne,
                                                        'paid' => $paidCarne,
                                                        'pending' => $totalCarne - $paidCarne,
                                                    ];
                                                }

                                                if ($currentPayment) {
                                                    $paymentStatus = strtolower($currentPayment->status ?? '');
                                                    $statusClass = match ($paymentStatus) {
                                                        'paid',
                                                        'approved',
                                                        'captured',
                                                        'autorizado',
                                                        'success',
                                                        'sucesso'
                                                            => 'bg-green-100 text-green-700',
                                                        'processing', 'processando' => 'bg-blue-100 text-blue-700',
                                                        'pending', 'pendente' => 'bg-orange-100 text-orange-700',
                                                        'error',
                                                        'erro',
                                                        'refused',
                                                        'recusado',
                                                        'cancelled',
                                                        'cancelado'
                                                            => 'bg-red-100 text-red-700',
                                                        default => 'bg-gray-100 text-gray-700',
                                                    };

                                                    $statusLabel = match ($paymentStatus) {
                                                        'paid' => 'PAGO',
                                                        'approved' => 'APROVADO',
                                                        'captured' => 'CAPTURADO',
                                                        'autorizado' => 'AUTORIZADO',
                                                        'processing', 'processando' => 'PROCESSANDO',
                                                        'pending', 'pendente' => 'PENDENTE',
                                                        'error', 'erro' => 'ERRO',
                                                        'refused', 'recusado' => 'RECUSADO',
                                                        'cancelled', 'cancelado' => 'CANCELADO',
                                                        default => strtoupper($paymentStatus ?: 'N/D'),
                                                    };
                                                }
                                            ?>

                                            <?php if($hasCarne && $carneInfo): ?>
                                                
                                                <div class="text-xs font-semibold text-gray-900">
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                            </path>
                                                        </svg>
                                                        CARNÊ
                                                    </span>
                                                </div>
                                                <div class="text-[10px] text-gray-600 mt-0.5">
                                                    <?php echo e($paymentType); ?> •
                                                    <?php echo e($carneInfo['paid']); ?>/<?php echo e($carneInfo['total']); ?> pagas
                                                </div>
                                                <?php if($isManualGateway): ?>
                                                    <div class="mt-1">
                                                        <span
                                                            class="px-2 py-0.5 text-[10px] font-semibold rounded bg-slate-100 text-slate-700">
                                                            GATEWAY: MANUAL
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="mt-1">
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                        <div class="bg-green-600 h-1.5 rounded-full"
                                                            style="width: <?php echo e(($carneInfo['paid'] / $carneInfo['total']) * 100); ?>%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if($carneInfo['pending'] > 0): ?>
                                                    <div class="mt-1">
                                                        <span
                                                            class="px-2 py-0.5 text-[10px] font-semibold rounded bg-orange-100 text-orange-700">
                                                            <?php echo e($carneInfo['pending']); ?>

                                                            PENDENTE<?php echo e($carneInfo['pending'] > 1 ? 'S' : ''); ?>

                                                        </span>
                                                    </div>
                                                <?php elseif(!$isOrderPaid): ?>
                                                    <div class="mt-1">
                                                        <span
                                                            class="px-2 py-0.5 text-[10px] font-semibold rounded bg-green-100 text-green-700">
                                                            COMPLETO
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                
                                                <div class="text-xs font-semibold text-gray-900"><?php echo e($paymentType); ?>

                                                </div>
                                                <?php if($isManualGateway): ?>
                                                    <div>
                                                        <span
                                                            class="px-2 py-0.5 text-[10px] font-semibold rounded bg-slate-100 text-slate-700">
                                                            GATEWAY: MANUAL
                                                        </span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if($currentPayment && isset($statusClass) && isset($statusLabel) && !$isOrderPaid): ?>
                                                    <div>
                                                        <span
                                                            class="px-2 py-0.5 text-[10px] font-semibold rounded <?php echo e($statusClass); ?>">
                                                            <?php echo e($statusLabel); ?>

                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center px-4 py-3 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded <?php echo e($order->status === 'paid' ? 'bg-green-100 text-green-700' : ($order->status === 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700')); ?>">
                                                <?php echo e($order->status === 'paid' ? 'PAGO' : ($order->status === 'pending' ? 'PENDENTE' : strtoupper($order->status))); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                            Nenhuma adesão encontrada com os filtros aplicados.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    
                    <?php if($selectedOrder): ?>
                        <div class="space-y-6">
                            
                            <button wire:click="goToOrderList"
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                                </svg>
                                Voltar para Lista
                            </button>

                            
                            <?php if(session()->has('success')): ?>
                                <div
                                    class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium"><?php echo e(session('success')); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if(session()->has('error')): ?>
                                <div
                                    class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium"><?php echo e(session('error')); ?></span>
                                </div>
                            <?php endif; ?>

                            
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="p-6 bg-gray-50 border-b border-gray-200">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-600 uppercase mb-2">
                                                Localizador</div>
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="text-2xl font-black tracking-wide text-blue-700 font-mono">
                                                    <?php echo e($selectedOrder->order_control); ?></div>
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold rounded-full
                                                <?php echo e($selectedOrder->status === 'paid' ? 'bg-green-100 text-green-700' : ($selectedOrder->status === 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-gray-200 text-gray-700')); ?>">
                                                    <?php echo e(strtoupper($selectedOrder->status)); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button wire:click="openOrderEditModal"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-800 text-white text-sm font-semibold shadow transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L11 17l-4 1 1-4 9.414-9.414z">
                                                    </path>
                                                </svg>
                                                Editar
                                            </button>
                                            <button wire:click="enviarEmailPorStatus" wire:loading.attr="disabled"
                                                wire:target="enviarEmailPorStatus"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white text-sm font-semibold shadow transition">
                                                <svg wire:loading.remove wire:target="enviarEmailPorStatus"
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <svg wire:loading wire:target="enviarEmailPorStatus"
                                                    class="animate-spin w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12"
                                                        r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                <span>Enviar Email</span>
                                            </button>
                                            <a href="<?php echo e(campanhaUrl($campaign->customer_organization_slug, $campaign->slug, $selectedOrder->id)); ?>"
                                                target="_blank"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-semibold shadow transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                                Acessar Adesão
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-700 uppercase mb-4 border-b pb-2">
                                                Dados do Doador</h4>
                                            <div class="space-y-3">
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Nome</div>
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        <?php echo e($selectedOrder->buyer_name); ?></div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">E-mail</div>
                                                    <div class="text-sm font-medium text-gray-900 lowercase">
                                                        <?php echo e($selectedOrder->buyer_email); ?></div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Telefone</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php
                                                            $selectedOrderPhone = trim(
                                                                implode(
                                                                    ' ',
                                                                    array_filter([
                                                                        !empty($selectedOrder->buyer_contact_country)
                                                                            ? '+' .
                                                                                $selectedOrder->buyer_contact_country
                                                                            : '',
                                                                        $selectedOrder->buyer_contact_ddd ?? '',
                                                                        $selectedOrder->buyer_contact_num ?? '',
                                                                    ]),
                                                                ),
                                                            );
                                                        ?>
                                                        <?php echo e($selectedOrderPhone !== '' ? $selectedOrderPhone : '-'); ?>

                                                    </div>
                                                </div>
                                                <?php if($selectedOrder->buyer_doc_num): ?>
                                                    <div>
                                                        <div class="text-xs text-gray-500 uppercase mb-1">Documento
                                                        </div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?php echo e($selectedOrder->buyer_doc_num); ?></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-700 uppercase mb-4 border-b pb-2">
                                                Informações da Adesão</h4>
                                            <?php
                                                $currentPaymentInfo = $selectedOrder->campaignPayments->first();
                                                $gatewayLabel = strtoupper($currentPaymentInfo->gateway_slug ?? '-');
                                                $gatewayManual =
                                                    $gatewayLabel === 'MANUAL' ||
                                                    \Illuminate\Support\Str::endsWith(
                                                        (string) $selectedOrder->order_control,
                                                        '-M',
                                                    );
                                                $orderObservation =
                                                    data_get($selectedOrder->metadata, 'observation') ??
                                                    data_get($selectedOrder->metadata, 'manual_observation');
                                            ?>
                                            <div class="space-y-3">
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Data/Hora</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo e($selectedOrder->created_at->format('d/m/Y H:i:s')); ?>

                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Gateway</div>
                                                    <div
                                                        class="text-sm font-semibold <?php echo e($gatewayManual ? 'text-slate-700' : 'text-gray-900'); ?>">
                                                        <?php echo e($gatewayManual ? 'MANUAL' : ($gatewayLabel ?: '-')); ?>

                                                    </div>
                                                    <?php if($gatewayManual): ?>
                                                        <div class="text-[11px] text-slate-600">Pagamento lançado sem
                                                            processamento no gateway online.</div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Valor Total
                                                    </div>
                                                    <div class="text-lg font-bold text-blue-600">
                                                        <?php echo e(toMoney($selectedOrder->amount_total, 'R$ ')); ?></div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Valor Pago</div>
                                                    <div
                                                        class="text-lg font-bold <?php echo e(($selectedOrder->amount_paid ?? 0) > 0 ? 'text-green-600' : 'text-gray-600'); ?>">
                                                        <?php echo e(toMoney($selectedOrder->amount_paid ?? 0, 'R$ ')); ?>

                                                    </div>
                                                </div>
                                                <?php if($selectedOrder->paid_at): ?>
                                                    <div>
                                                        <div class="text-xs text-gray-500 uppercase mb-1">Data do
                                                            Pagamento</div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?php echo e($selectedOrder->paid_at->format('d/m/Y H:i:s')); ?>

                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(!empty($orderObservation)): ?>
                                                    <div>
                                                        <div class="text-xs text-gray-500 uppercase mb-1">Observação
                                                        </div>
                                                        <div class="text-sm font-medium text-gray-900 break-words">
                                                            <?php echo e($orderObservation); ?></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if($selectedSubscription): ?>
                                <?php
                                    $subscriptionStatusMap = [
                                        'active' => ['label' => 'Ativa', 'class' => 'bg-green-100 text-green-700'],
                                        'paused' => ['label' => 'Pausada', 'class' => 'bg-yellow-100 text-yellow-700'],
                                        'canceled' => ['label' => 'Cancelada', 'class' => 'bg-gray-200 text-gray-700'],
                                        'error_disabled' => [
                                            'label' => 'Desativada por erro',
                                            'class' => 'bg-red-100 text-red-700',
                                        ],
                                    ];
                                    $subscriptionStatus = $subscriptionStatusMap[$selectedSubscription->status] ?? [
                                        'label' => strtoupper($selectedSubscription->status ?? '-'),
                                        'class' => 'bg-gray-100 text-gray-700',
                                    ];
                                ?>
                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                    <div class="p-6 bg-gray-50 border-b border-gray-200">
                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900">Recorrência</h3>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Cartão:
                                                    <?php echo e($selectedSubscription->card_description ?? 'Não informado'); ?>

                                                </div>
                                            </div>
                                            <?php
                                                $hasRecurringActions = in_array(
                                                    $selectedSubscription->status,
                                                    ['active', 'paused', 'error_disabled'],
                                                    true,
                                                );
                                            ?>
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold rounded-full <?php echo e($subscriptionStatus['class']); ?>">
                                                    <?php echo e($subscriptionStatus['label']); ?>

                                                </span>
                                                <?php if($hasRecurringActions): ?>
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button type="button"
                                                            class="inline-flex items-center justify-center h-9 w-9 rounded-full border border-gray-300 bg-white text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition"
                                                            @click="open = !open" aria-label="Ações da recorrência">
                                                            <svg class="h-5 w-5" fill="currentColor"
                                                                viewBox="0 0 20 20" aria-hidden="true">
                                                                <path
                                                                    d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z" />
                                                            </svg>
                                                        </button>
                                                        <div class="absolute right-0 mt-2 w-40 rounded-lg border border-gray-200 bg-white shadow-lg z-10"
                                                            x-show="open"
                                                            x-transition:enter="transition ease-out duration-150"
                                                            x-transition:enter-start="opacity-0 translate-y-1"
                                                            x-transition:enter-end="opacity-100 translate-y-0"
                                                            x-transition:leave="transition ease-in duration-100"
                                                            x-transition:leave-start="opacity-100 translate-y-0"
                                                            x-transition:leave-end="opacity-0 translate-y-1"
                                                            @click.outside="open = false">
                                                            <div class="py-1 text-sm text-gray-700">
                                                                <?php if($selectedSubscription->status === 'active'): ?>
                                                                    <button type="button"
                                                                        wire:click="pauseRecurring('<?php echo e($selectedSubscription->id); ?>')"
                                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                                        Pausar
                                                                    </button>
                                                                    <button type="button"
                                                                        wire:click="cancelRecurring('<?php echo e($selectedSubscription->id); ?>')"
                                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                                                        Cancelar
                                                                    </button>
                                                                <?php elseif($selectedSubscription->status === 'paused'): ?>
                                                                    <button type="button"
                                                                        wire:click="resumeRecurring('<?php echo e($selectedSubscription->id); ?>')"
                                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                                        Retomar
                                                                    </button>
                                                                    <button type="button"
                                                                        wire:click="cancelRecurring('<?php echo e($selectedSubscription->id); ?>')"
                                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                                                        Cancelar
                                                                    </button>
                                                                <?php elseif($selectedSubscription->status === 'error_disabled'): ?>
                                                                    <button type="button"
                                                                        wire:click="cancelRecurring('<?php echo e($selectedSubscription->id); ?>')"
                                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                                                        Cancelar
                                                                    </button>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <div class="text-xs text-gray-500 uppercase mb-1">Próxima cobrança
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    <?php echo e($selectedSubscription->next_charge_at ? \Carbon\Carbon::parse($selectedSubscription->next_charge_at)->format('d/m/Y H:i') : '-'); ?>

                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 uppercase mb-1">Última cobrança
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    <?php echo e($selectedSubscription->last_charge_at ? \Carbon\Carbon::parse($selectedSubscription->last_charge_at)->format('d/m/Y H:i') : '-'); ?>

                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 uppercase mb-1">Ciclo atual</div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    <?php echo e($selectedSubscription->current_cycle ?? 0); ?>

                                                </div>
                                            </div>
                                        </div>

                                        <?php if($selectedSubscription->error_message): ?>
                                            <div
                                                class="bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg px-4 py-2">
                                                <?php echo e($selectedSubscription->error_message); ?>

                                            </div>
                                        <?php endif; ?>

                                        <div class="space-y-3">
                                            <?php if($selectedSubscription->cycles && $selectedSubscription->cycles->count() > 0): ?>
                                                <?php $__currentLoopData = $selectedSubscription->cycles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cycle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $cycleStatusMap = [
                                                            'paid' => [
                                                                'label' => 'Pago',
                                                                'class' => 'bg-green-100 text-green-700',
                                                            ],
                                                            'pending' => [
                                                                'label' => 'Pendente',
                                                                'class' => 'bg-yellow-100 text-yellow-700',
                                                            ],
                                                            'failed' => [
                                                                'label' => 'Falhou',
                                                                'class' => 'bg-red-100 text-red-700',
                                                            ],
                                                        ];
                                                        $cycleStatus = $cycleStatusMap[$cycle->status] ?? [
                                                            'label' => strtoupper($cycle->status ?? '-'),
                                                            'class' => 'bg-gray-100 text-gray-700',
                                                        ];
                                                        $cyclePayments = $cycle->order?->campaignPayments ?? collect();
                                                        $cycleAttempts = $cycle->attempts ?? collect();
                                                    ?>
                                                    <div class="border border-gray-200 rounded-lg">
                                                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                                            <div
                                                                class="flex flex-wrap items-center justify-between gap-2">
                                                                <div class="text-sm font-semibold text-gray-900">
                                                                    RECORRÊNCIA
                                                                    <?php echo e(str_pad($cycle->cycle_number ?? 0, 2, '0', STR_PAD_LEFT)); ?>

                                                                    -
                                                                    <?php echo e(\Carbon\Carbon::parse($cycle->billing_date)->format('d/m/Y')); ?>

                                                                </div>
                                                                <span
                                                                    class="px-2 py-1 text-xs font-semibold rounded-full <?php echo e($cycleStatus['class']); ?>">
                                                                    <?php echo e($cycleStatus['label']); ?>

                                                                </span>
                                                            </div>
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                <?php if($cycle->paid_at): ?>
                                                                    Pago em
                                                                    <?php echo e(\Carbon\Carbon::parse($cycle->paid_at)->format('d/m/Y H:i')); ?>

                                                                <?php endif; ?>
                                                                <?php if($cycle->next_attempt_at): ?>
                                                                    • Próxima tentativa:
                                                                    <?php echo e(\Carbon\Carbon::parse($cycle->next_attempt_at)->format('d/m/Y H:i')); ?>

                                                                <?php endif; ?>
                                                            </div>
                                                            <?php if($cycle->error_message): ?>
                                                                <div class="text-xs text-red-600 mt-1">
                                                                    <?php echo e($cycle->error_message); ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="p-4 space-y-4">
                                                            <div>
                                                                <div
                                                                    class="text-[10px] font-semibold text-gray-500 uppercase mb-2">
                                                                    Transações</div>
                                                                <?php if($cyclePayments->count() > 0): ?>
                                                                    <div class="space-y-2">
                                                                        <?php $__currentLoopData = $cyclePayments->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php
                                                                                $paymentStatusClass = match (
                                                                                    $payment->status
                                                                                ) {
                                                                                    'paid',
                                                                                    'approved',
                                                                                    'autorizado',
                                                                                    'captured'
                                                                                        => 'bg-green-100 text-green-700',
                                                                                    'pending',
                                                                                    'processing'
                                                                                        => 'bg-yellow-100 text-yellow-700',
                                                                                    'error'
                                                                                        => 'bg-red-100 text-red-700',
                                                                                    default
                                                                                        => 'bg-gray-100 text-gray-700',
                                                                                };
                                                                            ?>
                                                                            <div
                                                                                class="flex flex-wrap items-center justify-between gap-2 text-xs border border-gray-200 rounded px-3 py-2">
                                                                                <div class="text-gray-700">
                                                                                    <?php echo e(strtoupper($payment->pay_type ?? '-')); ?>

                                                                                    •
                                                                                    <?php echo e($payment->created_at->format('d/m/Y H:i')); ?>

                                                                                    <?php if($payment->pay_transaction_id): ?>
                                                                                        • ID:
                                                                                        <?php echo e($payment->pay_transaction_id); ?>

                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <span
                                                                                    class="px-2 py-0.5 rounded <?php echo e($paymentStatusClass); ?>">
                                                                                    <?php echo e(strtoupper($payment->status ?? '-')); ?>

                                                                                </span>
                                                                            </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="text-xs text-gray-400">Nenhuma
                                                                        transação registrada.</div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div>
                                                                <div
                                                                    class="text-[10px] font-semibold text-gray-500 uppercase mb-2">
                                                                    Tentativas</div>
                                                                <?php if($cycleAttempts->count() > 0): ?>
                                                                    <div class="space-y-1">
                                                                        <?php $__currentLoopData = $cycleAttempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php
                                                                                $attemptStatusClass =
                                                                                    ($attempt->status ?? '') ===
                                                                                    'success'
                                                                                        ? 'bg-green-100 text-green-700'
                                                                                        : 'bg-red-100 text-red-700';
                                                                            ?>
                                                                            <div
                                                                                class="flex flex-wrap items-center justify-between gap-2 text-xs">
                                                                                <div class="text-gray-600">
                                                                                    #<?php echo e($attempt->attempt_number ?? '-'); ?>

                                                                                    •
                                                                                    <?php echo e($attempt->attempted_at ? $attempt->attempted_at->format('d/m/Y H:i') : '-'); ?>

                                                                                    <?php if($attempt->scheduled_at): ?>
                                                                                        • Previsto:
                                                                                        <?php echo e(\Carbon\Carbon::parse($attempt->scheduled_at)->format('d/m/Y')); ?>

                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <span
                                                                                    class="px-2 py-0.5 rounded <?php echo e($attemptStatusClass); ?>">
                                                                                    <?php echo e(strtoupper($attempt->status ?? '-')); ?>

                                                                                </span>
                                                                            </div>
                                                                            <?php if($attempt->error_message): ?>
                                                                                <div
                                                                                    class="text-[10px] text-red-600 ml-2">
                                                                                    <?php echo e($attempt->error_message); ?>

                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="text-xs text-gray-400">Nenhuma
                                                                        tentativa registrada.</div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <div class="text-sm text-gray-500">Nenhum ciclo de recorrência
                                                    registrado.</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="p-6 bg-gray-50 border-b border-gray-200">
                                    <div class="flex justify-between items-center gap-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">Pagamentos</h3>
                                        </div>
                                        <div>
                                            <button type="button"
                                                wire:click="selectOrder('<?php echo e($selectedOrder->id); ?>')"
                                                wire:target="selectOrder('<?php echo e($selectedOrder->id); ?>')"
                                                wire:loading.attr="disabled"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow transition disabled:opacity-70">
                                                <span class="flex items-center gap-2" wire:loading.remove
                                                    wire:target="selectOrder('<?php echo e($selectedOrder->id); ?>')"
                                                    wire:loading.class="hidden">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                        </path>
                                                    </svg>
                                                    <span>Atualizar</span>
                                                </span>
                                                <div class="flex items-center gap-2 hidden" wire:loading
                                                    wire:target="selectOrder('<?php echo e($selectedOrder->id); ?>')"
                                                    wire:loading.class.remove="hidden">
                                                    <div>Atualizando...</div>
                                                    <svg class="animate-spin h-4 w-4"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6">

                                    
                                    <?php if($selectedOrder->paymentSlips && $selectedOrder->paymentSlips->count() > 0): ?>
                                        <div class="mt-1">
                                            <div class="space-y-3">
                                                <?php $__currentLoopData = $selectedOrder->paymentSlips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="border border-gray-200 rounded">
                                                        
                                                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <div class="text-sm font-semibold text-gray-900">
                                                                        <?php echo e($slip->description ?? 'Doação'); ?>

                                                                    </div>
                                                                    <?php
                                                                        $firstPayment = $slip->payments->first();
                                                                    ?>
                                                                    <?php if($firstPayment && $firstPayment->pay_installments_number && $firstPayment->pay_installments_number > 1): ?>
                                                                        <div class="text-xs text-gray-600 mt-0.5">
                                                                            <?php echo e($firstPayment->pay_installments_number); ?>x
                                                                            de
                                                                            <?php echo e(toMoney($firstPayment->pay_installment_value, 'R$ ')); ?>

                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="text-right">
                                                                    <div class="text-base font-bold text-gray-900">
                                                                        <?php echo e(toMoney($slip->total_amount, 'R$ ')); ?>

                                                                    </div>
                                                                    <div
                                                                        class="text-xs <?php echo e(in_array($slip->status, ['paid', 'approved']) ? 'text-green-600' : 'text-gray-500'); ?>">
                                                                        <?php echo e(in_array($slip->status, ['paid', 'approved']) ? 'Pago' : 'Pendente'); ?>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        
                                                        <?php
                                                            $slipPayments = $slip->payments ?? collect();
                                                        ?>
                                                        <?php if($slipPayments->count() > 0): ?>
                                                            <div class="divide-y divide-gray-100">
                                                                <?php $__currentLoopData = $slipPayments->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                        if (
                                                                            in_array($payment->status, [
                                                                                'paid',
                                                                                'approved',
                                                                            ])
                                                                        ) {
                                                                            $classBorder = 'border-green-700';
                                                                            $classColor = 'bg-green-100 text-green-700';
                                                                            $classText = 'text-green-700 uppercase';
                                                                        } elseif (
                                                                            in_array($payment->status, ['pending'])
                                                                        ) {
                                                                            $classBorder = 'border-yellow-700';
                                                                            $classColor =
                                                                                'bg-yellow-100 text-yellow-700';
                                                                            $classText = 'text-yellow-700 uppercase';
                                                                        } elseif (
                                                                            in_array($payment->status, ['error'])
                                                                        ) {
                                                                            $classBorder = 'border-red-700';
                                                                            $classColor = 'bg-red-100 text-red-700';
                                                                            $classText = 'text-red-700 uppercase';
                                                                        } else {
                                                                            $classBorder = 'border-gray-600';
                                                                            $classColor = 'bg-gray-100 text-gray-600';
                                                                            $classText = 'text-gray-600 uppercase';
                                                                        }

                                                                        //
                                                                        $attempts = $payment->attempts ?? collect();
                                                                    ?>
                                                                    <div class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer"
                                                                        wire:click="showPaymentDetails('<?php echo e($payment->id); ?>')">
                                                                        <div
                                                                            class="border-l-8 <?php echo e($classBorder); ?> flex items-start justify-between px-3">
                                                                            <div class="flex-1">
                                                                                <div class="flex items-center gap-2">
                                                                                    <span
                                                                                        class="text-sm font-medium text-gray-900">
                                                                                        <?php echo e(strtoupper($payment->pay_type ?? '-')); ?>

                                                                                    </span>
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs font-medium rounded <?php echo e($classColor); ?>">
                                                                                        <?php echo e(strtoupper($payment->status)); ?>

                                                                                    </span>
                                                                                </div>
                                                                                <div
                                                                                    class="text-xs text-gray-500 mt-1">
                                                                                    <?php echo e($payment->created_at->format('d/m/Y H:i')); ?>

                                                                                    <?php if($payment->pay_transaction_id): ?>
                                                                                        • ID:
                                                                                        <?php echo e($payment->pay_transaction_id); ?>

                                                                                    <?php endif; ?>
                                                                                    <?php if($attempts->count() > 0): ?>
                                                                                        <?php
                                                                                            $lastAttempt = $attempts->last();
                                                                                            $errorMsg =
                                                                                                $lastAttempt->error_message ??
                                                                                                '';

                                                                                            // Se não tiver error_message, tenta buscar no response_data
                                                                                            if (
                                                                                                empty($errorMsg) &&
                                                                                                $lastAttempt->response_data &&
                                                                                                is_array(
                                                                                                    $lastAttempt->response_data,
                                                                                                )
                                                                                            ) {
                                                                                                if (
                                                                                                    isset(
                                                                                                        $lastAttempt
                                                                                                            ->response_data[
                                                                                                            'ResponseDetail'
                                                                                                        ]['Message'],
                                                                                                    )
                                                                                                ) {
                                                                                                    $errorMsg =
                                                                                                        $lastAttempt
                                                                                                            ->response_data[
                                                                                                            'ResponseDetail'
                                                                                                        ]['Message'];
                                                                                                } elseif (
                                                                                                    isset(
                                                                                                        $lastAttempt
                                                                                                            ->response_data[
                                                                                                            'message'
                                                                                                        ],
                                                                                                    )
                                                                                                ) {
                                                                                                    $errorMsg =
                                                                                                        $lastAttempt
                                                                                                            ->response_data[
                                                                                                            'message'
                                                                                                        ];
                                                                                                }

                                                                                                $message =
                                                                                                    $lastAttempt
                                                                                                        ->response_data[
                                                                                                        'msg'
                                                                                                    ] ?? null;
                                                                                                $messageSub =
                                                                                                    $lastAttempt
                                                                                                        ->response_data[
                                                                                                        'msg_sub'
                                                                                                    ] ?? null;
                                                                                            }
                                                                                        ?>

                                                                                        

                                                                                        <?php if($lastAttempt->response_data['msg'] ?? false): ?>
                                                                                            <span
                                                                                                class="<?php echo e($classText); ?>"
                                                                                                title="<?php echo e($lastAttempt->response_data['msg']); ?>">•
                                                                                                <?php echo e($lastAttempt->response_data['msg']); ?></span>
                                                                                            <?php if($lastAttempt->response_data['msg_sub'] ?? false): ?>
                                                                                                <span
                                                                                                    class="<?php echo e($classText); ?>"
                                                                                                    title="<?php echo e($lastAttempt->response_data['msg_sub']); ?>">
                                                                                                    -
                                                                                                    <?php echo e($lastAttempt->response_data['msg_sub']); ?></span>
                                                                                            <?php endif; ?>
                                                                                        <?php elseif(!$errorMsg): ?>
                                                                                            <span
                                                                                                class="<?php echo e($classText); ?>"
                                                                                                title="<?php echo e($errorMsg); ?>">•
                                                                                                <?php echo e($errorMsg); ?></span>
                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <?php if(
                                                                                    ($payment->pay_type === 'pix' || $payment->pay_type === 'slip_pix') &&
                                                                                        !empty($payment->pay_pix_expires_at) &&
                                                                                        in_array($payment->status, ['pending', 'processing', 'pix_expired'])): ?>
                                                                                    <?php
                                                                                        $pixExpiresAt = \Carbon\Carbon::parse(
                                                                                            $payment->pay_pix_expires_at,
                                                                                        );
                                                                                        $pixIsExpired =
                                                                                            $pixExpiresAt->isPast() ||
                                                                                            $payment->status ===
                                                                                                'pix_expired';
                                                                                    ?>
                                                                                    <div
                                                                                        class="mt-2 p-2 rounded <?php echo e($pixIsExpired ? 'bg-red-50 border border-red-200' : 'bg-orange-50 border border-orange-200'); ?>">
                                                                                        <div
                                                                                            class="text-[10px] font-semibold <?php echo e($pixIsExpired ? 'text-red-700' : 'text-orange-700'); ?> uppercase">
                                                                                            <?php echo e($pixIsExpired ? '⚠️ PIX Expirado' : '⏰ PIX Expira em'); ?>

                                                                                        </div>
                                                                                        <div
                                                                                            class="text-xs font-semibold <?php echo e($pixIsExpired ? 'text-red-900' : 'text-orange-900'); ?>">
                                                                                            <?php echo e($pixExpiresAt->format('d/m/Y H:i:s')); ?>

                                                                                        </div>
                                                                                        <?php if(!$pixIsExpired): ?>
                                                                                            <div
                                                                                                class="text-[10px] <?php echo e($pixIsExpired ? 'text-red-600' : 'text-orange-600'); ?> mt-0.5">
                                                                                                <?php echo e($pixExpiresAt->diffForHumans()); ?>

                                                                                            </div>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                <?php endif; ?>

                                                                                
                                                                                <?php if($attempts->count() > 1): ?>
                                                                                    <div
                                                                                        class="mt-2 text-xs text-gray-500">
                                                                                        <?php echo e($attempts->count()); ?>

                                                                                        tentativa<?php echo e($attempts->count() > 1 ? 's' : ''); ?>

                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div class="ml-4">
                                                                                <div
                                                                                    class="text-sm font-semibold text-gray-900">
                                                                                    <?php echo e(toMoney($payment->value_paid, 'R$ ')); ?>

                                                                                </div>
                                                                                <div
                                                                                    class="text-xs font-light text-gray-600">
                                                                                    <?php if(($payment->pay_installments_number ?? false) && $payment->pay_installments_number > 0): ?>
                                                                                        <?php echo e($payment->pay_installments_number); ?>x
                                                                                        <?php echo e(toMoney($payment->pay_installment_value, 'R$ ')); ?>

                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="px-4 py-3 text-center text-sm text-gray-400">
                                                                Nenhuma tentativa de pagamento
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="mt-6 text-center py-4 text-sm text-gray-400">
                                            Nenhum pagamento registrado
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>

                        
                        <div class="mt-6 bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="p-6 bg-gray-50 border-b border-gray-200">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <h4 class="text-lg font-semibold text-gray-900">Notificações Enviadas</h4>
                                    <button type="button" wire:click="refreshOrderNotifications"
                                        wire:loading.attr="disabled" wire:target="refreshOrderNotifications"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow transition disabled:opacity-70">
                                        <span class="flex items-center gap-2" wire:loading.remove
                                            wire:target="refreshOrderNotifications" wire:loading.class="hidden">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            <span>Atualizar</span>
                                        </span>
                                        <div class="flex items-center gap-2 hidden" wire:loading
                                            wire:target="refreshOrderNotifications"
                                            wire:loading.class.remove="hidden">
                                            <div>Atualizando...</div>
                                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <div class="p-6">
                                <?php
                                    $notificationTypes = [
                                        'payment_approved' => 'Pagamento Aprovado',
                                        'payment_pending' => 'Pagamento Pendente',
                                        'participation_proof' => 'Comprovante de Participação',
                                    ];
                                    $notificationStatuses = [
                                        'sent' => ['label' => 'Enviado', 'class' => 'bg-green-100 text-green-700'],
                                        'failed' => ['label' => 'Falhou', 'class' => 'bg-red-100 text-red-700'],
                                        'logged' => [
                                            'label' => 'Registrado',
                                            'class' => 'bg-orange-100 text-orange-700',
                                        ],
                                    ];
                                    $notificationLogs = $this->selectedOrderNotifications;
                                ?>

                                <?php if($notificationLogs->count()): ?>
                                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                                        Data/Hora</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                                        Destino</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                                        Tipo / Assunto</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                                        Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php $__currentLoopData = $notificationLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $statusInfo = $notificationStatuses[$log->status] ?? [
                                                            'label' => ucfirst($log->status),
                                                            'class' => 'bg-gray-100 text-gray-600',
                                                        ];
                                                    ?>
                                                    <tr>
                                                        <td class="px-4 py-3 text-xs text-gray-600">
                                                            <?php echo e(dataDataHora($log->created_at)); ?></td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">
                                                            <div class="font-medium"><?php echo e($log->recipient_email); ?>

                                                            </div>
                                                            <?php if($log->recipient_name): ?>
                                                                <div class="text-xs text-gray-500">
                                                                    <?php echo e($log->recipient_name); ?></div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600">
                                                            <div class="font-medium">
                                                                <?php echo e($notificationTypes[$log->notification_type] ?? $log->notification_type); ?>

                                                            </div>
                                                            <div><?php echo e($log->subject ?? '-'); ?></div>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="space-y-1">
                                                                <span
                                                                    class="inline-flex px-2 py-1 rounded-full text-xs <?php echo e($statusInfo['class']); ?>">
                                                                    <?php echo e($statusInfo['label']); ?>

                                                                </span>
                                                                <?php if($log->error_message ?? false): ?>
                                                                    <div class="text-xs <?php echo e($log->error_message ? 'text-red-600' : 'text-gray-400'); ?> truncate w-48"
                                                                        title="<?php echo e($log->error_message ?? '--'); ?>">
                                                                        <?php echo e($log->error_message); ?>

                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center text-sm text-gray-500 py-4">
                                        Nenhuma notificação registrada para esta adesão.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="mt-6 bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="p-6 bg-gray-50 border-b border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900">Dados de Acesso</h4>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs text-gray-500 uppercase mb-1">ID da Adesão</div>
                                        <div class="text-sm font-mono font-semibold text-gray-900">
                                            <?php echo e($selectedOrder->id); ?></div>
                                    </div>
                                    <?php if($selectedOrder->ip_address): ?>
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase mb-1">IP</div>
                                            <div class="text-sm font-mono text-gray-900">
                                                <?php echo e($selectedOrder->ip_address); ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($selectedOrder->user_agent): ?>
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase mb-1">User Agent</div>
                                            <div class="text-xs text-gray-700 break-words">
                                                <?php echo e(Str::limit($selectedOrder->user_agent ?? '--', 100)); ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($selectedOrder->referer): ?>
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase mb-1">Referer</div>
                                            <div class="text-xs text-gray-700 break-words">
                                                <?php echo e(Str::limit($selectedOrder->referer ?? '--', 100)); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>
                <?php endif; ?>

            </div>
        <?php endif; ?>
        

        
        <?php if($activeTab === 'participantes'): ?>
            <div wire:key="tab-participantes">

                <?php
                    $participants = $this->getParticipantsList();
                ?>

                <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h3 class="text-lg font-bold text-gray-800">Participantes das Adesões
                            (<?php echo e($participants->count()); ?>)</h3>
                        <a href="<?php echo e(route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id])); ?>?export=participantes"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Exportar CSV
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto bg-white border rounded-sm shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    Nome</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    Data de Nascimento</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    Telefone</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    E-mail</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    Adesões Geradas</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    Adesões Pagas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $contactCountry = $participant->contact_country
                                        ? '+' . $participant->contact_country
                                        : '';
                                    $contactDdd = $participant->contact_ddd ?? '';
                                    $contactNum = $participant->contact_num ?? '';
                                    $phone = trim(
                                        implode(' ', array_filter([$contactCountry, $contactDdd, $contactNum])),
                                    );
                                ?>
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        <?php echo e($participant->name ?? '-'); ?></td>
                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">
                                        <?php echo e($participant->birth_date ? dataData($participant->birth_date) : '-'); ?>

                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">
                                        <?php echo e($phone !== '' ? $phone : '-'); ?>

                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($participant->email ?? '-'); ?>

                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">
                                        <?php echo e($participant->total_orders ?? 0); ?></td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">
                                        <?php echo e($participant->paid_orders ?? 0); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                        Nenhum participante encontrado para esta campanha.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        <?php endif; ?>
        

        
        <?php if($activeTab === 'questionarios'): ?>
            <div wire:key="tab-questionarios">

                <?php if($campaign->questions->count() > 0): ?>
                    <?php
                        $filteredAnswers = $this->getFilteredAnswers();
                    ?>

                    <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Respostas dos Questionários
                                (<?php echo e(count($filteredAnswers)); ?> adesões)</h3>
                            <div class="flex gap-2">
                                <a href="<?php echo e(route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id])); ?>?export=questionarios"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Exportar CSV
                                </a>
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 uppercase mb-1">Pergunta</label>
                                <select wire:model="filterQuestion"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todas as perguntas</option>
                                    <?php $__currentLoopData = $campaign->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($question->id); ?>"><?php echo e($question->question_text); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Início</label>
                                <input type="date" wire:model="filterQuestionDateFrom"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Fim</label>
                                <input type="date" wire:model="filterQuestionDateTo"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                                            Localizador</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Data/Hora</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Nome</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            E-mail</th>
                                        <?php $__currentLoopData = $campaign->questions->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider min-w-[200px]">
                                                <div class="flex flex-col">
                                                    <span><?php echo e(Str::limit($question->question_text, 50)); ?></span>
                                                    <span class="text-[10px] text-gray-500 font-normal mt-1">
                                                        <?php echo e(ucfirst($question->question_type)); ?>

                                                    </span>
                                                </div>
                                            </th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__empty_1 = true; $__currentLoopData = $filteredAnswers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $order = $item['order'];
                                            $answers = $item['answers'];
                                        ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap sticky left-0 bg-white z-10">
                                                <span
                                                    class="font-mono font-bold text-sm text-blue-600"><?php echo e($order->order_control); ?></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                <?php echo e($order->created_at->format('d/m/Y H:i')); ?>

                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <?php echo e($order->buyer_name); ?>

                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                <?php echo e($order->buyer_email ?? '-'); ?>

                                            </td>
                                            <?php $__currentLoopData = $campaign->questions->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="px-4 py-3 text-sm text-gray-900">
                                                    <?php if(isset($answers[$question->id])): ?>
                                                        <?php
                                                            $answer = $answers[$question->id];
                                                            $decodedAnswer = json_decode($answer->answer_value, true);
                                                            if (is_array($decodedAnswer)) {
                                                                echo implode(', ', $decodedAnswer);
                                                            } else {
                                                                echo $answer->answer_value;
                                                            }
                                                        ?>
                                                    <?php else: ?>
                                                        <span class="text-gray-400 italic">--</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="<?php echo e(4 + $campaign->questions->count()); ?>"
                                                class="px-4 py-8 text-center text-sm text-gray-500">
                                                Nenhuma resposta encontrada com os filtros aplicados.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                        <div class="text-center py-8">
                            <p class="text-gray-500">Esta campanha não possui perguntas configuradas.</p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>
        

        
        <?php if($showManualOrderModal): ?>
            <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => 'Adicionar Adesão Manual','maxWidth' => '2xl'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'showManualOrderModal']); ?>
                <div class="space-y-4">
                    <?php
                        $manualErrorMessages = collect($errors->getMessages())
                            ->reject(function ($messages, $field) {
                                return \Illuminate\Support\Str::startsWith($field, 'edit');
                            })
                            ->flatten()
                            ->unique()
                            ->values();
                    ?>
                    <?php if($manualErrorMessages->isNotEmpty()): ?>
                        <div role="alert" aria-live="assertive"
                            class="sticky top-0 z-10 p-3 bg-red-50 border border-red-200 rounded">
                            <div class="text-xs font-semibold text-red-700 uppercase mb-2">Corrija os erros abaixo
                            </div>
                            <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">
                                <?php $__currentLoopData = $manualErrorMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $errorMessage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($errorMessage); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                        <div class="text-xs font-semibold text-slate-700 uppercase">Gateway</div>
                        <div class="text-sm font-bold text-slate-900">MANUAL</div>
                        <div class="text-[11px] text-slate-600">Esta adesão será registrada sem processamento no
                            gateway online.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nome do Doador
                                *</label>
                            <input type="text" wire:model.defer="manualBuyerName"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $__errorArgs = ['manualBuyerName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">E-mail</label>
                            <input type="email" wire:model.defer="manualBuyerEmail"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $__errorArgs = ['manualBuyerEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">CPF/CNPJ</label>
                            <input type="text" wire:model.defer="manualBuyerDocNum"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $__errorArgs = ['manualBuyerDocNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">País
                                    (DDI)</label>
                                <input type="text" wire:model.defer="manualBuyerContactCountry" maxlength="5"
                                    inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    placeholder="55"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <?php $__errorArgs = ['manualBuyerContactCountry'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">DDD</label>
                                <input type="text" wire:model.defer="manualBuyerContactDdd"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <?php $__errorArgs = ['manualBuyerContactDdd'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-span-6">
                                <label
                                    class="block text-xs font-semibold text-gray-600 uppercase mb-1">Telefone</label>
                                <input type="text" wire:model.defer="manualBuyerContactNum"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <?php $__errorArgs = ['manualBuyerContactNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Valor Total (R$)
                                *</label>
                            <input type="text" wire:model.defer="manualAmountTotal" placeholder="Ex: 150,00"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $__errorArgs = ['manualAmountTotal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Valor Pago
                                (R$)</label>
                            <input type="text" wire:model.defer="manualAmountPaid" placeholder="Ex: 150,00"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $__errorArgs = ['manualAmountPaid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status *</label>
                            <select wire:model="manualStatus"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="paid">Pago</option>
                                <option value="pending">Pendente</option>
                            </select>
                            <?php $__errorArgs = ['manualStatus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Forma de Pagamento
                                *</label>
                            <select wire:model.defer="manualPayType"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="manual">Manual</option>
                                <option value="cash">Dinheiro</option>
                                <option value="pix">PIX</option>
                                <option value="transfer">Transferência</option>
                                <option value="card_credit">Cartão</option>
                                <option value="boleto">Boleto</option>
                            </select>
                            <?php $__errorArgs = ['manualPayType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <?php if($manualStatus === 'paid'): ?>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data do
                                    Pagamento</label>
                                <input type="date" wire:model.defer="manualPaidAt"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <?php $__errorArgs = ['manualPaidAt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php endif; ?>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Descrição</label>
                            <input type="text" wire:model.defer="manualDescription"
                                placeholder="Ex: Recebido em dinheiro no evento"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $__errorArgs = ['manualDescription'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Observação</label>
                            <textarea wire:model.defer="manualObservation" rows="3"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            <?php $__errorArgs = ['manualObservation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                 <?php $__env->slot('footer', null, []); ?> 
                    <div class="flex justify-end gap-2">
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Cancelar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'closeManualOrderModal']); ?>
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
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Salvar Adesão','spinner' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'wire:click' => 'saveManualOrder']); ?>
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
                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
        <?php endif; ?>

        
        <?php if($showOrderEditModal): ?>
            <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => 'Editar Adesão','maxWidth' => '3xl'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'showOrderEditModal']); ?>
                <div class="space-y-5">
                    <?php
                        $editErrorMessages = collect($errors->getMessages())
                            ->reject(function ($messages, $field) {
                                return \Illuminate\Support\Str::startsWith($field, 'manual');
                            })
                            ->flatten()
                            ->unique()
                            ->values();
                    ?>
                    <?php if($editErrorMessages->isNotEmpty()): ?>
                        <div role="alert" aria-live="assertive"
                            class="sticky top-0 z-10 p-3 bg-red-50 border border-red-200 rounded">
                            <div class="text-xs font-semibold text-red-700 uppercase mb-2">Corrija os erros abaixo
                            </div>
                            <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">
                                <?php $__currentLoopData = $editErrorMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $errorMessage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($errorMessage); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if(!$editOrderIsManual): ?>
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded text-xs text-blue-800">
                            Nesta adesão você pode ajustar os dados do doador. Valores e dados de pagamento permanecem
                            bloqueados.
                        </div>
                    <?php endif; ?>

                    <div>
                        <h4 class="text-sm font-bold text-gray-700 uppercase mb-3">Dados do Doador</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nome do Doador
                                    *</label>
                                <input type="text" wire:model.defer="editBuyerName"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <?php $__errorArgs = ['editBuyerName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">E-mail</label>
                                <input type="email" wire:model.defer="editBuyerEmail"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <?php $__errorArgs = ['editBuyerEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 uppercase mb-1">CPF/CNPJ</label>
                                <input type="text" wire:model.defer="editBuyerDocNum"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <?php $__errorArgs = ['editBuyerDocNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="grid grid-cols-12 gap-2">
                                <div class="col-span-3">
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">País
                                        (DDI)</label>
                                    <input type="text" wire:model.defer="editBuyerContactCountry"
                                        maxlength="5" inputmode="numeric"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="55"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editBuyerContactCountry'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-span-3">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 uppercase mb-1">DDD</label>
                                    <input type="text" wire:model.defer="editBuyerContactDdd"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editBuyerContactDdd'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-span-6">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 uppercase mb-1">Telefone</label>
                                    <input type="text" wire:model.defer="editBuyerContactNum"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editBuyerContactNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Observação</label>
                            <textarea wire:model.defer="editOrderObservation" rows="3"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            <?php $__errorArgs = ['editOrderObservation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <?php if($editOrderIsManual): ?>
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-bold text-gray-700 uppercase mb-3">Pagamento</h4>
                            <div class="mb-4 p-3 bg-slate-50 border border-slate-200 rounded">
                                <div class="text-xs font-semibold text-slate-700 uppercase">Gateway</div>
                                <div class="text-sm font-bold text-slate-900">MANUAL</div>
                                <div class="text-[11px] text-slate-600">Esta adesão foi lançada sem processamento no
                                    gateway online.</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Valor
                                        Total (R$) *</label>
                                    <input type="text" wire:model.defer="editOrderAmountTotal"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editOrderAmountTotal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Valor Pago
                                        (R$)</label>
                                    <input type="text" wire:model.defer="editOrderAmountPaid"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editOrderAmountPaid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status
                                        *</label>
                                    <select wire:model="editOrderStatus"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="paid">Pago</option>
                                        <option value="pending">Pendente</option>
                                        <option value="cancelled">Cancelado</option>
                                    </select>
                                    <?php $__errorArgs = ['editOrderStatus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Forma de
                                        Pagamento *</label>
                                    <select wire:model.defer="editOrderPayType"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="manual">Manual</option>
                                        <option value="cash">Dinheiro</option>
                                        <option value="pix">PIX</option>
                                        <option value="transfer">Transferência</option>
                                        <option value="card_credit">Cartão Crédito</option>
                                        <option value="card_debit">Cartão Débito</option>
                                        <option value="boleto">Boleto</option>
                                    </select>
                                    <?php $__errorArgs = ['editOrderPayType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tipo da
                                        Transação *</label>
                                    <input type="text" wire:model.defer="editOrderPayIntegrationType"
                                        placeholder="Ex: manual, gateway, api"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editOrderPayIntegrationType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data do
                                        Pagamento</label>
                                    <input type="datetime-local" wire:model.defer="editOrderPaidAt"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editOrderPaidAt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data/Hora
                                        da Transação</label>
                                    <input type="datetime-local" wire:model.defer="editOrderPayDatetime"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editOrderPayDatetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Número da
                                        Transação</label>
                                    <input type="text" wire:model.defer="editOrderPayTransactionId"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editOrderPayTransactionId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-600 uppercase mb-1">NSU</label>
                                    <input type="text" wire:model.defer="editOrderPayNsu"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editOrderPayNsu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="md:col-span-2">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 uppercase mb-1">Descrição</label>
                                    <input type="text" wire:model.defer="editOrderDescription"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php $__errorArgs = ['editOrderDescription'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                 <?php $__env->slot('footer', null, []); ?> 
                    <div class="w-full flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <?php if($canDeleteSelectedOrder): ?>
                                <?php if(!$confirmDeleteOrder): ?>
                                    <button type="button" wire:click="beginDeleteOrderConfirmation"
                                        class="px-3 py-2 rounded text-xs font-semibold text-red-700 border border-red-300 hover:bg-red-50 transition-colors">
                                        Excluir Adesão
                                    </button>
                                <?php else: ?>
                                    <span class="text-xs font-semibold text-red-700">Confirma a exclusão?</span>
                                    <button type="button" wire:click="cancelDeleteOrderConfirmation"
                                        class="px-3 py-2 rounded text-xs font-semibold text-gray-700 border border-gray-300 hover:bg-gray-50 transition-colors">
                                        Não
                                    </button>
                                    <button type="button" wire:click="deleteOrder"
                                        class="px-3 py-2 rounded text-xs font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors">
                                        Sim, Excluir
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <div class="flex justify-end gap-2">
                            <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Cancelar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'closeOrderEditModal']); ?>
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
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Salvar Alterações','spinner' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'wire:click' => 'saveOrderEdit']); ?>
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
                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
        <?php endif; ?>

        
        <?php if($showPaymentModal && $selectedPayment): ?>
            <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: <?php if ((object) ('showPaymentModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showPaymentModal'->value()); ?>')<?php echo e('showPaymentModal'->hasModifier('defer') ? '.defer' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('showPaymentModal'); ?>')<?php endif; ?> }">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    
                    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                        wire:click="closePaymentModal"></div>

                    
                    <div
                        class="relative inline-block w-full max-w-4xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
                        
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900">Detalhes do Pagamento</h3>
                                    <?php if($selectedPayment->gateway_sandbox): ?>
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 border-2 border-yellow-400 uppercase animate-pulse">
                                            🧪 SANDBOX
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        
                        <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                            
                            <?php if($selectedPayment->gateway_sandbox): ?>
                                <div class="mb-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-yellow-800">Ambiente de TESTE
                                                (Sandbox)</p>
                                            <p class="text-xs text-yellow-700">Esta transação foi processada em
                                                ambiente de testes. Não houve movimentação financeira real.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <table class="w-full">
                                <tbody class="divide-y divide-gray-100">
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600 w-1/3">ID</td>
                                        <td class="py-2 text-sm text-gray-900 font-mono"><?php echo e($selectedPayment->id); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Status</td>
                                        <td class="py-2">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded <?php echo e(in_array($selectedPayment->status, ['paid', 'approved'])
                                                    ? 'bg-green-100 text-green-700'
                                                    : ($selectedPayment->status === 'pending'
                                                        ? 'bg-yellow-100 text-yellow-700'
                                                        : ($selectedPayment->status === 'error'
                                                            ? 'bg-red-100 text-red-700'
                                                            : 'bg-gray-100 text-gray-600'))); ?>">
                                                <?php echo e(strtoupper($selectedPayment->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Descrição</td>
                                        <td class="py-2 text-sm text-gray-900">
                                            <?php echo e($selectedPayment->description ?? '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Tipo de Pagamento</td>
                                        <td class="py-2 text-sm text-gray-900">
                                            <?php echo e(strtoupper($selectedPayment->pay_type ?? '-')); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Gateway</td>
                                        <td class="py-2">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-sm text-gray-900"><?php echo e(strtoupper($selectedPayment->gateway_slug ?? '-')); ?></span>
                                                <?php if($selectedPayment->gateway_sandbox): ?>
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-bold rounded bg-yellow-100 text-yellow-800 border border-yellow-400">
                                                        SANDBOX
                                                    </span>
                                                <?php else: ?>
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-bold rounded bg-green-100 text-green-800 border border-green-400">
                                                        LIVE
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Valor Pago</td>
                                        <td class="py-2 text-sm font-bold text-gray-900">
                                            <?php echo e(toMoney($selectedPayment->value_paid, 'R$ ')); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Taxa</td>
                                        <td class="py-2 text-sm text-gray-900">
                                            <?php echo e(toMoney($selectedPayment->value_fees ?? 0, 'R$ ')); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Valor Líquido</td>
                                        <td class="py-2 text-sm font-bold text-green-600">
                                            <?php echo e(toMoney($selectedPayment->value_liquid, 'R$ ')); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Data de Criação</td>
                                        <td class="py-2 text-sm text-gray-900">
                                            <?php echo e($selectedPayment->created_at->format('d/m/Y H:i:s')); ?></td>
                                    </tr>
                                    <?php if($selectedPayment->paid_at): ?>
                                        <tr>
                                            <td class="py-2 text-sm font-semibold text-gray-600">Data de Pagamento
                                            </td>
                                            <td class="py-2 text-sm text-green-600">
                                                <?php echo e($selectedPayment->paid_at->format('d/m/Y H:i:s')); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($selectedPayment->pay_transaction_id): ?>
                                        <tr>
                                            <td class="py-2 text-sm font-semibold text-gray-600">Transaction ID</td>
                                            <td class="py-2 text-sm font-mono text-gray-900">
                                                <?php echo e($selectedPayment->pay_transaction_id); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($selectedPayment->pay_nsu): ?>
                                        <tr>
                                            <td class="py-2 text-sm font-semibold text-gray-600">NSU</td>
                                            <td class="py-2 text-sm font-mono text-gray-900">
                                                <?php echo e($selectedPayment->pay_nsu); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($selectedPayment->pay_installments_number && $selectedPayment->pay_installments_number > 1): ?>
                                        <tr>
                                            <td class="py-2 text-sm font-semibold text-gray-600">Parcelas</td>
                                            <td class="py-2 text-sm text-gray-900">
                                                <?php echo e($selectedPayment->pay_installments_number); ?>x de
                                                <?php echo e(toMoney($selectedPayment->pay_installment_value, 'R$ ')); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($selectedPayment->pay_pix_qrcode): ?>
                                        <tr>
                                            <td class="py-2 text-sm font-semibold text-gray-600">PIX QR Code</td>
                                            <td class="py-2 text-xs font-mono text-gray-700 break-all">
                                                <?php echo e(Str::limit($selectedPayment->pay_pix_qrcode, 50)); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            
                            <?php if($selectedPayment->attempts && $selectedPayment->attempts->count() > 0): ?>
                                <div class="mt-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Tentativas
                                        (<?php echo e($selectedPayment->attempts->count()); ?>)</h4>
                                    <div class="space-y-2">
                                        <?php $__currentLoopData = $selectedPayment->attempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span
                                                        class="text-xs text-gray-500"><?php echo e($attempt->attempted_at->format('d/m/Y H:i:s')); ?></span>
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-medium rounded <?php echo e($attempt->status === 'success'
                                                            ? 'bg-green-100 text-green-700'
                                                            : ($attempt->status === 'error'
                                                                ? 'bg-red-100 text-red-700'
                                                                : 'bg-gray-100 text-gray-600')); ?>">
                                                        <?php echo e(strtoupper($attempt->status)); ?>

                                                    </span>
                                                </div>
                                                <?php if($attempt->error_message): ?>
                                                    <div class="text-xs text-red-600 font-semibold mb-1">
                                                        <span class="uppercase">Erro:</span>
                                                        <?php echo e($attempt->error_message); ?>

                                                    </div>
                                                <?php endif; ?>
                                                <?php if($attempt->response_data && is_array($attempt->response_data)): ?>
                                                    <?php
                                                        $errorSub = null;
                                                        // Tenta buscar mensagem adicional do response_data
                                                        if (
                                                            isset($attempt->response_data['ResponseDetail']['Message'])
                                                        ) {
                                                            $errorSub =
                                                                $attempt->response_data['ResponseDetail']['Message'];
                                                        } elseif (
                                                            isset(
                                                                $attempt->response_data['ResponseDetail']['ErrorCode'],
                                                            )
                                                        ) {
                                                            $errorSub =
                                                                $attempt->response_data['ResponseDetail']['ErrorCode'];
                                                        } elseif (isset($attempt->response_data['message'])) {
                                                            $errorSub = $attempt->response_data['message'];
                                                        } elseif (isset($attempt->response_data['error_description'])) {
                                                            $errorSub = $attempt->response_data['error_description'];
                                                        }
                                                    ?>
                                                    <?php if($errorSub && $errorSub !== $attempt->error_message): ?>
                                                        <div class="text-xs text-red-500 mb-1">
                                                            <span class="uppercase">Detalhes:</span>
                                                            <?php echo e($errorSub); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            
                            <?php if($selectedPayment->pay_json_request || $selectedPayment->pay_json_response): ?>
                                <div class="mt-6">
                                    <div class="space-y-3">
                                        <div class="border border-gray-200 rounded">
                                            <div class="bg-blue-50 px-3 py-2 border-b border-gray-200">
                                                <span class="text-xs font-semibold text-blue-700">REQUEST
                                                    GATEWAY</span>
                                            </div>
                                            <div class="p-3">
                                                <?php if($selectedPayment->pay_json_request): ?>
                                                    <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200"><?php echo e(json_encode($selectedPayment->pay_json_request, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                <?php else: ?>
                                                    <div
                                                        class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200">
                                                        NÃO POSSUI</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="border border-gray-200 rounded">
                                            <div class="bg-green-50 px-3 py-2 border-b border-gray-200">
                                                <span class="text-xs font-semibold text-green-700">RESPONSE
                                                    GATEWAY</span>
                                            </div>
                                            <div class="p-3">
                                                <?php if($selectedPayment->pay_json_response): ?>
                                                    <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200"><?php echo e(json_encode($selectedPayment->pay_json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                <?php else: ?>
                                                    <div
                                                        class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200">
                                                        NÃO POSSUI</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            
                            <?php if($selectedPayment->webhooks && $selectedPayment->webhooks->count() > 0): ?>
                                <div class="mt-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Webhooks Recebidos
                                        (<?php echo e($selectedPayment->webhooks->count()); ?>)</h4>
                                    <div class="space-y-2">
                                        <?php $__currentLoopData = $selectedPayment->webhooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webhook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="border border-gray-200 rounded">
                                                <div class="bg-purple-50 px-3 py-2 border-b border-gray-200">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <span
                                                                class="text-xs font-semibold text-purple-700"><?php echo e(strtoupper($webhook->webhook_type ?? 'WEBHOOK')); ?></span>
                                                            <span
                                                                class="text-xs text-gray-500 ml-2"><?php echo e($webhook->created_at->format('d/m/Y H:i:s')); ?></span>
                                                        </div>
                                                        <span
                                                            class="px-2 py-0.5 text-xs font-medium rounded <?php echo e($webhook->processing_status === 'processed'
                                                                ? 'bg-green-100 text-green-700'
                                                                : ($webhook->processing_status === 'error'
                                                                    ? 'bg-red-100 text-red-700'
                                                                    : 'bg-yellow-100 text-yellow-700')); ?>">
                                                            <?php echo e(strtoupper($webhook->processing_status)); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="p-3">
                                                    <?php if($webhook->processing_error): ?>
                                                        <div class="text-xs text-red-600 mb-2">Erro:
                                                            <?php echo e($webhook->processing_error); ?></div>
                                                    <?php endif; ?>
                                                    <?php if($webhook->payload): ?>
                                                        <details>
                                                            <summary
                                                                class="text-xs text-purple-600 cursor-pointer hover:text-purple-800 mb-2">
                                                                Ver Payload</summary>
                                                            <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto max-h-60 border border-gray-200"><?php echo e(json_encode($webhook->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                        </details>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                            <button wire:click="closePaymentModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Variáveis globais para armazenar instâncias dos gráficos
        let revenueChartInstance = null;
        let transactionsChartInstance = null;

        function initCharts() {
            const chartDataRaw = <?php echo json_encode($chartData, 15, 512) ?>;

            // Converte valores de receita de centavos para reais
            const chartData = {
                ...chartDataRaw,
                revenue: chartDataRaw.revenue.map(value => value / 100)
            };

            // Verifica se estamos na tab de analíticos
            const revenueCanvas = document.getElementById('revenueChart');
            const transactionsCanvas = document.getElementById('transactionsChart');

            if (!revenueCanvas || !transactionsCanvas) {
                return; // Gráficos não estão visíveis
            }

            // Destroi instâncias anteriores se existirem
            if (revenueChartInstance) {
                revenueChartInstance.destroy();
                revenueChartInstance = null;
            }
            if (transactionsChartInstance) {
                transactionsChartInstance.destroy();
                transactionsChartInstance = null;
            }

            // Configuração comum
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '5%',
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 9
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            };

            // Gráfico de Receita
            const revenueCtx = revenueCanvas.getContext('2d');
            revenueChartInstance = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Receita (R$)',
                        data: chartData.revenue,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        ...commonOptions.scales,
                        y: {
                            ...commonOptions.scales.y,
                            ticks: {
                                ...commonOptions.scales.y.ticks,
                                callback: function(value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    },
                    plugins: {
                        ...commonOptions.plugins,
                        tooltip: {
                            ...commonOptions.plugins.tooltip,
                            callbacks: {
                                label: function(context) {
                                    return 'Receita: R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de Transações
            const transactionsCtx = transactionsCanvas.getContext('2d');
            transactionsChartInstance = new Chart(transactionsCtx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                            label: 'Total de Adesões',
                            data: chartData.orders,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        },
                        {
                            label: 'Adesões Pagas',
                            data: chartData.paid,
                            backgroundColor: 'rgba(34, 197, 94, 0.7)',
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        ...commonOptions.scales,
                        y: {
                            ...commonOptions.scales.y,
                            ticks: {
                                ...commonOptions.scales.y.ticks,
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Inicializa gráficos no carregamento da página
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
        });

        // Reinicializa gráficos quando o Livewire atualiza o componente
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', (message, component) => {
                // Aguarda o DOM atualizar
                setTimeout(() => {
                    initCharts();
                }, 100);
            });
        });

        // Para Livewire v3
        if (typeof Livewire !== 'undefined') {
            Livewire.on('contentChanged', () => {
                setTimeout(() => {
                    initCharts();
                }, 100);
            });
        }

        // Função para copiar URL
        function copiarURL() {
            const urlInput = document.getElementById('campaign-url');
            const url = urlInput.value;

            // Tenta usar a API moderna do Clipboard
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(function() {
                    window.$wireui.notify({
                        title: 'Sucesso!',
                        description: 'URL copiada para a área de transferência',
                        icon: 'success'
                    });
                }).catch(function(err) {
                    console.error('Erro ao copiar:', err);
                    // Fallback
                    copiarURLFallback(urlInput);
                });
            } else {
                // Fallback para navegadores mais antigos
                copiarURLFallback(urlInput);
            }
        }

        function copiarURLFallback(input) {
            try {
                input.select();
                input.setSelectionRange(0, 99999);
                const success = document.execCommand('copy');

                if (success) {
                    window.$wireui.notify({
                        title: 'Sucesso!',
                        description: 'URL copiada para a área de transferência',
                        icon: 'success'
                    });
                } else {
                    window.$wireui.notify({
                        title: 'Erro!',
                        description: 'Não foi possível copiar a URL. Tente selecionar e copiar manualmente.',
                        icon: 'error'
                    });
                }
            } catch (err) {
                console.error('Erro no fallback:', err);
                window.$wireui.notify({
                    title: 'Erro!',
                    description: 'Não foi possível copiar a URL. Tente selecionar e copiar manualmente.',
                    icon: 'error'
                });
            }
        }
    </script>

    
    <?php if($showClonarModal && isAdmin()): ?>
        <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => 'Clonar Campanha','maxWidth' => 'lg'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'showClonarModal']); ?>
            <div class="space-y-4">
                <?php if($clonarStep === 1): ?>
                    
                    <div class="flex items-start gap-3 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-indigo-900">Você está prestes a clonar a campanha:</p>
                            <p class="text-base font-bold text-indigo-800 mt-1"><?php echo e($campaign->name); ?></p>
                        </div>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1 pl-4 list-disc">
                        <li>Todos os textos e configurações serão copiados.</li>
                        <li>As imagens <strong>não</strong> serão copiadas.</li>
                        <li>O status da nova campanha será <span class="font-semibold text-gray-800">Rascunho</span>.</li>
                        <li>As perguntas do questionário serão clonadas.</li>
                    </ul>
                    <p class="text-sm text-gray-500">Deseja continuar?</p>
                <?php else: ?>
                    
                    <div class="flex items-start gap-3 p-4 bg-orange-50 border border-orange-300 rounded-lg">
                        <svg class="w-6 h-6 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-orange-900">Confirmação final</p>
                            <p class="text-sm text-orange-800 mt-1">
                                Confirme que deseja criar um clone de <strong><?php echo e($campaign->name); ?></strong> com status <strong>Rascunho</strong> e sem imagens.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

             <?php $__env->slot('footer', null, []); ?> 
                <div class="flex justify-end items-center gap-2 w-full">
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Cancelar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => '$set(\'showClonarModal\', false)']); ?>
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
                    <?php if($clonarStep === 1): ?>
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Continuar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'wire:click' => 'clonarStep2']); ?>
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
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Confirmar Clonagem','spinner' => 'clonarCampanha'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['style' => 'background-color: #4f46e5; color: white;','wire:click' => 'clonarCampanha']); ?>
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
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
    <?php endif; ?>

    
    <?php if($showQrCodeModal): ?>
        <?php if (isset($component)) { $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b = $attributes; } ?>
<?php $component = WireUi\View\Components\ModalCard::resolve(['title' => 'QR Code da Campanha','maxWidth' => '2xl'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\ModalCard::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'showQrCodeModal']); ?>
            <div class="space-y-6">
                
                <div class="flex justify-center items-center py-2">
                    <div class="bg-white p-6 rounded-lg border-2 border-gray-200 shadow-sm">
                        <?php echo QrCode::size(300)->generate(campanhaUrl($campaign->customer_organization_slug, $campaign->slug)); ?>

                    </div>
                </div>
            </div>

             <?php $__env->slot('footer', null, []); ?> 
                <div class="flex justify-between items-center w-full gap-2">
                    <button onclick="downloadQRCode()"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Baixar QR Code
                    </button>
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['flat' => true,'label' => 'Fechar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'closeQrCodeModal']); ?>
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
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $attributes = $__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__attributesOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b)): ?>
<?php $component = $__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b; ?>
<?php unset($__componentOriginal21d5dabccc8e1e4e1a7c0a058172479b); ?>
<?php endif; ?>
    <?php endif; ?>

    <br>

    
    <script>
        function copiarURLModal() {
            const input = document.getElementById('qrcode-campaign-url');
            const url = input.value;

            // Tenta usar a API moderna do Clipboard
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(function() {
                    window.$wireui.notify({
                        title: 'Sucesso!',
                        description: 'URL copiada para a área de transferência',
                        icon: 'success'
                    });
                }).catch(function(err) {
                    console.error('Erro ao copiar:', err);
                    // Fallback
                    copiarModalFallback(input);
                });
            } else {
                // Fallback para navegadores mais antigos
                copiarModalFallback(input);
            }
        }

        function copiarModalFallback(input) {
            try {
                input.select();
                input.setSelectionRange(0, 99999);
                const success = document.execCommand('copy');

                if (success) {
                    window.$wireui.notify({
                        title: 'Sucesso!',
                        description: 'URL copiada para a área de transferência',
                        icon: 'success'
                    });
                } else {
                    window.$wireui.notify({
                        title: 'Erro!',
                        description: 'Não foi possível copiar. Tente selecionar e copiar manualmente.',
                        icon: 'error'
                    });
                }
            } catch (err) {
                console.error('Erro no fallback:', err);
                window.$wireui.notify({
                    title: 'Erro!',
                    description: 'Não foi possível copiar. Tente selecionar e copiar manualmente.',
                    icon: 'error'
                });
            }
        }

        function downloadQRCode() {
            try {
                // Pega o SVG do QR Code - busca de forma mais específica
                const qrContainer = document.querySelector('.bg-white.p-6.rounded-lg.border-2');
                if (!qrContainer) {
                    console.error('Container do QR Code não encontrado');
                    window.$wireui.notify({
                        title: 'Erro!',
                        description: 'QR Code não encontrado',
                        icon: 'error'
                    });
                    return;
                }

                const svg = qrContainer.querySelector('svg');
                if (!svg) {
                    console.error('SVG do QR Code não encontrado');
                    window.$wireui.notify({
                        title: 'Erro!',
                        description: 'QR Code SVG não encontrado',
                        icon: 'error'
                    });
                    return;
                }

                console.log('QR Code SVG encontrado:', svg);

                // Pega as dimensões do SVG
                const svgWidth = svg.width.baseVal.value || 300;
                const svgHeight = svg.height.baseVal.value || 300;

                // Serializa o SVG
                const svgData = new XMLSerializer().serializeToString(svg);
                console.log('SVG serializado');

                // Cria canvas
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                // Define tamanho maior para melhor qualidade
                const scale = 2; // 2x para melhor qualidade
                canvas.width = svgWidth * scale;
                canvas.height = svgHeight * scale;

                // Cria imagem
                const img = new Image();

                img.onerror = function(err) {
                    console.error('Erro ao carregar imagem:', err);
                    window.$wireui.notify({
                        title: 'Erro!',
                        description: 'Erro ao processar QR Code',
                        icon: 'error'
                    });
                };

                img.onload = function() {
                    console.log('Imagem carregada, dimensões:', img.width, 'x', img.height);

                    // Preenche com fundo branco
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);

                    // Desenha o QR Code escalado
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    console.log('QR Code desenhado no canvas');

                    // Converte para PNG e baixa
                    canvas.toBlob(function(blob) {
                        if (!blob) {
                            console.error('Erro ao criar blob');
                            window.$wireui.notify({
                                title: 'Erro!',
                                description: 'Erro ao gerar imagem',
                                icon: 'error'
                            });
                            return;
                        }

                        console.log('Blob criado, tamanho:', blob.size);

                        const url = URL.createObjectURL(blob);
                        const link = document.createElement('a');

                        // Monta o nome do arquivo: qrcode-organizador-campanha.png
                        <?php
                            $organizerSlug = $campaign->customer_organization_slug ?? 'organizador';
                            $campaignSlug = $campaign->slug ?? 'campanha';
                            $fileName = "qrcode-{$organizerSlug}-{$campaignSlug}.png";
                        ?>
                        link.download = <?php echo \Illuminate\Support\Js::from($fileName)->toHtml() ?>;
                        link.href = url;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // Aguarda um pouco antes de revogar a URL
                        setTimeout(() => {
                            URL.revokeObjectURL(url);
                        }, 100);

                        console.log('Download iniciado');
                        window.$wireui.notify({
                            title: 'Sucesso!',
                            description: 'QR Code baixado com sucesso',
                            icon: 'success'
                        });
                    }, 'image/png');
                };

                // Codifica o SVG para base64
                const svgBlob = new Blob([svgData], {
                    type: 'image/svg+xml;charset=utf-8'
                });
                const url = URL.createObjectURL(svgBlob);
                img.src = url;

            } catch (error) {
                console.error('Erro no download:', error);
                window.$wireui.notify({
                    title: 'Erro!',
                    description: 'Erro ao baixar QR Code: ' + error.message,
                    icon: 'error'
                });
            }
        }
    </script>
</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/dashboard/campanha-detalhes.blade.php ENDPATH**/ ?>