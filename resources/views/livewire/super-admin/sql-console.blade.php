<div class="pt-2 pb-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">SQL Console</h2>
                        <p class="mt-1 text-sm text-gray-600">Execute consultas diretamente no banco. Use com cuidado.</p>
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

        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 space-y-4">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                    <div class="flex items-center gap-1">
                        <label class="block text-xs font-semibold text-gray-600 uppercase">Query SQL</label>
                        @if($sqlExecutedAt)
                            <div class="text-xs text-gray-500">- Executado em {{ $sqlExecutedAt }}</div>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            wire:click="runSql"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-700"
                        >
                            Executar
                        </button>
                        <button
                            type="button"
                            wire:click="exportSql"
                            @disabled(!$sqlExportReady)
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Exportar CSV
                        </button>
                        <button
                            type="button"
                            wire:click="clearSql"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-xs font-semibold rounded-md hover:bg-gray-300"
                        >
                            Limpar
                        </button>
                    </div>
                </div>
                <textarea
                    wire:model.defer="sqlQuery"
                    rows="4"
                    class="w-full border-gray-300 rounded-md text-sm font-mono"
                    placeholder="Digite sua consulta SQL aqui..."
                ></textarea>
            </div>

            @if($sqlError)
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-md p-3">
                    {{ $sqlError }}
                </div>
            @endif

            @if(!is_null($sqlRowsAffected))
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-md p-3">
                    Linhas afetadas: {{ $sqlRowsAffected }}
                </div>
            @endif

            @if($sqlExecutedAt && empty($sqlResult) && is_null($sqlRowsAffected) && !$sqlError)
                <div class="bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded-md p-3">
                    Nenhum retorno encontrado para a consulta executada.
                </div>
            @endif

            @if(!empty($sqlResult))
                @if($sqlPaginated)
                    @php
                        $totalPages = $sqlTotalRows ? (int) ceil($sqlTotalRows / $sqlPerPage) : 1;
                        $fromRow = ($sqlPage - 1) * $sqlPerPage + 1;
                        $toRow = $sqlTotalRows ? min($sqlPage * $sqlPerPage, $sqlTotalRows) : ($fromRow + count($sqlResult) - 1);
                    @endphp
                    <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-gray-500">
                        <span>
                            Mostrando {{ $fromRow }}–{{ $toRow }} de {{ $sqlTotalRows }}
                        </span>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                wire:click="previousPage"
                                @disabled($sqlPage <= 1)
                                class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Anterior
                            </button>
                            <span>Página {{ $sqlPage }} / {{ $totalPages }}</span>
                            <button
                                type="button"
                                wire:click="nextPage"
                                @disabled($sqlTotalRows && $sqlPage >= $totalPages)
                                class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Próxima
                            </button>
                        </div>
                    </div>
                @endif
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach($sqlColumns as $column)
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">{{ $column }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sqlResult as $row)
                                <tr>
                                    @foreach($sqlColumns as $column)
                                        <td class="px-4 py-2 text-xs text-gray-700">
                                            {{ data_get($row, $column) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
