<?php extract(collect($attributes->getAttributes())->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['title','rounded','cardClasses','shadow','padding'])
<x-card :title="$title" :rounded="$rounded" :card-classes="$cardClasses" :shadow="$shadow" :padding="$padding" {{ $attributes }}>
<x-slot name="action" >{{ $action }}</x-slot>
<x-slot name="footer" >{{ $footer }}</x-slot>
{{ $slot ?? "" }}
</x-card>