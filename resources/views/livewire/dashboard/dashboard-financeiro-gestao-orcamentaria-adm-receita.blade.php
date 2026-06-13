<div class="w-full max-w-7xl mx-auto mb-10">

    <div class="mb-6">
        <x-jet-validation-errors />
    </div>

    @if ($target ?? false)

        {{-- HEADER MODERNO COM GRADIENTE --}}
        <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-receita" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-receita)"/>
                </svg>
            </div>
            <div class="relative z-10 p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Administrar Receitas</h1>
                                <p class="text-white/90 text-sm">{{ $target->event_name }} - {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-financeiro-gestao-orcamentaria') }}" class="hover:bg-white/20" />
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full">

            @if ($modal_budgetAdd ?? false)
                <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 uppercase">Adicionar Novo Tipo</h2>
                    </div>
                    <div class="p-6">
                        <x-input wire:model.defer="budget_title" label="Tipo" placeholder="Informe o tipo" class="uppercase" />
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col-reverse md:flex-row justify-end gap-2">
                        <x-button sm outline red label="Cancelar" wire:click="$set('modal_budgetAdd',false)" />
                        <x-button sm green label="ADICIONAR" wire:click="budgetCriar('receita')" />
                    </div>
                </div>

            @elseif ($modal_budgetAlt ?? false)
                <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 uppercase">Alterar Tipo</h2>
                    </div>
                    <div class="p-6">
                        <x-input wire:model.defer="budget_title" label="Tipo" placeholder="Informe o tipo" class="uppercase" />
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col-reverse md:flex-row justify-end gap-2">
                        <x-button sm outline red label="Cancelar" wire:click="$set('modal_budgetAlt',false)" />
                        <x-button sm green label="ALTERAR" wire:click="budgetAlterar('{{ $budget_id }}')" />
                    </div>
                </div>

            @elseif ($modal_budgetItemAdd ?? false)
                <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 uppercase">
                            Novo Item em <span class="font-normal">{{ $this->budget->budget_title ?? null }}</span>
                        </h2>
                    </div>
                    <div class="p-6">
                            <div class="flex-none md:flex w-full text-xs font-medium gap-2">
                                <div class="w-full md:w-6/12">
                                    <x-input wire:model.defer="item_nome" label="Descrição" placeholder="Item Nome" class="" />
                                </div>
                                <div class="w-full md:w-3/12">
                                    <x-input wire:model.defer="item_qtd" label="Quantidade Item" placeholder="Item Qtd" type="number" min="1" class="" />
                                </div>
                                <div class="w-full md:w-3/12">
                                    <x-inputs.currency wire:model.defer="item_valor" label="Valor Unitário" placeholder="Item Valor" prefix="R$" thousands="." decimal="," precision="2" class="" />
                                </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col-reverse md:flex-row justify-end gap-2">
                        <x-button sm outline red label="Cancelar" wire:click="$set('modal_budgetItemAdd',false)" />
                        <x-button sm green label="ADICIONAR" wire:click="budgetItemAlterar('receita', '{{ $budget_id }}')" />
                    </div>
                </div>

            @elseif ($modal_budgetItemAlt ?? false)
                <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 uppercase">
                            Alterar Item em <span class="font-normal">{{ $this->budget->budget_title ?? null }}</span>
                        </h2>
                    </div>
                    <div class="p-6">
                            <div class="flex-none md:flex w-full text-xs font-medium gap-2">
                                <div class="w-full md:w-6/12">
                                    <x-input wire:model.defer="item_nome" label="Descrição" placeholder="Item Nome" class="" />
                                </div>
                                <div class="w-full md:w-3/12">
                                    <x-input wire:model.defer="item_qtd" label="Quantidade Item" placeholder="Item Qtd" type="number" min="1" class="" />
                                </div>
                                <div class="w-full md:w-3/12">
                                    <x-inputs.currency wire:model.defer="item_valor" label="Valor Unitário" placeholder="Item Valor" prefix="R$" thousands="." decimal="," precision="2" class="" />
                                </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col-reverse md:flex-row justify-end gap-2">
                        <x-button sm outline red label="Cancelar" wire:click="$set('modal_budgetItemAlt',false)" />
                        <x-button sm green label="SALVAR ALTERAÇÃO" wire:click="budgetItemAlterar('receita', '{{ $budget_id }}', '{{ $budget_item_id }}')" />
                    </div>
                </div>

            @else
                {{-- RECEITAS --}}
                <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Receitas</h2>
                        <x-button outline zinc xs icon="plus-sm" label="NOVO TIPO" wire:click="$set('modal_budgetAdd',true)" />
                    </div>
                    <div class="p-6">
                        @php
                            // APPEND - DEMAIS RECEITAS
                            foreach ($target->budgetsReceita->sortBy('created_at') ?? [] as $budgetsReceitaKey => $budgetsReceitaValues)
                            {
                                $receitas[$budgetsReceitaKey] = [
                                    'id'       => $budgetsReceitaValues->id ?? null,
                                    'title'    => $budgetsReceitaValues->budget_title ?? null,
                                    'subtitle' => $budgetsReceitaValues->budget_subtitle ?? null,
                                    'amount'   => 0,
                                    'items'    => [],
                                ];

                                if($budgetsReceitaValues->budgetsItems->count())
                                {
                                    $valorReceitasItem = 0;

                                    foreach ($budgetsReceitaValues->budgetsItems->sortBy('created_at') as $budgetsItemsKey => $budgetsItemsValues)
                                    {
                                        $valor             = $budgetsItemsValues->item_amount_total ?? 0;
                                        $valorReceitas     = $valorReceitas + $valor;
                                        $valorReceitasItem = $valorReceitasItem + $valor;
                                        //
                                        $receitas[$budgetsReceitaKey]['items'][$budgetsItemsValues->id] = $budgetsItemsValues;
                                    }

                                    $receitas[$budgetsReceitaKey]['amount'] = $valorReceitasItem;
                                }
                            }
                        @endphp

                        @foreach ($receitas ?? [] as $receitaKey => $receita)
                            <div class="mb-6 border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold uppercase text-gray-900">{{ $receita['title'] ?? 'RECEITA #' . ($receitaKey+1) }}</h3>
                                    @if ($receitaKey != 'BILHETERIA')
                                        <div class="flex gap-2">
                                            <x-button squared outline xs green icon="plus" wire:click="modalBudgetItemAdd('{{ $receita['id'] }}')" />
                                            <x-button squared outline xs indigo icon="pencil" wire:click="modalBudgetAlt('receita','{{ $receita['id'] }}')" />
                                            <x-button squared outline xs red icon="trash" onclick="confirm('Confirma a remoção do tipo? Será irreversível!') || event.stopImmediatePropagation()" wire:click="removerBudget('{{ $receita['id'] }}')" />
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qtd.</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Unitário</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Total</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse ($receita['items'] ?? [] as $receitaItemKey => $receitaItem)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 uppercase">{{ $receitaItem->item_name }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ $receitaItem->item_qtd }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ toMoney($receitaItem->item_amount) }}</td>
                                                    <td class="px-4 py-3 text-sm font-semibold text-green-600 text-center">{{ toMoney($receitaItem->item_amount_total) }}</td>
                                                    <td class="px-4 py-3 text-sm text-center">
                                                        @if ($receitaItem->id ?? false)
                                                            <div class="flex justify-center gap-1">
                                                                <x-button squared xs indigo icon="pencil" wire:click="modalBudgetItemAlt('{{ $receita['id'] }}','{{ $receitaItem->id }}')" />
                                                                <x-button squared xs red icon="trash" onclick="confirm('Confirma a remoção do item? Será irreversível!') || event.stopImmediatePropagation()" wire:click="removerBudgetItem('{{ $receitaItem->id }}')" />
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">{{ __('msg_sem_itens_exibir') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between items-center">
                            <x-button outline zinc xs icon="plus-sm" label="NOVO TIPO" wire:click="$set('modal_budgetAdd',true)" />
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-medium text-gray-600">TOTAL:</span>
                                <span class="text-2xl font-bold text-green-600">{{ toMoney($valorReceitas ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- RECEITAS - FIM --}}
            @endif
        </div>
        @else
            {{-- SEM TARGET SELECIONADO --}}
            <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
                <div class="relative z-10 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Administrar Receitas</h1>
                            <p class="text-white/90 text-sm">É preciso selecionar um evento</p>
                                <div class="mt-2"><a href="{{ route('dashboard') }}" class="text-white/90 text-sm hover:text-white/70 border border-white mt-4 p-2 rounded shadow hover:bg-gray-50 hover:text-blue-500">Página Principal</a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

</div>
