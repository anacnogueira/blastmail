@props([
    'post' => null,
    'delete' => null,
    'put' => null,
    'flat' => false,
])

@php
    $method = ($post or $delete or $put) ? 'POST' : 'GET';
@endphp

<form {{ $attributes->class(['flex flex-col' => !$flat]) }} method="{{ $method }}">
    @if ($method != 'GET')
        @csrf
    @endif

    @if ($delete)
        @method('DELETE')
    @endif

    @if ($put)
        @method('PUT')
    @endif

    {{ $slot }}
</form>
