<div class="mb-10">

    <div class="{{ setClass('divContentHeader') }} ">
        <div class="w-full">
            <div class="flex justify-between items-center">
                <div>
                    {!! setLabelHeader('Plataforma Faturamento', 'Fatura' ) !!}
                </div>
                <div class="flex justify-end p-0">
                    {{-- <x-button flat white icon="switch-horizontal" wire:click="alterarTarget" class="hover:text-sky-500"/> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="{{ setClass('divContentTitleDiv') }}">

        <div class="w-auto mr-4">
            {!! setLabelHeader($event->organizer->organizer_name_full,$event->event_name,formatDateStartFinish($event->event_datetime_start,$event->event_datetime_finish)) !!}
        </div>

        {{-- <div class="w-full flex justify-end gap-4 items-end"> --}}
        <div class="w-full grid grid-cols-3 gap-4 items-end">
            <div class="w-full"><x-button sm rounded class="w-full" dark label="EVENTO ISENTO" onclick="confirm('Confirma o evento?') || event.stopImmediatePropagation()" wire:click="eventoCancelado('evento_isento')" /></div>
            <div class="w-full"><x-button sm rounded class="w-full" dark label="NÃO FATURAR" onclick="confirm('Confirma o não faturar do evento?') || event.stopImmediatePropagation()" wire:click="eventoCancelado('nao_faturar')" /></div>
            <div class="w-full"><x-button sm rounded class="w-full" negative label="CANCELAR EVENTO" onclick="confirm('Confirma o cancelamento do evento?') || event.stopImmediatePropagation()" wire:click="eventoCancelado" /></div>
            <div class="w-full"><x-button sm rounded class="w-full" secondary spinner label="{{ ($event->faturamento ?? false ) ? 'ATUALIZAR CALCULOS' : 'CALCULA VALOR' }}" wire:click="gerar" /></div>
            @if ($event->faturamento ?? false)
                <div class="w-full"><x-button sm rounded class="w-full" outline positive label="REMOVER" onclick="confirm('Confirma a remoção da fatura?') || event.stopImmediatePropagation()" wire:click="removerFatura" /></div>
            @endif
            <div class="w-full"><x-button sm rounded class="w-full" negative outline label="{{ ($event->faturamento ?? false ) ? 'VOLTAR' : 'CANCELAR' }}" href="{{ route('plataforma-faturamento') }}" /></div>
        </div>

    </div>

    <div class="{{ setClass('divContentErros') }}">
        <x-jet-validation-errors />
    </div>

    <div class="{{ setClass('divContent') }} gap-4 items-center">

        <div class="flex justify-between items-end gap-4">

            <div class="grid grid-cols-5 justify-start gap-2">
                <x-input type="date" wire:model.defer="data_faturamento" label="DATA FATURAMENTO" class="w-full" />
                <x-input type="text" wire:model.defer="nota_fiscal" label="Nº NOTA FISCAL" class="w-full" />
                <x-input type="text" wire:model.defer="pay_amont" label="VALOR (123,45 = 12345)" class="w-full" />
                <x-input type="text" wire:model.defer="descricao" label="DESCRICAO" class="w-full" />
                <x-input type="text" wire:model.defer="qtd_parcelas" label="QTD PARCELAS" class="w-full" />
            </div>


            @if ($event->faturamento ?? false)
                <div>
                    <x-button rounded negative outline label="PAGAMENTOS" icon="refresh" wire:click="gerar(true)" />
                </div>
            @endif

        </div>

    </div>

    @if ($event->faturamento ?? false)

        <div class="{{ setClass('divContent') }} mt-2">

            <div class="w-full">

                <div class="grid grid-cols-12">
                    <div class="col-span-6">{!! setLabel('STATUS', $event->faturamento->pay_status ) !!}</div>
                    <div class="col-span-3">{!! setLabel('DATA FATURAMENTO', convertToDate($event->faturamento->pay_date) ) !!}</div>
                    <div class="col-span-3">{!! setLabel('RECEITA', toMoney($event->faturamento->vendas_valor_total,'R$ ') ) !!}</div>
                    <div class="col-span-6">{!! setLabel('FATURA', $event->faturamento->descricao ) !!}</div>
                    <div class="col-span-3">{!! setLabel('VALOR', toMoney($event->faturamento->valor,'R$ ') ) !!}</div>
                    <div class="col-span-3">{!! setLabel('PARCELAS', $event->faturamento->qtd_parcelas ) !!}</div>
                </div>

                @if ($event->faturamento->pagamentos ?? false)

                    @foreach ($event->faturamento->pagamentos->sortBy('pay_data_vencimento') as $pagamento)

                        <div class="pt-2 pb-4"><hr></div>
                        {{--
                        <div class="w-full">
                            {{ $pagamento }}
                        </div>
                        --}}

                        <div class="w-full grid grid-cols-12 items-center">
                            <div class="col-span-2">{!! setLabel('DESCRIÇÃO', $pagamento->pay_descricao ) !!}</div>
                            <div class="col-span-2">{!! setLabel('STATUS', $pagamento->pay_status ) !!}</div>
                            <div class="col-span-2">{!! setLabel('VALOR', toMoney($pagamento->pay_valor,'R$ ') ) !!}</div>
                            <div class="col-span-2">{!! setLabel('VENCIMENTO', convertToDate($pagamento->pay_data_vencimento) ) !!}</div>
                            <div class="col-span-2">{!! setLabel('PAGAMENTO', ($pagamento->pay_data ? convertToDate($pagamento->pay_data) : '---')) !!}</div>
                            <div class="col-span-2">
                                <div class="w-full flex justify-end items-center gap-2">
                                    @if ($pagamento->pay_data ?? false)
                                        <x-button.circle rounded negative icon="x" wire:click="resetParcela('{{ $pagamento->id }}')" />
                                    @else
                                        <x-input type="date" wire:model.defer="pay_data" />
                                        <x-button.circle rounded positive icon="check" wire:click="pagarParcela('{{ $pagamento->id }}')" />
                                        <x-button.circle rounded negative icon="x" label="aaa" wire:click="removeParcela('{{ $pagamento->id }}')" />
                                    @endif
                                </div>
                            </div>
                        </div>

                    @endforeach

                @else

                    <div class="pt-2 pb-4"><hr></div>

                    <div>SEM PAGAMENTOS CADASTRADOS</div>

                @endif

            </div>

        </div>


        {{-- <pre>{{ print_r($event->faturamento->toArray()) }}</pre> --}}

    @endif

</div>
