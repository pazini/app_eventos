<?php extract(collect($attributes->getAttributes())->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['class','wire:loading.remove'])
<x-wireui::icons.outline.ticket :class="$class" :wire:loading.remove="$wireLoadingRemove" >

{{ $slot ?? "" }}
</x-wireui::icons.outline.ticket>