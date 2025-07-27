@php
    $bg = asset('img/bg-airport-papua.jpg');
    $logo = asset('img/logo-dishub.png');
@endphp

<x-filament-panels::page.simple>
    <div class="flex">
        <div class="w-0 md:w-2/3 bg-[url('{{ $bg }}')] bg-cover bg-no-repeat rounded-l-xl relative">
            <div class="backdrop-brightness-75 w-full h-full rounded-l-xl"></div>
            <div class="absolute bottom-0 p-4">
                <div class="flex gap-2 mb-2">
                    <img src="{{ $logo }}" alt="" width="40px">
                    <img src="{{ $logo }}" alt="" width="40px">
                    <img src="{{ $logo }}" alt="" width="40px">
                    <img src="{{ $logo }}" alt="" width="40px">
                </div>
                <p class="text-white text-sm">Sistem Informasi Arsip</p>
            </div>
        </div>
        <div class="md:w-1/2 p-6 py-8">

            <div class="text-center mb-6">
                <h1 class="text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white mb-4">{{config('app.name')}}</h1>

                <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">Masuk</h1>
            </div>
            
            @if (filament()->hasRegistration())
                <x-slot name="subheading">
                    {{ __('filament-panels::pages/auth/login.actions.register.before') }}

                    {{ $this->registerAction }}
                </x-slot>
            @endif

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

            <x-filament-panels::form id="form" wire:submit="authenticate">
                {{ $this->form }}

                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </x-filament-panels::form>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
        </div>
    </div>

</x-filament-panels::page.simple>
