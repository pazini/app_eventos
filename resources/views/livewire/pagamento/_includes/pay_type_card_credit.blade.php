
    @php
        foreach (listMes() as $MM => $mes) { $listaMM[$MM] = $MM; }
        foreach (range(now()->format('Y'), now()->addYears(15)->format('Y')) as $AAAA) { $listaAAAA[$AAAA] = $AAAA; }
    @endphp

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

    <form wire:submit.prevent="processarPagamento({{ $target->pay_sandbox ?? false }})">

        <div class="flex flex-col gap-5">

            {{-- Cabeçalho da seção --}}
            <div class="flex items-center gap-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: {{ $colorPrimary ?? '#6366f1' }}15;">
                    <svg class="w-4 h-4" style="color: {{ $colorPrimary ?? '#6366f1' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-widest text-gray-400">Pagamento</div>
                    <div class="text-base font-bold text-gray-800 leading-tight">Dados do Cartão de Crédito</div>
                </div>
            </div>

            {{-- Campos do formulário --}}
            @php
                $inputClass = 'w-full text-sm font-medium text-gray-800 bg-white rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-offset-0 transition placeholder-gray-300';
                $inputStyle = 'border: 1px solid #d1d5db;';
                $labelClass = 'block text-xs font-semibold uppercase tracking-widest text-gray-500 mb-1.5';
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- CPF --}}
                <div>
                    <label class="{{ $labelClass }}">CPF do titular do cartão</label>
                    <input type="text" wire:model.defer="card_credit_cpf" placeholder="000.000.000-00" required
                        maxlength="14" inputmode="numeric"
                        oninput="let v=this.value.replace(/\D/g,'').slice(0,11);this.value=v.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');"
                        class="{{ $inputClass }}" style="{{ $inputStyle }}" />
                </div>

                {{-- Número do cartão --}}
                <div>
                    <label class="{{ $labelClass }}">Número do cartão</label>
                    <input type="text" wire:model.defer="card_credit_num" placeholder="0000 0000 0000 0000" required
                        maxlength="19" inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').slice(0,16).replace(/(\d{4})(?=\d)/g,'$1 ');"
                        class="{{ $inputClass }}" style="{{ $inputStyle }}" />
                </div>

                {{-- Nome --}}
                <div>
                    <label class="{{ $labelClass }}">Nome impresso no cartão</label>
                    <input type="text" wire:model.defer="card_credit_nome" placeholder="Como está no cartão" required
                        oninput="this.value=this.value.toUpperCase();"
                        class="{{ $inputClass }}" style="{{ $inputStyle }}" />
                </div>

                {{-- Validade --}}
                <div>
                    <label class="{{ $labelClass }}">Validade</label>
                    <div class="flex gap-2">
                        <select wire:model.defer="card_credit_validade_mm" required
                            class="{{ $inputClass }} w-1/2"
                            style="{{ $inputStyle }}">
                            <option value="">MM</option>
                            @foreach ($listaMM as $mm)
                                <option value="{{ $mm }}">{{ $mm }}</option>
                            @endforeach
                        </select>
                        <select wire:model.defer="card_credit_validade_aaaa" required
                            class="{{ $inputClass }} w-1/2"
                            style="{{ $inputStyle }}">
                            <option value="">AAAA</option>
                            @foreach ($listaAAAA as $aaaa)
                                <option value="{{ $aaaa }}">{{ $aaaa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- CVV --}}
                <div>
                    <label class="{{ $labelClass }}">Código de segurança (CVV)</label>
                    <input type="text" wire:model.defer="card_credit_cvv" placeholder="CVV" required
                        maxlength="4" inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').slice(0,4);"
                        class="{{ $inputClass }}" style="{{ $inputStyle }}" />
                </div>

                {{-- Parcelamento --}}
                <div>
                    <label class="{{ $labelClass }}">Parcelamento</label>
                    <select wire:model.defer="pay_installments_number" required
                        class="{{ $inputClass }}"
                        style="{{ $inputStyle }}">
                        @foreach ($pagamento_parcelas ?? [] as $parcelaKey => $parcelamento)
                            <option value="{{ $parcelaKey }}">{{ $parcelamento['label'] }} {{ $parcelamento['encargos'] ? '— COM JUROS' : '— SEM ACRÉSCIMO' }}</option>
                        @endforeach
                    </select>
                    <span class="text-xs text-gray-400 mt-1 block">Juros conforme operadora do cartão</span>
                </div>

            </div>

            {{-- CTA --}}
            <div>
                @if ($target->pay_sandbox ?? false)
                    <x-button type="submit" rounded positive label="PAGAR COM CARTÃO — TESTE" class="w-full text-base font-bold py-3 shadow-md rounded-xl" spinner />
                @else
                    <x-button type="submit" rounded positive label="PAGAR COM CARTÃO" class="w-full text-base font-bold py-3 shadow-md rounded-xl" onclick="confirm('Confirma o pagamento com cartão de crédito?') || event.stopImmediatePropagation()" spinner />
                @endif
            </div>

        </div>

    </form>


