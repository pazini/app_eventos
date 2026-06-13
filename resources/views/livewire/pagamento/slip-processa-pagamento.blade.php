<div>
    {{-- LIVEWIRE - LOADER --}}
    <div wire:loading class="absolute top-0 left-0 z-50 w-full">
        <div class="fixed bg-gray-700 text-white opacity-50">
            <div class="flex h-screen w-screen justify-center items-center">
                <div class="text-center">
                    <img src="{{ asset('/assets/loader.v2.svg') }}" alt="" srcset="">
                </div>
            </div>
        </div>
    </div>
    {{-- LIVEWIRE - LOADER FIM --}}

    {{-- MODAL ERROR --}}
    @if (session('error'))
        <x-modal.card blur wire:model.defer="error">

            <div class="flex flex-col justify-center items-center px-6">

                <div>
                    <img src="{{ asset('images/icones/icon-error-animate.gif') }}" alt="Erro na Conclusão" class="h-32">
                </div>

                <div class="w-full text-center text-2xl text-red-700 mx-1 font-medium">{{ __(session('error')) }}</div>

                @if (session('error_sub'))
                    <div class="w-full text-center text-xl text-red-700 mx-8">{{ __(session('error_sub')) }}</div>
                @endif

            </div>

            <x-slot name="footer">
                <div class="flex justify-center gap-x-4">
                    <x-button flat label="Fechar" x-on:click="close" />
                </div>
            </x-slot>

        </x-modal.card>
    @endif
    {{-- MODAL ERROR - FIM --}}

    {{-- MODAL CONCLUSAO ERROR --}}
    @if (session('conclusao_error'))
        <x-modal.card blur wire:model.defer="conclusao_error">

            <div class="flex flex-col justify-center items-center px-6">

                <div>
                    <img src="{{ asset('images/icones/icon-alert-animate.gif') }}" alt="Erro na Conclusão" class="h-32">
                </div>

                <div class="w-full text-center text-2xl text-red-700 mx-1 mb-2 font-medium">{{ __(session('conclusao_error')) }}</div>

                @if (session('conclusao_error_sub'))
                    <div class="w-full text-center text-xl text-red-700 mx-8">{{ __(session('conclusao_error_sub')) }}</div>
                @endif

            </div>

            <x-slot name="footer">
                <div class="flex justify-center gap-x-4">
                    <x-button flat label="Fechar" x-on:click="close" />
                </div>
            </x-slot>
        </x-modal.card>
    @endif
    {{-- MODAL CONCLUSAO ERROR - FIM --}}

    {{-- MODAL SUCCESS --}}
    @if (session('conclusao_success'))
        <x-modal.card blur wire:model.defer="conclusao_success">

            <div class="flex flex-col justify-center items-center px-0 md:px-6">

                <div>
                    <img src="{{ asset('images/icones/icon-success-animate.gif') }}" alt="Sucesso" class="h-52 -mt-4">
                </div>

                <div class="w-full text-center text-2xl text-green-700 mx-1 font-medium -mt-10 uppercase">{{ __(session('conclusao_success')) }}</div>

                @if (session('conclusao_success_sub'))
                    <div class="w-full text-center text-xl text-green-700 mx-8">{{ __(session('conclusao_success_sub')) }}</div>
                @endif

                {{-- SE BOLETO PENDENTE --}}
                @if (($currentPayment ?? false) && $currentPayment->status == "pending_boleto" )

                    <div class="w-full py-2 px-4 mt-4 mb-1 uppercase text-center">
                        <div class="text-xs mb-1">CÓDIGO DE BARRAS</div>
                        <div class="w-full bg-gray-100 rounded shadow p-2">
                            <div class="text-base font-medium">{{ $currentPayment->pay_boleto_barcode ?? '---' }}</div>
                        </div>

                        <div class="flex justify-center gap-4 mt-4 capitalize">
                            <x-button outline flat primary class="w-1/2 md:w-1/3" icon="clipboard" label="Copiar Código"   title="Copiar Código"   id="pay_boleto_barcode" onclick="copyToClipboard('pay_boleto_barcode','Código de barras copiado!')" data-clipboard-text="{{ $currentPayment->pay_boleto_barcode ?? '---' }}" />
                            <x-button outline flat primary class="w-1/2 md:w-1/3" icon="printer"   label="Imprimir Boleto" title="Imprimir Boleto" href="{{ $currentPayment->pay_boleto_url ?? '#' }}" />
                        </div>
                    </div>

                @endif

                {{-- SE PIX PENDENTE --}}
                @if (($currentPayment ?? false) && in_array($currentPayment->status,["pending_pix","pending_slip_pix"]))

                    <div class="w-full py-2 px-4 mt-4 mb-1 uppercase text-center">
                        <div class="text-xs mb-1">CHAVE COPIA e COLA</div>
                        <div class="w-full bg-gray-100 rounded shadow p-2 text-center break-words overflow-hidden">
                            <div class="text-xs font-medium">{{ $currentPayment->pay_pix_key ?? 'CHAVE PIX AQUI' }}</div>
                        </div>

                        <div class="flex justify-center gap-4 mt-4 capitalize">
                            <x-button outline flat primary class="w-full md:w-1/2 uppercase" icon="clipboard" label="Copiar Código"   title="Copiar Código"   id="pay_pix_key" onclick="copyToClipboard('pay_pix_key','Código PIX copiado!')" data-clipboard-text="{{ $currentPayment->pay_pix_key ?? '---' }}" />
                            <x-button outline flat primary class="w-full md:w-1/2 uppercase" icon="check" label="Validar Pagamento"   title="Validar Pagamento" wire:click="validarPagamento" spinner="validarPagamento" />
                        </div>
                    </div>

                @endif

                <div class="flex flex-col justify-center items-center gap-2 pt-2 my-2">
                    <div class="text-sm font-normal">Pagamentos processados por</div>
                    <img src="{{ asset('assets/safe2pay-logo.png') }}" alt="Sucesso na Conclusão" class="h-10">
                </div>

            </div>

            <x-slot name="footer">
                <div class="flex justify-center gap-x-4">
                    <x-button flat label="Fechar" x-on:click="close" />
                </div>
            </x-slot>

        </x-modal.card>
    @endif
    {{-- MODAL SUCCESS - FIM --}}

    <div class="w-full grid grid-cols-12 justify-center gap-4">
        @if (in_array($slip->installment_pay_type,['pix','slip_pix']))
            @include('livewire.pagamento._includes.pay_type_pix')
        @else
            FORMA PAGAMENTO NÃO DEFINIDA
        @endif
    </div>

</div>
