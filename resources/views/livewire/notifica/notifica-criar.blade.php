<div class="mb-10">

    <div class="{{ setClass('divContentHeader') }} ">
        <div class="w-full">
            <div class="flex justify-between items-center">
                <div>
                    {!! setLabelHeader('Evento', $target->event_name, formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? null) !!}
                </div>
                <div class="p-0">
                    <x-button flat white icon="reply" label="VOLTAR" href="{{ route('notifica') }}" class="hover:text-sky-500" />
                </div>
            </div>

            <div class="border-t border-white mt-2 mb-4"></div>

            <div class="w-full flex flex-col md:flex-row justify-between items-center">

                <div class="flex justify-start gap-4">
                    <div class="text-2xl font-semibold">NOVA NOTIFICAÇÃO</div>
                </div>

                <div class="flex justify-end gap-4">
                    {!! setLabel('QTD.', ($envio_qtd ?? 0) . ' ENVIOS') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="{{ setClass('divContentErros') }}">
        <div class="w-full my-2">
            @include('_includes.alertas')
        </div>
    </div>

    {{--  --}}
    <div class="{{ setClass('divContent') }} bg-white py-8">

        <div class="w-full">

            <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4">

                <div class="col-span-full -mb-2">
                    {!! setLabel('Quem receberá essa notificação?', 'Inscrições com status de') !!}
                </div>

                @foreach ($this->envio_target_status as $target_status => $target_status_value)
                    <div class="col-span-full md:col-span-3">
                        <x-toggle md label="{{ __($target_status) }}" wire:model="{{ $target_status }}" />
                    </div>
                @endforeach

                <div class="col-span-full md:col-span-full my-4">
                    <hr>
                </div>

                <div class="col-span-full md:col-span-4">
                    <x-input label="NOME DA NOTIFICAÇÃO" wire:model.defer="envio_nome" class="uppercase" placeholder="Informe um nome para essa notificação" />
                </div>

                <div class="col-span-full md:col-span-8">
                    <x-input label="DEFINA UM ASSUNTO" wire:model.defer='envio_assunto' class="uppercase" placeholder="Assunto enviado no email da notificação" />
                </div>

                {{-- <div class="col-span-full md:col-span-full my-4">
                    <hr>
                </div> --}}

                <div class="col-span-full mt-6" wire:ignore>

                    <textarea wire:model="envio_body" name="envio_body" id="envio_body">{{$envio_body}}</textarea>

                    <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script>
                    {{-- <script src="https://cdn.ckbox.io/CKBox/1.5.1/ckbox.js"></script> --}}
                    <script>

                        ClassicEditor
                            .create(document.querySelector('#envio_body'),{
                                // plugins: [],
                                toolbar: ['heading', '|','bold', 'italic', 'link', 'bulletedList', 'numberedList', 'insertTable', '|', 'undo', 'redo'],
                            })
                            .then(editor => {

                                editor.model.document.on('change:data', () => {
                                    @this.set('envio_body', editor.getData());
                                })

                                console.log('Editor carregado com sucesso!', editor);
                            })
                            .catch(error => {
                                console.error('ERRO:', error);
                            });
                    </script>
                    <style>#cke_1_bottom {display: none;}  .ck.ck-editor__editable{min-height: 450px;}</style>
                </div>

                <div class="col-span-full flex justify-between gap-4">

                    <div>
                        @if ($notificacao_id ?? false)
                            <x-button rounded negative flat label="APAGAR" href="{{ route('notifica') }}" />
                        @endif
                    </div>

                    <div class="flex gap-4">

                        <x-button rounded positive outline label="PRÉ VISUALIZAR" onclick="editorGetData()" wire:click="$set('notificacao_prever',true)" />

                        @if ($envio_qtd ?? false)
                            <x-button rounded positive spinner="criar" label="{{ ($notificacao_id ?? false) ? 'ALTERAR NOTIFICAÇÃO' : 'CRIAR NOTIFICAÇÃO' }}" onclick="editorGetData()" wire:click="criar" />
                        @endif
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- MODAL --}}
    <x-modal blur max-width="6xl" wire:model="notificacao_prever">

        <x-card>

            <div class="w-full flex justify-between uppercase border-b mb-4 pb-4">
                <div>
                    <div class="text-xs">ASSUNTO</div>
                    <div class="font-bold">{{ mb_strtoupper($envio_assunto ?? '---') }}</div>
                </div>
                <x-button flat negative icon="x" x-on:click="close" />
            </div>

            <div class="w-full mb-4">{!! $envio_body ?? '---' !!}</div>
        </x-card>

    </x-modal>
    {{-- MODAL - END --}}

</div>
