<?php extract(collect($attributes->getAttributes())->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['spacing','zIndex','maxWidth','align','blur'])
<x-modal :spacing="$spacing" :z-index="$zIndex" :max-width="$maxWidth" :align="$align" :blur="$blur" {{ $attributes }}>

{{ $slot ?? "" }}
</x-modal>