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
                        <pattern id="grid-pattern-gestao-orcamentaria" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-gestao-orcamentaria)"/>
                </svg>
            </div>
            <div class="relative z-10 p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Gestão Orçamentária</h1>
                                <p class="text-white/90 text-sm">{{ $target->event_name }} - {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-button white outline sm icon="document-text" label="PLANILHA" href="{{ route('dashboard-financeiro-gestao-orcamentaria-planilha') }}" class="hover:bg-white/20" />
                        <a href="{{ $target->id ? route('evento-by-uuid', $target->id) : ($referer ?? route('dashboard-evento')) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-white/20 border border-white/40 rounded hover:bg-white/30 hover:border-white/60 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            VOLTAR
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full">

            {{-- CARDS DE RESUMO --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- RECEITAS --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Receitas</div>
                        <div class="text-3xl font-bold text-gray-900 mt-2">
                            R$ {{ number_format((int) $valorReceitas / 100 , 2 , ',' , '.') }}
                        </div>
                    </div>
                    <div class="px-6 py-3">
                        <p class="text-sm text-gray-500">Somatório das receitas</p>
                    </div>
                </div>

                {{-- DESPESAS --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-red-50 to-red-100 px-6 py-4 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Despesas</div>
                        <div class="text-3xl font-bold text-gray-900 mt-2">
                            R$ {{ number_format((int) $valorDespesas / 100 , 2 , ',' , '.') }}
                        </div>
                    </div>
                    <div class="px-6 py-3">
                        <p class="text-sm text-gray-500">Somatório das despesas</p>
                    </div>
                </div>

                {{-- SALDO PREVISTO --}}
                @php
                    $saldoColor = ($valorSaldo >= 0) ? 'green' : 'red';
                    $saldoBg = ($valorSaldo >= 0) ? 'from-green-50 to-green-100' : 'from-red-50 to-red-100';
                @endphp
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br {{ $saldoBg }} px-6 py-4 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Saldo Previsto</div>
                        <div class="text-3xl font-bold text-{{ $saldoColor }}-700 mt-2">
                            R$ {{ number_format((int) $valorSaldo / 100 , 2 , ',' , '.') }}
                        </div>
                    </div>
                    <div class="px-6 py-3">
                        <p class="text-sm text-gray-500">Receita subtraída da despesa</p>
                    </div>
                </div>
            </div>

            {{-- RECEITAS --}}
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Receitas</h2>
                    <x-button flat lg primary label="Editar" href="{{ route('dashboard-financeiro-gestao-orcamentaria-receita') }}" />
                </div>
                <div class="p-6">
                    <div class="divide-y divide-gray-200">

                        @forelse ($receitas ?? [] as $receitaKey => $receita)
                            <div class="py-4">
                                <a class="flex justify-between items-center w-full text-base font-medium text-gray-900 hover:bg-gray-50 p-3 rounded-lg transition-colors cursor-pointer" data-bs-toggle="collapse" href="#collapse_receita_{{ $receitaKey }}" role="button" aria-expanded="false">
                                    <div class="font-semibold uppercase">{{ $receita['title'] ?? 'RECEITA #' . ($receitaKey+1) }}</div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-green-600">{{ toMoney($receita['amount']) }}</span>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </a>

                                <div class="w-full pt-4 collapse overflow-x-auto" id="collapse_receita_{{ $receitaKey }}">
                                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qtd.</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Unitário</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Líquido</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse ($receita['items'] ?? [] as $item)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 uppercase">{{ $item->item_name }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ $item->item_qtd }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ toMoney($item->item_amount) }}</td>
                                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">{{ toMoney($item->item_amount_total) }}</td>
                                                        <td class="px-4 py-3 text-sm font-semibold text-green-600 text-center">{{ toMoney($item->item_amount_liquid) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">Sem itens adicionados</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <p class="mt-2 text-sm font-medium text-gray-900">Nenhuma receita lançada</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- DESPESAS --}}
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg mt-6">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Despesas</h2>
                    <x-button flat lg primary label="Editar" href="{{ route('dashboard-financeiro-gestao-orcamentaria-despesa') }}" />
                </div>
                <div class="p-6">
                    <div class="divide-y divide-gray-200">

                        @forelse ($despesas ?? [] as $despesaKey => $despesa)
                            <div class="py-4">
                                <a class="flex justify-between items-center w-full text-base font-medium text-gray-900 hover:bg-gray-50 p-3 rounded-lg transition-colors cursor-pointer" data-bs-toggle="collapse" href="#collapse_despesas_{{ $despesaKey }}" role="button" aria-expanded="false">
                                    <div class="font-semibold uppercase">{{ $despesa['title'] ?? 'DESPESA #' . ($despesaKey+1) }}</div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-red-600">{{ toMoney($despesa['amount'] ?? 0) }}</span>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </a>

                                <div class="w-full pt-4 collapse overflow-x-auto" id="collapse_despesas_{{ $despesaKey }}">
                                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição / Fornecedor</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qtd.</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Unitário</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Investimento</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">A Pagar</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pago</th>
                                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Situação</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse ($despesa['items'] ?? [] as $item)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 text-sm">
                                                            <div class="font-medium text-gray-900 uppercase">{{ $item->item_name }}</div>
                                                            @if ($item->provider_name ?? false)
                                                                <div class="text-xs text-gray-500 uppercase mt-1">{{ $item->provider_name }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ $item->item_qtd ?? 0 }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ toMoney($item->item_amount) }}</td>
                                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">{{ toMoney($item->item_amount_total) }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ toMoney($item->item_amount_investment) }}</td>
                                                        <td class="px-4 py-3 text-sm font-semibold text-orange-600 text-center">{{ toMoney($item->item_amount_total - $item->item_amount_paid) }}</td>
                                                        <td class="px-4 py-3 text-sm font-semibold text-green-600 text-center">{{ toMoney($item->item_amount_paid) }}</td>
                                                        <td class="px-4 py-3 text-sm text-center">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 uppercase">
                                                                {{ __($item->item_status ?? '--') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="px-4 py-4 text-center text-sm text-gray-500">Sem itens adicionados</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <p class="mt-2 text-sm font-medium text-gray-900">Nenhuma despesa lançada</p>
                            </div>
                        @endforelse
                    </div>
                </div>
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
                            <h1 class="text-2xl font-bold text-white">Gestão Orçamentária</h1>
                            <p class="text-white/90 text-sm">É preciso selecionar um evento</p>
                                <div class="mt-2"><a href="{{ route('dashboard') }}" class="text-white/90 text-sm hover:text-white/70 border border-white mt-4 p-2 rounded shadow hover:bg-gray-50 hover:text-blue-500">Página Principal</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg p-6">
                <div class="flex-none md:flex w-full gap-2">
                    @include('livewire.dashboard.includes.financeiro-menu-top')
                </div>
            </div>
        @endif

</div>
