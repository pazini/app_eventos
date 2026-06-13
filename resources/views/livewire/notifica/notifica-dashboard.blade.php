<div class="mb-10">

    <div class="{{ setClass('divContentHeader') }} ">
        <div class="w-full">
            <div class="flex justify-between items-center">
                <div>
                    {!! setLabelHeader('Evento', $target->event_name, formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? null) !!}
                </div>
                <div class="p-0">
                    <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-evento') }}" class="hover:text-sky-500" />
                </div>
            </div>
            <div class="border-t border-white mt-2 mb-4"></div>
            <div class="w-full flex flex-col md:flex-row justify-between">
                <div class="flex justify-start gap-4">
                    <div class="text-2xl font-semibold">NOFITICAÇÕES</div>
                </div>
                <div class="flex justify-end gap-4">

                    @if ($this->notificacao ?? false)
                        @if (!in_array($this->notificacao->status,['concluido']))
                            <x-button outline white label="ALTERAR" href="{{ route('notifica-alterar',['notificacao_id' => $this->notificacao->id]) }}" class="hover:text-blue-700" />
                        @endif
                    @else
                        <x-button outline white label="Nova Notificação" href="{{ route('notifica-nova') }}" class="hover:text-blue-700" />
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="{{ setClass('divContentErros') }}">
        <div class="w-full my-2">
            <x-jet-validation-errors />
        </div>
    </div>

    {{--  --}}
    @if ($this->notificacao ?? false)

        <div class="{{ setClass('divContent') }} bg-white py-8">

            <div class="w-full">

                <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4 items-center">

                    <div class="col-span-full md:col-span-5">
                        {!! setLabelHeader(false, $this->notificacao->envio_nome,$this->notificacao->envio_descricao) !!}
                    </div>

                    <div class="col-span-full md:col-span-2">
                        {!! setLabel('TIPO', __($this->notificacao->envio_tipo ?? '---')) !!}
                    </div>

                    <div class="col-span-full md:col-span-3">
                        {!! setLabel('STATUS', $this->notificacao->status ?? '---') !!}
                    </div>

                    <div class="col-span-full md:col-span-2">
                        {!! setLabel('QTD ENVIOS', $this->notificacao->notificacaoEnvio->count() ?? 0) !!}
                    </div>

                    <div class="col-span-full md:col-span-full">
                        <hr>
                    </div>

                    @if (!in_array($this->notificacao->status,['concluido']))

                        <div class="col-span-full">

                            <div class="flex justify-between items-center">

                                <div class="w-9/12 grid grid-cols-1 md:grid-cols-5 items-end gap-4">

                                    <div class="text-sm font-normal text-gray-700 uppercase">
                                        STATUS ENVIOS
                                    </div>

                                    @foreach ($this->notificacao->notificacaoEnvio->groupBy('status') as $envioStatus => $envioItem)

                                        <div class="w-full text-sm font-normal px-4 bg-gray-700 text-white rounded-full shadow-sm uppercase">
                                            <div class="flex justify-between items-center gap-1">
                                                <div class="truncate">{{ __($envioStatus ?? '---') }}</div>
                                                <div class="font-bold ml-2">{{ $envioItem->count() ?? 0 }}</div>
                                            </div>
                                        </div>

                                    @endforeach

                                </div>

                                <div class="w-3/12">

                                    <div class="flex flex-col gap-2">

                                        @if (($processar ?? 0) > 0)


                                            <div wire:poll.3s="processarEnvio" class="flex gap-4">
                                                <span>PROCESSANDO ...</span>
                                                <x-button sm negative outline spinner label="Cancelar" wire:click="$set('processar',false)" class="py-1" />
                                            </div>

                                        @else

                                            <x-button sm positive spinner label="PROCESSAR ENVIO" wire:click="$set('processar',true)" class="py-1" />

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-span-full md:col-span-full">
                            <hr>
                        </div>

                    @endif

                    <div class="col-span-full">
                        {!! setLabel('ASSUNTO', $this->notificacao->envio_assunto ?? '---') !!}
                    </div>

                    <div class="col-span-full md:col-span-full">
                        <hr>
                    </div>

                    <div class="col-span-full -mt-4">

                        @forelse ($this->notificacao->notificacaoEnvio ?? [] as $envio_item)

                            <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4 rounded shadow {{ in_array($envio_item->status,['ok']) ? 'bg-green-100' : 'bg-gray-100' }} py-2 px-4 mb-2 items-center">

                                <div class="col-span-full md:col-span-5 uppercase">
                                    {{ $envio_item->destino_nome }}
                                </div>

                                <div class="col-span-full md:col-span-5">
                                    {{ $envio_item->destino }}
                                </div>

                                <div class="col-span-full md:col-span-2 text-center">
                                    @if ($envio_item->datahora ?? false)
                                        <div>{{ $envio_item->datahora->format('d/m/Y H:i') }}</div>
                                    @else
                                        <div>{{ $envio_item->status }}</div>
                                    @endif
                                </div>

                            </div>

                        @empty

                            <div class="col-span-full">
                                <div>NENHUM ENVIO CADASTRADO</div>
                            </div>

                        @endforelse

                    </div>

                    <div class="col-span-full md:col-span-full mb-4">
                        <hr>
                    </div>

                </div>

            </div>

        </div>

    @else

        @forelse ($this->notificacoes ?? [] as $notificacao_item)

            <div class="{{ setClass('divContent') }} bg-white py-2">

                <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4 items-center">

                    <div class="col-span-full md:col-span-5">
                        {!! setLabelHeader(false, $notificacao_item->envio_nome,$notificacao_item->envio_descricao) !!}
                    </div>

                    <div class="col-span-full md:col-span-2">
                        {!! setLabel('TIPO', __($notificacao_item->envio_tipo ?? '---')) !!}
                    </div>

                    <div class="col-span-full md:col-span-2">
                        {!! setLabel('STATUS', $notificacao_item->status ?? '---') !!}
                    </div>

                    <div class="col-span-full md:col-span-2">
                        {!! setLabel('QTD ENVIOS', $notificacao_item->notificacaoEnvio->count() ?? 0) !!}
                    </div>

                    <div class="col-span-full md:col-span-1 text-center">

                        <x-button negative flat label="exibir" href="{{ route('notifica-exibir',['notificacao_id' => $notificacao_item->id]) }}" class="py-1" />

                    </div>


                </div>

            </div>

        @empty

            <div class="{{ setClass('divContent') }} bg-white py-8">

                <div class="w-full">NENHUMA NOTIFICAÇÃO ENCONTRADA</div>

            </div>

        @endforelse

    @endif

</div>
