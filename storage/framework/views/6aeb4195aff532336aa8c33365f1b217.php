<?php
    use Illuminate\Support\Str;
?>

<?php $__env->startPush('meta'); ?>
    <?php
        $campaignUrl = campanhaUrl(
            $campaign->customer_organization_slug,
            $campaign->slug,
            null,
            $appUserUuid ?? getAppUserUuid(),
            $appSource ?? getAppSource(),
        );

        // Função helper para gerar URL completa da imagem
        $getImageUrl = function ($url) {
            if (empty($url)) {
                return null;
            }
            // Verifica se já é URL completa
            if (substr($url, 0, 7) === 'http://' || substr($url, 0, 8) === 'https://') {
                return $url;
            }
            // Se começa com /storage/, usa asset diretamente (compatibilidade com URLs antigas)
            if (substr($url, 0, 9) === '/storage/') {
                return asset($url);
            }
            // Se começa com storage/, adiciona / (compatibilidade com URLs antigas)
            if (substr($url, 0, 8) === 'storage/') {
                return asset('/' . $url);
            }
            // Caso padrão: usa tenantAsset para storage isolado por tenant
            return tenantAsset($url, true);
        };

        // Prioriza banner, depois thumb, depois logo padrão
        $campaignImage =
            $getImageUrl($campaign->url_image_banner) ?? ($getImageUrl($campaign->url_image_thumb) ?? appLogo(true)); // White Label: logo dinâmico

        $campaignOrganizerLabel =
            $campaign->organizer->organizer_name_full ?? ($campaign->organizer->organizer_name ?? appName());
        $campaignTitle = $campaign->name . ' - ' . $campaignOrganizerLabel; // White Label: nome dinâmico

        // Limpa HTML e espaços extras da descrição
        $rawDescription =
            $campaign->description ?? ($campaign->about ?? 'Participe desta campanha e faça a diferença!');
        $campaignDescription = strip_tags($rawDescription);
        $campaignDescription = preg_replace('/\s+/', ' ', $campaignDescription); // Remove espaços múltiplos
        $campaignDescription = trim($campaignDescription);
        $campaignDescription = mb_substr($campaignDescription, 0, 200);
        if (mb_strlen($rawDescription) > 200) {
            $campaignDescription .= '...';
        }

        // Favicon
        $campaignFavicon = $getImageUrl($campaign->url_image_thumb) ?? appFavicon(true); // White Label: favicon dinâmico
    ?>

    <!-- Primary Meta Tags -->
    <title><?php echo e($campaignTitle); ?></title>
    <meta name="title" content="<?php echo e($campaignTitle); ?>">
    <meta name="description" content="<?php echo e($campaignDescription); ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e($campaignUrl); ?>">
    <meta property="og:title" content="<?php echo e($campaignTitle); ?>">
    <meta property="og:description" content="<?php echo e($campaignDescription); ?>">
    <meta property="og:image" content="<?php echo e($campaignImage); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="<?php echo e(appName()); ?> - Campanhas"> 

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo e($campaignUrl); ?>">
    <meta name="twitter:title" content="<?php echo e($campaignTitle); ?>">
    <meta name="twitter:description" content="<?php echo e($campaignDescription); ?>">
    <meta name="twitter:image" content="<?php echo e($campaignImage); ?>">

    <!-- Favicon -->
    <?php if($campaignFavicon): ?>
        <link rel="icon" type="image/png" href="<?php echo e($campaignFavicon); ?>">
        <link rel="apple-touch-icon" href="<?php echo e($campaignFavicon); ?>">
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        :root {
            --campaign-color-primary: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>;
        }

        /* Aplica estilos do CKEditor apenas no conteúdo rico da campanha */
        .campaign-editor-content .text-tiny {
            font-size: 0.7em;
        }

        .campaign-editor-content .text-small {
            font-size: 0.85em;
        }

        .campaign-editor-content .text-big {
            font-size: 1.4em;
        }

        .campaign-editor-content .text-huge {
            font-size: 1.8em;
        }

        /* No conteúdo do CKEditor, mantém a cor inline mesmo quando houver <strong> dentro de <span style="color:..."> */
        .campaign-editor-content span[style*="color"] strong,
        .campaign-editor-content span[style*="color"] b {
            color: inherit;
        }

        /*
                             * Fidelidade ao editor (CKEditor):
                             * O Tailwind `prose` aplica margin-top/bottom ~1.25em em cada <p>,
                             * causando espaçamento muito maior do que o exibido no editor.
                             * Reduzimos para que linhas consecutivas fiquem próximas,
                             * exatamente como o usuário vê ao digitar.
                             * Parágrafos vazios (linha vazia intencional) mantêm separação maior.
                             */
        .campaign-editor-content p {
            margin-top: 0.1em;
            margin-bottom: 0.1em;
        }

        .campaign-editor-content p:empty,
        .campaign-editor-content p>br:only-child {
            margin-top: 0.7em;
            margin-bottom: 0.7em;
        }

        #menu-compartilhar-campanha {
            --share-menu-base: 0%;
            --share-menu-shift: 0px;
            transform: translate3d(calc(var(--share-menu-base) + var(--share-menu-shift)), 0, 0);
            transition: transform 0.2s ease;
        }

        .campaign-modern-surface {
            position: relative;
            isolation: isolate;
            background-image:
                radial-gradient(circle at 12% -8%, var(--color-primary-soft) 0%, transparent 40%),
                radial-gradient(circle at 88% 4%, var(--color-secondary-soft) 0%, transparent 36%),
                linear-gradient(180deg, #f4f7fc 0%, #edf2fa 100%);
        }

        .campaign-modern-surface::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.1) 1px, transparent 1px);
            background-size: 26px 26px;
            opacity: 0.24;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.26), rgba(0, 0, 0, 0.06) 52%, transparent 82%);
        }

        .campaign-modern-panel {
            position: relative;
            border-radius: 24px;
            border: 1px solid #dbe3f1;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.97), rgba(248, 251, 255, 0.94));
            box-shadow: 0 20px 44px -34px rgba(15, 23, 42, 0.55);
            overflow: hidden;
            backdrop-filter: blur(5px);
        }

        .campaign-modern-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(circle at 87% 16%, var(--color-primary-soft), transparent 30%);
            opacity: 0.7;
        }

        .campaign-modern-hero {
            border-radius: 30px;
        }

        .campaign-progress-mini {
            position: relative;
            overflow: hidden;
            box-shadow: 0 16px 28px -24px rgba(15, 23, 42, 0.6);
        }

        .campaign-progress-mini::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(140deg, rgba(255, 255, 255, 0.36), transparent 50%);
        }

        .campaign-progress-track {
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: inset 0 1px 3px rgba(148, 163, 184, 0.2);
        }

        .campaign-modern-shell {
            position: relative;
            border-radius: 30px;
            border: 1px solid #dbe3f1;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.97), rgba(245, 249, 255, 0.95));
            box-shadow: 0 26px 54px -38px rgba(15, 23, 42, 0.58);
            overflow: hidden;
            backdrop-filter: blur(4px);
        }

        .campaign-modern-shell::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(rgba(148, 163, 184, 0.09) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.09) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.2;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), transparent 65%);
        }

        .campaign-modern-shell::after {
            content: '';
            position: absolute;
            right: -120px;
            bottom: -120px;
            width: 320px;
            height: 320px;
            pointer-events: none;
            background: radial-gradient(circle, var(--color-secondary-soft), transparent 68%);
        }

        .campaign-stepbar {
            position: relative;
            z-index: 20;
            padding: 1rem 1.15rem 1.2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            background: linear-gradient(120deg, var(--color-primary), var(--color-secondary));
            overflow: hidden;
        }

        .campaign-stepbar::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 14% 16%, rgba(255, 255, 255, 0.35), transparent 40%),
                linear-gradient(130deg, rgba(255, 255, 255, 0.22), transparent 52%);
            opacity: 0.85;
        }

        .campaign-step-track {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: flex-start;
            gap: 0.4rem;
        }

        .campaign-step-node {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .campaign-step-badge {
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.48);
            background: rgba(255, 255, 255, 0.16);
            color: rgba(255, 255, 255, 0.96);
            font-size: 0.82rem;
            font-weight: 800;
            box-shadow: 0 10px 20px -14px rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(6px);
        }

        .campaign-step-node.is-active .campaign-step-badge,
        .campaign-step-node.is-done .campaign-step-badge {
            background: #ffffff;
            color: var(--color-primary);
            border-color: #ffffff;
        }

        .campaign-step-label {
            margin-top: 0.42rem;
            font-size: 11px;
            line-height: 1.05;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.95);
            text-align: center;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .campaign-step-link {
            flex: 1;
            height: 2px;
            margin-top: 1.25rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.3);
        }

        .campaign-step-link.is-active {
            background: #ffffff;
        }

        .campaign-step-mobile-caption {
            display: none;
        }

        .campaign-stage-content {
            position: relative;
            z-index: 20;
        }

        .campaign-step-card {
            position: relative;
            border-radius: 22px;
            border: 1px solid #e1e9f4;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.97), rgba(247, 251, 255, 0.94));
            box-shadow: 0 20px 36px -30px rgba(15, 23, 42, 0.5);
            padding: 1rem;
        }

        .campaign-donation-split {
            border-radius: 18px;
            border: 1px solid #dbe4f2;
            background: linear-gradient(180deg, rgba(247, 251, 255, 0.95), rgba(241, 246, 255, 0.9));
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
            padding: 0.8rem;
        }

        .campaign-donation-pane {
            border-radius: 14px;
            border: 1px solid #dbe4f2;
            background: rgba(255, 255, 255, 0.9);
            padding: 0.8rem;
            height: 100%;
        }

        .campaign-currency-input {
            border-radius: 12px;
            border: 1px solid #cfd9e8;
            background: #ffffff;
            box-shadow: 0 8px 18px -18px rgba(15, 23, 42, 0.65);
            overflow: hidden;
        }

        .campaign-currency-prefix {
            background: linear-gradient(180deg, rgba(248, 250, 253, 1), rgba(238, 244, 252, 1));
        }

        .campaign-recurring-options {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.55rem;
        }

        .campaign-recurring-option {
            min-height: 54px;
            border-radius: 12px;
            box-shadow: 0 8px 16px -18px rgba(15, 23, 42, 0.55);
        }

        .campaign-amount-shell {
            position: relative;
            display: inline-flex;
            border-radius: 9999px;
            padding: 1px;
            background: linear-gradient(120deg, var(--amount-primary), var(--amount-secondary));
            box-shadow: 0 10px 30px -18px var(--amount-primary-soft), 0 8px 24px -16px var(--amount-secondary-soft);
        }

        .campaign-amount-shell::after {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: inherit;
            background: linear-gradient(120deg, var(--amount-primary-soft), var(--amount-secondary-soft));
            filter: blur(12px);
            opacity: 0.55;
            pointer-events: none;
            z-index: 0;
        }

        .campaign-amount-pill {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            min-width: 198px;
            border-radius: inherit;
            padding: 0.5rem 1.5rem;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 255, 0.98) 100%);
            border: 1px solid rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(4px);
        }

        .campaign-amount-label {
            font-size: 10px;
            line-height: 1;
            font-weight: 700;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            opacity: 0.72;
            color: var(--amount-primary);
        }

        .campaign-amount-value {
            font-size: clamp(1.5rem, 2vw, 2.15rem);
            line-height: 1.12;
            font-weight: 800;
            letter-spacing: 0.02em;
            color: var(--amount-primary);
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.65);
        }

        @media (max-width: 640px) {
            .campaign-amount-pill {
                min-width: 100%;
                width: 100%;
            }

            .campaign-stepbar {
                padding: 0.82rem 0.7rem 0.72rem;
            }

            .campaign-step-track {
                gap: 0.12rem;
                align-items: center;
            }

            .campaign-step-badge {
                width: 2.06rem;
                height: 2.06rem;
                font-size: 0.72rem;
            }

            .campaign-step-label {
                display: none;
            }

            .campaign-step-link {
                margin-top: 0;
                height: 1.5px;
                opacity: 0.72;
            }

            .campaign-step-mobile-caption {
                display: block;
                margin-top: 0.58rem;
                text-align: center;
                font-size: 10px;
                line-height: 1.2;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                color: rgba(255, 255, 255, 0.95);
            }

            .campaign-step-card {
                border-radius: 18px;
                padding: 0.82rem;
            }

            .campaign-donation-split,
            .campaign-donation-pane {
                border-radius: 14px;
                padding: 0.65rem;
            }

            .campaign-recurring-options {
                grid-template-columns: 1fr;
                gap: 0.45rem;
            }
        }

        @media (max-width: 640px) {
            #menu-compartilhar-campanha {
                right: auto;
                left: 50%;
                --share-menu-base: -50%;
                width: min(260px, calc(100vw - 1.5rem));
                max-width: calc(100vw - 1.5rem);
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<div id="div-title-campaing" class="min-h-screen pt-2 campaign-modern-surface"
    style="--color-primary: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>; --color-secondary: <?php echo e($campaign->color_secondary ?? '#10B981'); ?>; --color-primary-soft: <?php echo e(($campaign->color_primary ?? '#3B82F6') . '2D'); ?>; --color-secondary-soft: <?php echo e(($campaign->color_secondary ?? '#10B981') . '2D'); ?>; background-color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>15;">

    <div class="max-w-4xl mx-auto px-4 pt-4 relative z-10">

        
        <?php if(!in_array($step, [3]) && $campaign->url_image_banner): ?>
            <div class="overflow-hidden" style="border-radius: 24px 24px 0 0;">
                <img src="<?php echo e($getImageUrl($campaign->url_image_banner)); ?>" alt="<?php echo e($campaign->name); ?>"
                    class="w-full h-auto block">
            </div>
        <?php endif; ?>

        
        <?php if($campaign->status === 'paused'): ?>
            <div class="bg-orange-50 border-l-4 border-orange-500 rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold text-orange-900">Campanha Pausada</h3>
                        <p class="text-sm text-orange-800 mt-1">Esta campanha está temporariamente pausada e não está
                            aceitando novas participações no momento.</p>
                    </div>
                </div>
            </div>
        <?php elseif($campaign->status === 'draft'): ?>
            <div class="bg-gray-50 border-l-4 border-gray-500 rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                        </path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Campanha em Elaboração</h3>
                        <p class="text-sm text-gray-700 mt-1">Esta campanha ainda está em fase de elaboração e não está
                            aceitando participações.</p>
                    </div>
                </div>
            </div>
        <?php elseif($campaign->status === 'finished'): ?>
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold text-blue-900">Campanha Finalizada</h3>
                        <p class="text-sm text-blue-800 mt-1">Esta campanha já foi finalizada e não está mais aceitando
                            novas participações.</p>
                    </div>
                </div>
            </div>
        <?php elseif($campaign->status === 'cancelled'): ?>
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-red-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold text-red-900">Campanha Cancelada</h3>
                        <p class="text-sm text-red-800 mt-1">Esta campanha foi cancelada e não está mais disponível.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if($step !== 3): ?>
            <div class="campaign-modern-panel campaign-modern-hero mb-6 p-6" style="border-radius: 0 0 24px 24px;">
                <div class="w-full flex flex-col md:flex-row justify-between items-start">
                    
                    <div>
                        <div class="text-xl md:text-3xl font-bold text-gray-900 uppercase"><?php echo e($campaign->name); ?></div>
                        <?php if($campaign->organizer && ($campaign->organizer->organizer_name_full ?? $campaign->organizer->organizer_name)): ?>
                            <div class="flex items-center gap-1 text-xs sm:text-base font-medium text-gray-700">
                                <div>
                                    <?php echo e($campaign->organizer->organizer_name_full ?? $campaign->organizer->organizer_name); ?>

                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="relative" data-share-wrapper>
                        <div class="relative flex items-center">
                            <button type="button" onclick="compartilharCampanha()"
                                class="group inline-flex h-8 md:h-11 pl-1 md:pl-3 pr-2 md:pr-3.5 items-center gap-1 md:gap-2.5 rounded-l-xl border border-emerald-200 bg-white text-emerald-700 shadow-sm hover:bg-emerald-50 hover:border-emerald-300 transition-all duration-200"
                                title="Compartilhar campanha">
                                <span class="flex h-4 md:h-6 w-4 md:w-6 items-center justify-center">
                                    <svg class="h-4 md:h-6 w-4 md:w-6 shrink-0"
                                        enable-background="new 0 0 155.35 155.354" height="155.354" id="Layer_1"
                                        overflow="visible" version="1.1" viewBox="0 0 155.35 155.354"
                                        width="155.35" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="icon_2_">
                                            <path
                                                d="M77.675,0c42.899,0,77.675,34.776,77.675,77.675c0,42.896-34.775,77.678-77.675,77.678S0,120.571,0,77.675   C0,34.776,34.776,0,77.675,0z"
                                                fill="#6EBC51" />
                                            <path
                                                d="M47.209,91.788c3.572,0,6.799-1.331,9.337-3.476l29.542,14.771c-0.027,0.283-0.09,0.538-0.09,0.827   c0,8.048,6.503,14.564,14.55,14.564c8.034,0,14.551-6.517,14.551-14.564c0-8.033-6.517-14.543-14.551-14.543   c-3.578,0-6.806,1.331-9.337,3.482L61.677,78.075c0.014-0.279,0.069-0.542,0.069-0.824c0-0.286-0.062-0.555-0.069-0.838   l29.535-14.778c2.531,2.141,5.759,3.486,9.337,3.486c8.034,0,14.551-6.51,14.551-14.554c0-8.03-6.517-14.536-14.551-14.536   c-8.047,0-14.55,6.506-14.55,14.54c0,0.29,0.063,0.555,0.09,0.834L56.546,66.173c-2.538-2.138-5.765-3.482-9.337-3.482   c-8.041,0-14.55,6.517-14.55,14.561C32.659,85.285,39.168,91.788,47.209,91.788z"
                                                fill="#FFFFFF" />
                                        </g>
                                    </svg>
                                </span>
                                <span class="text-xs md:textsm font-semibold tracking-tight">Compartilhar</span>
                            </button>

                            <button type="button" onclick="toggleMenuCompartilhar(event)"
                                class="inline-flex h-8 md:h-11 w-8 md:w-11 shrink-0 items-center justify-center rounded-r-xl border border-l-0 border-emerald-200 bg-white text-emerald-700 hover:bg-emerald-50 hover:border-emerald-300 transition-colors duration-200"
                                title="Mais opções de compartilhamento" aria-label="Mais opções de compartilhamento"
                                aria-expanded="false" data-share-menu-toggle>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none"
                                    stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="menu-compartilhar-campanha"
                            class="hidden absolute right-0 top-full mt-2.5 w-[260px] max-w-[calc(100vw-2rem)] rounded-xl border border-gray-200 bg-white p-2.5 shadow-xl z-[80]"
                            role="menu" aria-label="Opções de compartilhamento">
                            <div class="px-1 pb-1.5">
                                <p class="text-[11px] uppercase tracking-wide font-semibold text-gray-500">Compartilhar
                                    campanha</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Escolha onde divulgar</p>
                            </div>

                            <div class="grid grid-cols-2 gap-1.5">
                                <button type="button" onclick="compartilharVia('whatsapp')"
                                    class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-2.5 py-2 text-sm font-medium text-gray-700 hover:border-green-300 hover:bg-green-50 transition-colors"
                                    role="menuitem">
                                    <svg class="w-5 h-5 text-green-600 shrink-0" viewBox="0 0 512 512" fill="none"
                                        aria-hidden="true">
                                        <g fill="currentColor">
                                            <path
                                                d="M500.4,241.8c-0.6,63.4-16.9,113-50.8,156.1c-36.4,46.3-83.8,75.7-141.8,87.6c-44.8,9.1-88.4,5.1-131.2-10.4c-9.5-3.5-18.8-7.6-27.7-12.5c-2.7-1.5-5-1.7-8-0.7c-40.5,13.1-81.1,26.1-121.7,39c-1.8,0.6-4.2,2.3-5.6,1c-1.6-1.6,0.4-4,1-5.9c8.1-24.1,16.2-48.3,24.4-72.4c5-14.7,9.8-29.3,15-43.9c1.4-3.8,0.9-6.7-1.2-10.2c-10.3-17.6-17.9-36.4-23.3-56.1c-10.1-37-11.2-74.4-3.3-111.8c8.6-40.8,26.6-77.2,54.3-108.6c33.5-38,74.8-63.1,123.9-75.2c31.3-7.7,62.9-8.9,94.7-3.7c41.6,6.8,79.1,23.3,111.8,49.9c34,27.7,58.8,62.1,74.4,103.4C493.9,189.7,500.7,226.2,500.4,241.8z M74.6,441.5c24.8-8,48.5-15.6,72-23.3c3.1-1,5.6-0.8,8.3,1c9,6,18.7,10.8,28.7,15c32.5,13.8,66.3,18.8,101.2,14.3c42.8-5.5,80.4-22.7,112-52.6c39.8-37.7,61.1-83.9,63-138.5c1.8-53.5-15.4-100.5-51.3-140.4c-34.1-37.9-76.9-59.5-127.4-64.9c-43.9-4.7-85,4.8-123,27.6c-32.5,19.6-57.4,46.3-74.7,80c-19.1,37.1-25.6,76.5-20.1,117.8c4.1,31.2,15.3,59.9,33.3,85.7c2,2.9,2.6,5.3,1.4,8.7c-3.7,10.2-7,20.5-10.5,30.7C83.3,415.2,79.2,427.8,74.6,441.5z">
                                            </path>
                                            <path
                                                d="M141,194.7c0.5-23.8,8.9-41.5,24.8-55.6c5.1-4.6,11.4-6.6,18.5-5.6c3,0.4,6,0.7,9,0.5c5.6-0.3,9.4,2.4,11.3,7.3c6.7,17.4,13.2,34.8,19.7,52.3c2.2,6-1.6,10.7-4.8,15c-3.7,5.1-8.2,9.6-12.6,14.1c-4.5,4.6-5.1,7.2-2,12.8c18.1,32.9,43.5,58.2,78,73.8c1.2,0.5,2.4,1.2,3.6,1.8c4.6,2.1,8.7,1.6,12.2-2.4c6.4-7.5,13.4-14.4,19.3-22.3c3.9-5.2,6.6-6.1,12.5-3.5c17.6,7.6,34.1,17.2,50.9,26.5c3.1,1.7,4.2,4.3,4.1,7.9c-0.9,29.5-17.8,44.9-47.5,51.7c-14.4,3.3-27.7-0.3-40.9-5.1c-27.7-10-54.4-21.8-76.8-41.6c-17.8-15.8-33.5-33.4-46.8-53.1c-10.2-15.2-20.8-30.1-26.7-47.8C143.4,212,140.9,202.4,141,194.7z">
                                            </path>
                                        </g>
                                    </svg>
                                    WhatsApp
                                </button>

                                <button type="button" onclick="compartilharVia('telegram')"
                                    class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-2.5 py-2 text-sm font-medium text-gray-700 hover:border-sky-300 hover:bg-sky-50 transition-colors"
                                    role="menuitem">
                                    <svg class="w-4 h-4 text-sky-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M9.78 18.65l-.4 5.63c.57 0 .82-.24 1.12-.54l2.69-2.57 5.58 4.08c1.02.57 1.74.27 2-.95l3.63-17.01h.01c.31-1.44-.52-2-1.51-1.63L1.55 13.2c-1.4.55-1.38 1.32-.24 1.67l5.43 1.69L19.3 8.63c.59-.37 1.13-.17.69.2" />
                                    </svg>
                                    Telegram
                                </button>

                                <button type="button" onclick="compartilharVia('facebook')"
                                    class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-2.5 py-2 text-sm font-medium text-gray-700 hover:border-blue-300 hover:bg-blue-50 transition-colors"
                                    role="menuitem">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M22.68 0H1.32C.59 0 0 .59 0 1.32v21.36C0 23.41.59 24 1.32 24h11.5v-9.29H9.69V11.1h3.13V8.41c0-3.1 1.9-4.79 4.66-4.79 1.33 0 2.47.1 2.8.14v3.25h-1.92c-1.5 0-1.79.72-1.79 1.76v2.32h3.58l-.47 3.61h-3.11V24h6.11c.73 0 1.32-.59 1.32-1.32V1.32C24 .59 23.41 0 22.68 0z" />
                                    </svg>
                                    Facebook
                                </button>

                                <button type="button" onclick="compartilharVia('email')"
                                    class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-2.5 py-2 text-sm font-medium text-gray-700 hover:border-orange-300 hover:bg-orange-50 transition-colors"
                                    role="menuitem">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-2 11H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2z" />
                                    </svg>
                                    E-mail
                                </button>
                            </div>

                            <button type="button" onclick="copiarLinkCampanha()"
                                class="mt-1.5 w-full inline-flex items-center justify-center gap-2 rounded-lg border border-dashed border-emerald-300 bg-emerald-50 px-2.5 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 transition-colors"
                                role="menuitem">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 16h8M8 12h8m-3-8H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V9m-6-5l6 6" />
                                </svg>
                                Copiar link da campanha
                            </button>
                        </div>
                    </div>
                </div>

                

                <?php if(!in_array($step, [3, 4])): ?>
                    <?php if($campaign->description): ?>
                        <div class="mt-4 text-gray-700 prose max-w-none campaign-editor-content">
                            <?php echo $campaign->description; ?>

                        </div>
                    <?php endif; ?>

                    <?php if($campaign->about): ?>
                        <div class="mt-3 text-gray-700 border-t pt-3 prose max-w-none campaign-editor-content">
                            <?php echo $campaign->about; ?>

                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        
        <?php if($campaign->show_goal_amount && $campaign->goal_amount && $step == 1): ?>
            <?php
                // Calcula o total arrecadado a partir dos pedidos pagos
                $totalReceived =
                    $campaign->orders()->where('status', 'paid')->sum('amount_paid') ?:
                    $campaign->orders()->where('status', 'paid')->sum('amount_total');

                // Conta o número de adesões (pedidos pagos)
                $totalAdesoes = $campaign->orders()->where('status', 'paid')->count();

                // Calcula a porcentagem alcançada (sem limite de 100% para mostrar quando ultrapassa a meta)
                $percentReceived = $campaign->goal_amount > 0 ? ($totalReceived / $campaign->goal_amount) * 100 : 0;

                // Calcula o valor restante para alcançar a meta
                $remaining = max(0, $campaign->goal_amount - $totalReceived);
            ?>
            <div class="campaign-modern-panel p-6 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4">
                    <h2 class="text-xl font-semibold text-gray-800  flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        Progresso da Campanha
                    </h2>
                    <?php if($campaign->datetime_start || $campaign->datetime_finish): ?>
                        <div class="text-lg text-left md:text-right text-gray-600">
                            <?php if($campaign->datetime_start): ?>
                                <div>Desde <?php echo e(\Carbon\Carbon::parse($campaign->datetime_start)->format('d/m/Y')); ?>

                                </div>
                            <?php endif; ?>
                            <?php if($campaign->datetime_finish): ?>
                                <div>Até <?php echo e(\Carbon\Carbon::parse($campaign->datetime_finish)->format('d/m/Y')); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="space-y-5">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                        <div class="campaign-progress-mini px-4 py-2 rounded-xl border"
                            style="border-color: <?php echo e($campaign->color_primary); ?>33; background-color: <?php echo e($campaign->color_primary); ?>0F;">
                            <p class="text-xs font-semibold uppercase tracking-wide"
                                style="color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>">Arrecadado</p>
                            <p class="text-3xl md:text-3xl font-bold"
                                style="color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>">
                                <?php echo e(toMoney($totalReceived, 'R$ ')); ?>

                            </p>
                            <p class="text-[11px] font-medium"
                                style="color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>">Arrecadamos até agora</p>
                        </div>

                        <div class="campaign-progress-mini px-4 py-2 rounded-xl border"
                            style="border-color: <?php echo e($campaign->color_secondary); ?>33; background-color: <?php echo e($campaign->color_secondary); ?>0F;">
                            <p class="text-xs font-semibold uppercase tracking-wide"
                                style="color: <?php echo e($campaign->color_secondary ?? '#10B981'); ?>">Meta</p>
                            <p class="text-2xl md:text-3xl font-bold"
                                style="color: <?php echo e($campaign->color_secondary ?? '#10B981'); ?>">
                                <?php echo e(toMoney($campaign->goal_amount, 'R$ ')); ?>

                            </p>
                            <p class="text-[11px] font-medium"
                                style="color: <?php echo e($campaign->color_secondary ?? '#10B981'); ?>">Nosso Objetivo</p>
                        </div>

                        <!-- <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Faltam</p>
                            <p class="text-2xl md:text-3xl font-bold text-slate-800">
                                R$ <?php echo e(number_format($remaining, 2, ',', '.')); ?>

                            </p>
                            <p class="text-[11px] text-slate-500 font-medium">para alcançar a meta</p>
                        </div> -->
                    </div>

                    
                    <?php if($campaign->show_progress): ?>
                        <div class="space-y-2">
                            <div class="campaign-progress-track w-full rounded-full h-4 shadow-inner overflow-hidden"
                                style="background-color: <?php echo e($campaign->color_primary); ?>33;">
                                <?php if($percentReceived >= 100): ?>
                                    <div class="h-4 rounded-full transition-all duration-500"
                                        style="width: 100%; background: linear-gradient(to right, <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>, <?php echo e($campaign->color_secondary ?? '#10B981'); ?>);">
                                    </div>
                                <?php else: ?>
                                    <div class="h-4 rounded-full transition-all duration-500"
                                        style="width: <?php echo e($percentReceived); ?>%; background: linear-gradient(to right, <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>, <?php echo e($campaign->color_secondary ?? '#10B981'); ?>);">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between text-sm gap-1">
                                <span class="font-semibold"
                                    style="color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>">
                                    <?php echo e(number_format($percentReceived, 0)); ?>% alcançado · <?php echo e($totalAdesoes); ?>

                                    <?php echo e($totalAdesoes == 1 ? 'adesão' : 'adesões'); ?>

                                </span>
                                <span class="text-slate-600 font-medium">
                                    Projeção restante: <span class="font-semibold"
                                        style="color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>"><?php echo e(toMoney($remaining, 'R$ ')); ?></span>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div id="div-step" class="pt-4">

            
            <div class="campaign-modern-shell">

                <div class="campaign-stage-content px-4 py-6 md:px-5">
                    <?php if(!in_array($campaign->status, ['active', 'active_direct'])): ?>

                        <?php if (isset($component)) { $__componentOriginal522a59481d8bfd4d44478643bc3270fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal522a59481d8bfd4d44478643bc3270fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.validation-errors','data' => ['class' => 'mb-4','noLoading' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','no_loading' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $attributes = $__attributesOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $component = $__componentOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__componentOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>

                        <div class="bg-gray-100 border border-gray-300 rounded-lg p-8 text-center campaign-step-card">
                            <svg class="w-10 md:w-16 h-10 md:h-16 text-gray-400 mx-auto mb-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            <p class="text-lg font-semibold text-gray-700">Formulário de participação indisponível</p>
                            <p class="text-sm text-gray-600 mt-2">Esta campanha não está aceitando novas participações
                                no momento.</p>
                        </div>
                    <?php else: ?>
                        
                        <?php if($step === 1): ?>

                            <?php if (isset($component)) { $__componentOriginal522a59481d8bfd4d44478643bc3270fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal522a59481d8bfd4d44478643bc3270fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.validation-errors','data' => ['class' => 'mb-4','noLoading' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','no_loading' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $attributes = $__attributesOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $component = $__componentOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__componentOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>

                            <div class="space-y-6 mt-2 campaign-step-card">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                        <?php echo e($campaign->enable_questions && $campaign->questions->count() > 0 ? 'Antes de contribuir, responda algumas perguntas' : 'Escolha o valor'); ?>

                                    </h2>
                                    <p class="text-sm text-gray-600">
                                        <?php echo e($campaign->enable_questions && $campaign->questions->count() > 0 ? 'Suas respostas nos ajudam a entender melhor nossa comunidade' : 'Toda contribuição faz diferença!'); ?>

                                    </p>
                                </div>

                                
                                <?php if($campaign->enable_questions && $campaign->questions->count() > 0): ?>
                                    <div class="space-y-5">
                                        <?php $__currentLoopData = $campaign->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                                                <label class="block text-sm font-bold text-gray-900 mb-3">
                                                    <?php echo e($question->question_text); ?>

                                                    <?php if($question->is_required): ?>
                                                        <span class="text-red-600">*</span>
                                                    <?php endif; ?>
                                                </label>

                                                <?php if($question->question_type === 'text'): ?>
                                                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'quizAnswers.'.e($question->id).'','placeholder' => ''.e($question->placeholder).'','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                <?php elseif($question->question_type === 'textarea'): ?>
                                                    <?php if (isset($component)) { $__componentOriginal05e078adad918d7a9c127c65d98f7d47 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal05e078adad918d7a9c127c65d98f7d47 = $attributes; } ?>
<?php $component = WireUi\View\Components\Textarea::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Textarea::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'quizAnswers.'.e($question->id).'','placeholder' => ''.e($question->placeholder).'','rows' => '4','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal05e078adad918d7a9c127c65d98f7d47)): ?>
<?php $attributes = $__attributesOriginal05e078adad918d7a9c127c65d98f7d47; ?>
<?php unset($__attributesOriginal05e078adad918d7a9c127c65d98f7d47); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal05e078adad918d7a9c127c65d98f7d47)): ?>
<?php $component = $__componentOriginal05e078adad918d7a9c127c65d98f7d47; ?>
<?php unset($__componentOriginal05e078adad918d7a9c127c65d98f7d47); ?>
<?php endif; ?>
                                                <?php elseif($question->question_type === 'number'): ?>
                                                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','wire:model.defer' => 'quizAnswers.'.e($question->id).'','placeholder' => ''.e($question->placeholder).'','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                <?php elseif($question->question_type === 'date'): ?>
                                                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','wire:model.defer' => 'quizAnswers.'.e($question->id).'','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                <?php elseif($question->question_type === 'select'): ?>
                                                    <select wire:model.defer="quizAnswers.<?php echo e($question->id); ?>"
                                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                        <option value="">
                                                            <?php echo e($question->placeholder ?: 'Selecione...'); ?></option>
                                                        <?php $__currentLoopData = $question->question_options ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($option); ?>"><?php echo e($option); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php elseif($question->question_type === 'radio'): ?>
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2"
                                                        wire:key="radio-group-<?php echo e($question->id); ?>">
                                                        <?php $__currentLoopData = $question->question_options ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $isSelected =
                                                                    isset($quizAnswers[$question->id]) &&
                                                                    $quizAnswers[$question->id] === $option;
                                                            ?>
                                                            <label
                                                                wire:key="radio-<?php echo e($question->id); ?>-<?php echo e($loop->index); ?>"
                                                                wire:click="$set('quizAnswers.<?php echo e($question->id); ?>', '<?php echo e($option); ?>')"
                                                                class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition <?php echo e($isSelected ? 'font-bold' : 'border-gray-300 bg-white'); ?>"
                                                                style="<?php echo e($isSelected ? 'border-color: ' . ($campaign->color_primary ?? '#3B82F6') . '; background-color: ' . ($campaign->color_primary ?? '#3B82F6') . '0F;' : ''); ?> <?php echo e(!$isSelected ? '' : 'transition: border-color 0.3s;'); ?>"
                                                                onmouseover="if (!this.querySelector('input').checked) this.style.borderColor = '<?php echo e($campaign->color_primary ?? '#3B82F6'); ?>66'"
                                                                onmouseout="if (!this.querySelector('input').checked) this.style.borderColor = '#D1D5DB'">
                                                                <input type="radio"
                                                                    wire:model="quizAnswers.<?php echo e($question->id); ?>"
                                                                    value="<?php echo e($option); ?>" class="hidden" />
                                                                <div class="flex items-center gap-2 flex-1">
                                                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition <?php echo e($isSelected ? '' : 'border-gray-400'); ?>"
                                                                        style="<?php echo e($isSelected ? 'border-color: ' . ($campaign->color_primary ?? '#3B82F6') . '; background-color: ' . ($campaign->color_primary ?? '#3B82F6') . ';' : ''); ?>">
                                                                        <?php if($isSelected): ?>
                                                                            <div class="w-2 h-2 rounded-full bg-white">
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <span
                                                                        class="text-sm text-gray-900"><?php echo e($option); ?></span>
                                                                </div>
                                                            </label>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php elseif($question->question_type === 'checkbox'): ?>
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2"
                                                        x-data="{
                                                            selected: <?php echo \Illuminate\Support\Js::from(isset($quizAnswers[$question->id]) && is_array($quizAnswers[$question->id]) ? $quizAnswers[$question->id] : [])->toHtml() ?>,
                                                            toggle(value) {
                                                                const index = this.selected.indexOf(value);
                                                                if (index > -1) {
                                                                    this.selected.splice(index, 1);
                                                                } else {
                                                                    this.selected.push(value);
                                                                }
                                                                // Sincroniza com Livewire
                                                                window.livewire.find('<?php echo e($_instance->id); ?>').set('quizAnswers.<?php echo e($question->id); ?>', [...this.selected]);
                                                            },
                                                            isSelected(value) {
                                                                return this.selected.includes(value);
                                                            }
                                                        }"
                                                        wire:key="checkbox-group-<?php echo e($question->id); ?>">
                                                        <?php $__currentLoopData = $question->question_options ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php $optionValue = addslashes($option); ?>
                                                            <div wire:key="checkbox-<?php echo e($question->id); ?>-<?php echo e($loop->index); ?>"
                                                                @click="toggle('<?php echo e($optionValue); ?>')"
                                                                class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
                                                                :class="isSelected('<?php echo e($optionValue); ?>') ? 'font-bold' :
                                                                    'border-gray-300 bg-white'"
                                                                :style="isSelected('<?php echo e($optionValue); ?>') ?
                                                                    'border-color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>; background-color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>0F;' :
                                                                    ''"
                                                                @mouseover="if (!isSelected('<?php echo e($optionValue); ?>')) $el.style.borderColor = '<?php echo e($campaign->color_primary ?? '#3B82F6'); ?>66'"
                                                                @mouseout="if (!isSelected('<?php echo e($optionValue); ?>')) $el.style.borderColor = '#D1D5DB'">
                                                                <input type="checkbox"
                                                                    wire:model="quizAnswers.<?php echo e($question->id); ?>"
                                                                    value="<?php echo e($option); ?>" class="hidden"
                                                                    :checked="isSelected('<?php echo e($optionValue); ?>')" />
                                                                <div class="flex items-center gap-2 flex-1">
                                                                    <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition"
                                                                        :class="isSelected('<?php echo e($optionValue); ?>') ? '' :
                                                                            'border-gray-400'"
                                                                        :style="isSelected('<?php echo e($optionValue); ?>') ?
                                                                            'border-color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>; background-color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>;' :
                                                                            ''">
                                                                        <svg class="w-4 h-4 text-white transition"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24"
                                                                            x-show="isSelected('<?php echo e($optionValue); ?>')"
                                                                            style="display: none;">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="3" d="M5 13l4 4L19 7">
                                                                            </path>
                                                                        </svg>
                                                                    </div>
                                                                    <span class="text-sm"><?php echo e($option); ?></span>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if($question->help_text): ?>
                                                    <p class="text-xs text-gray-600 pt-3 flex items-start gap-1">
                                                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-blue-500"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="italic"><?php echo e($question->help_text); ?></span>
                                                    </p>
                                                <?php endif; ?>

                                                <?php $__errorArgs = ['quiz_' . $question->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <div class="border-t pt-5 mt-5"></div>
                                <?php endif; ?>

                                
                                <div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-3">Valores
                                            Sugestivos</label>
                                        <div class="grid grid-cols-3 md:grid-cols-6 gap-2 md:gap-3">
                                            <button type="button" wire:click="setAmount(50)"
                                                class="px-4 py-3 rounded-full border-2 font-bold text-sm transition-all <?php echo e($amount_total == 5000 ? '' : 'border-gray-300 bg-white text-gray-700'); ?>"
                                                style="<?php echo e($amount_total == 5000 ? 'border-color: ' . ($campaign->color_primary ?? '#3B82F6') . '; background-color: ' . ($campaign->color_primary ?? '#3B82F6') . '0F; color: ' . ($campaign->color_primary ?? '#3B82F6') . ';' : ''); ?>"
                                                onmouseover="if (<?php echo e($amount_total == 5000 ? 'false' : 'true'); ?>) this.style.borderColor = '<?php echo e($campaign->color_primary ?? '#3B82F6'); ?>66'"
                                                onmouseout="if (<?php echo e($amount_total == 5000 ? 'false' : 'true'); ?>) this.style.borderColor = '#D1D5DB'">
                                                R$ 50
                                            </button>
                                            <button type="button" wire:click="setAmount(100)"
                                                class="px-4 py-3 rounded-full border-2 font-bold text-sm transition-all <?php echo e($amount_total == 10000 ? '' : 'border-gray-300 bg-white text-gray-700'); ?>"
                                                style="<?php echo e($amount_total == 10000 ? 'border-color: ' . ($campaign->color_primary ?? '#3B82F6') . '; background-color: ' . ($campaign->color_primary ?? '#3B82F6') . '0F; color: ' . ($campaign->color_primary ?? '#3B82F6') . ';' : ''); ?>"
                                                onmouseover="if (<?php echo e($amount_total == 10000 ? 'false' : 'true'); ?>) this.style.borderColor = '<?php echo e($campaign->color_primary ?? '#3B82F6'); ?>66'"
                                                onmouseout="if (<?php echo e($amount_total == 10000 ? 'false' : 'true'); ?>) this.style.borderColor = '#D1D5DB'">
                                                R$ 100
                                            </button>
                                            <button type="button" wire:click="setAmount(200)"
                                                class="px-4 py-3 rounded-full border-2 font-bold text-sm transition-all <?php echo e($amount_total == 20000 ? '' : 'border-gray-300 bg-white text-gray-700'); ?>"
                                                style="<?php echo e($amount_total == 20000 ? 'border-color: ' . ($campaign->color_primary ?? '#3B82F6') . '; background-color: ' . ($campaign->color_primary ?? '#3B82F6') . '0F; color: ' . ($campaign->color_primary ?? '#3B82F6') . ';' : ''); ?>"
                                                onmouseover="if (<?php echo e($amount_total == 20000 ? 'false' : 'true'); ?>) this.style.borderColor = '<?php echo e($campaign->color_primary ?? '#3B82F6'); ?>66'"
                                                onmouseout="if (<?php echo e($amount_total == 20000 ? 'false' : 'true'); ?>) this.style.borderColor = '#D1D5DB'">
                                                R$ 200
                                            </button>
                                            <button type="button" wire:click="setAmount(250)"
                                                class="px-4 py-3 rounded-full border-2 font-bold text-sm transition-all <?php echo e($amount_total == 25000 ? '' : 'border-gray-300 bg-white text-gray-700'); ?>"
                                                style="<?php echo e($amount_total == 25000 ? 'border-color: ' . ($campaign->color_primary ?? '#3B82F6') . '; background-color: ' . ($campaign->color_primary ?? '#3B82F6') . '0F; color: ' . ($campaign->color_primary ?? '#3B82F6') . ';' : ''); ?>"
                                                onmouseover="if (<?php echo e($amount_total == 25000 ? 'false' : 'true'); ?>) this.style.borderColor = '<?php echo e($campaign->color_primary ?? '#3B82F6'); ?>66'"
                                                onmouseout="if (<?php echo e($amount_total == 25000 ? 'false' : 'true'); ?>) this.style.borderColor = '#D1D5DB'">
                                                R$ 250
                                            </button>
                                            <button type="button" wire:click="setAmount(500)"
                                                class="px-4 py-3 rounded-full border-2 font-bold text-sm transition-all <?php echo e($amount_total == 50000 ? '' : 'border-gray-300 bg-white text-gray-700'); ?>"
                                                style="<?php echo e($amount_total == 50000 ? 'border-color: ' . ($campaign->color_primary ?? '#3B82F6') . '; background-color: ' . ($campaign->color_primary ?? '#3B82F6') . '0F; color: ' . ($campaign->color_primary ?? '#3B82F6') . ';' : ''); ?>"
                                                onmouseover="if (<?php echo e($amount_total == 50000 ? 'false' : 'true'); ?>) this.style.borderColor = '<?php echo e($campaign->color_primary ?? '#3B82F6'); ?>66'"
                                                onmouseout="if (<?php echo e($amount_total == 50000 ? 'false' : 'true'); ?>) this.style.borderColor = '#D1D5DB'">
                                                R$ 500
                                            </button>
                                            <button type="button" wire:click="setAmount(750)"
                                                class="px-4 py-3 rounded-full border-2 font-bold text-sm transition-all <?php echo e($amount_total == 75000 ? '' : 'border-gray-300 bg-white text-gray-700'); ?>"
                                                style="<?php echo e($amount_total == 75000 ? 'border-color: ' . ($campaign->color_primary ?? '#3B82F6') . '; background-color: ' . ($campaign->color_primary ?? '#3B82F6') . '0F; color: ' . ($campaign->color_primary ?? '#3B82F6') . ';' : ''); ?>"
                                                onmouseover="if (<?php echo e($amount_total == 75000 ? 'false' : 'true'); ?>) this.style.borderColor = '<?php echo e($campaign->color_primary ?? '#3B82F6'); ?>66'"
                                                onmouseout="if (<?php echo e($amount_total == 75000 ? 'false' : 'true'); ?>) this.style.borderColor = '#D1D5DB'">
                                                R$ 750
                                            </button>
                                        </div>
                                    </div>

                                    
                                    <?php
                                        $showRecurringOption = (bool) ($campaign->allow_recurring ?? false);
                                        $recurringBlockedReason = null;
                                        $gatewaySlug = $campaign->gateway->pay_gateway_slug ?? '';

                                        if ($is_anonymous) {
                                            $recurringBlockedReason = 'Recorrência indisponível para doações anônimas.';
                                        } elseif (!$campaign->allow_recurring) {
                                            $recurringBlockedReason =
                                                'Recorrência indisponível: habilite a recorrência na campanha.';
                                        } elseif (!$campaign->pay_card_credit) {
                                            $recurringBlockedReason =
                                                'Recorrência indisponível: habilite cartão de crédito na campanha.';
                                        } elseif (
                                            $gatewaySlug === '' ||
                                            !\Illuminate\Support\Str::contains($gatewaySlug, 'safe2pay')
                                        ) {
                                            $recurringBlockedReason =
                                                'Recorrência indisponível: configure o gateway Safe2Pay na campanha.';
                                        }

                                        $recurringAvailable = $recurringBlockedReason === null;
                                        $recurringHelperText = $recurringAvailable
                                            ? 'Cobrança mensal no cartão de crédito.'
                                            : $recurringBlockedReason;
                                    ?>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-start">
                                        <div class="<?php echo e($showRecurringOption ? 'md:col-span-1' : 'md:col-span-3'); ?>">
                                            <div class="campaign-donation-pane">
                                                <label
                                                    class="block text-xs font-semibold text-gray-700 uppercase mb-2">Ou
                                                    Informe Outro Valor *</label>
                                                <div class="campaign-currency-input flex items-stretch focus-within:ring-2 focus-within:border-transparent"
                                                    style="--tw-ring-color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>;"
                                                    x-data="currencyField(<?php if ((object) ('amount_total_input') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('amount_total_input'->value()); ?>')<?php echo e('amount_total_input'->hasModifier('defer') ? '.defer' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('amount_total_input'); ?>')<?php endif; ?>.defer)" x-init="init()">
                                                    <span
                                                        class="campaign-currency-prefix px-4 py-2 text-sm font-semibold text-gray-600 border-r border-gray-200 flex justify-center items-center">
                                                        R$
                                                    </span>
                                                    <input type="text" x-model="display"
                                                        x-on:input="handleInput($event.target.value)"
                                                        inputmode="decimal" pattern="[0-9.,]*" placeholder="0,00"
                                                        maxlength="18"
                                                        class="border-none flex-1 px-6 py-3 text-left text-lg font-bold text-gray-900 placeholder-gray-400 focus:outline-none bg-transparent" />
                                                </div>
                                                <p class="text-[10px] text-gray-500 mt-1">
                                                    Valor mínimo: R$ <?php echo e(toMoney($campaign->amount_min)); ?>

                                                </p>
                                                <?php $__errorArgs = ['amount_total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>

                                        <?php if($showRecurringOption): ?>
                                            <div class="h-full md:col-span-2">
                                                <div class="campaign-donation-pane">
                                                    <label
                                                        class="block text-xs font-semibold text-gray-700 uppercase mb-2">
                                                        Deseja doar de forma recorrente
                                                    </label>

                                                    <div class="campaign-recurring-options">
                                                        <label
                                                            class="campaign-recurring-option flex items-center gap-3 w-full border px-4 py-3.5 text-sm font-semibold transition cursor-pointer <?php echo e($recurringAvailable ? '' : 'opacity-50 cursor-not-allowed'); ?>"
                                                            style="<?php echo e($is_recurring === true ? 'border-color: ' . ($campaign->color_primary ?? '#3B82F6') . '; background-color: ' . ($campaign->color_primary ?? '#3B82F6') . '0F; color: ' . ($campaign->color_primary ?? '#3B82F6') . ';' : 'border-color: #D1D5DB; color: #374151; background-color: #ffffff;'); ?>"
                                                            <?php if($recurringAvailable): ?> wire:click="$set('is_recurring', true)" <?php endif; ?>>
                                                            <input type="radio" name="recurring_choice"
                                                                class="h-5 w-5 text-blue-600 focus:ring-blue-500"
                                                                <?php if($is_recurring === true): ?> checked <?php endif; ?>
                                                                <?php if(!$recurringAvailable): ?> disabled <?php endif; ?> />
                                                            <span class="leading-tight">SIM, todos os meses</span>
                                                        </label>
                                                        <label
                                                            class="campaign-recurring-option flex items-center gap-3 w-full border px-4 py-3.5 text-sm font-semibold transition cursor-pointer"
                                                            style="<?php echo e($is_recurring === false ? 'border-color: #111827; background-color: #111827; color: #ffffff;' : 'border-color: #D1D5DB; color: #374151; background-color: #ffffff;'); ?>"
                                                            wire:click="$set('is_recurring', false)">
                                                            <input type="radio" name="recurring_choice"
                                                                class="h-5 w-5 text-blue-600 focus:ring-blue-500"
                                                                <?php if($is_recurring === false): ?> checked <?php endif; ?> />
                                                            <span class="leading-tight">NÃO, apenas esta</span>
                                                        </label>
                                                    </div>

                                                    <div class="mt-2 text-[11px] text-gray-500">
                                                        Selecionado:
                                                        <?php if($is_recurring === true): ?>
                                                            Recorrente (todo mês, cancele quando quiser)
                                                        <?php elseif($is_recurring === false): ?>
                                                            Única (só esta vez)
                                                        <?php else: ?>
                                                            Selecione uma opção
                                                        <?php endif; ?>
                                                    </div>

                                                    <?php $__errorArgs = ['is_recurring'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="mt-2 text-xs text-red-600"><?php echo e($message); ?>

                                                        </div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                                    <?php if(!$recurringAvailable): ?>
                                                        <div class="mt-2 text-xs text-red-600">
                                                            <?php echo e($recurringHelperText); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <div class="flex justify-end pt-4 border-t">
                                    <button wire:click="proximaEtapa" wire:loading.attr="disabled"
                                        class="w-full md:w-auto px-8 py-4 rounded-lg font-bold text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all text-lg flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        style="background-color: var(--color-primary)">
                                        <span wire:loading.remove>Continuar</span>
                                        <svg wire:loading.remove class="hidden md:block w-5 h-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                        <svg wire:loading class="animate-spin h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span wire:loading>Processando...</span>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>

                        
                        <?php if($step === 2): ?>

                            <?php if (isset($component)) { $__componentOriginal522a59481d8bfd4d44478643bc3270fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal522a59481d8bfd4d44478643bc3270fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.validation-errors','data' => ['class' => 'mb-4','noLoading' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','no_loading' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $attributes = $__attributesOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $component = $__componentOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__componentOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>

                            <?php
                                $ddiList = listDdi();
                            ?>

                            <div class="space-y-6 campaign-step-card"
                                wire:key="step-2-form-<?php echo e($order->id ?? 'new'); ?>">
                                
                                <div class="flex flex-col md:flex-row justify-between items-center gap-2">
                                    <div>
                                        <h2 class="text-center md:text-left text-2xl font-bold text-gray-900 mb-2">Seus
                                            Dados</h2>
                                        <p class="text-center md:text-left text-sm text-gray-600 pb-2">Precisamos de
                                            alguns dados para processar sua contribuição</p>
                                    </div>
                                    <?php if($amount_total ?? false): ?>
                                        <div class="w-full md:w-auto flex justify-center md:justify-end">
                                            <div class="campaign-amount-shell"
                                                style="
                                                    --amount-primary: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>;
                                                    --amount-secondary: <?php echo e($campaign->color_secondary ?? '#10B981'); ?>;
                                                    --amount-primary-soft: <?php echo e(($campaign->color_primary ?? '#3B82F6') . '22'); ?>;
                                                    --amount-secondary-soft: <?php echo e(($campaign->color_secondary ?? '#10B981') . '26'); ?>;
                                                ">
                                                <div class="campaign-amount-pill">
                                                    <span class="campaign-amount-label">Contribuição</span>
                                                    <span
                                                        class="campaign-amount-value"><?php echo e(toMoney($amount_total ?? 0, 'R$ ')); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                
                                <?php if($campaign->allow_anonymous): ?>
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <label class="block text-sm font-semibold text-gray-800 mb-1">
                                                    Fazer doação anônima
                                                </label>
                                                <p class="text-xs text-gray-600">Sua contribuição será registrada sem
                                                    exibir seu nome publicamente</p>
                                            </div>
                                            <?php if (isset($component)) { $__componentOriginale45caf11f55ea97b78a13a84cea67cba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale45caf11f55ea97b78a13a84cea67cba = $attributes; } ?>
<?php $component = WireUi\View\Components\Toggle::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Toggle::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'is_anonymous']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale45caf11f55ea97b78a13a84cea67cba)): ?>
<?php $attributes = $__attributesOriginale45caf11f55ea97b78a13a84cea67cba; ?>
<?php unset($__attributesOriginale45caf11f55ea97b78a13a84cea67cba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale45caf11f55ea97b78a13a84cea67cba)): ?>
<?php $component = $__componentOriginale45caf11f55ea97b78a13a84cea67cba; ?>
<?php unset($__componentOriginale45caf11f55ea97b78a13a84cea67cba); ?>
<?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                
                                <?php if(!($is_anonymous ?? false)): ?>
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-800 uppercase mb-4 pb-2 border-b">Seus
                                            Dados</h3>

                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start mb-4">
                                            
                                            <div class="col-span-12 md:col-span-2">
                                                <div class="grid grid-cols-12 items-start gap-2">

                                                    
                                                    <div class="col-span-12 md:col-span-4">
                                                        <label
                                                            class="block text-xs font-semibold text-gray-700 uppercase mb-2">País
                                                            *</label>
                                                        <select wire:model="buyer_contact_country"
                                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                            <?php $__currentLoopData = $ddiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ddiValue => $ddiLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($ddiValue); ?>">
                                                                    <?php echo e($ddiLabel); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>

                                                    
                                                    <?php if($buyer_contact_country === '55'): ?>
                                                        <div class="col-span-4 md:col-span-3">
                                                            <label
                                                                class="block text-xs font-semibold text-gray-700 uppercase mb-2">DDD
                                                                *</label>
                                                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'tel','inputmode' => 'numeric','pattern' => '[0-9]*','wire:model' => 'buyer_contact_ddd','placeholder' => 'DDD','maxlength' => '3','oninput' => 'this.value = this.value.replace(/[^0-9]/g, \'\')','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                        </div>
                                                        <div class="col-span-8 md:col-span-5">
                                                            <label
                                                                class="block text-xs font-semibold text-gray-700 uppercase mb-2">Telefone
                                                                *</label>
                                                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'tel','inputmode' => 'numeric','pattern' => '[0-9]*','wire:model' => 'buyer_contact_num','placeholder' => 'Telefone','maxlength' => '15','oninput' => 'this.value = this.value.replace(/[^0-9]/g, \'\')','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="col-span-12 md:col-span-8">
                                                            <label
                                                                class="block text-xs font-semibold text-gray-700 uppercase mb-2">Telefone
                                                                *</label>
                                                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'tel','inputmode' => 'numeric','pattern' => '[0-9]*','wire:model' => 'buyer_contact_num','placeholder' => 'Telefone','maxlength' => '15','oninput' => 'this.value = this.value.replace(/[^0-9]/g, \'\')','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            
                                            <div class="col-span-12 md:col-span-1">
                                                <label
                                                    class="block text-xs font-semibold text-gray-700 uppercase mb-2">Data
                                                    de Nascimento <span class="text-red-600">*</span></label>
                                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','inputmode' => 'numeric','pattern' => '[0-9]*','wire:model' => 'buyer_birth_date','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                            </div>
                                        </div>

                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-700 uppercase mb-2">Nome
                                                    Completo *</label>
                                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'buyer_name','placeholder' => 'Seu nome completo','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                            </div>

                                            
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-700 uppercase mb-2">
                                                    E-mail <span class="text-red-600">*</span>
                                                </label>
                                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'email','wire:model' => 'buyer_email','placeholder' => 'seu@email.com','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                            </div>

                                            <?php if($campaign->require_doc): ?>
                                                <div>
                                                    <label
                                                        class="block text-xs font-semibold text-gray-700 uppercase mb-2">
                                                        CPF/CNPJ<span class="text-red-600">*</span>
                                                    </label>
                                                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'tel','inputmode' => 'numeric','pattern' => '[0-9]*','wire:model' => 'buyer_doc_num','placeholder' => 'Somente números','maxlength' => '20','oninput' => 'this.value = this.value.replace(/[^0-9]/g, \'\')','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                
                                <div
                                    class="flex flex-col-reverse md:flex-row justify-between gap-3 md:gap-0 pt-4 border-t">
                                    <button wire:click="etapaAnterior"
                                        class="w-full md:w-auto px-6 py-3 rounded-lg font-semibold text-gray-700 bg-white border-0 md:border-2 border-gray-300 hover:bg-gray-50 transition flex items-center justify-center gap-2">
                                        <svg class="hidden md:block w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                                        </svg>
                                        Voltar
                                    </button>
                                    <button wire:click="proximaEtapa" wire:loading.attr="disabled"
                                        class="w-full md:w-auto px-8 py-4 rounded-lg font-bold text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all text-lg flex flex-nowrap items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        style="background-color: var(--color-primary)">
                                        <span wire:loading.remove wire:target="proximaEtapa"
                                            class="whitespace-nowrap">Ir para Pagamento</span>
                                        <svg wire:loading.remove wire:target="proximaEtapa"
                                            class="hidden md:block w-5 h-5 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                        <svg wire:loading wire:target="proximaEtapa"
                                            class="animate-spin h-5 w-5 text-white flex-shrink-0"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span wire:loading wire:target="proximaEtapa"
                                            class="whitespace-nowrap">Processando...</span>
                                    </button>
                                </div>
                        <?php endif; ?>

                        
                        <?php if($step === 3): ?>

                            <?php if (isset($component)) { $__componentOriginal522a59481d8bfd4d44478643bc3270fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal522a59481d8bfd4d44478643bc3270fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.validation-errors','data' => ['class' => 'mb-4','noLoading' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','no_loading' => 'false']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $attributes = $__attributesOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $component = $__componentOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__componentOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>

                            
                            <div class="px-4"><?php echo $__env->make('_includes.alertas_forma_pagamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>

                            <?php if($order ?? false): ?>
                                <script>
                                    (function() {
                                        try {
                                            const orderId = '<?php echo e($order->id); ?>';
                                            if (!orderId || orderId === 'null' || orderId === '') return;

                                            const currentPath = window.location.pathname;
                                            const pathParts = currentPath.split('/').filter(p => p);

                                            // Valida formato: /{customer}/{campaign}/{order?}
                                            // Nova estrutura sem prefixo /campanha/
                                            if (pathParts.length < 2) return;

                                            // Se já tem order_id na URL, não faz nada
                                            if (pathParts.length === 3 && pathParts[2] === orderId) return;

                                            // Remove order_id existente se houver
                                            if (pathParts.length === 3) {
                                                pathParts.pop();
                                            }

                                            // Adiciona o order_id atual
                                            const newPath = '/' + pathParts.join('/') + '/' + orderId;

                                            // Atualiza URL sem recarregar
                                            if (currentPath !== newPath) {
                                                window.history.replaceState({}, '', newPath);
                                            }
                                        } catch (e) {
                                            // Silenciosamente ignora erros
                                        }
                                    })();
                                </script>
                            <?php endif; ?>

                            <div class="space-y-6 campaign-step-card"
                                <?php if($order ?? false): ?> data-order-id="<?php echo e($order->id); ?>" <?php endif; ?>
                                wire:key="payment-step-<?php echo e($order->id ?? 'new'); ?>">

                                <div
                                    class="flex flex-col md:flex-row justify-start md:justify-between items-start gap-x-4 gap-y-2">

                                    <div class="w-full md:w-auto">
                                        <h2
                                            class="text-3xl md:text-2xl text-center md:text-left font-bold text-gray-900 mb-2">
                                            Pagamento</h2>
                                        <p class="text-sm text-center md:text-left text-gray-600">Selecione o método e
                                            finalize sua contribuição</p>
                                    </div>

                                    
                                    <div
                                        class="w-full md:w-auto flex flex-col md:flex-row justify-center md:justify-end items-center gap-2 text-xs text-gray-500 gap-y-2">
                                        <div>Transação processada por</div>
                                        <div
                                            class="font-bold text-blue-600 flex items-center gap-1 px-3 py-1 bg-blue-50 rounded-full">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            SAFE2PAY
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="py-6 border-t border-b">

                                    
                                    <div class="mb-4 pb-4 border-b">
                                        <div class="text-base font-light text-gray-600">Campanha</div>
                                        <div class="text-xl font-bold text-gray-900 mb-2 uppercase">
                                            <?php echo e($campaign->name); ?></div>
                                        <?php if($campaign->description): ?>
                                            <div class="text-sm text-gray-700 line-clamp-3 capitalize">
                                                <?php echo e(strip_tags($campaign->description)); ?>

                                            </div>
                                        <?php elseif($campaign->about): ?>
                                            <div class="text-sm text-gray-700 line-clamp-3">
                                                <?php echo e(strip_tags($campaign->about)); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <h3 class="text-base font-light text-gray-600 mb-4">Resumo da Contribuição</h3>
                                    <div class="space-y-2">

                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700">Nome</span>
                                            <span class="font-semibold uppercase"><?php echo e($buyer_name); ?></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700">Telefone</span>
                                            <span class="font-semibold uppercase"><?php echo e($buyer_contact_country); ?>

                                                <?php echo e($buyer_contact_ddd ? $buyer_contact_ddd . ' ' : null); ?><?php echo e($buyer_contact_num); ?></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700">E-mail</span>
                                            <span class="font-semibold"><?php echo e($buyer_email); ?></span>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xl font-bold text-gray-900">Total</span>
                                            <span class="text-3xl font-bold"
                                                style="color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>">
                                                <?php echo e(toMoney($amount_total ?? 0, 'R$ ')); ?>

                                            </span>
                                        </div>
                                        <?php if($payment_method === 'credit_card' && ($pay_installments_number ?? 1) > 1): ?>
                                            <?php
                                                // amount_total está em centavos, então divide por 100 para obter reais, depois divide pelas parcelas
                                                $installmentValue =
                                                    ($amount_total ?? 0) / ($pay_installments_number ?? 1);
                                            ?>
                                            <div class="flex justify-end mt-1">
                                                <span class="text-xs text-gray-500">
                                                    <?php echo e($pay_installments_number); ?>x de
                                                    <?php echo e(toMoney($installmentValue, 'R$ ')); ?>

                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <div class="flex-none md:flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">Escolha a Forma de Pagamento</h3>
                                    <div class="text-xs text-gray-400 py-2">
                                        <?php if($campaign->pay_sandbox): ?>
                                            <div
                                                class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-1 text-center">
                                                <span class="text-sm font-bold text-yellow-800">MODO TESTE</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <?php if($is_anonymous): ?>
                                    <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4 mb-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-blue-800">Doação Anônima</p>
                                                <p class="text-xs text-blue-700 mt-1">Para doações anônimas, apenas a
                                                    forma de pagamento <strong>PIX</strong> está disponível.</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php
                                    $isRecurringPayment = (bool) $is_recurring;
                                    $hasRecurringMethod = (bool) ($campaign->pay_card_credit ?? false);
                                    $hasAnyMethod =
                                        (bool) (($campaign->pay_pix ?? false) ||
                                            ($campaign->pay_boleto ?? false) ||
                                            ($campaign->pay_card_credit ?? false));
                                ?>
                                <?php if($isRecurringPayment && !$hasRecurringMethod): ?>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Recorrência indisponível</strong>
                                        <div class="text-xs text-gray-400">
                                            <span>Esta campanha não possui cartão de crédito habilitado.</span>
                                        </div>
                                        </p>
                                    </div>
                                <?php elseif(!$isRecurringPayment && !$hasAnyMethod): ?>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Nenhuma forma de pagamento configurada</strong>
                                        <div class="text-xs text-gray-400">
                                            <span>Contate o administrador da campanha para configurar as formas de
                                                pagamento</span>
                                        </div>
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="space-y-3" id="payment-methods-container">

                                        
                                        <div wire:loading wire:target="payment_method"
                                            class="fixed inset-0 bg-black bg-opacity-30 z-40 flex items-center justify-center"
                                            style="display: none;">
                                            <div
                                                class="bg-white rounded-lg shadow-xl p-6 flex flex-col items-center gap-4 min-w-[200px]">
                                                <svg class="animate-spin h-8 w-8 text-blue-600"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                <span class="text-sm font-semibold text-gray-700">Carregando forma de
                                                    pagamento...</span>
                                            </div>
                                        </div>

                                        
                                        <?php if(!$isRecurringPayment && ($campaign->pay_pix ?? false)): ?>
                                            <?php
                                                $currentPaymentPix = $campaignPayment ?? null;

                                                // Verifica se tem PIX válido (não expirado e com dados)
                                                $isPixExpiredHere =
                                                    $currentPaymentPix &&
                                                    ($currentPaymentPix->status === 'pix_expired' ||
                                                        ($currentPaymentPix->pay_pix_expires_at &&
                                                            \Carbon\Carbon::parse(
                                                                $currentPaymentPix->pay_pix_expires_at,
                                                            )->isPast()));
                                                $hasPixGenerated =
                                                    $currentPaymentPix &&
                                                    !$isPixExpiredHere &&
                                                    in_array($currentPaymentPix->status ?? '', [
                                                        'pending',
                                                        'processing',
                                                        'autorizado',
                                                        'sending_provider',
                                                    ]) &&
                                                    ($currentPaymentPix->pay_pix_qr_code_url ||
                                                        $currentPaymentPix->pay_pix_key ||
                                                        ($payment_result['pay_pix_qr_code_url'] ?? false) ||
                                                        ($payment_result['pay_pix_key'] ?? false));

                                                // Abre automaticamente se tem PIX gerado ou se payment_method é pix
                                                $shouldOpenPix = $hasPixGenerated || $payment_method === 'pix';
                                                $shouldOpenPix = $payment_method === 'pix';

                                                // Para compatibilidade
                                                $payment = $currentPaymentPix;
                                            ?>
                                            <div class="border-2 rounded-lg overflow-hidden transition relative <?php echo e($shouldOpenPix ? 'border-blue-500' : 'border-gray-300'); ?>"
                                                x-data="{ isOpen: <?php echo e($shouldOpenPix ? 'true' : 'false'); ?> }" x-init="isOpen = <?php echo e($shouldOpenPix ? 'true' : 'false'); ?>"
                                                x-effect="if ($wire.payment_method === 'pix') { isOpen = true; } else if ($wire.payment_method !== 'pix' && $wire.payment_method !== '') { isOpen = false; }">
                                                

                                                <label
                                                    class="flex items-center gap-4 p-4 cursor-pointer hover:bg-gray-50 transition relative <?php echo e($shouldOpenPix ? 'bg-blue-50 font-bold' : ''); ?>"
                                                    wire:loading.attr="disabled" wire:target="payment_method"
                                                    @click="$wire.set('payment_method', 'pix'); isOpen = true;">
                                                    <input type="radio" wire:model="payment_method" value="pix"
                                                        <?php if($shouldOpenPix): ?> checked <?php endif; ?>
                                                        class="text-blue-600 focus:ring-blue-500" />
                                                    <div class="flex items-center gap-3 flex-1">
                                                        <svg class="w-8 h-8 text-green-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                                            </path>
                                                        </svg>
                                                        <div>
                                                            <div class="font-semibold text-gray-900">PIX</div>
                                                            <div class="text-xs text-gray-600">Instantâneo via QR Code
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>

                                                
                                                <div x-show="isOpen"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                    class="border-t border-gray-200">
                                                    <div class="p-6 bg-white space-y-4">
                                                        <?php
                                                            $currentPayment = $campaignPayment ?? null;
                                                            $isPixExpired =
                                                                $currentPayment &&
                                                                $currentPayment->status === 'pix_expired';
                                                            $hasPixGenerated =
                                                                $currentPayment &&
                                                                !$isPixExpired &&
                                                                ($currentPayment->pay_pix_qr_code_url ||
                                                                    $currentPayment->pay_pix_key ||
                                                                    ($payment_result['pay_pix_qr_code_url'] ?? false) ||
                                                                    ($payment_result['pay_pix_key'] ?? false));
                                                            // Para compatibilidade, mantém $payment apontando para o payment correto
                                                            $payment = $currentPayment;
                                                        ?>

                                                        <?php if(!$hasPixGenerated || $isPixExpired): ?>
                                                            <h3 class="text-lg font-bold text-gray-900">Dados para PIX
                                                            </h3>
                                                            <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['label' => 'CPF do Pagador *','mask' => '[\'###.###.###-##\',\'##.###.###/####-##\']'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => '000.000.000-00','wire:model.defer' => 'pix_cpf','wire:key' => 'pix_cpf_maskable','inputmode' => 'numeric','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $attributes = $__attributesOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__attributesOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $component = $__componentOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__componentOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>

                                                            
                                                            <div class="flex justify-between gap-3 pt-4 border-t">
                                                                <div class="px-4"></div>
                                                                <div>
                                                                    <button wire:click="processarPagamento"
                                                                        wire:loading.attr="disabled"
                                                                        class="px-8 py-3 rounded-lg font-bold text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                                                        style="background-color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>">
                                                                        <span wire:loading.remove
                                                                            wire:target="processarPagamento"
                                                                            class="whitespace-nowrap">Gerar PIX</span>
                                                                        <svg wire:loading
                                                                            wire:target="processarPagamento"
                                                                            class="animate-spin h-5 w-5 text-white flex-shrink-0"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24">
                                                                            <circle class="opacity-25" cx="12"
                                                                                cy="12" r="10"
                                                                                stroke="currentColor"
                                                                                stroke-width="4"></circle>
                                                                            <path class="opacity-75"
                                                                                fill="currentColor"
                                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                            </path>
                                                                        </svg>
                                                                        <span wire:loading
                                                                            wire:target="processarPagamento"
                                                                            class="whitespace-nowrap">Processando...</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="space-y-2">
                                                                
                                                                <?php if(
                                                                    $currentPayment &&
                                                                        !in_array($currentPayment->status ?? 'pending', ['paid', 'approved', 'autorizado', 'captured', 'pix_expired'])): ?>
                                                                    <div wire:poll.10s="validarPagamento(false)"
                                                                        class="hidden"></div>
                                                                <?php endif; ?>
                                                                <div
                                                                    class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                                                    <?php if($payment->pay_pix_qr_code_url ?? ($payment_result['pay_pix_qr_code_url'] ?? false)): ?>
                                                                        <div class="md:col-span-1 flex justify-center">
                                                                            <img src="<?php echo e($payment->pay_pix_qr_code_url ?? $payment_result['pay_pix_qr_code_url']); ?>"
                                                                                alt="QR Code PIX"
                                                                                class="w-auto md:w-48">
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="md:col-span-2 space-y-3">
                                                                        <h3 class="text-lg font-bold text-gray-900">PIX
                                                                            Gerado, pague para concluir!</h3>
                                                                        <div>
                                                                            <div
                                                                                class="text-xs text-gray-500 uppercase font-semibold mb-1">
                                                                                Código PIX</div>
                                                                            <div
                                                                                class="p-2 bg-gray-50 border rounded font-mono text-sm break-all">
                                                                                <?php echo e($payment->pay_pix_key ?? ($payment_result['pay_pix_key'] ?? '-')); ?>

                                                                            </div>
                                                                        </div>
                                                                        <button type="button"
                                                                            onclick="navigator.clipboard.writeText('<?php echo e($payment->pay_pix_key ?? ($payment_result['pay_pix_key'] ?? '')); ?>').then(() => { alert('Código PIX copiado!'); }).catch(() => { alert('Erro ao copiar. Tente novamente.'); });"
                                                                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded transition-colors flex items-center justify-center gap-2">
                                                                            <svg class="w-4 h-4" fill="none"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                                </path>
                                                                            </svg>
                                                                            COPIAR CHAVE PIX
                                                                        </button>
                                                                        <button type="button"
                                                                            wire:click="validarPagamento(true)"
                                                                            wire:target="validarPagamento"
                                                                            wire:loading.attr="disabled"
                                                                            class="w-full px-4 py-2 border-2 border-blue-600 hover:bg-blue-50 text-blue-600 text-sm font-semibold rounded transition-colors flex items-center justify-center gap-2 disabled:opacity-70">
                                                                            <div class="flex items-center gap-2"
                                                                                wire:loading.remove
                                                                                wire:target="validarPagamento"
                                                                                wire:loading.class="hidden">
                                                                                <div>VALIDAR PAGAMENTO</div>
                                                                            </div>
                                                                            <div class="flex items-center gap-2 hidden pt-0.5 pb-0.5"
                                                                                wire:loading
                                                                                wire:target="validarPagamento"
                                                                                wire:loading.class.remove="hidden">
                                                                                <svg class="animate-spin h-4 w-4"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none"
                                                                                    viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25"
                                                                                        cx="12" cy="12"
                                                                                        r="10" stroke="currentColor"
                                                                                        stroke-width="4"></circle>
                                                                                    <path class="opacity-75"
                                                                                        fill="currentColor"
                                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                                    </path>
                                                                                </svg>
                                                                            </div>
                                                                        </button>
                                                                        <?php if($payment->pay_pix_expires_at ?? ($payment_result['pay_pix_expires_at'] ?? false)): ?>
                                                                            <?php
                                                                                $pixExpiresAt = \Carbon\Carbon::parse(
                                                                                    $payment->pay_pix_expires_at ??
                                                                                        $payment_result[
                                                                                            'pay_pix_expires_at'
                                                                                        ],
                                                                                );
                                                                                $minutesRemaining = now()->diffInSeconds(
                                                                                    $pixExpiresAt,
                                                                                    false,
                                                                                );
                                                                            ?>
                                                                            
                                                                            <?php if($minutesRemaining > 0 && $minutesRemaining <= 300): ?>
                                                                                <div
                                                                                    class="text-xs text-orange-600 font-semibold flex items-center gap-1">
                                                                                    <svg class="w-4 h-4"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                                        </path>
                                                                                    </svg>
                                                                                    Válido até:
                                                                                    <?php echo e($pixExpiresAt->format('d/m/Y H:i')); ?>

                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="px-4"><?php echo $__env->make('_includes.alertas_forma_pagamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        
                                        <?php if(!$isRecurringPayment && ($campaign->pay_pix_direto ?? false)): ?>
                                            <?php
                                                $currentPaymentPixDireto = $campaignPayment ?? null;

                                                // Verifica se PIX direto expirou
                                                $isPixDiretoExpired =
                                                    $currentPaymentPixDireto &&
                                                    ($currentPaymentPixDireto->status === 'pix_expired' ||
                                                        ($currentPaymentPixDireto->pay_pix_expires_at &&
                                                            \Carbon\Carbon::parse(
                                                                $currentPaymentPixDireto->pay_pix_expires_at,
                                                            )->isPast()));

                                                $hasPixDiretoGenerated =
                                                    !$isPixDiretoExpired &&
                                                    $currentPaymentPixDireto &&
                                                    ($currentPaymentPixDireto->pay_pix_qr_code_url ||
                                                        $currentPaymentPixDireto->pay_pix_key);
                                                $shouldOpenPixDireto =
                                                    $payment_method === 'pix_direto' || $hasPixDiretoGenerated;
                                            ?>

                                            <div class="border-2 rounded-lg overflow-hidden transition relative <?php echo e($payment_method === 'pix_direto' || $hasPixDiretoGenerated ? 'border-purple-500' : 'border-gray-300'); ?>"
                                                x-data="{ isOpen: <?php echo e($shouldOpenPixDireto ? 'true' : 'false'); ?> }"
                                                x-effect="isOpen = ($wire.payment_method === 'pix_direto')">

                                                <label
                                                    class="flex items-center gap-4 p-4 cursor-pointer hover:bg-gray-50 transition <?php echo e($payment_method === 'pix_direto' || $hasPixDiretoGenerated ? 'bg-purple-50 font-bold' : ''); ?>"
                                                    @click="$wire.set('payment_method', 'pix_direto')">
                                                    <input type="radio" wire:model="payment_method"
                                                        value="pix_direto" class="text-purple-600" />
                                                    <div class="flex items-center gap-3 flex-1">
                                                        <svg class="w-8 h-8 text-purple-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                        </svg>
                                                        <div>
                                                            <div class="font-semibold text-gray-900">PIX Direto</div>
                                                            <div class="text-xs text-gray-600">PIX estático -
                                                                Instantâneo</div>
                                                        </div>
                                                    </div>
                                                </label>

                                                
                                                <div x-show="isOpen"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                    class="border-t border-gray-200">
                                                    <div class="p-6 bg-white space-y-4">
                                                        <?php if($hasPixDiretoGenerated): ?>
                                                            
                                                            <div class="text-center space-y-4">
                                                                <?php if($currentPaymentPixDireto->pay_pix_qr_code_url): ?>
                                                                    <div class="flex justify-center">
                                                                        <img src="<?php echo e($currentPaymentPixDireto->pay_pix_qr_code_url); ?>"
                                                                            alt="QR Code PIX"
                                                                            class="w-64 h-64 border-4 border-purple-500 rounded-lg shadow-lg">
                                                                    </div>
                                                                <?php endif; ?>

                                                                <?php if($currentPaymentPixDireto->pay_pix_key): ?>
                                                                    <div>
                                                                        <div
                                                                            class="text-sm font-semibold text-gray-700 mb-2">
                                                                            Código PIX Copia e Cola:</div>
                                                                        <div class="relative">
                                                                            <input type="text"
                                                                                value="<?php echo e($currentPaymentPixDireto->pay_pix_key); ?>"
                                                                                readonly
                                                                                class="w-full px-4 py-2 pr-20 border border-gray-300 rounded-lg text-xs font-mono bg-gray-50"
                                                                                id="pix-direto-key">
                                                                            <button
                                                                                onclick="
                                                                                navigator.clipboard.writeText('<?php echo e($currentPaymentPixDireto->pay_pix_key); ?>');
                                                                                this.innerText = 'Copiado!';
                                                                                setTimeout(() => this.innerText = 'Copiar', 2000);
                                                                            "
                                                                                class="absolute right-2 top-2 px-3 py-1 bg-purple-600 text-white text-xs rounded hover:bg-purple-700 transition">
                                                                                Copiar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <div
                                                                    class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-sm text-purple-800">
                                                                    <strong>✨ PIX Direto gerado!</strong><br>
                                                                    Escaneie o QR Code ou copie o código acima para
                                                                    pagar instantaneamente.
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            
                                                            <div class="text-center space-y-4">
                                                                <div class="flex items-center justify-center mb-4">
                                                                    <div class="bg-purple-100 p-4 rounded-full">
                                                                        <svg class="w-12 h-12 text-purple-600"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                                <p class="text-sm text-gray-600">
                                                                    O <strong>PIX Direto</strong> gera um QR Code
                                                                    estático que pode ser pago a qualquer momento.<br>
                                                                    Pagamento instantâneo e seguro.
                                                                </p>
                                                                <button wire:click="processPayment"
                                                                    wire:loading.attr="disabled"
                                                                    class="w-full px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 disabled:opacity-50 transition">
                                                                    <span wire:loading.remove
                                                                        wire:target="processPayment">Gerar PIX
                                                                        Direto</span>
                                                                    <span wire:loading wire:target="processPayment"
                                                                        class="flex items-center justify-center gap-2">
                                                                        <svg class="animate-spin h-5 w-5"
                                                                            viewBox="0 0 24 24">
                                                                            <circle class="opacity-25" cx="12"
                                                                                cy="12" r="10"
                                                                                stroke="currentColor" stroke-width="4"
                                                                                fill="none"></circle>
                                                                            <path class="opacity-75"
                                                                                fill="currentColor"
                                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                            </path>
                                                                        </svg>
                                                                        Gerando...
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="px-4"><?php echo $__env->make('_includes.alertas_forma_pagamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        
                                        <?php if(($campaign->pay_card_credit ?? false) && !$is_anonymous): ?>
                                            <div class="border-2 rounded-lg overflow-hidden transition relative <?php echo e($payment_method === 'credit_card' ? 'border-blue-500' : 'border-gray-300'); ?>"
                                                x-data="{ isOpen: <?php if ((object) ('payment_method') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('payment_method'->value()); ?>')<?php echo e('payment_method'->hasModifier('defer') ? '.defer' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($_instance->id); ?>').entangle('<?php echo e('payment_method'); ?>')<?php endif; ?>.live === 'credit_card' }"
                                                x-effect="isOpen = $wire.payment_method === 'credit_card'">
                                                

                                                <label
                                                    class="flex items-center gap-4 p-4 cursor-pointer hover:bg-gray-50 transition relative <?php echo e($payment_method === 'credit_card' ? 'bg-blue-50 font-bold' : ''); ?>"
                                                    wire:loading.attr="disabled" wire:target="payment_method"
                                                    @click="$wire.set('payment_method', 'credit_card')">
                                                    <input type="radio" wire:model="payment_method"
                                                        value="credit_card"
                                                        class="text-blue-600 focus:ring-blue-500" />
                                                    <div class="flex items-center gap-3 flex-1">
                                                        <svg class="w-8 h-8 text-blue-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                            </path>
                                                        </svg>
                                                        <div>
                                                            <div class="font-semibold text-gray-900">Cartão de Crédito
                                                            </div>
                                                            <div class="text-xs text-gray-600">
                                                                <?php if($is_recurring === true): ?>
                                                                    Recorrência mensal • 1x
                                                                <?php else: ?>
                                                                    Parcelamento em até
                                                                    <?php echo e($campaign->pay_card_credit_installment_max ?? 1); ?>x
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>

                                                
                                                <div x-show="isOpen"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                    class="border-t border-gray-200">
                                                    <div class="p-6 bg-white space-y-4">
                                                        <h3 class="text-lg font-bold text-gray-900">Dados do Cartão
                                                        </h3>

                                                        <div class="grid grid-cols-6 gap-4">
                                                            <div class="col-span-full md:col-span-3">
                                                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Nome no Cartão *'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'card_credit_nome','placeholder' => 'IMPRESSO NO CARTÃO','class' => 'uppercase']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                            </div>
                                                            <div class="col-span-full md:col-span-3">
                                                                <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['label' => 'Número do Cartão *','mask' => '[\'#### #### #### ####\',\'#### #### #### #### ###\']'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => '0000 0000 0000 0000','wire:model.defer' => 'card_credit_num','wire:key' => 'card_credit_num_maskable','inputmode' => 'numeric','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $attributes = $__attributesOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__attributesOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $component = $__componentOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__componentOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
                                                            </div>
                                                            <div class="col-span-full md:col-span-3">
                                                                <label
                                                                    class="block text-base font-light uppercase mb-1">Validade
                                                                    *</label>
                                                                <div class="flex gap-2 w-full">
                                                                    <div class="w-1/2">
                                                                        <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'card_credit_validade_mm','class' => 'w-full rounded-none']); ?>
                                                                            <option value="">MM</option>
                                                                            <?php for($i = 1; $i <= 12; $i++): ?>
                                                                                <option
                                                                                    value="<?php echo e(str_pad($i, 2, '0', STR_PAD_LEFT)); ?>">
                                                                                    <?php echo e(str_pad($i, 2, '0', STR_PAD_LEFT)); ?>

                                                                                </option>
                                                                            <?php endfor; ?>
                                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $attributes = $__attributesOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__attributesOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $component = $__componentOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__componentOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>

                                                                    </div>
                                                                    <div class="w-1/2">
                                                                        <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'card_credit_validade_aaaa','class' => 'w-full rounded-none']); ?>
                                                                            <option value="">AAAA</option>
                                                                            <?php for($i = date('Y'); $i <= date('Y') + 10; $i++): ?>
                                                                                <option value="<?php echo e($i); ?>">
                                                                                    <?php echo e($i); ?></option>
                                                                            <?php endfor; ?>
                                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $attributes = $__attributesOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__attributesOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $component = $__componentOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__componentOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <?php $__errorArgs = ['card_credit_validade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <p class="mt-2 text-sm text-negative-600">
                                                                        <?php echo e($message); ?></p>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>

                                                            <div class="col-span-3 md:col-span-1">
                                                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'CVV *'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'card_credit_cvv','placeholder' => '000','type' => 'tel','inputmode' => 'numeric','pattern' => '[0-9]*','oninput' => 'this.value = this.value.replace(/[^0-9]/g, \'\').slice(0, 4)','maxlength' => '4','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                            </div>

                                                            <div class="col-span-full md:col-span-2">
                                                                <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['label' => 'CPF / CNPJ do Titular *','mask' => '[\'###.###.###-##\',\'##.###.###/####-##\']'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => '000.000.000-00','wire:model.defer' => 'card_credit_cpf','wire:key' => 'card_credit_cpf_maskable','inputmode' => 'numeric','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $attributes = $__attributesOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__attributesOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $component = $__componentOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__componentOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
                                                            </div>

                                                            <div class="col-span-full md:col-span-3">
                                                                <?php if($is_recurring): ?>
                                                                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Parcelas *'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => '1x - À vista','readonly' => true,'class' => 'rounded-none bg-gray-50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $attributes = $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e)): ?>
<?php $component = $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e; ?>
<?php unset($__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e); ?>
<?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['label' => 'Parcelas *'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'pay_installments_number','class' => 'rounded-none','wire:loading.class' => 'opacity-50']); ?>
                                                                        <?php if($payment_method === 'credit_card'): ?>
                                                                            <option value="">Selecione...
                                                                            </option>
                                                                            <?php if(!empty($available_installments) && is_array($available_installments) && count($available_installments) > 0): ?>
                                                                                <?php $__currentLoopData = $available_installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <?php if(is_array($installment) && isset($installment['installments'])): ?>
                                                                                        <option
                                                                                            value="<?php echo e($installment['installments']); ?>">
                                                                                            <?php echo e($installment['label'] ?? $installment['installments'] . 'x'); ?>

                                                                                        </option>
                                                                                    <?php endif; ?>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php else: ?>
                                                                                <option value="" disabled>
                                                                                    Calculando parcelas...</option>
                                                                                <?php for($i = 1; $i <= ($campaign->pay_card_credit_installment_max ?? 1); $i++): ?>
                                                                                    <option
                                                                                        value="<?php echo e($i); ?>">
                                                                                        <?php echo e($i); ?>x
                                                                                        <?php if($i > 1 && $campaign->pay_card_credit_installment_fee_payer === 'customer'): ?>
                                                                                            - COM JUROS
                                                                                        <?php endif; ?>
                                                                                    </option>
                                                                                <?php endfor; ?>
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            <!-- Para PIX e Boleto, sempre 1 parcela -->
                                                                            <option value="1" selected>1x - À
                                                                                vista</option>
                                                                        <?php endif; ?>
                                                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $attributes = $__attributesOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__attributesOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85ca4b3e56109309ed152b03e950458a)): ?>
