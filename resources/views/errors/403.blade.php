<x-guest-layout>
    {{-- @extends('errors::minimal')

    @section('title', __('Forbidden'))
    @section('code', '403')
    @section('message', __($exception->getMessage() ?: 'Forbidden')) --}}

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-white">
        <div class="flex flex-col justify-center items-center w-full mt-6 px-6 py-4 overflow-hidden sm:rounded-sm">

            <a href="{{ getHomeUrl() }}">
                <x-jet-authentication-card-logo />
            </a>

            <div class="my-4 py-4">
                <div class="text-center text-6xl font-bold">403</div>
                <div class="text-center text-2xl font-semibold">Acesso negado</div>
            </div>

            @auth
                <x-button dark label="IR PARA O PAINEL" href="{{ getPainelUrl() }}/" />
            @else
                <x-button dark label="IR PARA A HOME" href="/" />
            @endauth


        </div>
    </div>
</x-guest-layout>

