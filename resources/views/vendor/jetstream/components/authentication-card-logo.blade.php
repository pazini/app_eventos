@props(['dark' => false])
<a href="/" class="px-4 block">
    @php
        // Prioridade: Logo do customer (se detectado pelo domínio), senão logo do app
        // Para páginas de autenticação, usa logo squared
        $customerLogoUrl = customerLogo();
        $logoUrl = $customerLogoUrl ?: ($dark ? appLogoDark(false, null, true) : appLogo(false, null, true));
    @endphp
    <img src="{{ $logoUrl }}" alt="{{ appName() }}" style="min-height: 70px; max-width:300px; height:auto;">
</a>
