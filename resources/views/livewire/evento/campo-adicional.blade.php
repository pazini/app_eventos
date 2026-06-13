<div class="w-full max-w-7xl mx-auto mb-10">

    {{-- HEADER MODERNO COM GRADIENTE --}}
    <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-campo-adicional" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-campo-adicional)"/>
            </svg>
        </div>
        <div class="relative z-10 p-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Campos Adicionais</h1>
                            <p class="text-white/90 text-sm">{{ $target->event_name }} - {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if (!($novo_campo ?? false) && !($alterar_campo ?? false))
                        <x-button white outline sm label="NOVO CAMPO" wire:click="$set('novo_campo',true)" class="hover:bg-white/20" />
                    @endif
                    <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-evento') }}" class="hover:bg-white/20" />
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <x-jet-validation-errors />
    </div>

    {{-- FORMULÁRIO NOVO/EDITAR CAMPO --}}
    @if (($novo_campo ?? false) || ($alterar_campo ?? false))

        <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    @if ($alterar_campo ?? false)
                        Alterar Campo
                    @else
                        Novo Campo
                    @endif
                </h2>
            </div>
            <div class="p-6">

                <div class="w-full">

                    <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-6">

                        <div class="col-span-full md:col-span-2">
                            <x-native-select label="{{ __('Tipo') }}" wire:model="input_type" class="uppercase">
                                <option value="">--</option>
                                <option value="text" class="uppercase">Texto</option>
                                <option value="select" class="uppercase">Seleção</option>
                                @if (Auth()->user()->email == 'admin@empresateste.com')
                                    <option value="select" class="uppercase">Seleção admin@empresateste.com</option>
                                @endif

                            </x-native-select>
                        </div>

                        @if ($input_type ?? false)

                            <div class="col-span-full md:col-span-4">
                                <x-input label="{{ __('Nome do Campo') }}" title="{{ __('input_label') }}" wire:model.defer="input_label" />
                            </div>

                            <div class="col-span-full md:col-span-6">
                                <x-input label="{{ __('Legenda') }}" title="{{ __('input_placeholder') }}" wire:model.defer="input_placeholder" />
                            </div>

                            <div class="col-span-full md:col-span-2">
                                <div class="mt-9">
                                    <x-toggle lg wire:model.defer="input_required" label="Obrigatório" />
                                </div>
                            </div>

                            @if (($input_type ?? false) && $input_type == 'select')
                                <div class="col-span-full md:col-span-3">
                                    <div class="mt-9">
                                        <x-toggle lg wire:model.defer="input_filter" label="Filtro relatório" />
                                    </div>
                                </div>
                            @endif


                        @endif

                    </div>

                </div>

            </div>

            {{-- SE TIPO SELEÇÃO --}}
            @if ($input_type == 'select')

                <div class="bg-white shadow-sm border-x border-b rounded-b-lg mt-6">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-800">Opções de Seleção</h3>
                    </div>
                    <div class="p-6">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-1">
                            <x-input wire:model.defer="input_opcao_value" label="Nova Opção" placeholder="Digite a opção..." />
                            <div class="w-full flex justify-between gap-2 mt-4">
                                @if ($input_opcao_key ?? false)
                                    <x-button
                                        wire:click="editarOpcao"
                                        label="Cancelar"
                                        negative
                                        flat
                                        class="flex-1"
                                    />
                                    <x-button
                                        wire:click="alterarOpcao('{{ $input_opcao_key }}')"
                                        label="ALTERAR"
                                        positive
                                        outline
                                        class="flex-1"
                                    />
                                @else
                                    <x-button
                                        wire:click="adicionarOpcao"
                                        label="Adicionar"
                                        positive
                                        outline
                                        class="w-full"
                                        icon="plus"
                                    />
                                @endif
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="text-sm font-semibold text-gray-700 mb-3">Opções Cadastradas</div>
                            <div class="flex flex-col gap-3 max-h-96 overflow-y-auto">
                                @forelse ($input_type_options as $option_key => $option_item)
                                    <div class="flex justify-between items-center rounded-lg shadow-sm border border-gray-200 bg-white p-3 hover:shadow-md transition-shadow">
                                        <div class="flex-1 px-2 text-gray-800">
                                            {{ $option_item ?? '---' }}
                                        </div>
                                        <div class="flex gap-2">
                                            <x-button square primary flat icon="pencil" wire:click="editarOpcao('{{ $option_key }}')" class="hover:bg-blue-50" />
                                            <x-button square negative flat icon="x" onclick="confirm('Confirma a remoção da opção de seleção?') || event.stopImmediatePropagation()" wire:click="removerOpcao('{{ $option_key }}')" class="hover:bg-red-50" />
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Você ainda não cadastrou nenhuma opção</p>
                                        <p class="text-xs text-gray-500 mt-1">Informe a opção ao lado e clique em adicionar</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

            @endif

            @if ($input_type ?? false)

                <div class="bg-white shadow-sm border-x border-b rounded-b-lg mt-6">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-800">Lotes - Omitir Campo</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                            @forelse ($ticketsTypes ?? [] as $ticketsTypeId => $ticketsTypeItem)
                                <div>
                                    <x-toggle lg wire:model.defer="{{ $ticketsTypeItem['var'] }}" wire:click="loteHiddenView('{{ $ticketsTypeId }}')" label="{{ mb_strtoupper($ticketsTypeItem['ticket_name']) }}" />
                                </div>
                            @empty
                                <div class="col-span-full text-center text-gray-500 py-4">Não possui lotes cadastrados</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm border-x border-b rounded-b-lg mt-6">
                    <div class="p-6">
                    <div class="w-full flex justify-between">
                        @if ($alterar_campo ?? false)
                            <div>
                                <x-button lg negative outline label="Remover" onclick="confirm('Confirma a remoção do campo?') || event.stopImmediatePropagation()" wire:click="remover('{{ $input_ref }}')" />
                            </div>
                            <div>
                                <x-button lg negative flat label="Cancelar" wire:click="$set('alterar_campo',false)" />
                                <x-button lg positive spinner label="ALTERAR" onclick="confirm('Confirma a alteração?') || event.stopImmediatePropagation()" wire:click="submit('{{ $input_ref }}')" />
                            </div>
                        @else
                            <div>
                            </div>
                            <div>
                                <x-button lg negative flat label="Cancelar" wire:click="$set('novo_campo',false)" />
                                <x-button lg positive spinner label="CADASTRAR" onclick="confirm('Confirma o cadastro?') || event.stopImmediatePropagation()" wire:click="submit" />
                            </div>
                        @endif
                    </div>
                </div>

            @endif

        </div>

    @else

        {{-- LISTA DE CAMPOS --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Campos Cadastrados</h2>
                @if (($questions_fields_order ?? false) && count($questions_fields_order) > 0)
                    <span class="text-sm text-gray-600">{{ count($questions_fields_order) }} campo(s)</span>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th width="40%" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Campo
                            </th>
                            <th width="20%" scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th width="10%" scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Obrigatório
                            </th>
                            <th width="10%" scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Filtro Relatório
                            </th>
                            <th width="10%" scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ordem
                            </th>
                            <th width="10%" scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if ($questions_fields_order ?? false)
                            @foreach ($questions_fields_order as $question_key => $question_values)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $question_values['input_label'] ?? '---' }}</div>
                                        <div class="text-sm text-gray-500">{{ $question_values['input_placeholder'] ?? '---' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($question_values['input_type'] ?? '--') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($question_values['input_required'] ?? false)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Obrigatório</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Opcional</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($question_values['input_filter'] ?? false)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Filtro relatório</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Não exibir</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center gap-1">
                                            @if ($question_key > 1)
                                                <x-button flat primary icon="arrow-up" wire:click="alterar_ordem('{{ $question_key }}','voltar')" class="hover:bg-blue-50" />
                                            @else
                                                <div class="w-8 h-8"></div>
                                            @endif
                                            @if ($question_key < $this->questions_fields_count)
                                                <x-button flat primary icon="arrow-down" wire:click="alterar_ordem('{{ $question_key }}','avancar')" class="hover:bg-blue-50" />
                                            @else
                                                <div class="w-8 h-8"></div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <x-button flat primary label="ALTERAR" wire:click="$set('alterar_campo','{{ $question_values['id'] }}')" />
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Não possui campos adicionais</h3>
                                    <p class="mt-1 text-sm text-gray-500">Comece criando um novo campo.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    @endif

</div>
