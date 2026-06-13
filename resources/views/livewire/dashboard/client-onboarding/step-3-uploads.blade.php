<!-- Step 3: Upload de Logo e Materiais -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-image text-4xl text-indigo-500 mb-4"></i>
        <p class="text-gray-600">
            Faça upload do logo e materiais visuais para personalizar a identidade do cliente.
        </p>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Upload de Logo -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                <i class="fas fa-image mr-2"></i>Logo da Empresa
            </h3>

            <!-- Área de Upload do Logo -->
            <div class="space-y-4">
                <div x-data="{ isDragOver: false }"
                     @dragover.prevent="isDragOver = true"
                     @dragleave.prevent="isDragOver = false"
                     @drop.prevent="isDragOver = false; $wire.upload('logo', $event.dataTransfer.files[0])"
                     :class="isDragOver ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'"
                     class="border-2 border-dashed rounded-lg p-6 text-center transition-colors">

                    @if ($logo)
                        <!-- Preview do Logo -->
                        <div class="flex flex-col items-center space-y-4">
                            <div class="w-32 h-32 bg-white rounded-lg shadow-md flex items-center justify-center overflow-hidden">
                                @if (is_string($logo))
                                    <img src="{{ Storage::url($logo) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                @else
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Logo Preview" class="max-w-full max-h-full object-contain">
                                @endif
                            </div>

                            <div class="flex space-x-3">
                                <button type="button" wire:click="$set('logo', null)"
                                        class="px-4 py-2 text-sm font-medium text-red-700 bg-red-100 border border-red-300 rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                    <i class="fas fa-trash mr-2"></i>Remover
                                </button>
                                <label for="logo-upload" class="px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 border border-indigo-300 rounded-md hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors cursor-pointer">
                                    <i class="fas fa-sync-alt mr-2"></i>Trocar
                                </label>
                            </div>
                        </div>
                    @else
                        <!-- Área de Upload -->
                        <div class="space-y-4">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <div>
                                <p class="text-lg font-medium text-gray-900">Arraste o logo aqui</p>
                                <p class="text-gray-500">ou clique para selecionar</p>
                            </div>

                            <div class="text-sm text-gray-500">
                                <p>PNG, JPG ou SVG até 2MB</p>
                                <p>Recomendado: 400x400px</p>
                            </div>

                            <label for="logo-upload" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer transition-colors">
                                <i class="fas fa-plus mr-2"></i>Selecionar Arquivo
                            </label>
                        </div>
                    @endif

                    <input type="file" id="logo-upload" wire:model="logo"
                           accept="image/png,image/jpeg,image/jpg,image/svg+xml"
                           class="sr-only">
                </div>

                @error('logo')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Upload de Favicon -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                <i class="fas fa-bookmark mr-2"></i>Favicon
            </h3>

            <!-- Área de Upload do Favicon -->
            <div class="space-y-4">
                <div x-data="{ isDragOver: false }"
                     @dragover.prevent="isDragOver = true"
                     @dragleave.prevent="isDragOver = false"
                     @drop.prevent="isDragOver = false; $wire.upload('favicon', $event.dataTransfer.files[0])"
                     :class="isDragOver ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'"
                     class="border-2 border-dashed rounded-lg p-6 text-center transition-colors">

                    @if ($favicon)
                        <!-- Preview do Favicon -->
                        <div class="flex flex-col items-center space-y-4">
                            <div class="w-16 h-16 bg-white rounded-lg shadow-md flex items-center justify-center overflow-hidden">
                                @if (is_string($favicon))
                                    <img src="{{ Storage::url($favicon) }}" alt="Favicon" class="max-w-full max-h-full object-contain">
                                @else
                                    <img src="{{ $favicon->temporaryUrl() }}" alt="Favicon Preview" class="max-w-full max-h-full object-contain">
                                @endif
                            </div>

                            <div class="flex space-x-3">
                                <button type="button" wire:click="$set('favicon', null)"
                                        class="px-3 py-2 text-sm font-medium text-red-700 bg-red-100 border border-red-300 rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                    <i class="fas fa-trash mr-1"></i>Remover
                                </button>
                                <label for="favicon-upload" class="px-3 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 border border-indigo-300 rounded-md hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors cursor-pointer">
                                    <i class="fas fa-sync-alt mr-1"></i>Trocar
                                </label>
                            </div>
                        </div>
                    @else
                        <!-- Área de Upload -->
                        <div class="space-y-3">
                            <i class="fas fa-bookmark text-3xl text-gray-400"></i>
                            <div>
                                <p class="font-medium text-gray-900">Favicon</p>
                                <p class="text-sm text-gray-500">Ícone da aba do navegador</p>
                            </div>

                            <div class="text-xs text-gray-500">
                                <p>ICO, PNG até 1MB</p>
                                <p>Recomendado: 32x32px</p>
                            </div>

                            <label for="favicon-upload" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer transition-colors">
                                <i class="fas fa-plus mr-1"></i>Selecionar
                            </label>
                        </div>
                    @endif

                    <input type="file" id="favicon-upload" wire:model="favicon"
                           accept="image/x-icon,image/png,image/ico"
                           class="sr-only">
                </div>

                @error('favicon')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Materiais Complementares -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-folder mr-2"></i>Materiais Complementares (Opcional)
        </h3>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Background/Banner -->
            <div class="space-y-4">
                <h4 class="font-medium text-gray-900">
                    <i class="fas fa-image mr-2 text-blue-500"></i>Banner de Fundo
                </h4>

                <div x-data="{ isDragOver: false }"
                     @dragover.prevent="isDragOver = true"
                     @dragleave.prevent="isDragOver = false"
                     @drop.prevent="isDragOver = false; $wire.upload('banner', $event.dataTransfer.files[0])"
                     :class="isDragOver ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
                     class="border-2 border-dashed rounded-lg p-4 text-center transition-colors">

                    @if ($banner)
                        <!-- Preview do Banner -->
                        <div class="space-y-3">
                            <div class="w-full h-24 bg-gray-100 rounded overflow-hidden">
                                @if (is_string($banner))
                                    <img src="{{ Storage::url($banner) }}" alt="Banner" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ $banner->temporaryUrl() }}" alt="Banner Preview" class="w-full h-full object-cover">
                                @endif
                            </div>

                            <div class="flex justify-center space-x-2">
                                <button type="button" wire:click="$set('banner', null)"
                                        class="text-xs px-3 py-1 text-red-700 bg-red-100 rounded hover:bg-red-200 transition-colors">
                                    Remover
                                </button>
                                <label for="banner-upload" class="text-xs px-3 py-1 text-blue-700 bg-blue-100 rounded hover:bg-blue-200 transition-colors cursor-pointer">
                                    Trocar
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="space-y-2">
                            <i class="fas fa-image text-2xl text-gray-400"></i>
                            <p class="text-sm text-gray-600">Banner 1920x400px</p>
                            <label for="banner-upload" class="inline-block text-xs px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors cursor-pointer">
                                Selecionar
                            </label>
                        </div>
                    @endif

                    <input type="file" id="banner-upload" wire:model="banner"
                           accept="image/png,image/jpeg,image/jpg"
                           class="sr-only">
                </div>

                @error('banner')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Documentos/Manual -->
            <div class="space-y-4">
                <h4 class="font-medium text-gray-900">
                    <i class="fas fa-file-pdf mr-2 text-red-500"></i>Manual/Documentação
                </h4>

                <div x-data="{ isDragOver: false }"
                     @dragover.prevent="isDragOver = true"
                     @dragleave.prevent="isDragOver = false"
                     @drop.prevent="isDragOver = false; $wire.upload('manual', $event.dataTransfer.files[0])"
                     :class="isDragOver ? 'border-red-500 bg-red-50' : 'border-gray-300'"
                     class="border-2 border-dashed rounded-lg p-4 text-center transition-colors">

                    @if ($manual)
                        <!-- Preview do Manual -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ is_string($manual) ? basename($manual) : $manual->getClientOriginalName() }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ is_string($manual) ? 'Arquivo carregado' : number_format($manual->getSize() / 1024, 1) . ' KB' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-center space-x-2">
                                <button type="button" wire:click="$set('manual', null)"
                                        class="text-xs px-3 py-1 text-red-700 bg-red-100 rounded hover:bg-red-200 transition-colors">
                                    Remover
                                </button>
                                <label for="manual-upload" class="text-xs px-3 py-1 text-red-700 bg-red-100 rounded hover:bg-red-200 transition-colors cursor-pointer">
                                    Trocar
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="space-y-2">
                            <i class="fas fa-file-pdf text-2xl text-gray-400"></i>
                            <p class="text-sm text-gray-600">PDF até 10MB</p>
                            <label for="manual-upload" class="inline-block text-xs px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors cursor-pointer">
                                Selecionar
                            </label>
                        </div>
                    @endif

                    <input type="file" id="manual-upload" wire:model="manual"
                           accept="application/pdf"
                           class="sr-only">
                </div>

                @error('manual')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Preview da Identidade Visual -->
    @if ($logo || $favicon || $banner)
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-indigo-800 mb-4">
                <i class="fas fa-eye mr-2"></i>Preview da Identidade Visual
            </h3>

            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        @if ($logo)
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                @if (is_string($logo))
                                    <img src="{{ Storage::url($logo) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                @else
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                @endif
                            </div>
                        @endif

                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $company_name ?: 'Nome da Empresa' }}</h4>
                            <p class="text-sm text-gray-600">{{ $company_type === 'empresa' ? 'Empresa' : 'Organização' }}</p>
                        </div>
                    </div>

                    @if ($favicon)
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <span>Favicon:</span>
                            <div class="w-4 h-4 bg-gray-100 rounded overflow-hidden">
                                @if (is_string($favicon))
                                    <img src="{{ Storage::url($favicon) }}" alt="Favicon" class="w-full h-full object-contain">
                                @else
                                    <img src="{{ $favicon->temporaryUrl() }}" alt="Favicon" class="w-full h-full object-contain">
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                @if ($banner)
                    <div class="mt-4 w-full h-20 bg-gray-100 rounded overflow-hidden">
                        @if (is_string($banner))
                            <img src="{{ Storage::url($banner) }}" alt="Banner" class="w-full h-full object-cover">
                        @else
                            <img src="{{ $banner->temporaryUrl() }}" alt="Banner" class="w-full h-full object-cover">
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Informações sobre uploads -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-yellow-800">Dicas para melhores resultados:</h4>
                <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside space-y-1">
                    <li><strong>Logo:</strong> Use fundo transparente (PNG) para melhor adaptação</li>
                    <li><strong>Favicon:</strong> Prefira formatos ICO ou PNG pequenos (32x32px)</li>
                    <li><strong>Banner:</strong> Resolução mínima 1920x400px para telas grandes</li>
                    <li><strong>Tamanhos:</strong> Arquivos menores carregam mais rápido</li>
                </ul>
            </div>
        </div>
    </div>
</div>
