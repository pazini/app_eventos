<div>

    {{-- Alertas --}}
    @if (session('conclusao_error'))
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm">{{ __(session('conclusao_error')) }}</p>
            @if (session('conclusao_error_sub'))
                <p class="text-xs font-normal uppercase mt-1">{{ __(session('conclusao_error_sub')) }}</p>
            @endif
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm">{{ __(session('error')) }}</p>
        </div>
    @endif
    @if (session('info'))
        <div class="mb-4 bg-blue-50 text-blue-700 border border-blue-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm">{{ __(session('info')) }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm">Ops! Erro no preenchimento. Revise os dados.</p>
        </div>
    @endif

    <form wire:submit.prevent="processarPagamento">

        <div class="flex flex-col md:flex-row gap-5">

            {{-- ════════════════════════════════════
                COLUNA ESQUERDA — Parcelas
            ════════════════════════════════════ --}}
            <div class="w-full md:w-1/2">

                {{-- Cabeçalho da seção --}}
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-widest text-gray-400">Parcelamento</div>
                        <div class="text-base font-bold text-gray-800 leading-tight mt-0.5">Carnê PIX</div>
                    </div>
                    @if ($installment_max > 1)
                        <div>
                            <select wire:model="pay_installments_number_slip" required
                                class="text-sm font-semibold rounded-lg px-3 py-2 focus:outline-none transition bg-white text-gray-800"
                                style="border: 2px solid {{ $colorPrimary ?? '#6366f1' }}; min-width: 130px;">
                                <option value="">Selecione</option>
                                @foreach (range(2, $installment_max) as $parcela)
                                    <option value="{{ $parcela }}">{{ $parcela }}x parcelas</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                {{-- Tabela de parcelas --}}
                @if (count($pagamento_parcelas ?? []))
                    <div class="rounded-xl overflow-hidden" style="border: 1px solid #e2e8f0;">

                        {{-- Header --}}
                        <div class="grid grid-cols-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400 px-1 py-2.5 bg-gray-50" style="border-bottom: 1px solid #e2e8f0;">
                            <div>Parcela</div>
                            <div>Vencimento</div>
                            <div>Valor</div>
                        </div>

                        @foreach ($pagamento_parcelas as $parcela_key => $parcela_item)
                            <div class="grid grid-cols-3 text-center items-center px-1 py-3 transition-colors hover:bg-gray-50 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">

                                {{-- Parcela --}}
                                <div class="flex justify-center items-center gap-1.5">
                                    <span class="text-sm font-semibold text-gray-700">{{ str_replace('Parcela ', '', $parcela_item['label']) }}</span>
                                </div>

                                {{-- Vencimento --}}
                                <div>
                                    @if ($parcela_item['parcela'] == 1)
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-bold text-orange-600 bg-orange-50 rounded-full" style="border: 1px solid #fed7aa;">IMEDIATO</span>
                                    @else
                                        <span class="text-sm font-semibold text-gray-700">{{ $parcela_item['vencimento'] }}</span>
                                    @endif
                                </div>

                                {{-- Valor --}}
                                <div class="text-sm font-bold text-gray-800 whitespace-nowrap">
                                    {{ toMoney($parcela_item['parcela_valor'], 'R$ ') }}
                                </div>

                            </div>
                        @endforeach

                        {{-- Total --}}
                        <div class="flex items-center justify-between px-4 py-3" style="background: {{ $colorPrimary ?? '#6366f1' }}08; border-top: 2px solid {{ $colorPrimary ?? '#6366f1' }}20;">
                            <span class="text-xs font-semibold uppercase tracking-widest" style="color: {{ $colorPrimary ?? '#6366f1' }};">Total do Carnê</span>
                            <span class="text-lg font-bold" style="color: {{ $colorPrimary ?? '#6366f1' }};">
                                {{ toMoney($parcela_item['parcela_valor'] * $parcela_item['parcela_qtd'], 'R$ ') }}
                            </span>
                        </div>

                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-sm text-gray-400 uppercase tracking-widest">
                        Nenhuma parcela disponível
                    </div>
                @endif

            </div>

            {{-- ════════════════════════════════════
                COLUNA DIREITA — Ação
            ════════════════════════════════════ --}}
            <div id="PagarParcela" class="w-full md:w-1/2 flex flex-col gap-4">

                @if ($pagamento_parcelas ?? false)

                    {{-- Bloco destaque: prazo --}}
                    <div class="flex items-start gap-3 rounded-xl px-4 py-3" style="background: {{ $colorPrimary ?? '#6366f1' }}08; border: 1px solid {{ $colorPrimary ?? '#6366f1' }}20;">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: {{ $colorPrimary ?? '#6366f1' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-wide" style="color: {{ $colorPrimary ?? '#6366f1' }};">Atenção ao prazo</div>
                            <div class="text-xs text-gray-500 mt-0.5 leading-relaxed">Após gerar a chave você tem <strong>10 minutos</strong> para pagar a 1ª parcela via PIX.</div>
                        </div>
                    </div>

                    {{-- CPF --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest text-gray-500 mb-1.5">CPF do pagador PIX</label>
                        <input type="text" wire:model.defer="pix_cpf" placeholder="000.000.000-00" required
                            maxlength="14" inputmode="numeric"
                            oninput="let v=this.value.replace(/\D/g,'').slice(0,11);this.value=v.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');"
                            class="w-full text-sm font-medium text-gray-800 bg-white rounded-lg px-3 py-2.5 focus:outline-none transition placeholder-gray-300"
                            style="border: 1px solid #d1d5db;" />
                        <span class="text-xs text-gray-400 mt-1 block">CPF de quem vai realizar o pagamento</span>
                    </div>

                    {{-- Termos --}}
                    @if (isset($aceiteTermos['slip_pix']))

                        <div>
                            {{-- Banner termos --}}
                            <div class="flex items-center gap-2 rounded-t-xl px-4 py-3 bg-amber-500">
                                <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <div class="flex flex-col leading-tight">
                                    <span class="text-xs font-bold uppercase tracking-widest text-white">Termos do Carnê PIX</span>
                                    <span class="text-xs font-black uppercase tracking-widest text-white opacity-90">⚠ Leia com atenção</span>
                                </div>
                            </div>

                            {{-- Itens dos termos --}}
                            <div class="rounded-b-xl overflow-hidden" style="border: 1px solid #e2e8f0; border-top: none;">
                                @foreach ($aceiteTermos['slip_pix'] ?? [] as $termosKey => $termosItem)
                                    <label for="{{ $termosKey }}"
                                        class="flex items-start gap-3 px-4 py-3 cursor-pointer transition-colors hover:bg-gray-50 {{ !$loop->last ? 'border-b border-gray-100' : '' }}"
                                    >
                                        <input
                                            type="checkbox"
                                            id="{{ $termosKey }}"
                                            name="{{ $termosKey }}"
                                            wire:model.defer="aceite_termos.slip_pix.{{ $termosKey }}.check"
                                            class="mt-0.5 rounded flex-shrink-0"
                                            required
                                        />
                                        <span class="text-xs text-gray-600 leading-relaxed">{{ $termosItem['termo'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    @endif

                    {{-- Botão CTA --}}
                    <div class="mt-auto">
                        @if ($target->pay_sandbox ?? false)
                            <x-button rounded positive type="submit"
                                label="GERAR CARNÊ PIX — TESTE"
                                class="w-full text-base font-bold py-3 shadow-md rounded-xl"
                                spinner="processarPagamentoSlip" />
                        @else
                            <x-button rounded positive type="submit"
                                label="GERAR CARNÊ PIX"
                                class="w-full text-base font-bold py-3 shadow-md rounded-xl"
                                onclick="confirm('Confirma a geração de carnê PIX? Após a geração, o método de pagamento não poderá ser alterado.') || event.stopImmediatePropagation()"
                                wire:confirm=""
                                spinner="processarPagamentoSlip" />
                        @endif
                    </div>

                @else
                    <x-button red label="Nenhum pagamento disponível" class="w-full rounded-xl" />
                @endif

            </div>

        </div>

    </form>

</div>
