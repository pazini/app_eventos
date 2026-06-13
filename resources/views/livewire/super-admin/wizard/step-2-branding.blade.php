<!-- Step 2: Branding -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-palette text-4xl text-purple-500 mb-4"></i>
        <p class="text-gray-600">
            Personalize a identidade visual da aplicação com logos e cores.
        </p>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Upload de Logos -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                <i class="fas fa-image mr-2"></i>Logos
            </h3>

            <!-- Logo Principal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Logo Principal
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                    @if ($logo_preview ?? false)
                        <div class="relative inline-block">
                            <img src="{{ $logo_preview }}" alt="Preview" class="max-h-20 mx-auto">
                            <button type="button" wire:click="removePreview('logo')"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600 transition-colors">
                                ×
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Clique no × para remover</p>
                    @else
                        <input type="file" wire:model="logo_file" accept="image/*" class="hidden" id="logo_file">
                        <label for="logo_file" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2 block"></i>
                            <p class="text-sm text-gray-600">Clique para fazer upload</p>
                            <p class="text-xs text-gray-400">PNG, JPG até 2MB (500x500px máx)</p>
                        </label>
                    @endif
                </div>
                @error('logo_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Logo Dark Mode -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Logo Dark Mode (Opcional)
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                    @if ($logo_dark_preview ?? false)
                        <div class="relative inline-block">
                            <img src="{{ $logo_dark_preview }}" alt="Preview Dark" class="max-h-20 mx-auto">
                            <button type="button" wire:click="removePreview('logo_dark')"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600 transition-colors">
                                ×
                            </button>
                        </div>
                    @else
                        <input type="file" wire:model="logo_dark_file" accept="image/*" class="hidden" id="logo_dark_file">
                        <label for="logo_dark_file" class="cursor-pointer">
                            <i class="fas fa-moon text-2xl text-gray-400 mb-2 block"></i>
                            <p class="text-sm text-gray-600">Upload logo para modo escuro</p>
                            <p class="text-xs text-gray-400">PNG, JPG até 2MB (500x500px máx)</p>
                        </label>
                    @endif
                </div>
                @error('logo_dark_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Favicon -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Favicon (Opcional)
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                    @if ($favicon_preview ?? false)
                        <div class="relative inline-block">
                            <img src="{{ $favicon_preview }}" alt="Favicon Preview" class="w-8 h-8 mx-auto">
                            <button type="button" wire:click="removePreview('favicon')"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600 transition-colors">
                                ×
                            </button>
                        </div>
                    @else
                        <input type="file" wire:model="favicon_file" accept="image/*" class="hidden" id="favicon_file">
                        <label for="favicon_file" class="cursor-pointer">
                            <i class="fas fa-bookmark text-2xl text-gray-400 mb-2 block"></i>
                            <p class="text-sm text-gray-600">Upload favicon</p>
                            <p class="text-xs text-gray-400">PNG, ICO até 512KB (64x64px máx)</p>
                        </label>
                    @endif
                </div>
                @error('favicon_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Cores -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                <i class="fas fa-paint-brush mr-2"></i>Cores
            </h3>

            <!-- Cor Primária -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Cor Primária *
                </label>
                <div class="flex items-center space-x-3">
                    <input type="color" wire:model="color_primary"
                           class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                    <input type="text" wire:model="color_primary"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('color_primary') border-red-500 @enderror"
                           placeholder="#1a202c">
                </div>
                @error('color_primary')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Cor principal da interface (botões, links, destaques)</p>
            </div>

            <!-- Cor Secundária -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Cor Secundária *
                </label>
                <div class="flex items-center space-x-3">
                    <input type="color" wire:model="color_secondary"
                           class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                    <input type="text" wire:model="color_secondary"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('color_secondary') border-red-500 @enderror"
                           placeholder="#2d3748">
                </div>
                @error('color_secondary')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Cor de apoio (barras, backgrounds secundários)</p>
            </div>

            <!-- Cor de Destaque -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Cor de Destaque *
                </label>
                <div class="flex items-center space-x-3">
                    <input type="color" wire:model="color_accent"
                           class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                    <input type="text" wire:model="color_accent"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('color_accent') border-red-500 @enderror"
                           placeholder="#3182ce">
                </div>
                @error('color_accent')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Cor para acentos e elementos de destaque</p>
            </div>
        </div>
    </div>

    <!-- Preview Geral -->
    @if ($name ?? false)
        <div class="bg-gray-50 rounded-lg p-6 border">
            <h4 class="font-medium text-gray-800 mb-4">Preview da Identidade Visual:</h4>
            <div class="bg-white rounded-lg p-6 border" style="border-color: {{ $color_primary }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        @if ($logo_preview ?? false)
                            <img src="{{ $logo_preview }}" alt="Logo" class="h-8 w-auto">
                        @else
                            <div class="w-8 h-8 rounded flex items-center justify-center text-white text-sm font-bold"
                                 style="background-color: {{ $color_primary }}">
                                {{ strtoupper(substr($name, 0, 1)) }}
                            </div>
                        @endif
                        <h2 class="font-bold text-lg">{{ $name }}</h2>
                    </div>
                    @if ($favicon_preview ?? false)
                        <img src="{{ $favicon_preview }}" alt="Favicon" class="w-4 h-4">
                    @endif
                </div>
                <div class="space-y-2">
                    <button class="px-4 py-2 rounded text-white text-sm font-medium"
                            style="background-color: {{ $color_primary }}">
                        Botão Primário
                    </button>
                    <button class="px-4 py-2 rounded text-white text-sm font-medium ml-2"
                            style="background-color: {{ $color_accent }}">
                        Botão Destaque
                    </button>
                    <div class="w-full h-2 rounded mt-3" style="background-color: {{ $color_secondary }}"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Dicas -->
    <div class="bg-purple-50 border-l-4 border-purple-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-purple-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-purple-800">Dicas de Branding:</h4>
                <ul class="mt-2 text-sm text-purple-700 list-disc list-inside">
                    <li>Use logos com fundo transparente (PNG) para melhor integração</li>
                    <li>Escolha cores que representem sua marca e sejam agradáveis aos olhos</li>
                    <li>O favicon aparecerá na aba do navegador - mantenha-o simples</li>
                    <li>Teste as cores em diferentes contextos antes de finalizar</li>
                </ul>
            </div>
        </div>
    </div>
</div>
