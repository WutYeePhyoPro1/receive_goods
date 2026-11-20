@props(['url', 'label' => 'Export Excel'])

@php
    $filters = request()->except(['page']);

    $exportUrl = $url;

    if (!empty($filters)) {
        $separator = str_contains($url, '?') ? '&' : '?';

        $exportUrl .= $separator . http_build_query($filters);
    }
@endphp

<a href="{{ $exportUrl }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center gap-2 px-4 py-2
                       bg-green-600 hover:bg-green-700
                       text-white text-sm font-medium
                       rounded-lg shadow-sm transition-colors',
    ]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
        stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 12l-4-4m4 4l4-4M4 20h16" />
    </svg>

    {{ $label }}
</a>
