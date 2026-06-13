@props([
    'title' => 'Título',
    'subtitle' => '',
    'icon' => 'cog',
    'gradient' => 'from-blue-500 via-sky-500 to-cyan-500',
    'accentColor' => 'blue',
    'showRefresh' => false,
    'refreshAction' => null,
])

<div class="w-full max-w-7xl mx-auto bg-gradient-to-r {{ $gradient }} rounded-t-lg relative overflow-hidden shadow-xl">
    <!-- Decorative Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid-pattern-{{ uniqid() }}" width="8" height="8" patternUnits="userSpaceOnUse">
                    <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern-{{ uniqid() }})"/>
        </svg>
    </div>

    <div class="relative z-10 p-6 space-y-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                        @if($icon === 'cog')
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        @elseif($icon === 'users')
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        @elseif($icon === 'organizer')
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
                        @if($subtitle)
                            <p class="text-sm text-white/90 mt-1">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @if($showRefresh && $refreshAction)
                <div class="flex items-center gap-2">
                    <x-button 
                        white 
                        sm 
                        icon="refresh" 
                        wire:click="{{ $refreshAction }}" 
                        spinner="{{ $refreshAction }}"
                        class="px-4 py-2 bg-white/95 text-{{ $accentColor }}-600 hover:bg-white hover:text-{{ $accentColor }}-700 transition-all duration-200 rounded-lg font-medium shadow-sm"
                    />
                </div>
            @endif
        </div>

        @if(isset($filters))
            <div class="border-t border-white/30"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{ $filters }}
            </div>
        @endif

        @if(isset($actions))
            <div class="border-t border-white/30"></div>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                {{ $actions }}
            </div>
        @endif
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-4 right-4 w-16 h-16 bg-white/10 rounded-full blur-xl"></div>
    <div class="absolute bottom-4 left-4 w-12 h-12 bg-pink-400/20 rounded-full blur-lg"></div>
</div>

