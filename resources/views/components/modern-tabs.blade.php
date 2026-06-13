@props([
    'tabs' => [],
    'activeTab' => null,
    'accentColor' => 'blue',
])

<div class="border-b border-gray-200 bg-gray-50">
    <nav class="flex space-x-1 px-6" aria-label="Tabs">
        @foreach($tabs as $tab)
            <button
                type="button"
                wire:click="$set('activeTab','{{ $tab['key'] }}')"
                class="px-6 py-4 text-sm font-medium transition-all duration-200 {{ $activeTab === $tab['key'] ? 'border-b-2 border-{{ $accentColor }}-500 text-{{ $accentColor }}-600 bg-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}"
            >
                <div class="flex items-center gap-2">
                    @if(isset($tab['icon']))
                        {!! $tab['icon'] !!}
                    @endif
                    {{ $tab['label'] }}
                </div>
            </button>
        @endforeach
    </nav>
</div>

