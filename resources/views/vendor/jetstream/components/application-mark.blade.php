@php
    $customerLogoUrl = customerLogo();
    $dark = config('app.theme', 'light') === 'dark';
    $logoUrl = $customerLogoUrl ?: ($dark ? appLogoDark() : appLogo());
@endphp

<img src="{{ $logoUrl }}" {{ $attributes }} />
