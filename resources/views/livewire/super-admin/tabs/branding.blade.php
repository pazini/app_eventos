<div class="p-6">
    <form wire:submit.prevent="saveBranding">
        {{-- Upload de Logo Principal --}}
        <div class="space-y-8">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Logo Principal</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Upload área --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Novo Logo</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="new_logo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Clique para enviar</span></p>
                                    <p class="text-xs text-gray-500">PNG, JPG ou SVG (MAX. 2MB)</p>
                                </div>
                                <input wire:model="new_logo" id="new_logo" type="file" class="hidden" accept="image/*">
                            </label>
                        </div>

                        {{-- Preview do novo upload --}}
                        @if($logo_preview)
                            <div class="mt-4 relative">
                                <img src="{{ $logo_preview }}" class="h-20 w-auto rounded border border-gray-200" alt="Preview">
                                <button type="button" wire:click="removePreview('logo')"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Logo atual --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo Atual</label>
                        @if($app->url_image_logo)
                            <div class="p-4 bg-white border border-gray-200 rounded-lg" wire:key="logo-{{ $branding_version }}">
                                <img src="{{ appLogo(true, $app) }}"
                                     class="h-auto w-auto mx-auto"
                                     alt="{{ $app->app_name }}"
                                     onerror="this.src='{{ asset('images/app/default-logo.png') }}'">
                                <p class="text-xs text-gray-500 text-center mt-2">{{ $app->url_image_logo }}</p>
                            </div>
                        @else
                            <div class="p-8 bg-white border border-gray-200 rounded-lg text-center">
                                <svg class="h-12 w-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500">Nenhum logo carregado</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Upload de Logo Dark Mode --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Logo Dark Mode (Opcional)</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Upload área --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Novo Logo Dark</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="new_logo_dark" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-800 hover:bg-gray-700">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-300"><span class="font-semibold">Logo para fundo escuro</span></p>
                                    <p class="text-xs text-gray-400">PNG, JPG ou SVG (MAX. 2MB)</p>
                                </div>
                                <input wire:model="new_logo_dark" id="new_logo_dark" type="file" class="hidden" accept="image/*">
                            </label>
                        </div>

                        @if($logo_dark_preview)
                            <div class="mt-4 relative bg-gray-800 p-4 rounded">
                                <img src="{{ $logo_dark_preview }}" class="h-20 w-auto rounded" alt="Preview Dark">
                                <button type="button" wire:click="removePreview('logo_dark')"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Logo dark atual --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo Dark Atual</label>
                        @if($app->url_image_logo_dark)
                            <div class="p-4 bg-gray-800 border border-gray-200 rounded-lg" wire:key="logo-dark-{{ $branding_version }}">
                                <img src="{{ appLogoDark(true, $app) }}"
                                     class="h-20 w-auto mx-auto"
                                     alt="{{ $app->app_name }} Dark">
                                <p class="text-xs text-gray-300 text-center mt-2">{{ $app->url_image_logo_dark }}</p>
                            </div>
                        @else
                            <div class="p-8 bg-gray-100 border border-gray-200 rounded-lg text-center">
                                <svg class="h-12 w-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <p class="text-sm text-gray-500">Logo dark mode não configurado</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Upload de Favicon --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Favicon</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Novo Favicon</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="new_favicon" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-3 pb-3">
                                    <svg class="w-6 h-6 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-xs text-gray-500">ICO, PNG (16x16 - 256x256)</p>
                                </div>
                                <input wire:model="new_favicon" id="new_favicon" type="file" class="hidden" accept="image/*,.ico">
                            </label>
                        </div>

                        @if($favicon_preview)
                            <div class="mt-4 relative inline-block">
                                <img src="{{ $favicon_preview }}" class="h-8 w-8 rounded border border-gray-200" alt="Favicon Preview">
                                <button type="button" wire:click="removePreview('favicon')"
                                        class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-4 h-4 flex items-center justify-center hover:bg-red-600">×</button>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Favicon Atual</label>
                        @if($app->url_image_favicon)
                            <div class="p-4 bg-white border border-gray-200 rounded-lg" wire:key="favicon-{{ $branding_version }}">
                                <img src="{{ appFavicon(true, $app) }}"
                                     class="h-8 w-8 mx-auto"
                                     alt="Favicon">
                                <p class="text-xs text-gray-500 text-center mt-2">{{ $app->url_image_favicon }}</p>
                            </div>
                        @else
                            <div class="p-4 bg-white border border-gray-200 rounded-lg text-center">
                                <div class="h-8 w-8 bg-gray-200 rounded mx-auto mb-1"></div>
                                <p class="text-xs text-gray-500">Favicon padrão</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Upload de Thumbnail Padrão --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thumbnail Padrão</h3>
                <p class="text-sm text-gray-600 mb-4">Imagem que será usada como thumbnail quando campanhas e eventos não tiverem uma imagem específica.</p>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Upload área --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nova Thumbnail</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="new_default_thumb" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Clique para enviar</span></p>
                                    <p class="text-xs text-gray-500">PNG, JPG (MIN. 100x100px, MAX. 2MB)</p>
                                    <p class="text-xs text-gray-400 mt-1">Recomendado: 400x300px ou maior</p>
                                </div>
                                <input wire:model="new_default_thumb" id="new_default_thumb" type="file" class="hidden" accept="image/*">
                            </label>
                        </div>

                        {{-- Preview da nova thumbnail --}}
                        @if($default_thumb_preview)
                            <div class="mt-4 relative">
                                <img src="{{ $default_thumb_preview }}" class="h-32 w-auto rounded border border-gray-200" alt="Preview Thumbnail">
                                <button type="button" wire:click="removePreview('default_thumb')"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Thumbnail atual --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail Atual</label>
                        @if($app->url_image_default_thumb)
                            <div class="p-4 bg-white border border-gray-200 rounded-lg" wire:key="thumb-{{ $branding_version }}">
                                <img src="{{ appDefaultThumb(true, $app) }}"
                                     class="h-32 w-auto mx-auto rounded"
                                     alt="Thumbnail Padrão">
                                <p class="text-xs text-gray-500 text-center mt-2">{{ $app->url_image_default_thumb }}</p>
                            </div>
                        @else
                            <div class="p-8 bg-white border border-gray-200 rounded-lg text-center">
                                <svg class="h-12 w-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500">Nenhuma thumbnail padrão definida</p>
                                <p class="text-xs text-gray-400 mt-1">Sistema usará imagem padrão global</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Paleta de Cores --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cores da Aplicação</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    {{-- Cor primária --}}
                    <div>
                        <label for="color_primary" class="block text-sm font-medium text-gray-700 mb-2">Cor Primária</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" wire:model.defer="color_primary" id="color_primary"
                                   class="h-10 w-16 border border-gray-300 rounded-md cursor-pointer">
                            <input type="text" wire:model.defer="color_primary"
                                   class="flex-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   placeholder="#1a202c">
                        </div>
                    </div>

                    {{-- Cor secundária --}}
                    <div>
                        <label for="color_secondary" class="block text-sm font-medium text-gray-700 mb-2">Cor Secundária</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" wire:model.defer="color_secondary" id="color_secondary"
                                   class="h-10 w-16 border border-gray-300 rounded-md cursor-pointer">
                            <input type="text" wire:model.defer="color_secondary"
                                   class="flex-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   placeholder="#2d3748">
                        </div>
                    </div>

                    {{-- Cor de destaque --}}
                    <div>
                        <label for="color_accent" class="block text-sm font-medium text-gray-700 mb-2">Cor de Destaque</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" wire:model.defer="color_accent" id="color_accent"
                                   class="h-10 w-16 border border-gray-300 rounded-md cursor-pointer">
                            <input type="text" wire:model.defer="color_accent"
                                   class="flex-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   placeholder="#ed8936">
                        </div>
                    </div>
                </div>

                {{-- Preview das cores --}}
                <div class="mt-6">
                    <p class="text-sm font-medium text-gray-700 mb-3">Preview:</p>
                    <div class="flex space-x-4">
                        <div class="flex flex-col items-center">
                            <div class="h-12 w-12 rounded" style="background-color: {{ $color_primary }}"></div>
                            <span class="text-xs text-gray-500 mt-1">Primária</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="h-12 w-12 rounded" style="background-color: {{ $color_secondary }}"></div>
                            <span class="text-xs text-gray-500 mt-1">Secundária</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="h-12 w-12 rounded" style="background-color: {{ $color_accent }}"></div>
                            <span class="text-xs text-gray-500 mt-1">Destaque</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Erros --}}
        @error('branding')
            <div class="mt-4 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ $message }}</p>
                    </div>
                </div>
            </div>
        @enderror

        {{-- Botões de ação --}}
        <div class="mt-8 flex justify-end space-x-3">
            <button type="button" wire:click="loadAppData"
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancelar
            </button>
            <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg wire:loading wire:target="saveBranding" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="saveBranding">Salvar Branding</span>
                <span wire:loading wire:target="saveBranding">Salvando...</span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Força recarga das imagens após salvamento
    document.addEventListener('livewire:load', function () {
        Livewire.on('notify', (message, type) => {
            if (type === 'success' && message.includes('Branding atualizado')) {
                // Aguarda um pouco para garantir que o DOM foi atualizado
                setTimeout(() => {
                    // Força recarga de todas as imagens de branding
                    document.querySelectorAll('img[src*="branding"]').forEach(img => {
                        const src = img.src;
                        img.src = src + (src.includes('?') ? '&' : '?') + '_t=' + Date.now();
                    });
                }, 100);
            }
        });
    });
</script>
@endpush
