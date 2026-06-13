@component('layouts.app-pep-flat')
    {{-- @php
        $isLocalDev = in_array(request()->getHost(), ['127.0.0.1', 'localhost']);
        $painelUrl = $isLocalDev ? url('/painel/assinaturas') : rtrim(getPainelUrl(), '/') . '/assinaturas';
    @endphp --}}

    <div class="max-w-3xl mx-auto text-center space-y-4">
        <div class="text-xs font-semibold uppercase tracking-widest text-gray-500">Assinaturas</div>
        <h1 class="text-2xl md:text-3xl font-semibold text-gray-900">Módulo em Construção</h1>
        <p class="text-sm md:text-base text-gray-600">
            Estamos preparando uma area de assinaturas. Enquanto isso, explore as outras funcionalidades.
        </p>
    </div>
@endcomponent
