<div class="pt-2 pb-6">
    <div class="max-w-7xl mx-auto space-y-6">

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Listas de Referência</h2>
                        <p class="mt-1 text-sm text-gray-600">Administre dados comuns a todos os aplicativos.</p>
                    </div>
                    <a
                        href="{{ route('super-administrador.dashboard') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        Voltar
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="border-b border-gray-200">
                <nav class="flex">
                    <button
                        type="button"
                        wire:click="setTab('states')"
                        class="flex-1 px-6 py-3 text-sm font-semibold {{ $activeTab === 'states' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                    >
                        Estados (UF)
                    </button>
                    <button
                        type="button"
                        wire:click="setTab('types')"
                        class="flex-1 px-6 py-3 text-sm font-semibold {{ $activeTab === 'types' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                    >
                        Tipos de Evento
                    </button>
                    <button
                        type="button"
                        wire:click="setTab('categories')"
                        class="flex-1 px-6 py-3 text-sm font-semibold {{ $activeTab === 'categories' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                    >
                        Categorias de Evento
                    </button>
                </nav>
            </div>

            <div class="p-6 space-y-6">
                <x-jet-validation-errors />

                @if($activeTab === 'states')
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">Novo Estado</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Slug</label>
                                <input type="text" wire:model.defer="stateForm.ref_slug" class="w-full border-gray-300 rounded-md text-sm uppercase" placeholder="ex: rj">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Valor</label>
                                <input type="text" wire:model.defer="stateForm.ref_value" class="w-full border-gray-300 rounded-md text-sm" placeholder="RJ - Rio de Janeiro">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Label</label>
                                <input type="text" wire:model.defer="stateForm.ref_label" class="w-full border-gray-300 rounded-md text-sm" placeholder="RJ - Rio de Janeiro">
                            </div>
                            <div class="flex items-center gap-3 pt-6">
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" wire:model.defer="stateForm.to_view" class="rounded border-gray-300">
                                    Visível
                                </label>
                                <button
                                    type="button"
                                    wire:click="createState"
                                    class="uppercase ml-auto inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-700"
                                >
                                    Adicionar Novo Item
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Slug</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Valor</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Label</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Visível</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($states as $state)
                                    @php
                                        $stateId = data_get($state, 'id');
                                        $stateSlug = data_get($state, 'ref_slug');
                                        $stateValue = data_get($state, 'ref_value');
                                        $stateLabel = data_get($state, 'ref_label');
                                        $stateVisible = (bool) data_get($state, 'to_view');
                                    @endphp
                                    <tr wire:key="state-{{ $stateId }}">
                                        @if($editingStateId == $stateId)
                                            <td class="px-4 py-3">
                                                <input type="text" wire:model.defer="stateEdit.ref_slug" class="w-full border-gray-300 rounded-md text-sm uppercase">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" wire:model.defer="stateEdit.ref_value" class="w-full border-gray-300 rounded-md text-sm">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" wire:model.defer="stateEdit.ref_label" class="w-full border-gray-300 rounded-md text-sm">
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input type="checkbox" wire:model.defer="stateEdit.to_view" class="rounded border-gray-300">
                                            </td>
                                            <td class="px-4 py-3 text-right space-x-2">
                                                <button type="button" wire:click="updateState" class="px-3 py-1 text-xs font-semibold bg-green-600 text-white rounded-md">Salvar</button>
                                                <button type="button" wire:click="cancelEditState" class="px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded-md">Cancelar</button>
                                            </td>
                                        @else
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900 uppercase">{{ $stateSlug }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $stateValue }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $stateLabel }}</td>
                                            <td class="px-4 py-3 text-sm text-center">
                                                <span class="px-2 py-1 rounded-full text-xs {{ $stateVisible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                                    {{ $stateVisible ? 'Sim' : 'Não' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right space-x-2">
                                                <button type="button" wire:click="editState({{ $stateId }})" class="px-3 py-1 text-xs font-semibold bg-blue-600 text-white rounded-md">Editar</button>
                                                <button type="button" wire:click="deleteState({{ $stateId }})" onclick="confirm('Remover este estado?') || event.stopImmediatePropagation()" class="px-3 py-1 text-xs font-semibold bg-red-600 text-white rounded-md">Excluir</button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Nenhum estado cadastrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif

                @if($activeTab === 'types')
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">Novo Tipo</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Slug</label>
                                <input type="text" wire:model.defer="typeForm.ref_slug" class="w-full border-gray-300 rounded-md text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Valor</label>
                                <input type="text" wire:model.defer="typeForm.ref_value" class="w-full border-gray-300 rounded-md text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Label</label>
                                <input type="text" wire:model.defer="typeForm.ref_label" class="w-full border-gray-300 rounded-md text-sm">
                            </div>
                            <div class="flex items-center gap-3 pt-6">
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" wire:model.defer="typeForm.to_view" class="rounded border-gray-300">
                                    Visível
                                </label>
                                <button
                                    type="button"
                                    wire:click="createType"
                                    class="uppercase ml-auto inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-700"
                                >
                                    Adicionar Novo Item
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Slug</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Valor</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Label</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Visível</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($types as $type)
                                    @php
                                        $typeId = data_get($type, 'id');
                                        $typeSlug = data_get($type, 'ref_slug');
                                        $typeValue = data_get($type, 'ref_value');
                                        $typeLabel = data_get($type, 'ref_label');
                                        $typeVisible = (bool) data_get($type, 'to_view');
                                    @endphp
                                    <tr wire:key="type-{{ $typeId }}">
                                        @if($editingTypeId == $typeId)
                                            <td class="px-4 py-3">
                                                <input type="text" wire:model.defer="typeEdit.ref_slug" class="w-full border-gray-300 rounded-md text-sm">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" wire:model.defer="typeEdit.ref_value" class="w-full border-gray-300 rounded-md text-sm">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" wire:model.defer="typeEdit.ref_label" class="w-full border-gray-300 rounded-md text-sm">
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input type="checkbox" wire:model.defer="typeEdit.to_view" class="rounded border-gray-300">
                                            </td>
                                            <td class="px-4 py-3 text-right space-x-2">
                                                <button type="button" wire:click="updateType" class="px-3 py-1 text-xs font-semibold bg-green-600 text-white rounded-md">Salvar</button>
                                                <button type="button" wire:click="cancelEditType" class="px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded-md">Cancelar</button>
                                            </td>
                                        @else
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $typeSlug }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $typeValue }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $typeLabel }}</td>
                                            <td class="px-4 py-3 text-sm text-center">
                                                <span class="px-2 py-1 rounded-full text-xs {{ $typeVisible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                                    {{ $typeVisible ? 'Sim' : 'Não' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right space-x-2">
                                                <button type="button" wire:click="editType({{ $typeId }})" class="px-3 py-1 text-xs font-semibold bg-blue-600 text-white rounded-md">Editar</button>
                                                <button type="button" wire:click="deleteType({{ $typeId }})" onclick="confirm('Remover este tipo?') || event.stopImmediatePropagation()" class="px-3 py-1 text-xs font-semibold bg-red-600 text-white rounded-md">Excluir</button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Nenhum tipo cadastrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif

                @if($activeTab === 'categories')
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">Nova Categoria</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Slug</label>
                                <input type="text" wire:model.defer="categoryForm.ref_slug" class="w-full border-gray-300 rounded-md text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Valor</label>
                                <input type="text" wire:model.defer="categoryForm.ref_value" class="w-full border-gray-300 rounded-md text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Label</label>
                                <input type="text" wire:model.defer="categoryForm.ref_label" class="w-full border-gray-300 rounded-md text-sm">
                            </div>
                            <div class="flex items-center gap-3 pt-6">
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" wire:model.defer="categoryForm.to_view" class="rounded border-gray-300">
                                    Visível
                                </label>
                                <button
                                    type="button"
                                    wire:click="createCategory"
                                    class="uppercase ml-auto inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-700"
                                >
                                    Adicionar Novo Item
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Slug</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Valor</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Label</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Visível</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($categories as $category)
                                    @php
                                        $categoryId = data_get($category, 'id');
                                        $categorySlug = data_get($category, 'ref_slug');
                                        $categoryValue = data_get($category, 'ref_value');
                                        $categoryLabel = data_get($category, 'ref_label');
                                        $categoryVisible = (bool) data_get($category, 'to_view');
                                    @endphp
                                    <tr wire:key="category-{{ $categoryId }}">
                                        @if($editingCategoryId == $categoryId)
                                            <td class="px-4 py-3">
                                                <input type="text" wire:model.defer="categoryEdit.ref_slug" class="w-full border-gray-300 rounded-md text-sm">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" wire:model.defer="categoryEdit.ref_value" class="w-full border-gray-300 rounded-md text-sm">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" wire:model.defer="categoryEdit.ref_label" class="w-full border-gray-300 rounded-md text-sm">
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input type="checkbox" wire:model.defer="categoryEdit.to_view" class="rounded border-gray-300">
                                            </td>
                                            <td class="px-4 py-3 text-right space-x-2">
                                                <button type="button" wire:click="updateCategory" class="px-3 py-1 text-xs font-semibold bg-green-600 text-white rounded-md">Salvar</button>
                                                <button type="button" wire:click="cancelEditCategory" class="px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded-md">Cancelar</button>
                                            </td>
                                        @else
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $categorySlug }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $categoryValue }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $categoryLabel }}</td>
                                            <td class="px-4 py-3 text-sm text-center">
                                                <span class="px-2 py-1 rounded-full text-xs {{ $categoryVisible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                                    {{ $categoryVisible ? 'Sim' : 'Não' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right space-x-2">
                                                <button type="button" wire:click="editCategory({{ $categoryId }})" class="px-3 py-1 text-xs font-semibold bg-blue-600 text-white rounded-md">Editar</button>
                                                <button type="button" wire:click="deleteCategory({{ $categoryId }})" onclick="confirm('Remover esta categoria?') || event.stopImmediatePropagation()" class="px-3 py-1 text-xs font-semibold bg-red-600 text-white rounded-md">Excluir</button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Nenhuma categoria cadastrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
