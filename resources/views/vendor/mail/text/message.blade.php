@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <div style="text-transform: uppercase;">{{ config('app.name') }}</div>
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Copyright © {{ now()->format('Y') }} - {{ config('app.name') }} . ' - ' . @lang('Todos os direitos reservados.')</div>
        @endcomponent
    @endslot
@endcomponent
