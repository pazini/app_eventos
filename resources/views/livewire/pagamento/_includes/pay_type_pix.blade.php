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
            @if (session('info_sub'))
                <p class="text-xs font-normal mt-1">{{ __(session('info_sub')) }}</p>
            @endif
        </div>
    @endif
    @if (session('pix_alert'))
        <div class="mb-4 bg-blue-50 text-blue-700 border border-blue-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm">{{ __(session('pix_alert')) }}</p>
            @if (session('pix_alert_sub'))
                <p class="text-xs font-normal mt-1">{{ __(session('pix_alert_sub')) }}</p>
            @endif
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-xl text-center">
            <p class="font-semibold uppercase text-sm">Ops! Erro no preenchimento. Revise os dados.</p>
        </div>
    @endif

    {{-- ════════════════════════════════════
        ESTADO: PIX JÁ GERADO (legado ou atual)
    ════════════════════════════════════ --}}
    @php
        $pixAtivo = ($currentPayment ?? false)
            || (($pixValido ?? false) && ($payment ?? false) && !in_array($payment->status, listPaymentStatusPaidCanceled()));
        $pixData  = $currentPayment ?? $payment ?? null;
        $isLegado = (bool)($currentPayment ?? false);
    @endphp

    @if ($pixAtivo && $pixData)

        <div class="flex flex-col gap-4">

            {{-- Status --}}
            <div class="flex items-center gap-3 rounded-xl px-4 py-3" style="background: {{ $colorPrimary ?? '#6366f1' }}08; border: 1px solid {{ $colorPrimary ?? '#6366f1' }}20;">
                <svg class="w-5 h-5 flex-shrink-0" style="color: {{ $colorPrimary ?? '#6366f1' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-widest" style="color: {{ $colorPrimary ?? '#6366f1' }};">{{ __($pixData->status) }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ __($pixData->description) }}</div>
                </div>
            </div>

            {{-- Dados do PIX --}}
            <div class="flex flex-col md:flex-row gap-5 items-start">

                {{-- Info + ações --}}
                <div class="w-full md:flex-1 flex flex-col gap-3">

                    {{-- Linha Valor --}}
                    <div class="flex justify-between items-center py-2.5" style="border-bottom: 1px solid #e2e8f0;">
                        <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Valor</span>
                        <span class="text-lg font-bold text-gray-800">
                            {{ toMoney($pixData->value_paid ?: ($pixData->pay_installment_value ?? 0), 'R$ ') }}
                        </span>
                    </div>

                    {{-- Linha Validade --}}
                    <div class="flex justify-between items-center py-2.5" style="border-bottom: 1px solid #e2e8f0;">
                        <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Válido até</span>
                        <span class="text-sm font-semibold text-gray-700">{{ dataCarbon($pixData->pay_pix_expires_at, 'd/m/Y H:i') }}</span>
                    </div>

                    {{-- PIX Copia e Cola --}}
                    <div class="flex flex-col gap-1.5">
                        <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">PIX Copia e Cola</span>
                        <div class="flex items-center gap-2 rounded-xl px-3 py-2.5" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <span class="flex-1 text-xs font-mono text-gray-600 break-all leading-relaxed">{{ $pixData->pay_pix_key ?? '---' }}</span>
                            <x-button flat blue class="flex-shrink-0 p-1" right-icon="clipboard" title="Copiar"
                                id="pay_pix_key_btn"
                                onclick="copyToClipboard('pay_pix_key_btn','Código PIX copiado!')"
                                data-clipboard-text="{{ $pixData->pay_pix_key ?? '---' }}" />
                        </div>
                    </div>

                    {{-- Botões --}}
                    <div class="flex flex-col md:flex-row gap-2 mt-1">
                        <x-button blue class="w-full" right-icon="clipboard"
                            label="COPIAR CHAVE PIX"
                            id="pay_pix_key"
                            onclick="copyToClipboard('pay_pix_key','Código PIX copiado!')"
                            data-clipboard-text="{{ $pixData->pay_pix_key ?? '---' }}" />
                        @if ($isLegado)
                            <x-button green outline class="w-full" label="VALIDAR PAGAMENTO" wire:click="validarPagamento" spinner="validarPagamento" />
                        @else
                            <x-button green outline class="w-full" label="VALIDAR PAGAMENTO" wire:click="paymentCheckProcessed" spinner="paymentCheckProcessed" />
                        @endif
                    </div>

                </div>

                {{-- QR Code --}}
                @if ($pixData->pay_pix_qr_code_url ?? false)
                    <div class="w-full md:w-auto flex-shrink-0 flex justify-center">
                        <img src="{{ $pixData->pay_pix_qr_code_url }}" alt="QR Code PIX" class="w-full md:w-52 object-contain">
                    </div>
                @endif

            </div>

            {{-- Aviso pendente --}}
            <div class="text-center rounded px-4 py-2.5 bg-yellow-50 text-yellow-800 text-xs font-medium" style="border: 1px solid #fde68a;">
                {{ __('pending_pix_sub') }}
            </div>

            @if (!$isLegado && ($order->buyer_email == "proeventpay@gmail.com"))
                <x-button red icon="trash" class="w-full" label="REMOVER PAGAMENTO ATUAL {{ $payment->id }}" wire:click="paymentReset('pagamento_cancelado')" />
            @endif

        </div>

    @else

        {{-- ════════════════════════════════════
            ESTADO: GERAR PIX
        ════════════════════════════════════ --}}
        <div class="flex flex-col gap-5">

            {{-- Cabeçalho --}}
            <div class="flex items-start gap-3 rounded-xl px-4 py-3" style="background: {{ $colorPrimary ?? '#6366f1' }}08; border: 1px solid {{ $colorPrimary ?? '#6366f1' }}20;">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: {{ $colorPrimary ?? '#6366f1' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <div class="text-xs font-bold uppercase tracking-wide" style="color: {{ $colorPrimary ?? '#6366f1' }};">Atenção ao prazo</div>
                    <div class="text-xs text-gray-500 mt-0.5 leading-relaxed">Após gerar a chave você tem <strong>10 minutos</strong> para realizar o pagamento via PIX.</div>
                </div>
            </div>

            {{-- CPF + Botão --}}
            <div class="flex flex-col gap-3">
                <div class="w-full">
                    <label class="block text-xs font-semibold uppercase tracking-widest text-gray-500 mb-1.5">CPF do pagador PIX</label>
                    <input type="text" wire:model.defer="pix_cpf" placeholder="000.000.000-00" required
                        maxlength="14" inputmode="numeric"
                        oninput="let v=this.value.replace(/\D/g,'').slice(0,11);this.value=v.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');"
                        class="w-full text-sm font-medium text-gray-800 bg-white rounded-lg px-3 py-2.5 focus:outline-none transition placeholder-gray-300"
                        style="border: 1px solid #d1d5db;" />
                    <span class="text-xs text-gray-400 mt-1 block">CPF de quem vai realizar o pagamento</span>
                </div>
                <div class="w-full">
                    @if ($target->pay_sandbox ?? false)
                        <x-button rounded positive
                            label="GERAR PIX {{ toMoney($slipPayment->installment_value ?? $order_amount, 'R$ ') }} — TESTE"
                            class="w-full text-base font-bold py-3 shadow-md rounded-xl"
                            wire:click="processarPagamento(true)"
                            spinner />
                    @else
                        <x-button rounded positive
                            label="GERAR PIX {{ toMoney($slipPayment->installment_value ?? $order_amount, 'R$ ') }}"
                            class="w-full text-base font-bold py-3 shadow-md rounded-xl"
                            onclick="confirm('Confirma o pagamento com PIX?') || event.stopImmediatePropagation()"
                            wire:click="processarPagamento()"
                            spinner />
                    @endif
                </div>
            </div>

        </div>

    @endif


