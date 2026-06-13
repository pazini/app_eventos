<div class="">

    @auth
        {{-- @if ($faturasVencidas->count() ?? false) --}}
        @if ($faturasVencidasBloqueio->count() ?? false)

            <x-modal.card lg blur wire:model.defer="faturas_pendentes" title="{{ session('faturas_pendentes') }}">

                <div class="flex flex-col justify-center items-center">

                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/icones/icon-alert-animate.gif') }}" alt="Erro na Conclusão" class="h-16">

                        <div class="">
                            <div class="text-base text-red-700">
                                {{ getNome(auth()->user()->name) }}, existem boletos vencidos a mais de {{ $vencimentoDias }} dias.
                            </div>
                            <div class="hidden text-2xs text-gray-700 uppercase">
                                <span class="font-semibold">Atenção - </span> ultrapassando {{ $this->vencimentoDias * 2 }} dias as vendas serão suspensas automaticamente
                            </div>
                        </div>
                    </div>

                    @foreach ($faturasVencidas ? $faturasVencidas->sortBy('pay_data_vencimento') : [] as $fatura_item)
                        <div class="w-full mt-1 pt-1 border-t">
                            <div class="w-full grid grid-cols-12 items-center bg-gray-100 py-1 px-2 text-xs uppercase">
                                <div class="col-span-5">
                                    <div class="text-gray-600">{{ $fatura_item->faturamento->event->event_name ?? '--' }}</div>
                                    <div class="text-gray-600 text-3xs">{{ $fatura_item->faturamento->event->organizer->organizer_name_full ?? '--' }}</div>
                                </div>
                                <div class="col-span-3 text-center">{{ $fatura_item->pay_descricao ?? '--' }}</div>
                                <div class="col-span-2 text-center">{{ toMoney($fatura_item->pay_valor,'R$ ') }}</div>
                                <div class="col-span-2 text-center">
                                    <div class="">{{ convertToDate($fatura_item->pay_data_vencimento ?? '--') }}</div class="">
                                    <div class="text-2xs">{{ dateAgo($fatura_item->pay_data_vencimento ?? '--') }}</div class="">
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>

                <div class="w-full text-sm text-center font-normal mt-4 text-red-700">
                    Regularize os pagamentos para normalizar os acessos e evitar bloqueio total da Plataforma
                </div>

                <x-slot name="footer">
                    <div class="flex justify-center gap-x-4">
                        <x-button flat label="Fechar" x-on:click="close" />
                    </div>
                </x-slot>
            </x-modal.card>

        @endif
    @endauth

    @if (($bloquear ?? false) && $this->faturasVencidasBloqueio->count() ?? false)
        <script>
            window.location.replace("{{route('home')}}");
        </script>
    @endif

</div>