<?php $component = $__componentOriginal85ca4b3e56109309ed152b03e950458a; ?>
<?php unset($__componentOriginal85ca4b3e56109309ed152b03e950458a); ?>
<?php endif; ?>
                                                                    <?php if(empty($available_installments) && $payment_method === 'credit_card'): ?>
                                                                        <p class="mt-1 text-xs text-yellow-600">
                                                                            <span wire:loading
                                                                                wire:target="payment_method">Calculando
                                                                                parcelas disponíveis...</span>
                                                                        </p>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        
                                                        <div class="flex justify-end pt-4 border-t">
                                                            <button wire:click="processarPagamento"
                                                                wire:loading.attr="disabled"
                                                                class="px-8 py-3 rounded-lg font-bold text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                                                style="background-color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>">
                                                                <span wire:loading.remove
                                                                    wire:target="processarPagamento"
                                                                    class="whitespace-nowrap">Pagar com Cartão</span>
                                                                <svg wire:loading wire:target="processarPagamento"
                                                                    class="animate-spin h-5 w-5 text-white flex-shrink-0"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                    </path>
                                                                </svg>
                                                                <span wire:loading wire:target="processarPagamento"
                                                                    class="whitespace-nowrap">Processando...</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="px-4"><?php echo $__env->make('_includes.alertas_forma_pagamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        
                                        <?php if(!$isRecurringPayment && ($campaign->pay_boleto ?? false) && !$is_anonymous): ?>
                                            <?php
                                                $currentPaymentBoleto = $campaignPayment ?? null;

                                                // Verifica se o boleto expirou
                                                $boletoExpired = false;
                                                if (
                                                    $currentPaymentBoleto &&
                                                    $currentPaymentBoleto->pay_boleto_expiration_date
                                                ) {
                                                    $boletoExpired = \Carbon\Carbon::parse(
                                                        $currentPaymentBoleto->pay_boleto_expiration_date,
                                                    )->isPast();
                                                }

                                                // Só considera boleto gerado se não estiver expirado
                                                $hasBoletoGenerated =
                                                    !$boletoExpired &&
                                                    $currentPaymentBoleto &&
                                                    ($currentPaymentBoleto->pay_boleto_barcode ||
                                                        $currentPaymentBoleto->pay_boleto_url ||
                                                        ($payment_result['pay_boleto_barcode'] ?? false) ||
                                                        ($payment_result['pay_boleto_url'] ?? false));
                                                $shouldOpenBoleto = $payment_method === 'boleto' || $hasBoletoGenerated;
                                                // Para compatibilidade
                                                $payment = $currentPaymentBoleto;
                                            ?>
                                            <div class="border-2 rounded-lg overflow-hidden transition relative <?php echo e($payment_method === 'boleto' || $hasBoletoGenerated ? 'border-blue-500' : 'border-gray-300'); ?>"
                                                x-data="{ isOpen: <?php echo e($shouldOpenBoleto ? 'true' : 'false'); ?> }" x-init="if (<?php echo e($hasBoletoGenerated ? 'true' : 'false'); ?> || $wire.payment_method === 'boleto') {
                                                    isOpen = true;
                                                }"
                                                x-effect="isOpen = ($wire.payment_method === 'boleto')">
                                                

                                                <label
                                                    class="flex items-center gap-4 p-4 cursor-pointer hover:bg-gray-50 transition relative <?php echo e($payment_method === 'boleto' || $hasBoletoGenerated ? 'bg-blue-50 font-bold' : ''); ?>"
                                                    wire:loading.attr="disabled" wire:target="payment_method"
                                                    @click="$wire.set('payment_method', 'boleto')">
                                                    <input type="radio" wire:model="payment_method" value="boleto"
                                                        <?php if($payment_method === 'boleto' || $hasBoletoGenerated): ?> checked <?php endif; ?>
                                                        class="text-blue-600 focus:ring-blue-500" />
                                                    <div class="flex items-center gap-3 flex-1">
                                                        <svg class="w-8 h-8 text-orange-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                        <div>
                                                            <div class="font-semibold text-gray-900">Boleto Bancário
                                                            </div>
                                                            <div class="text-xs text-gray-600">Vencimento em até 3 dias
                                                                úteis</div>
                                                        </div>
                                                    </div>
                                                </label>

                                                
                                                <div x-show="isOpen"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                    class="border-t border-gray-200">
                                                    <div class="p-6 bg-white">
                                                        <?php
                                                            // Usa $currentPaymentBoleto e $boletoExpired definidos acima
                                                            $hasBoletoGenerated =
                                                                !$boletoExpired &&
                                                                $currentPaymentBoleto &&
                                                                ($currentPaymentBoleto->pay_boleto_barcode ||
                                                                    $currentPaymentBoleto->pay_boleto_url ||
                                                                    ($payment_result['pay_boleto_barcode'] ?? false) ||
                                                                    ($payment_result['pay_boleto_url'] ?? false));
                                                        ?>

                                                        <?php if(!$hasBoletoGenerated): ?>
                                                            <h3 class="text-lg font-bold text-gray-900 mb-3">Dados para
                                                                Boleto</h3>
                                                            <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['label' => 'CPF do Pagador *','hint' => 'Banco Central exige desde 2017','mask' => '[\'###.###.###-##\',\'##.###.###/####-##\']'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => '000.000.000-00','wire:model.defer' => 'boleto_cpf','wire:key' => 'boleto_cpf_maskable','inputmode' => 'numeric','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $attributes = $__attributesOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__attributesOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale644eb2797c754d297bfd720474f3ddc)): ?>
<?php $component = $__componentOriginale644eb2797c754d297bfd720474f3ddc; ?>
<?php unset($__componentOriginale644eb2797c754d297bfd720474f3ddc); ?>
<?php endif; ?>

                                                            
                                                            <div class="flex justify-end mt-4 pt-4 border-t">
                                                                <button wire:click="processarPagamento"
                                                                    wire:loading.attr="disabled"
                                                                    class="px-8 py-3 rounded-lg font-bold text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                                                    style="background-color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>">
                                                                    <span wire:loading.remove
                                                                        wire:target="processarPagamento"
                                                                        class="whitespace-nowrap">Gerar Boleto</span>
                                                                    <svg wire:loading wire:target="processarPagamento"
                                                                        class="animate-spin h-5 w-5 text-white flex-shrink-0"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12"
                                                                            cy="12" r="10"
                                                                            stroke="currentColor" stroke-width="4">
                                                                        </circle>
                                                                        <path class="opacity-75" fill="currentColor"
                                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                        </path>
                                                                    </svg>
                                                                    <span wire:loading wire:target="processarPagamento"
                                                                        class="whitespace-nowrap">Processando...</span>
                                                                </button>
                                                            </div>
                                                        <?php else: ?>
                                                            <?php
                                                                // Prioriza payment_result (dados frescos) sobre $payment (banco de dados)
                                                                $boletoBarcode =
                                                                    $payment_result['pay_boleto_barcode'] ??
                                                                    ($payment->pay_boleto_barcode ?? null);
                                                                $boletoUrl =
                                                                    $payment_result['pay_boleto_url'] ??
                                                                    ($payment->pay_boleto_url ?? null);
                                                                $boletoExpiration =
                                                                    $payment_result['pay_boleto_expiration_date'] ??
                                                                    ($payment->pay_boleto_expiration_date ?? null);
                                                            ?>

                                                            
                                                            <?php if($boletoBarcode): ?>

                                                                <div
                                                                    class="flex flex-col md:flex-row justify-between items-start gap-3 mb-4">

                                                                    <div>
                                                                        <h2 class="text-xl font-bold text-gray-900">
                                                                            Boleto Gerado</h2>
                                                                        <p class="text-sm text-gray-500 mt-1">Use o
                                                                            código digitável</p>
                                                                    </div>

                                                                    <div class="text-left md:text-right">
                                                                        <div class="text-sm text-gray-700">Pague até
                                                                        </div>
                                                                        <div class="text-xl font-bold text-blue-700">
                                                                            <?php echo e(\Carbon\Carbon::parse($boletoExpiration)->format('d/m/Y')); ?>

                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="mb-4">
                                                                    <label
                                                                        class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Linha
                                                                        Digitável</label>
                                                                    <div
                                                                        class="w-full bg-gray-50 border border-gray-300 rounded p-3 mb-2">
                                                                        <div id="boleto-barcode-display-step3"
                                                                            data-barcode="<?php echo e($boletoBarcode); ?>"
                                                                            class="text-sm font-mono text-center text-gray-900 break-all leading-relaxed select-all">
                                                                            <?php echo e(putMask($boletoBarcode, 'boleto')); ?>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="flex flex-col-reverse md:flex-row justify-center md:justify-end gap-3">
                                                                    
                                                                    <?php if($boletoUrl): ?>
                                                                        <a href="<?php echo e($boletoUrl); ?>"
                                                                            target="_blank"
                                                                            class="w-full md:w-auto flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white rounded hover:bg-green-700 font-semibold">
                                                                            <svg class="h-5 w-5"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                fill="none" viewBox="0 0 24 24"
                                                                                stroke-width="1.5"
                                                                                stroke="currentColor">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                                            </svg>
                                                                            Acessar Boleto Online
                                                                        </a>
                                                                    <?php endif; ?>
                                                                    <div class="w-full md:w-auto">
                                                                        <button
                                                                            onclick="copyBoletoBarcodeStep3(event)"
                                                                            class="w-full px-10 py-3.5 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-semibold">
                                                                            Copiar
                                                                        </button>
                                                                    </div>
                                                                </div>


                                                            <?php endif; ?>

                                                        <?php endif; ?>

                                                        <?php if(
                                                            ($payment->pay_pix_qr_code_url ?? false) ||
                                                                ($payment->pay_pix_key ?? false) ||
                                                                ($payment_result['pay_pix_qr_code_url'] ?? false) ||
                                                                ($payment_result['pay_pix_key'] ?? false)): ?>

                                                            <div class="space-y-2 mt-4 pt-4 border-t">
                                                                
                                                                <?php if(
                                                                    $currentPayment &&
                                                                        !in_array($currentPayment->status ?? 'pending', ['paid', 'approved', 'autorizado', 'captured', 'pix_expired'])): ?>
                                                                    <div wire:poll.10s="validarPagamento(false)"
                                                                        class="hidden"></div>
                                                                <?php endif; ?>
                                                                <div
                                                                    class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                                                    <?php if($payment->pay_pix_qr_code_url ?? ($payment_result['pay_pix_qr_code_url'] ?? false)): ?>
                                                                        <div
                                                                            class="md:col-span-1 flex justify-center">
                                                                            <img src="<?php echo e($payment->pay_pix_qr_code_url ?? $payment_result['pay_pix_qr_code_url']); ?>"
                                                                                alt="QR Code PIX"
                                                                                class="w-auto md:w-48">
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="md:col-span-2 space-y-3">
                                                                        <h3 class="text-lg font-bold text-gray-900">Se
                                                                            preferir, também geramos um PIX!</h3>
                                                                        <div>
                                                                            <div
                                                                                class="text-xs text-gray-500 uppercase font-semibold mb-1">
                                                                                Código PIX</div>
                                                                            <div
                                                                                class="p-2 bg-gray-50 border rounded font-mono text-sm break-all">
                                                                                <?php echo e($payment->pay_pix_key ?? ($payment_result['pay_pix_key'] ?? '-')); ?>

                                                                            </div>
                                                                        </div>
                                                                        <button type="button"
                                                                            onclick="navigator.clipboard.writeText('<?php echo e($payment->pay_pix_key ?? ($payment_result['pay_pix_key'] ?? '')); ?>').then(() => { alert('Código PIX copiado!'); }).catch(() => { alert('Erro ao copiar. Tente novamente.'); });"
                                                                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded transition-colors flex items-center justify-center gap-2">
                                                                            <svg class="w-4 h-4" fill="none"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                                </path>
                                                                            </svg>
                                                                            COPIAR CHAVE PIX
                                                                        </button>
                                                                        <button type="button"
                                                                            wire:click="validarPagamento(true)"
                                                                            wire:target="validarPagamento"
                                                                            wire:loading.attr="disabled"
                                                                            class="w-full px-4 py-2 border-2 border-blue-600 hover:bg-blue-50 text-blue-600 text-sm font-semibold rounded transition-colors flex items-center justify-center gap-2 disabled:opacity-70">
                                                                            <div class="flex items-center gap-2"
                                                                                wire:loading.remove
                                                                                wire:target="validarPagamento"
                                                                                wire:loading.class="hidden">
                                                                                <div>VALIDAR PAGAMENTO</div>
                                                                            </div>
                                                                            <div class="flex items-center gap-2 hidden pt-0.5 pb-0.5"
                                                                                wire:loading
                                                                                wire:target="validarPagamento"
                                                                                wire:loading.class.remove="hidden">
                                                                                <svg class="animate-spin h-4 w-4"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none"
                                                                                    viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25"
                                                                                        cx="12"
                                                                                        cy="12" r="10"
                                                                                        stroke="currentColor"
                                                                                        stroke-width="4"></circle>
                                                                                    <path class="opacity-75"
                                                                                        fill="currentColor"
                                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                                    </path>
                                                                                </svg>
                                                                            </div>
                                                                        </button>
                                                                        <?php if($payment->pay_pix_expires_at ?? ($payment_result['pay_pix_expires_at'] ?? false)): ?>
                                                                            <?php
                                                                                $pixExpiresAt = \Carbon\Carbon::parse(
                                                                                    $payment->pay_pix_expires_at ??
                                                                                        $payment_result[
                                                                                            'pay_pix_expires_at'
                                                                                        ],
                                                                                );
                                                                                $minutesRemaining = now()->diffInSeconds(
                                                                                    $pixExpiresAt,
                                                                                    false,
                                                                                );
                                                                            ?>
                                                                            
                                                                            <?php if($minutesRemaining > 0 && $minutesRemaining <= 120): ?>
                                                                                <div
                                                                                    class="text-xs text-orange-600 font-semibold flex items-center gap-1">
                                                                                    <svg class="w-4 h-4"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                                        </path>
                                                                                    </svg>
                                                                                    Válido até:
                                                                                    <?php echo e($pixExpiresAt->format('d/m/Y H:i')); ?>

                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="px-4"><?php echo $__env->make('_includes.alertas_forma_pagamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                <?php endif; ?>

                                <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-xs text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                
                                <div class="flex justify-start pt-4 border-t">
                                    <button wire:click="etapaAnterior"
                                        class="px-6 py-3 rounded-lg font-semibold text-gray-700 bg-white border-2 border-gray-300 hover:bg-gray-50 transition flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                                        </svg>
                                        Voltar
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>

                        
                        <?php if($step === 4): ?>

                            <?php if (isset($component)) { $__componentOriginal522a59481d8bfd4d44478643bc3270fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal522a59481d8bfd4d44478643bc3270fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.validation-errors','data' => ['class' => 'mb-4','noLoading' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','no_loading' => 'false']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $attributes = $__attributesOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $component = $__componentOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__componentOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>

                            <?php if(!$payment ?? false): ?>
                                <h2>NENHUM PAGAMENTO ENCONTRADO</h2>
                            <?php else: ?>
                                <div id="payment-confirmation" class="space-y-6 text-center campaign-step-card"
                                    x-data x-init="setTimeout(() => { document.getElementById('div-title-campaing')?.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 100)">

                                    <?php if(
                                        ($payment->status ?? false) === 'paid' ||
                                            in_array($payment->status ?? false, ['paid', 'approved', 'captured', 'autorizado'])): ?>
                                        
                                        <div class="flex justify-center">
                                            <div
                                                class="w-16 md:w-24 h-16 md:h-24 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg class="w-10 md:w-16 h-10 md:h-16 text-green-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                                                <?php echo e(appText('messages.success.payment', 'Pagamento Confirmado!')); ?>

                                            </h2>
                                            <p class="text-lg md:text-lg text-gray-600">
                                                <?php echo e(appText('campaigns.thank_you', 'Obrigado por apoiar nossa campanha')); ?>

                                            </p>
                                        </div>
                                    <?php elseif($payment->pay_type === 'pix' && $payment->pay_pix_qr_code): ?>
                                        
                                        <div>
                                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Escaneie o QR Code para
                                                pagar</h2>
                                            <p class="text-gray-600 mb-6">Use o app do seu banco para escanear e
                                                concluir o pagamento</p>
                                        </div>

                                        <div class="bg-white rounded-lg p-6 border-2 border-gray-200 inline-block">
                                            <img src="<?php echo e($payment->pay_pix_qr_code_url ?? 'data:image/png;base64,' . $payment->pay_pix_qr_code); ?>"
                                                alt="QR Code PIX" class="w-64 h-64 mx-auto mb-4">
                                            <?php if($payment->pay_pix_expires_at ?? false): ?>
                                                <?php
                                                    $pixExpiresAt = \Carbon\Carbon::parse($payment->pay_pix_expires_at);
                                                    $minutesRemaining = now()->diffInSeconds($pixExpiresAt, false);
                                                ?>
                                                
                                                <?php if($minutesRemaining > 0 && $minutesRemaining <= 120): ?>
                                                    <p class="text-sm text-orange-600 font-semibold">
                                                        ⚠️ Válido até: <?php echo e($pixExpiresAt->format('d/m/Y H:i')); ?>

                                                    </p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>

                                        <?php if($payment->pay_pix_key): ?>
                                            <div
                                                class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-left max-w-md mx-auto">
                                                <label class="block text-xs font-semibold text-gray-700 mb-2">Chave
                                                    PIX (Copiar e Colar)</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="text" value="<?php echo e($payment->pay_pix_key); ?>"
                                                        readonly
                                                        class="flex-1 px-3 py-2 bg-white border border-gray-300 rounded text-sm font-mono"
                                                        id="pix-key-input">
                                                    <button onclick="copyPixKey()"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-semibold">
                                                        Copiar
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        
                                        <?php
                                            // Prioriza o status do payment sobre o order
                                            $isPaid =
                                                (($payment ?? false) &&
                                                    in_array($payment->status, [
                                                        'paid',
                                                        'approved',
                                                        'captured',
                                                        'autorizado',
                                                    ])) ||
                                                $order->status === 'paid';
                                            $hasError =
                                                !$isPaid &&
                                                (in_array($order->status, ['pay-error', 'error']) ||
                                                    (($payment ?? false) && $payment->status === 'error'));
                                        ?>

                                        <?php if($isPaid): ?>
                                            <div class="flex justify-center mb-4">
                                                <div
                                                    class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-green-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="text-center mb-8">
                                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                                                    Pagamento Aprovado!</h2>
                                                <p class="text-gray-600">Sua contribuição foi processada com sucesso
                                                </p>
                                            </div>
                                        <?php elseif($hasError): ?>
                                            
                                            
                                            <div
                                                class="bg-orange-50 border-l-4 border-orange-500 rounded-lg p-4 mb-6">
                                                <div class="flex items-center gap-3">
                                                    <svg class="w-6 h-6 text-orange-600 flex-shrink-0"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    <div>
                                                        <h3 class="text-sm font-bold text-orange-900">Erro no
                                                            Pagamento Anterior</h3>
                                                        <p class="text-xs text-orange-800 mt-1">Houve um problema ao
                                                            processar seu pagamento. Você pode tentar novamente abaixo.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="flex justify-center mb-4">
                                                <div
                                                    class="w-20 h-20 rounded-full bg-yellow-100 flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-yellow-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="text-center mb-8">
                                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Aguardando
                                                    Confirmação</h2>
                                                <p class="text-gray-600">Seu pedido está em processamento</p>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    
                                    <div class="bg-white rounded-xl p-8 border border-gray-300 shadow-lg">
                                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 text-left">Seus Dados
                                        </h3>
                                        <div class="">
                                            <div
                                                class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center py-1">
                                                <span class="text-gray-600 text-sm">Localizador</span>
                                                <span class="font-medium text-gray-900 capitalize"
                                                    style="color: <?php echo e($campaign->color_primary ?? '#3B82F6'); ?>"><?php echo e($order->order_control); ?></span>
                                            </div>

                                            <div
                                                class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center py-1">
                                                <span class="text-gray-600 text-sm">Nome</span>
                                                <span
                                                    class="font-medium text-gray-900 capitalize"><?php echo e($order->buyer_name); ?></span>
                                            </div>

                                            <?php if($order->buyer_email): ?>
                                                <div
                                                    class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center py-1">
                                                    <span class="text-gray-600 text-sm">E-mail</span>
                                                    <span
                                                        class="font-medium text-gray-900 lowercase"><?php echo e($order->buyer_email); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($order->buyer_contact_ddd || $order->buyer_contact_num): ?>
                                                <?php
                                                    $buyer = $order->buyer_id
                                                        ? \App\Models\AppBuyers::find($order->buyer_id)
                                                        : null;
                                                    $contactCountry = $buyer ? $buyer->contact_country : '55';
                                                ?>
                                                <div
                                                    class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center py-1">
                                                    <span class="text-gray-600 text-sm">Telefone</span>
                                                    <span class="font-medium text-gray-900">+<?php echo e($contactCountry); ?>

                                                        <?php echo e($order->buyer_contact_ddd ? $order->buyer_contact_ddd . ' ' : ''); ?><?php echo e($order->buyer_contact_num); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    
                                    <div class="bg-white rounded-xl p-8 border border-gray-300 shadow-lg">
                                        <div>
                                            <div class="text-xl md:text-2xl font-bold text-gray-900 text-left">Resumo
                                                da Contribuição</div>
                                            
                                            <?php if($order->buyer_email): ?>
                                                <div class="text-left text-xs text-gray-500">
                                                    Você receberá um e-mail de confirmação em breve
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="space-y-3 py-4">

                                            <div
                                                class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg px-4 py-2 shadow">
                                                <div
                                                    class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center">
                                                    <span
                                                        class="text-gray-700 font-medium text-lg">Contribuição</span>
                                                    <span class="text-2xl font-black text-green-600">
                                                        <?php echo e(toMoney($order->amount_total, 'R$ ')); ?>

                                                    </span>
                                                </div>
                                            </div>

                                            <?php if($order->is_recurring): ?>
                                                <div
                                                    class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center px-4 py-2 rounded bg-indigo-50 shadow">
                                                    <span class="text-indigo-700 text-sm">Recorrência</span>
                                                    <span class="font-semibold text-indigo-900 text-sm uppercase">SIM
                                                        - MENSALMENTE</span>
                                                </div>
                                            <?php endif; ?>

                                            <div
                                                class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center px-4 py-2 rounded bg-gray-50 shadow">
                                                <span class="text-gray-600 text-sm">Forma de Pagamento</span>
                                                <span class="font-medium text-gray-900 text-sm uppercase">
                                                    <?php if($payment->pay_type === 'pix'): ?>
                                                        PIX
                                                    <?php elseif($payment->pay_type === 'boleto'): ?>
                                                        BOLETO
                                                    <?php elseif($payment->pay_type === 'card_credit'): ?>
                                                        CARTÃO DE CRÉDITO
                                                    <?php else: ?>
                                                        <?php echo e($payment->pay_type); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>

                                            
                                            <div
                                                class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center px-4 py-2 rounded bg-gray-50 shadow">
                                                <span class="text-gray-600 text-sm">Data/Hora</span>
                                                <span
                                                    class="font-medium text-gray-900 text-sm"><?php echo e($payment->created_at ? $payment->created_at->format('d/m/Y H:i') : '--'); ?></span>
                                            </div>
                                            
                                            <div
                                                class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center px-4 py-2 rounded bg-gray-50 shadow">
                                                <span class="text-gray-600 text-sm">NSU</span>
                                                <span
                                                    class="font-medium text-gray-900 text-sm font-mono"><?php echo e($payment->pay_nsu ?? '--'); ?></span>
                                            </div>
                                            
                                            <?php
                                                $cardNumber = $payment->pay_card_last ?? null;
                                                $cardBrand = $payment->pay_card_brand ?? null;

                                                // Se não tiver nos campos diretos, busca no JSON
                                                if (
                                                    !$cardNumber &&
                                                    isset(
                                                        $payment->pay_json_response['response']['ResponseDetail'][
                                                            'CreditCard'
                                                        ]['CardNumber'],
                                                    )
                                                ) {
                                                    $cardNumber =
                                                        $payment->pay_json_response['response']['ResponseDetail'][
                                                            'CreditCard'
                                                        ]['CardNumber'];
                                                }
                                                if (
                                                    !$cardBrand &&
                                                    isset(
                                                        $payment->pay_json_response['response']['ResponseDetail'][
                                                            'CreditCard'
                                                        ]['Brand'],
                                                    )
                                                ) {
                                                    $brandId =
                                                        $payment->pay_json_response['response']['ResponseDetail'][
                                                            'CreditCard'
                                                        ]['Brand'];
                                                    $brands = [
                                                        1 => 'VISA',
                                                        2 => 'MASTERCARD',
                                                        3 => 'ELO',
                                                        4 => 'AMEX',
                                                        5 => 'DINERS',
                                                        6 => 'HIPERCARD',
                                                        99 => 'OUTROS',
                                                    ];
                                                    $cardBrand = $brands[$brandId] ?? 'Cartão';
                                                }
                                            ?>
                                            <?php if($cardNumber || $cardBrand): ?>
                                                <div
                                                    class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center px-4 py-2 rounded bg-gray-50 shadow">
                                                    <span class="text-gray-600 text-sm">Cartão</span>
                                                    <span class="font-medium text-gray-900 text-sm font-mono">
                                                        <?php echo e($cardNumber ?? '--'); ?>

                                                    </span>
                                                </div>
                                                <div
                                                    class="flex flex-col md:flex-row justify-start md:justify-between items-start md:items-center px-4 py-2 rounded bg-gray-50 shadow">
                                                    <span class="text-gray-600 text-sm">Bandeira</span>
                                                    <span
                                                        class="font-medium text-gray-900 text-sm font-mono uppercase"><?php echo e($cardBrand ?? '--'); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        
                                        <div class="">
                                            <div class="text-center text-xs mb-1 text-black">Pagamentos Processados
                                                por</div>
                                            <div
                                                class="text-center font-bold text-blue-600 flex justify-center items-center gap-1.5">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-xl">SAFE2PAY</span>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="py-2">
                                        <a href="<?php echo e($campaignUrl); ?>"
                                            class="px-8 py-3 rounded-lg font-light text-blue-700 bg-blue-50 hover:bg-blue-100 transition uppercase">
                                            Nova Contribuição
                                        </a>
                                    </div>
                                </div>

                            <?php endif; ?>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>
            </div>
        </div>

        
        <div class="p-4">
            <div class="text-center text-xs text-gray-400 mb-6">
                <?php if($order ?? false): ?>
                    <span>Localizador: <span
                            class="font-mono font-semibold text-gray-500"><?php echo e($order->order_control); ?></span></span>
                <?php elseif(session()->get('appUserUuid') ?? false): ?>
                    <span>AppSession: <span
                            class="font-mono font-semibold text-gray-500"><?php echo e(session()->get('appUserUuid') ?? null); ?></span></span>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <br><br><br><br>

    
    <script>
        (function() {
            console.log('🚀 Script de scroll iniciado');

            // Função para rolar até o div-step
            function scrollToStep() {
                console.log('📜 Tentando fazer scroll...');
                const stepDiv = document.getElementById('div-step');
                if (stepDiv) {
                    stepDiv.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                        inline: 'nearest'
                    });
                    console.log('✅ Scroll executado para div-step');
                } else {
                    console.log('❌ div-step não encontrado');
                }
            }

            // Verificar se deve fazer scroll baseado na URL
            function shouldScrollBasedOnUrl() {
                const currentPath = window.location.pathname;
                const pathParts = currentPath.split('/').filter(p => p);
                console.log('🔍 Verificando URL:', {
                    path: currentPath,
                    parts: pathParts
                });

                // Se tem 3 partes (/customer/campaign/donation-id), está em step avançado
                if (pathParts.length === 3) {
                    console.log('💡 URL indica step avançado, fazendo scroll...');
                    setTimeout(scrollToStep, 100);
                    return true;
                }
                return false;
            }

            // Escutar cliques em botões de próxima etapa
            function setupClickListeners() {
                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('[wire\\:click*="proximaEtapa"]');
                    if (btn) {
                        console.log('🎯 Botão próxima etapa clicado');

                        // Aguardar um tempo para o processamento
                        setTimeout(() => {
                            console.log('⏰ Executando scroll após click...');
                            scrollToStep();

                            // Verificar URL novamente após mais tempo (para redirecionamentos)
                            setTimeout(() => {
                                shouldScrollBasedOnUrl();
                            }, 1000);
                        }, 800);
                    }
                });
            }

            // Configurar quando a página carrega
            function init() {
                console.log('🔧 Inicializando script de scroll');
                setupClickListeners();

                // Verificar se já deve fazer scroll na carga
                setTimeout(() => {
                    shouldScrollBasedOnUrl();
                }, 300);
            }

            // Executar quando DOM estiver pronto
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }

            // Escutar eventos do Livewire se disponível
            document.addEventListener('livewire:load', function() {
                console.log('🔌 Livewire carregado');

                Livewire.on('stepChanged', function() {
                    console.log('📶 Event stepChanged recebido');
                    setTimeout(scrollToStep, 200);
                });
            });

            // Fallback: observer de mudanças na URL
            let lastHref = document.location.href;
            const urlObserver = new MutationObserver(function() {
                if (lastHref !== document.location.href) {
                    console.log('🔄 URL mudou:', {
                        from: lastHref,
                        to: document.location.href
                    });
                    lastHref = document.location.href;
                    setTimeout(() => {
                        shouldScrollBasedOnUrl();
                    }, 400);
                }
            });

            urlObserver.observe(document, {
                childList: true,
                subtree: true
            });

        })();
    </script>
