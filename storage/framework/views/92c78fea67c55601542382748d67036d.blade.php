<?php extract(collect($attributes->getAttributes())->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['name'])
<x-error :name="$name" {{ $attributes }}>

{{ $slot ?? "" }}
</x-error>