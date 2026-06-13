<label {{ $attributes->class([
        'block text-base font-light uppercase',
        'text-negative-600'  => $hasError,
        'opacity-60'         => $attributes->get('disabled'),
        'text-black dark:text-gray-400' => !$hasError,
    ]) }}>
    {{ $label ?? $slot }}
</label>
