<x-layout> {{-- Layout for all aplication --}}

    {{-- If is set, the title for the current page --}}
    @isset($title)
        <x-slot:title>
            {{ $title }}
        </x-slot>
    @endisset

    {{-- Header of the aplication --}}
    <x-header />

    {{-- Aside of the aplication --}}
    <x-aside />

    {{-- Page content --}}
    {{ $slot }}

    {{-- Footer of the aplication --}}
    <x-footer />
</x-layout>
