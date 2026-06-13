<div class="w-full max-w-7xl mx-auto mb-10">

    {{-- HEADER MODERNO COM GRADIENTE --}}
    <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-layout" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-layout)"/>
            </svg>
        </div>
        <div class="relative z-10 p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Definições de Layout</h1>
                            <p class="text-white/90 text-sm">{{ $target->event_name }} - {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <x-button white outline sm icon="desktop-computer" label="Página Evento" href="{{ eventoUrl($target->event_slug) }}" target="_blank" class="hover:bg-white/20" />
                    <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-evento') }}" class="hover:bg-white/20" />
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <x-jet-validation-errors />
    </div>

    {{-- CORES --}}
    <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Cores</h2>
        </div>
        <div class="p-6">
            <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <x-input type="color" label="{{ __('color_default') }}" wire:model.blur="color_default" class="h-12 pt-0 pb-0 pl-1 pr-1 cursor-pointer shadow rounded" />
                    <div class="w-full text-center text-sm text-gray-600 mt-2">{{$this->target->color_default ?? '---'}}</div>
                </div>

                <div>
                    <x-input type="color" label="{{ __('color_default_inverse') }}" wire:model.blur="color_default_inverse" class="h-12 pt-0 pb-0 pl-1 pr-1 cursor-pointer shadow rounded" />
                    <div class="w-full text-center text-sm text-gray-600 mt-2">{{$this->target->color_default_inverse ?? '---'}}</div>
                </div>

                <div>
                    <x-input type="color" label="{{ __('color_primary') }}" wire:model.blur="color_primary" class="h-12 pt-0 pb-0 pl-1 pr-1 cursor-pointer shadow rounded" />
                    <div class="w-full text-center text-sm text-gray-600 mt-2">{{$this->target->color_primary ?? '---'}}</div>
                </div>

                <div>
                    <x-input type="color" label="{{ __('color_secondary') }}" wire:model.blur="color_secondary" class="h-12 pt-0 pb-0 pl-1 pr-1 cursor-pointer shadow rounded" />
                    <div class="w-full text-center text-sm text-gray-600 mt-2">{{$this->target->color_secondary ?? '---'}}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- IMAGENS --}}
    <div class="bg-white shadow-sm border-x border-b rounded-b-lg mt-6">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Imagens</h2>
        </div>
        <div class="p-6">
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Thumbnail --}}
                <div>
                    <div class="w-full h-64 border-2 border-dashed border-gray-300 rounded-lg shadow-sm bg-gray-50 flex flex-col justify-center items-center relative overflow-hidden" style="background: url({{ str_starts_with($this->url_image_thumbnail ?? '', '/storage/') ? asset($this->url_image_thumbnail) : tenantAsset($this->url_image_thumbnail, true) }});background-position: center;background-size: cover;background-repeat: no-repeat;">
                        @if (!$this->url_image_thumbnail ?? false)
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <x-input wire:model="url_image_thumbnail" type="file" class="text-sm" />
                            </div>
                        @else
                            <div class="absolute bottom-2 right-2">
                                <x-button xs negative spinner label="Remover" wire:click="$set('url_image_thumbnail',false)" />
                            </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <label class="text-sm font-semibold text-gray-700">{{ __('image_thumbnail') }}</label>
                        <p class="text-xs text-gray-500 mt-1">{{ __('url_image_thumbnail_title') }}</p>
                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V6a2 2 0 012-2h2M4 16v2a2 2 0 002 2h2m8-16h2a2 2 0 012 2v2m0 8v2a2 2 0 01-2 2h-2"/></svg>
                            400 × 300 px
                        </span>
                        @if(isAdmin() && $this->url_image_thumbnail)
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-gray-400 truncate" title="{{ str_starts_with($this->url_image_thumbnail ?? '', '/storage/') ? asset($this->url_image_thumbnail) : tenantAsset($this->url_image_thumbnail, true) }}">{{ str_starts_with($this->url_image_thumbnail ?? '', '/storage/') ? asset($this->url_image_thumbnail) : tenantAsset($this->url_image_thumbnail, true) }}</span>
                            <button type="button" title="Copiar URL" onclick="navigator.clipboard.writeText('{{ str_starts_with($this->url_image_thumbnail ?? '', '/storage/') ? asset($this->url_image_thumbnail) : tenantAsset($this->url_image_thumbnail, true) }}').then(() => this.innerHTML = '&#10003;').then(() => setTimeout(() => this.innerHTML = '&#128203;', 1500))" class="flex-shrink-0 text-gray-400 hover:text-blue-600">&#128203;</button>
                        </div>
                        @endif
                    </div>
                    <div wire:loading wire:target="url_image_thumbnail" class="mt-2 text-sm text-blue-600">Carregando Arquivo...</div>
                </div>

                {{-- Imagem Destaque --}}
                <div>
                    <div class="w-full h-64 border-2 border-dashed border-gray-300 rounded-lg shadow-sm bg-gray-50 flex flex-col justify-center items-center relative overflow-hidden" style="background: url({{ str_starts_with($this->url_image ?? '', '/storage/') ? asset($this->url_image) : tenantAsset($this->url_image, true) }});background-position: center;background-size: cover;background-repeat: no-repeat;">
                        @if (!$this->url_image ?? false)
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <x-input wire:model="url_image" type="file" class="text-sm" />
                            </div>
                        @else
                            <div class="absolute bottom-2 right-2">
                                <x-button xs negative spinner label="Remover" wire:click="$set('url_image',false)" />
                            </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <label class="text-sm font-semibold text-gray-700">{{ __('imagem destaque') }}</label>
                        <p class="text-xs text-gray-500 mt-1">{{ __('url_image_title') }}</p>
                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V6a2 2 0 012-2h2M4 16v2a2 2 0 002 2h2m8-16h2a2 2 0 012 2v2m0 8v2a2 2 0 01-2 2h-2"/></svg>
                            1200 × 628 px
                        </span>
                        @if(isAdmin() && $this->url_image)
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-gray-400 truncate" title="{{ str_starts_with($this->url_image ?? '', '/storage/') ? asset($this->url_image) : tenantAsset($this->url_image, true) }}">{{ str_starts_with($this->url_image ?? '', '/storage/') ? asset($this->url_image) : tenantAsset($this->url_image, true) }}</span>
                            <button type="button" title="Copiar URL" onclick="navigator.clipboard.writeText('{{ str_starts_with($this->url_image ?? '', '/storage/') ? asset($this->url_image) : tenantAsset($this->url_image, true) }}').then(() => this.innerHTML = '&#10003;').then(() => setTimeout(() => this.innerHTML = '&#128203;', 1500))" class="flex-shrink-0 text-gray-400 hover:text-blue-600">&#128203;</button>
                        </div>
                        @endif
                    </div>
                    <div wire:loading wire:target="url_image" class="mt-2 text-sm text-blue-600">Carregando Arquivo...</div>
                </div>

                {{-- Logo --}}
                <div>
                    <div class="w-full h-64 border-2 border-dashed border-gray-300 rounded-lg shadow-sm bg-gray-50 flex flex-col justify-center items-center relative overflow-hidden" style="background: url({{ str_starts_with($this->url_image_logo ?? '', '/storage/') ? asset($this->url_image_logo) : tenantAsset($this->url_image_logo, true) }});background-position: center;background-size: contain;background-repeat: no-repeat;">
                        @if (!$this->url_image_logo ?? false)
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <x-input wire:model="url_image_logo" type="file" class="text-sm" />
                            </div>
                        @else
                            <div class="absolute bottom-2 right-2">
                                <x-button xs negative spinner label="Remover" wire:click="$set('url_image_logo',false)" />
                            </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <label class="text-sm font-semibold text-gray-700">{{ __('image_logo') }}</label>
                        <p class="text-xs text-gray-500 mt-1">{{ __('url_image_logo_title') }}</p>
                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V6a2 2 0 012-2h2M4 16v2a2 2 0 002 2h2m8-16h2a2 2 0 012 2v2m0 8v2a2 2 0 01-2 2h-2"/></svg>
                            300 × 100 px
                        </span>
                        @if(isAdmin() && $this->url_image_logo)
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-gray-400 truncate" title="{{ str_starts_with($this->url_image_logo ?? '', '/storage/') ? asset($this->url_image_logo) : tenantAsset($this->url_image_logo, true) }}">{{ str_starts_with($this->url_image_logo ?? '', '/storage/') ? asset($this->url_image_logo) : tenantAsset($this->url_image_logo, true) }}</span>
                            <button type="button" title="Copiar URL" onclick="navigator.clipboard.writeText('{{ str_starts_with($this->url_image_logo ?? '', '/storage/') ? asset($this->url_image_logo) : tenantAsset($this->url_image_logo, true) }}').then(() => this.innerHTML = '&#10003;').then(() => setTimeout(() => this.innerHTML = '&#128203;', 1500))" class="flex-shrink-0 text-gray-400 hover:text-blue-600">&#128203;</button>
                        </div>
                        @endif
                    </div>
                    <div wire:loading wire:target="url_image_logo" class="mt-2 text-sm text-blue-600">Carregando Arquivo...</div>
                </div>

                {{-- Background --}}
                <div>
                    <div class="w-full h-64 border-2 border-dashed border-gray-300 rounded-lg shadow-sm bg-gray-50 flex flex-col justify-center items-center relative overflow-hidden" style="background: url({{ str_starts_with($this->url_image_bg ?? '', '/storage/') ? asset($this->url_image_bg) : tenantAsset($this->url_image_bg, true) }});background-position: center;background-size: cover;background-repeat: no-repeat;">
                        @if (!$this->url_image_bg ?? false)
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <x-input wire:model="url_image_bg" type="file" class="text-sm" />
                            </div>
                        @else
                            <div class="absolute bottom-2 right-2">
                                <x-button xs negative spinner label="Remover" wire:click="$set('url_image_bg',false)" />
                            </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <label class="text-sm font-semibold text-gray-700">{{ __('image_bg') }}</label>
                        <p class="text-xs text-gray-500 mt-1">{{ __('image_bg_title') }}</p>
                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V6a2 2 0 012-2h2M4 16v2a2 2 0 002 2h2m8-16h2a2 2 0 012 2v2m0 8v2a2 2 0 01-2 2h-2"/></svg>
                            1920 × 600 px
                        </span>
                        @if(isAdmin() && $this->url_image_bg)
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-gray-400 truncate" title="{{ str_starts_with($this->url_image_bg ?? '', '/storage/') ? asset($this->url_image_bg) : tenantAsset($this->url_image_bg, true) }}">{{ str_starts_with($this->url_image_bg ?? '', '/storage/') ? asset($this->url_image_bg) : tenantAsset($this->url_image_bg, true) }}</span>
                            <button type="button" title="Copiar URL" onclick="navigator.clipboard.writeText('{{ str_starts_with($this->url_image_bg ?? '', '/storage/') ? asset($this->url_image_bg) : tenantAsset($this->url_image_bg, true) }}').then(() => this.innerHTML = '&#10003;').then(() => setTimeout(() => this.innerHTML = '&#128203;', 1500))" class="flex-shrink-0 text-gray-400 hover:text-blue-600">&#128203;</button>
                        </div>
                        @endif
                    </div>
                    <div wire:loading wire:target="url_image_bg" class="mt-2 text-sm text-blue-600">Carregando Arquivo...</div>
                </div>

            </div>
        </div>
    </div>

</div>