</div>

<?php if (! $__env->hasRenderedOnce('c22ce65a-a692-44d2-945b-121efc0cf82c')): $__env->markAsRenderedOnce('c22ce65a-a692-44d2-945b-121efc0cf82c'); ?>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('currencyField', (entangledValue) => ({
                model: entangledValue,
                display: '',
                init() {
                    this.display = this.format(this.model ?? '');

                    this.$watch('model', (value) => {
                        const formatted = this.format(value ?? '');
                        if (formatted !== this.display) {
                            this.display = formatted;
                        }
                    });
                },
                handleInput(value) {
                    this.display = this.format(value);
                    this.model = this.display;
                },
                format(value) {
                    const digits = (value || '').toString().replace(/\D/g, '');
                    const number = (parseInt(digits || '0', 10) / 100).toFixed(2);
                    const [intPart, decimalPart] = number.split('.');
                    const formattedInt = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    return `${formattedInt},${decimalPart}`;
                },
            }));
        });


        // Função para atualizar URL com order_id de forma segura
        function updateOrderUrl(orderId) {
            if (!orderId || typeof orderId !== 'string' || orderId.length !== 36) return;

            try {
                const currentPath = window.location.pathname;
                const pathParts = currentPath.split('/').filter(p => p);

                // Valida formato básico: /{customer}/{campaign}/{order?}
                // Nova estrutura sem prefixo /campanha/
                if (pathParts.length < 2) return;

                // Se já tem o order_id correto, não faz nada
                if (pathParts.length === 3 && pathParts[2] === orderId) return;

                // Remove order_id existente se houver
                if (pathParts.length === 3) {
                    pathParts.pop();
                }

                // Constrói nova URL
                const newPath = '/' + pathParts.join('/') + '/' + orderId;

                // Atualiza URL sem criar entrada no histórico
                if (currentPath !== newPath) {
                    window.history.replaceState({}, '', newPath);
                }
            } catch (e) {
                // Ignora erros silenciosamente
            }
        }

        // Função para rolar até o título da campanha
        function scrollToTitle() {
            const titleCampaing = document.getElementById('div-title-campaing');
            if (titleCampaing) {
                setTimeout(function() {
                    titleCampaing.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 300);
            }
        }

        // Executa no carregamento inicial da página
        document.addEventListener('DOMContentLoaded', scrollToTitle);

        // Atualiza URL quando pedido for criado
        window.addEventListener('order-created', function(event) {
            const orderId = event.detail?.orderId || event.detail;
            if (orderId) {
                // Aguarda um pouco para garantir que o DOM foi atualizado
                setTimeout(function() {
                    updateOrderUrl(orderId);
                }, 100);
            }
        });

        // Também escuta eventos do Livewire (compatível com versões antigas)
        document.addEventListener('livewire:load', function() {
            window.addEventListener('order-created', function(event) {
                const orderId = event.detail?.orderId || event.detail;
                if (orderId) {
                    setTimeout(function() {
                        updateOrderUrl(orderId);
                    }, 100);
                }
            });
        });

        // Verifica e atualiza URL quando detectar pedido na página
        function checkOrderUrl() {
            try {
                const orderElement = document.querySelector('[data-order-id]');
                if (orderElement) {
                    const orderId = orderElement.getAttribute('data-order-id');
                    if (orderId && orderId !== 'null' && orderId !== '') {
                        updateOrderUrl(orderId);
                    }
                }
            } catch (e) {
                // Ignora erros
            }
        }

        // Verifica URL quando Livewire carregar
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', checkOrderUrl);
        } else {
            setTimeout(checkOrderUrl, 100);
        }

        document.addEventListener('livewire:load', checkOrderUrl);
        document.addEventListener('livewire:update', function() {
            setTimeout(checkOrderUrl, 300);
        });

        // Força o Alpine.js a reinicializar componentes maskable quando renderizados pelo Livewire
        document.addEventListener('livewire:load', () => {
            if (typeof Livewire !== 'undefined' && Livewire.hook) {
                Livewire.hook('message.processed', (message, component) => {
                    // Aguarda o DOM ser atualizado
                    setTimeout(() => {
                        // Força o Alpine a processar novos elementos
                        if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                            const maskableInputs = document.querySelectorAll(
                                '[x-data*="wireui_inputs_maskable"]');
                            maskableInputs.forEach(el => {
                                if (!el._x_dataStack) {
                                    window.Alpine.initTree(el);
                                }
                            });
                        }
                    }, 100);
                });
            }
        });

        // Função para copiar chave PIX
        function copyPixKey() {
            const input = document.getElementById('pix-key-input');
            if (input) {
                input.select();
                input.setSelectionRange(0, 99999); // Para mobile
                document.execCommand('copy');

                // Feedback visual
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copiado!';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');

                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }, 4000);
            }
        }

        // Função para copiar código de barras do boleto
        function copyBoletoBarcode() {
            const input = document.getElementById('boleto-barcode-input');
            if (input) {
                input.select();
                input.setSelectionRange(0, 99999); // Para mobile
                document.execCommand('copy');

                // Feedback visual
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copiado!';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');

                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }, 4000);
            }
        }

        // Função para copiar chave PIX do boleto (STEP 4)
        function copyBoletoPixKey() {
            const input = document.getElementById('boleto-pix-key-input');
            if (input) {
                input.select();
                input.setSelectionRange(0, 99999); // Para mobile
                document.execCommand('copy');

                // Feedback visual
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copiado!';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-teal-600', 'hover:bg-teal-700');

                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-teal-600', 'hover:bg-teal-700');
                }, 4000);
            }
        }

        // Função para copiar código de barras do boleto (STEP 3)
        function copyBoletoBarcodeStep3(event) {
            const display = document.getElementById('boleto-barcode-display-step3');
            if (display) {
                // Copia o valor sem máscara do atributo data-barcode
                const barcodeValue = display.getAttribute('data-barcode');
                const button = event.target;
                const originalText = button.textContent;

                navigator.clipboard.writeText(barcodeValue).then(() => {
                    button.textContent = 'Copiado!';
                    button.classList.add('bg-green-600');
                    button.classList.remove('bg-blue-600', 'hover:bg-blue-700');

                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('bg-green-600');
                        button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    }, 4000);
                }).catch(() => {
                    // Fallback para navegadores antigos
                    const range = document.createRange();
                    range.selectNode(display);
                    window.getSelection().removeAllRanges();
                    window.getSelection().addRange(range);
                    document.execCommand('copy');
                    window.getSelection().removeAllRanges();

                    button.textContent = 'Copiado!';
                    button.classList.add('bg-green-600');
                    button.classList.remove('bg-blue-600', 'hover:bg-blue-700');

                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('bg-green-600');
                        button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    }, 4000);
                });
            }
        }

        // Função para copiar chave PIX do boleto (STEP 3)
        function copyBoletoPixKeyStep3() {
            const input = document.getElementById('boleto-pix-key-input-step3');
            if (input) {
                input.select();
                input.setSelectionRange(0, 99999);
                document.execCommand('copy');

                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copiado!';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-teal-600', 'hover:bg-teal-700');

                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-teal-600', 'hover:bg-teal-700');
                }, 4000);
            }
        }

        // O Livewire já gerencia tudo via wire:model
        // Não precisa de JavaScript adicional - o wire:model atualiza automaticamente

        function getDadosCompartilhamentoCampanha() {
            const url = '<?php echo e($campaignUrl); ?>';
            const titulo = '<?php echo e(addslashes($campaign->name)); ?>';
            const descricao =
                '<?php echo e(addslashes(Str::limit(strip_tags($campaign->description ?? 'Participe desta campanha!'), 110))); ?>';

            return {
                url,
                titulo,
                descricao,
                textoCompleto: `${titulo}\n${descricao}\n${url}`,
            };
        }

        function ajustarPosicionamentoMenuCompartilhar() {
            const menu = document.getElementById('menu-compartilhar-campanha');
            if (!menu || menu.classList.contains('hidden')) {
                return;
            }

            menu.style.setProperty('--share-menu-shift', '0px');

            requestAnimationFrame(() => {
                const rect = menu.getBoundingClientRect();
                const viewportWidth = window.innerWidth;
                const safePadding = 12;
                let shift = 0;

                if (rect.right > viewportWidth - safePadding) {
                    shift -= rect.right - (viewportWidth - safePadding);
                }

                if (rect.left < safePadding) {
                    shift += safePadding - rect.left;
                }

                menu.style.setProperty('--share-menu-shift', `${shift}px`);
            });
        }

        function toggleMenuCompartilhar(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            const menu = document.getElementById('menu-compartilhar-campanha');
            const toggleButton = document.querySelector('[data-share-menu-toggle]');
            const toggleIcon = toggleButton ? toggleButton.querySelector('svg') : null;

            if (!menu) {
                return;
            }

            const isHidden = menu.classList.contains('hidden');

            if (isHidden) {
                menu.classList.remove('hidden');
                toggleButton?.setAttribute('aria-expanded', 'true');
                toggleIcon?.classList.add('rotate-180');
                ajustarPosicionamentoMenuCompartilhar();
                return;
            }

            closeMenuCompartilhar();
        }

        function closeMenuCompartilhar() {
            const menu = document.getElementById('menu-compartilhar-campanha');
            const toggleButton = document.querySelector('[data-share-menu-toggle]');
            const toggleIcon = toggleButton ? toggleButton.querySelector('svg') : null;

            if (menu) {
                menu.classList.add('hidden');
                menu.style.setProperty('--share-menu-shift', '0px');
            }

            toggleButton?.setAttribute('aria-expanded', 'false');
            toggleIcon?.classList.remove('rotate-180');
        }

        function compartilharCampanha() {
            const {
                url,
                titulo,
                descricao
            } = getDadosCompartilhamentoCampanha();
            closeMenuCompartilhar();

            if (navigator.share) {
                navigator.share({
                        title: titulo,
                        text: descricao,
                        url: url
                    })
                    .then(() => showShareToast('Campanha compartilhada com sucesso.'))
                    .catch((error) => {
                        if (error.name !== 'AbortError') {
                            copiarLinkCampanha();
                        }
                    });
                return;
            }

            copiarLinkCampanha();
        }

        function compartilharVia(canal) {
            const {
                url,
                titulo,
                descricao,
                textoCompleto
            } = getDadosCompartilhamentoCampanha();
            closeMenuCompartilhar();

            const encodedUrl = encodeURIComponent(url);
            const encodedDescricao = encodeURIComponent(`${titulo} - ${descricao}`);
            const encodedTextoCompleto = encodeURIComponent(textoCompleto);

            let link = '';

            switch (canal) {
                case 'whatsapp':
                    link = `https://wa.me/?text=${encodedTextoCompleto}`;
                    break;
                case 'telegram':
                    link = `https://t.me/share/url?url=${encodedUrl}&text=${encodedDescricao}`;
                    break;
                case 'facebook':
                    link = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
                    break;
                case 'email':
                    window.location.href = `mailto:?subject=${encodeURIComponent(titulo)}&body=${encodedTextoCompleto}`;
                    showShareToast('Cliente de e-mail aberto.');
                    return;
                default:
                    copiarLinkCampanha();
                    return;
            }

            window.open(link, '_blank', 'noopener,noreferrer');
            showShareToast('Abrimos o canal para você compartilhar.');
        }

        function copiarLinkCampanha() {
            const {
                url
            } = getDadosCompartilhamentoCampanha();
            closeMenuCompartilhar();

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url)
                    .then(() => showShareToast('Link copiado para a área de transferência.'))
                    .catch(() => copiarLinkLegacy(url));
                return;
            }

            copiarLinkLegacy(url);
        }

        function copiarLinkLegacy(url) {
            const input = document.createElement('input');
            input.value = url;
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.appendChild(input);

            input.select();
            input.setSelectionRange(0, 99999);

            try {
                const sucesso = document.execCommand('copy');
                if (sucesso) {
                    showShareToast('Link copiado para a área de transferência.');
                } else {
                    prompt('Copie o link da campanha:', url);
                }
            } catch (err) {
                prompt('Copie o link da campanha:', url);
            }

            document.body.removeChild(input);
        }

        function showShareToast(message) {
            const toastId = 'campaign-share-toast';
            let toast = document.getElementById(toastId);

            if (!toast) {
                toast = document.createElement('div');
                toast.id = toastId;
                toast.className =
                    'fixed bottom-5 left-1/2 -translate-x-1/2 translate-y-2 bg-emerald-600 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-2xl z-[120] opacity-0 pointer-events-none transition-all duration-300';
                document.body.appendChild(toast);
            }

            toast.textContent = message;
            toast.classList.remove('opacity-0', 'translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');

            clearTimeout(window.__campaignShareToastTimeout);
            window.__campaignShareToastTimeout = setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-2');
            }, 2400);
        }

        document.addEventListener('click', function(event) {
            if (!event.target.closest('[data-share-wrapper]')) {
                closeMenuCompartilhar();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeMenuCompartilhar();
            }
        });

        let shareMenuResizeTimeout;
        window.addEventListener('resize', () => {
            if (shareMenuResizeTimeout) {
                clearTimeout(shareMenuResizeTimeout);
            }
            shareMenuResizeTimeout = setTimeout(ajustarPosicionamentoMenuCompartilhar, 120);
        });

        window.addEventListener('orientationchange', () => {
            setTimeout(ajustarPosicionamentoMenuCompartilhar, 200);
        });
    </script>
<?php endif; ?>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/campanha/campanha-publica.blade.php ENDPATH**/ ?>