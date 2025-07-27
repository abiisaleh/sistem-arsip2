@php
    $bg = asset('img/bg-airport-papua.jpg');
    $logos = [
            asset('img/logo-dishub.svg'),
            asset('img/logo-blu.svg'),
            asset('img/logo-mozes.png'),
            asset('img/logo-penerbangan.png'),
        ];
@endphp

<x-filament-panels::page.simple>
    <div class="flex">
        <div class="w-0 md:w-2/3 bg-cover bg-no-repeat rounded-l-xl relative" style="background-image: url('{{ $bg }}');">
            <div class="backdrop-brightness-75 w-full h-full rounded-l-xl"></div>
            <div class="absolute bottom-0 p-4">
                <div class="flex gap-2 mb-2">
                    @foreach ($logos as $logo)
                    <img src="{{ $logo }}" alt="" width="40px">
                    @endforeach
                </div>
                <p class="text-white text-sm">Sistem Informasi Arsip</p>
            </div>
        </div>
        <div class="md:w-1/2 p-6 py-8">

            <div class="text-center mb-6">

                <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">Profil</h1>
            </div>

            <x-filament-panels::form id="form" wire:submit="save">
                {{ $this->form }}

                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </x-filament-panels::form>

        </div>
    </div>

</x-filament-panels::page.simple>
