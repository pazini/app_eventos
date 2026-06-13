<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-white">
        <div class="flex flex-col justify-center items-center w-full mt-6 px-6 py-4 overflow-hidden sm:rounded-sm">

            <a href="{{ getHomeUrl() }}">
                <x-jet-authentication-card-logo />
            </a>

            <div class="my-4 py-4">
                <div class="text-center text-6xl font-bold">404</div>
                <div class="text-center text-2xl font-semibold">Página não localizada ou inexistente</div>
            </div>

            @php
                $host = request()->getHost();
                $targetUrl = url('/');
                $targetLabel = str_contains($host, 'painel') ? 'IR PARA O PAINEL' : 'TENTAR NOVAMENTE';
            @endphp
            <x-button dark label="{{ $targetLabel }}" href="{{ $targetUrl }}" />


        </div>
    </div>
</x-guest-layout>
