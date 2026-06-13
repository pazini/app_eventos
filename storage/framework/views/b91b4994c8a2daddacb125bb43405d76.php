<div class="min-h-screen bg-white">

    
    <div wire:loading.class.remove="hidden" class="hidden fixed inset-0 z-[999] flex items-center justify-center" style="background: rgba(255,255,255,0.80); backdrop-filter: blur(6px);">
        <div class="flex flex-col items-center gap-4">
            <img src="<?php echo e(asset('/assets/loader.v2.svg')); ?>" alt="" class="w-16 h-16">
            <span class="text-gray-500 text-sm uppercase tracking-widest font-light">Aguarde…</span>
        </div>
    </div>
    

    
    <?php
        // Detect if event is from another instance
        $isExternalEvent = !empty($event->referer_url)
            && rtrim($event->referer_url, '/') !== rtrim(config('domains.eventos'), '/');

        $isInternalMediaPath = function ($path) {
            if (!is_string($path) || $path === '') {
                return false;
            }

            $internalPrefixes = [
                '/storage/',
                'storage/',
                'events/',
                'campaigns/',
                'customers/',
                'images_eventos/',
                'images_patrocinadores/',
                'images_customers_logo/',
            ];

            foreach ($internalPrefixes as $prefix) {
                if (str_starts_with($path, $prefix)) {
                    return true;
                }
            }

            return false;
        };

        // Build background image URL
        $urlImageBg = null;
        if ($event->url_image_bg ?? false) {
            if ($isExternalEvent && !$isInternalMediaPath($event->url_image_bg)) {
                $urlImageBg = $event->referer_url . '/' . $event->url_image_bg;
            } else {
                $urlImageBg = str_starts_with($event->url_image_bg, '/storage/')
                    ? asset($event->url_image_bg)
                    : tenantAsset($event->url_image_bg, true);
            }
        }

        // Build event logo URL - if event has logo, show it (from origin if external)
        $urlImageLogo = null;
        if ($event->url_image_logo ?? false) {
            if ($isExternalEvent && !$isInternalMediaPath($event->url_image_logo)) {
                $urlImageLogo = $event->referer_url . '/' . $event->url_image_logo;
            } else {
                $urlImageLogo = str_starts_with($event->url_image_logo, '/storage/')
                    ? asset($event->url_image_logo)
                    : tenantAsset($event->url_image_logo, true);
            }
        }

        // Build customer logo URL (fallback) - if customer has logo, show it (external events won't have customer logo)
        $urlCustomerLogo = null;
        if ($event->customer->url_image_logo ?? false) {
            $urlCustomerLogo = str_starts_with($event->customer->url_image_logo, '/storage/')
                ? asset($event->customer->url_image_logo)
                : tenantAsset($event->customer->url_image_logo, true);
        }

        // Build main event image URL
        $urlImage = null;
        if ($event->url_image ?? false) {
            if ($isExternalEvent && !$isInternalMediaPath($event->url_image)) {
                $urlImage = $event->referer_url . '/' . $event->url_image;
            } else {
                $urlImage = str_starts_with($event->url_image, '/storage/')
                    ? asset($event->url_image)
                    : tenantAsset($event->url_image, true);
            }
        }

        $colorPrimary   = $event->color_primary   ?? $event->color_default ?? '#6366f1';
        $colorSecondary = $event->color_secondary  ?? $event->color_default ?? '#8b5cf6';
        $colorDefault   = $event->color_default    ?? '#6366f1';
        $colorInverse   = $event->color_default_inverse ?? '#ffffff';
    ?>

    <style>@keyframes heroBgDrift{0%{transform:scale(1.08) translate(0%,0%)}25%{transform:scale(1.13) translate(-1.5%,-1%)}50%{transform:scale(1.10) translate(1%,-2%)}75%{transform:scale(1.14) translate(-0.5%,1%)}100%{transform:scale(1.08) translate(0%,0%)}}.hero-bg-animate{animation:heroBgDrift 24s ease-in-out infinite;will-change:transform;}</style>

    
    <section class="relative w-full overflow-hidden" style="min-height: <?php echo e($isAppVersion ? '260px' : '420px'); ?>;">

        
        <?php if($urlImageBg): ?>
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hero-bg-animate" style="background-image: url('<?php echo e($urlImageBg); ?>');"></div>
            <div class="absolute inset-0" style="background: linear-gradient(160deg, <?php echo e($colorPrimary); ?>cc 0%, rgba(10,10,20,0.88) 100%);"></div>
        <?php elseif($urlImage): ?>
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hero-bg-animate" style="background-image: url('<?php echo e($urlImage); ?>'); filter: blur(2px) brightness(0.35);"></div>
            <div class="absolute inset-0" style="background: linear-gradient(160deg, <?php echo e($colorPrimary); ?>88 0%, rgba(10,10,20,0.92) 100%);"></div>
        <?php else: ?>
            <div class="absolute inset-0" style="background: linear-gradient(135deg, <?php echo e($colorPrimary); ?> 0%, <?php echo e($colorSecondary); ?> 50%, rgba(10,10,20,1) 100%);"></div>
        <?php endif; ?>

        
        <div class="absolute -top-20 -left-20 w-96 h-96 rounded-full opacity-20 blur-3xl pointer-events-none" style="background: <?php echo e($colorPrimary); ?>;"></div>
        <div class="absolute -bottom-10 -right-10 w-72 h-72 rounded-full opacity-15 blur-3xl pointer-events-none" style="background: <?php echo e($colorSecondary); ?>;"></div>

        
        <div class="relative z-10 h-full flex flex-col justify-between <?php echo e($isAppVersion ? 'px-4 py-5 pb-12' : 'max-w-7xl mx-auto px-6 md:px-10 py-6 md:py-10 pb-16 md:pb-28'); ?>">

            
            <?php
                $eventDate       = $event->event_datetime_start  ? \Carbon\Carbon::parse($event->event_datetime_start)  : null;
                $eventDateFinish = $event->event_datetime_finish ? \Carbon\Carbon::parse($event->event_datetime_finish) : null;
                $eventIsOngoing  = $eventDate && $eventDate->isPast() && (!$eventDateFinish || $eventDateFinish->isFuture());
                $eventIsPast     = $eventDate && $eventDate->isPast() && (!$eventIsOngoing);
            ?>

            
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between w-full">
                <div class="flex items-center gap-4 min-w-0">
                    <?php if($urlImageLogo): ?>
                        <img class="<?php echo e($isAppVersion ? 'h-10' : 'h-12 md:h-14'); ?> w-auto drop-shadow-lg flex-shrink-0" src="<?php echo e($urlImageLogo); ?>" alt="">
                    <?php elseif($urlCustomerLogo): ?>
                        <img class="<?php echo e($isAppVersion ? 'h-10' : 'h-12 md:h-14'); ?> w-auto drop-shadow-lg flex-shrink-0" src="<?php echo e($urlCustomerLogo); ?>" alt="">
                    <?php elseif(!$isAppVersion): ?>
                        <img class="h-12 md:h-14 w-auto drop-shadow-lg flex-shrink-0" src="<?php echo e(appLogo(true)); ?>" alt="<?php echo e(appName()); ?>">
                    <?php endif; ?>

                    
                    <?php if($eventDate && !$eventIsPast): ?>
                        <div class="hidden md:flex flex-col min-w-0">
                            <span class="text-white text-sm md:text-base font-bold drop-shadow-lg truncate">
                                <?php echo e($eventDate->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY [·] HH[h]mm')); ?>

                            </span>
                            <span class="text-xs font-semibold drop-shadow" style="color: <?php echo e($colorDefault); ?>;">
                                <?php echo e($eventDate->locale('pt_BR')->diffForHumans()); ?>

                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <style>
                    @keyframes eventPastPulse {
                        0%, 100% { box-shadow: 0 0 0 0 rgba(234,88,12,0.7), 0 4px 20px rgba(234,88,12,0.45); }
                        50%       { box-shadow: 0 0 0 10px rgba(234,88,12,0), 0 4px 20px rgba(234,88,12,0.45); }
                    }
                    @keyframes eventOngoingPulse {
                        0%, 100% { box-shadow: 0 0 0 0 rgba(22,163,74,0.7), 0 4px 20px rgba(22,163,74,0.45); }
                        50%       { box-shadow: 0 0 0 10px rgba(22,163,74,0), 0 4px 20px rgba(22,163,74,0.45); }
                    }
                    @keyframes btnCompraPulse {
                        0%, 100% { box-shadow: 0 0 0 0 var(--btn-glow), 0 4px 20px var(--btn-glow); }
                        50%       { box-shadow: 0 0 0 10px transparent, 0 4px 20px var(--btn-glow); }
                    }
                    .badge-evento-passado { animation: eventPastPulse    2.2s ease-in-out infinite; }
                    .badge-evento-ongoing { animation: eventOngoingPulse 2.2s ease-in-out infinite; }
                    .btn-comprar-agora    { animation: btnCompraPulse    2.2s ease-in-out infinite; }
                </style>

                <?php if($eventIsPast): ?>
                    <span class="badge-evento-passado self-start md:self-auto flex-shrink-0 inline-flex items-center gap-2 <?php echo e($isAppVersion ? 'px-5 py-2 text-xs' : 'px-6 py-2.5 text-sm'); ?> font-bold uppercase tracking-wider rounded-full"
                          style="background: linear-gradient(135deg, #ea580c 0%, #f97316 100%); color: #ffffff;">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        EVENTO JÁ REALIZADO
                    </span>
                <?php elseif($eventIsOngoing): ?>
                    <span class="badge-evento-ongoing self-start md:self-auto flex-shrink-0 inline-flex items-center <?php echo e($isAppVersion ? 'px-5 py-2 text-xs' : 'px-6 py-2.5 text-sm'); ?> font-bold uppercase tracking-wider rounded-full"
                          style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); color: #ffffff;">
                        EM ANDAMENTO
                    </span>
                <?php else: ?>
                    <a href="#comprar"
                       class="btn-comprar-agora self-start md:self-auto flex-shrink-0 inline-flex items-center <?php echo e($isAppVersion ? 'px-4 py-1.5 text-xs' : 'px-4 py-1.5 text-xs md:px-6 md:py-2.5 md:text-sm'); ?> font-semibold uppercase tracking-wider rounded-full transition-all duration-200 hover:opacity-90 hover:scale-105 active:scale-95"
                       style="--btn-glow: <?php echo e($colorDefault); ?>99; background-color: <?php echo e($colorDefault); ?>; color: <?php echo e($colorInverse); ?>;">
                        COMPRAR AGORA MESMO
                    </a>
                <?php endif; ?>
            </div>

            
            <div class="w-full <?php echo e($isAppVersion ? 'mt-6 text-center' : 'mt-8 md:mt-12'); ?>">

                <?php
                    $locationParts = array_filter([
                        $event->city ?? null,
                        $event->state ?? null,
                    ]);
                    $heroLocationText = implode(', ', $locationParts);
                    if (!$heroLocationText && ($event->organizer->organization->organization_name ?? false)) {
                        $heroLocationText = $event->organizer->organization->organization_name;
                    }
                ?>
                <?php if($heroLocationText): ?>
                    <div class="inline-flex items-center gap-1.5 mb-3 px-3 py-1 rounded-full text-xs font-medium uppercase tracking-widest"
                         style="background: <?php echo e($colorPrimary); ?>33; color: <?php echo e($colorInverse); ?>; border: 1px solid <?php echo e($colorPrimary); ?>55;">
                        <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <?php echo e($heroLocationText); ?>

                    </div>
                <?php endif; ?>

                <h1 class="<?php echo e($isAppVersion ? 'text-2xl' : 'text-3xl md:text-5xl lg:text-6xl'); ?> font-extrabold text-white leading-tight uppercase tracking-tight drop-shadow-xl">
                    <?php echo e($event->event_name ?? '--'); ?>

                </h1>

                
                <?php
                    $orgName = $event->organizer->organizer_name_full ?? ($event->customer->name_corporate ?? null);
                    $evtDesc = $event->event_description ?? null;
                    $subLine = collect([$orgName, $evtDesc])->filter()->implode(' — ');
                ?>
                <?php if($subLine): ?>
                    <p class="<?php echo e($isAppVersion ? 'text-sm mt-2' : 'text-base md:text-lg mt-3'); ?> font-medium text-white/60 uppercase tracking-wide leading-relaxed max-w-4xl">
                        <?php echo e($subLine); ?>

                    </p>
                <?php endif; ?>

                
                <?php if($eventDate && !$eventIsPast): ?>
                    <div class="mt-4 flex flex-col items-center gap-1 md:hidden">
                        <span class="text-white text-base font-bold drop-shadow-lg text-center">
                            <?php echo e($eventDate->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY [·] HH[h]mm')); ?>

                        </span>
                        <span class="text-xs font-semibold drop-shadow" style="color: <?php echo e($colorDefault); ?>;">
                            <?php echo e($eventDate->locale('pt_BR')->diffForHumans()); ?>

                        </span>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

    
    <?php if($urlImage): ?>
        <div id="image_thumbnail" class="relative z-10 w-full flex justify-center <?php echo e($isAppVersion ? 'px-3 -mt-6' : 'max-w-5xl mx-auto px-6 md:px-10 -mt-10 md:-mt-16'); ?>">
            <div class="w-full rounded-2xl overflow-hidden shadow-xl ring-1 ring-gray-200"
                 style="box-shadow: 0 25px 50px rgba(0,0,0,0.15);">
                <img class="w-full h-auto block" src="<?php echo e($urlImage); ?>" alt="<?php echo e($event->event_name); ?>"
                     style="background-color: <?php echo e($colorDefault); ?>;">
            </div>
        </div>
    <?php endif; ?>

    
    <main class="<?php echo e($isAppVersion ? 'px-3 pb-8 pt-6' : 'max-w-5xl mx-auto px-6 md:px-10 pb-16 pt-8 md:pt-10'); ?>">

        <div id="div_comprar_agora" class="w-full flex flex-col">

            <?php if($ticketTypeSelected ?? false): ?>

                
                <?php
                    // LISTA DDD
                    $listaDdd = ['11','12','13','14','15','16','17','18','19','21','22','24','27','28','31','32','33','34','35','37','38','41','42','43','44','45','46','47','48','49','51','53','54','55','61','62','63','64','65','66','67','68','69','71','73','74','75','77','79','81','82','83','84','85','86','87','88','89','91','92','93','94','95','96','97','98','99'];

                    // LISTA DIAS
                    foreach (range(1,31) as $key => $value)
                    {
                        $key   = str_pad($value , 2 , '0' , STR_PAD_LEFT);
                        $value = str_pad($value , 2 , '0' , STR_PAD_LEFT);
                        //
                        $listaDd[$key] = $value;
                    }

                    // LISTA MES
                    foreach (range(1,12) as $key => $value)
                    {
                        $key   = str_pad($value , 2 , '0' , STR_PAD_LEFT);
                        $value = str_pad($value , 2 , '0' , STR_PAD_LEFT);
                        //
                        $listaMm[$key] = $value;
                    }

                    // LISTA ANO NASCIMENTO
                    foreach (range(now()->format('Y'),now()->subYear(100)->format('Y')) as $aaaa)
                    {
                        $listaAaaa[$aaaa] = $aaaa;
                    }

                    // LISTA ANO CARD
                    foreach (range(now()->format('Y'),now()->addYear(10)->format('Y')) as $aaaa)
                    {
                        $listaCardAaaa[$aaaa] = $aaaa;
                    }
                ?>

                
                <div id="comprar_ingresso" class="w-full <?php echo e($isAppVersion ? 'mb-4' : 'max-w-2xl mx-auto mb-8'); ?>">

                    
                    <div class="w-full flex items-start justify-between gap-3 mt-2 mb-5">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: <?php echo e($colorPrimary); ?>;">
                                <?php echo e($ticketTypeSelected->sales_label_btn ?? 'COMPRAR'); ?>

                            </div>
                            <div class="<?php echo e($isAppVersion ? 'text-xl' : 'text-2xl md:text-3xl'); ?> font-bold text-gray-800 leading-tight uppercase">
                                <?php echo e($ticketTypeSelected->sales_label_title ?? $ticketTypeSelected->ticket_name ?? 'INGRESSO'); ?>

                            </div>
                        </div>
                        <button wire:click="cancelTicketType" type="button"
                                class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium uppercase tracking-wider transition-all duration-200 hover:bg-gray-100 text-gray-400 border border-gray-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            cancelar
                        </button>
                    </div>

                    
                    <div class="w-full flex items-center justify-between rounded-2xl px-5 py-4 mb-3 bg-white"
                         style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                        <div class="flex-1 min-w-0">
                            <?php if($ticketTypeSelected->ticket_name ?? false): ?>
                                <div class="text-xs font-medium uppercase tracking-wider mb-0.5" style="color: <?php echo e($colorPrimary); ?>;">
                                    <?php echo e($ticketTypeSelected->sales_label_title ?? null); ?>

                                </div>
                                <div class="font-bold text-gray-800 <?php echo e($isAppVersion ? 'text-base' : 'text-lg md:text-xl'); ?> uppercase truncate">
                                    <?php echo e($ticketTypeSelected->ticket_name); ?>

                                </div>
                            <?php else: ?>
                                <div class="font-bold text-gray-800 <?php echo e($isAppVersion ? 'text-base' : 'text-lg md:text-xl'); ?> uppercase truncate">
                                    <?php echo e($ticketTypeSelected->sales_label_title ?? 'INGRESSO'); ?>

                                </div>
                            <?php endif; ?>
                            <?php if($ticketTypeSelected->ticket_description ?? false): ?>
                                <div class="text-gray-400 text-xs mt-0.5 truncate"><?php echo e($ticketTypeSelected->ticket_description); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-shrink-0 text-right ml-4">
                            <div class="text-xs text-gray-500 font-semibold mb-0.5 uppercase tracking-widest">por ingresso</div>
                            <div class="flex items-baseline gap-1">
                                <span class="<?php echo e($isAppVersion ? 'text-sm' : 'text-base'); ?> font-bold" style="color: <?php echo e($colorPrimary); ?>;">R$</span>
                                <span class="<?php echo e($isAppVersion ? 'text-2xl' : 'text-3xl md:text-4xl'); ?> font-extrabold leading-none text-gray-800"><?php echo e(toMoney($ticketTypeSelected->price ?? 0)); ?></span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl mb-5 text-xs font-medium uppercase tracking-wide bg-red-50 border border-red-100 text-red-500">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        Valor não reembolsável após 7 dias
                    </div>

                    
                    <div class="w-full space-y-5">

                        
                        <div class="rounded-2xl overflow-hidden bg-white" style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                            <div class="px-5 py-3 flex items-center gap-3"
                                 style="background: <?php echo e($colorPrimary); ?>0d; border-bottom: 1px solid #e2e8f0;">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                     style="background: <?php echo e($colorPrimary); ?>; color: <?php echo e($colorInverse); ?>;">1</div>
                                <div>
                                    <div class="font-bold text-gray-700 uppercase text-sm tracking-wide">Dados do comprador</div>
                                    <div class="text-gray-400 text-xs">Informe os dados abaixo</div>
                                </div>
                            </div>
                            <div class="p-5 space-y-4">
                                <div class="w-full <?php echo e($isAppVersion ? 'flex flex-col gap-4' : 'flex gap-4'); ?>">
                                    <div class="w-full <?php echo e($isAppVersion ? '' : 'md:w-1/2'); ?>">
                                        <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => '* Nome'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => '','id' => 'comprador_nome','wire:model.defer' => 'comprador_nome','class' => 'rounded capitalize','required' => true]); ?>
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
                                    <div class="w-full <?php echo e($isAppVersion ? '' : 'md:w-1/2'); ?>">
                                        <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => '* Sobrenome'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => '','wire:model.defer' => 'comprador_sobrenome','class' => 'rounded capitalize','required' => true]); ?>
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
                                <div class="w-full <?php echo e($isAppVersion ? 'flex flex-col gap-4' : 'flex gap-4'); ?>">
                                    <div class="w-full <?php echo e($isAppVersion ? '' : 'md:w-1/2'); ?>">
                                        <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => '* Email'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'email','class' => 'lowercase','wire:model.defer' => 'comprador_email','required' => true]); ?>
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
                                    <div class="w-full <?php echo e($isAppVersion ? '' : 'md:w-1/2'); ?>">
                                        <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['label' => '* CPF','mask' => '###.###.###-##'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => '____ . ____ . ____ - ___','wire:model.defer' => 'comprador_cpf','required' => true]); ?>
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
                                </div>
                                <div class="w-full <?php echo e($isAppVersion ? 'flex flex-col gap-4' : 'flex gap-4'); ?>">
                                    <div class="w-full <?php echo e($isAppVersion ? '' : 'md:w-1/2'); ?>">
                                        <div class="<?php echo e(setClass('divContentLabel')); ?>">* Data Nascimento</div>
                                        <div class="w-full flex mt-1">
                                            <div class="w-1/3">
                                                <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['placeholder' => 'DIA','options' => $listaDd ?? []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'comprador_nascimento_dd','class' => 'rounded-r-none','required' => true]); ?>
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
                                            <div class="w-1/3">
                                                <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['placeholder' => 'MÊS','options' => $listaMm ?? []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'comprador_nascimento_mm','class' => 'rounded-none','required' => true]); ?>
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
                                            <div class="w-1/3">
                                                <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['placeholder' => 'ANO','options' => $listaAaaa ?? []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'comprador_nascimento_aaaa','class' => 'rounded-l-none','required' => true]); ?>
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
                                    </div>
                                    <div class="w-full <?php echo e($isAppVersion ? '' : 'md:w-1/2'); ?>">
                                        <div class="<?php echo e(setClass('divContentLabel')); ?>">* Telefone</div>
                                        <div class="w-full flex mt-1">
                                            <div class="w-1/2">
                                                <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['placeholder' => 'DDD','options' => $listaDdd ?? []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'comprador_celular_ddd','class' => 'rounded-r-none','required' => true]); ?>
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
                                                <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['mask' => '[\'####-####\',\'#####-####\']'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Número','wire:model.defer' => 'comprador_celular_num','class' => 'rounded-l-none','required' => true]); ?>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="rounded-2xl overflow-hidden bg-white" style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                            <div class="px-5 py-3 flex items-center gap-3"
                                 style="background: <?php echo e($colorPrimary); ?>0d; border-bottom: 1px solid #e2e8f0;">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                     style="background: <?php echo e($colorPrimary); ?>; color: <?php echo e($colorInverse); ?>;">2</div>
                                <div>
                                    <div class="font-bold text-gray-700 uppercase text-sm tracking-wide">Quantidade</div>
                                    <div class="text-gray-400 text-xs">Selecione a quantidade</div>
                                </div>
                            </div>
                            <div class="p-5">
                                <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'comprador_ingressos_qtd','wire:model' => 'comprador_ingressos_qtd','class' => 'rounded-xl uppercase','required' => true]); ?>
                                        <option value="0">Selecione a quantidade</option>
                                        <?php $__currentLoopData = $listaParticipantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $listaParticipanteKey => $listaParticipanteItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($listaParticipanteKey); ?>" class="uppercase"><?php echo e($listaParticipanteItem['label']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                                
                                <?php if($comprador_ingressos_qtd ?? false): ?>
                                    <div class="flex flex-col mt-5 gap-4">
                                        <?php $__currentLoopData = range(1, $comprador_ingressos_qtd ?? 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participanteKey => $participanteInput): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $label = '* nome do ' . (!in_array($event->sales_label_item,['casal']) ? $event->sales_label_item : 'participante');
                                            ?>
                                            <div class="rounded-xl p-4 bg-gray-50" style="border: 1px solid <?php echo e($colorPrimary); ?>10;">
                                                <div class="flex items-center gap-2 mb-3 pb-2" style="border-bottom: 1px solid <?php echo e($colorPrimary); ?>10;">
                                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                                                         style="background: <?php echo e($colorPrimary); ?>15; color: <?php echo e($colorPrimary); ?>; border: 1px solid <?php echo e($colorPrimary); ?>30;">
                                                        <?php echo e($participanteInput); ?>

                                                    </div>
                                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                        DADOS <?php echo e($event->sales_label_item ?? 'Participante'); ?>

                                                        <?php if(($comprador_ingressos_qtd ?? 1) > 1): ?> <?php echo e($participanteInput); ?> <?php endif; ?>
                                                    </span>
                                                </div>
                                                <div class="w-full">
                                                    <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => ''.e($label).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'participantes.'.e($participanteInput).'','placeholder' => 'Nome Completo','class' => 'w-full uppercase']); ?>
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

                                                
                                                <?php $__currentLoopData = collect($event_questions_item ?? [])->sortBy('input_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $questions_key => $questions_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        if(in_array($ticketTypeSelected->id, $questions_item['input_hidden_lotes'] ?? []))
                                                        {
                                                            continue;
                                                        }

                                                        $name        = 'participantes_questions.' . $participanteInput . '.' . $questions_key;
                                                        $label       = $questions_item['input_label'] ?? $questions_key;
                                                        $placeholder = $questions_item['input_placeholder'] ?? '';
                                                        $type        = $questions_item['input_type'] ?? 'text';
                                                        $required    = false;
                                                        $options     = $questions_item['input_type_options'] ?? [];

                                                        if(!empty($options)) asort($options);

                                                        if($questions_item['input_required'] ?? false)
                                                        {
                                                            $label    = '* ' . $label;
                                                        }
                                                    ?>

                                                    <div class="w-full mt-3">
                                                        <?php if($type == 'text'): ?>
                                                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => ''.e($label).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => ''.e($name).'','placeholder' => ''.e($placeholder).'','class' => 'w-full uppercase']); ?>
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
                                                        <?php elseif($type == 'select'): ?>
                                                            <?php if (isset($component)) { $__componentOriginal85ca4b3e56109309ed152b03e950458a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85ca4b3e56109309ed152b03e950458a = $attributes; } ?>
<?php $component = WireUi\View\Components\NativeSelect::resolve(['label' => ''.e($label).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\NativeSelect::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => ''.e($name).'','title' => ''.e($placeholder).'','class' => 'w-full uppercase']); ?>
                                                                <option value="">---</option>
                                                                <?php $__currentLoopData = $options ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($option_item); ?>"><?php echo e($option_item); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                                        <?php else: ?>
                                                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => ''.e($label).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => ''.e($name).'','placeholder' => ''.e($placeholder).'','class' => 'w-full uppercase']); ?>
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
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <?php if($comprador_ingressos_qtd ?? false): ?>

                            <?php if(session('conclusao_error')): ?>
                                <div class="flex items-start gap-3 px-5 py-4 rounded-2xl bg-red-50 border border-red-200">
                                    <svg class="w-5 h-5 flex-shrink-0 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                    </svg>
                                    <div>
                                        <div class="text-red-700 font-bold uppercase text-sm"><?php echo e(__(session('conclusao_error'))); ?></div>
                                        <?php if(session('conclusao_error_sub')): ?>
                                            <div class="text-red-500 text-xs mt-0.5 uppercase"><?php echo e(__(session('conclusao_error_sub'))); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($this->orderPrice ?? false): ?>

                                
                                <div class="rounded-2xl overflow-hidden bg-white" style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                                    <div class="px-5 py-3 flex items-center gap-3"
                                         style="background: <?php echo e($colorPrimary); ?>0d; border-bottom: 1px solid #e2e8f0;">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                             style="background: <?php echo e($colorPrimary); ?>; color: <?php echo e($colorInverse); ?>;">3</div>
                                        <div class="font-bold text-gray-700 uppercase text-sm tracking-wide">Resumo da compra</div>
                                    </div>
                                    <div class="p-5">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="text-gray-500 text-xs uppercase tracking-wide mb-0.5">Total</div>
                                                <div class="text-xs text-gray-400">** Encargos e descontos serão calculados no pagamento</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="<?php echo e($isAppVersion ? 'text-2xl' : 'text-3xl md:text-4xl'); ?> font-extrabold"
                                                     style="color: <?php echo e($colorPrimary); ?>;">
                                                    <?php echo e(toMoney($this->orderPrice ?? 0, 'R$ ')); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <button
                                    wire:click="concluirCompra()"
                                    type="button"
                                    class="w-full flex items-center justify-center gap-3 rounded-2xl <?php echo e($isAppVersion ? 'py-4 text-base' : 'py-5 text-lg md:text-xl'); ?> font-extrabold uppercase tracking-wider transition-all duration-200 hover:opacity-90 hover:scale-[1.01] active:scale-[0.99] shadow-lg"
                                    style="background: linear-gradient(135deg, <?php echo e($colorPrimary); ?>, <?php echo e($colorSecondary); ?>); color: <?php echo e($colorInverse); ?>; box-shadow: 0 8px 30px <?php echo e($colorPrimary); ?>55;">
                                    <span>PRÓXIMA ETAPA</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </button>

                            <?php else: ?>

                                
                                <button
                                    onclick="confirm('Confirma o pedido?') || event.stopImmediatePropagation()"
                                    wire:click="concluirCompra()"
                                    type="button"
                                    class="w-full flex items-center justify-center gap-3 rounded-2xl <?php echo e($isAppVersion ? 'py-4 text-base' : 'py-5 text-lg md:text-xl'); ?> font-extrabold uppercase tracking-wider transition-all duration-200 hover:opacity-90 hover:scale-[1.01] active:scale-[0.99] shadow-lg"
                                    style="background: linear-gradient(135deg, <?php echo e($colorPrimary); ?>, <?php echo e($colorSecondary); ?>); color: <?php echo e($colorInverse); ?>; box-shadow: 0 8px 30px <?php echo e($colorPrimary); ?>55;">
                                    CONCLUIR
                                </button>

                            <?php endif; ?>

                        <?php endif; ?>

                    </div>

                    <?php
                        $numWhatsapp = false;
                        if(($event->organizer->owner_phone_country ?? false) && ($event->organizer->owner_phone_ddd ?? false) && ($event->organizer->owner_phone_num ?? false))
                        {
                            $numWhatsapp  = $event->organizer->owner_phone_country.$event->organizer->owner_phone_ddd.$event->organizer->owner_phone_num;
                            $linkWhatsapp = "https://api.whatsapp.com/send?phone=" .  $numWhatsapp . "&text=Fazendo contato sobre o evento " . $event->event_name . '.';
                        }
                    ?>

                    
                    <?php if($numWhatsapp ?? false): ?>
                        <div class="mt-8 flex items-center justify-center gap-3 px-4 py-4 rounded-2xl text-sm bg-green-50 border border-green-100">
                            <svg class="w-5 h-5 flex-shrink-0" fill="#25D366" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span class="text-gray-500">Precisa de ajuda? Fale conosco pelo
                                <a href="<?php echo e($linkWhatsapp); ?>" target="_blank" class="font-bold" style="color: #25D366;">WhatsApp</a>
                            </span>
                        </div>
                    <?php endif; ?>

                </div>

                <script>
                    const element = document.getElementById("div_comprar_agora");
                          element.scrollIntoView();
                          document.getElementById("comprador_nome").focus();
                </script>


            <?php else: ?>

                

                
                <?php if($event->event_about ?? false): ?>
                    <section id="session_sobre-ooevento" class="w-full mb-10">
                        <div class="rounded-2xl overflow-hidden bg-white" style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                            <div class="px-5 py-3 flex items-center gap-3" style="background: <?php echo e($colorPrimary); ?>0d; border-bottom: 1px solid #e2e8f0;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: <?php echo e($colorPrimary); ?>;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-bold text-gray-700 uppercase text-xs tracking-widest">Sobre o Evento</span>
                            </div>
                            <div class="p-5 md:p-6">
                                <div class="prose max-w-none <?php echo e($isAppVersion ? 'text-sm' : 'text-sm md:text-base'); ?> text-gray-600 leading-relaxed">
                                    <?php echo $event->event_about; ?>

                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                
                <section id="comprar" class="w-full mb-10">

                    
                    <div class="flex items-center gap-4 mb-6">
                        <svg class="w-9 h-9 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: <?php echo e($colorPrimary); ?>;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        <div>
                            <h2 class="<?php echo e($isAppVersion ? 'text-lg' : 'text-xl md:text-2xl'); ?> font-extrabold text-gray-800 uppercase tracking-tight">
                                <?php echo e($event->sales_label ?? 'Ingressos'); ?>

                            </h2>
                            <p class="text-xs mt-0.5 text-gray-400">Escolha seu ingresso e garanta sua vaga</p>
                        </div>
                    </div>

                    
                    <?php if(session('conclusao_error')): ?>
                        <div class="flex items-start gap-3 px-5 py-4 rounded-2xl mb-5 bg-red-50 border border-red-200">
                            <svg class="w-5 h-5 flex-shrink-0 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            <div>
                                <div class="text-red-700 font-bold uppercase text-sm"><?php echo e(__(session('conclusao_error'))); ?></div>
                                <?php if(session('conclusao_error_sub')): ?>
                                    <div class="text-red-500 text-xs mt-0.5 uppercase"><?php echo e(__(session('conclusao_error_sub'))); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <div class="grid grid-cols-1 <?php echo e($isAppVersion ? 'gap-3' : 'md:grid-cols-2 lg:grid-cols-3 gap-4'); ?>">

                        <?php $__empty_1 = true; $__currentLoopData = $ticketTypes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticketTypeId => $ticketTypeValues): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $isSoldOut = ($ticketTypeValues->esgotado ?? false);
                                $isClosed  = ($ticketTypeValues->loteFechado ?? false);
                                $isBlocked = $isSoldOut || $isClosed;
                            ?>

                            <div class="relative rounded-2xl overflow-hidden transition-all duration-300 bg-white flex flex-col <?php echo e($isBlocked ? 'opacity-60' : 'hover:-translate-y-1'); ?>"
                                 style="border: 1px solid <?php echo e($isBlocked ? '#e5e7eb' : '#e2e8f0'); ?>; box-shadow: <?php echo e($isBlocked ? '0 1px 4px rgba(0,0,0,0.06)' : '0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06)'); ?>;">

                                
                                <div class="h-1 flex-shrink-0" style="background: linear-gradient(90deg, <?php echo e($colorPrimary); ?>, <?php echo e($colorSecondary); ?>);"></div>

                                <div class="<?php echo e($isAppVersion ? 'p-4' : 'p-5 md:p-6'); ?> flex flex-col flex-1">

                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <?php if($ticketTypeValues->sales_label_title ?? false): ?>
                                                <span class="text-xs font-semibold uppercase tracking-widest" style="color: <?php echo e($colorPrimary); ?>;">
                                                    <?php echo e($ticketTypeValues->sales_label_title); ?>

                                                </span>
                                            <?php endif; ?>

                                            
                                            <?php if($isSoldOut): ?>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-red-50 border border-red-200 text-red-500">
                                                    Esgotado
                                                </span>
                                            <?php elseif($isClosed): ?>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-red-50 border border-red-200 text-red-500">
                                                    Encerrado
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <?php if($ticketTypeValues->ticket_name ?? false): ?>
                                            <h3 class="font-extrabold text-gray-800 <?php echo e($isAppVersion ? 'text-lg' : 'text-xl'); ?> uppercase leading-tight">
                                                <?php echo e($ticketTypeValues->ticket_name); ?>

                                            </h3>
                                        <?php else: ?>
                                            <h3 class="font-extrabold text-gray-800 <?php echo e($isAppVersion ? 'text-lg' : 'text-xl'); ?> uppercase leading-tight">
                                                <?php echo e($ticketTypeValues->sales_label_title ?? 'INGRESSO'); ?>

                                            </h3>
                                        <?php endif; ?>

                                        <?php if($ticketTypeValues->ticket_description ?? false): ?>
                                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">
                                                <?php echo e($ticketTypeValues->ticket_description); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <div class="pt-3 mt-auto border-t border-gray-100">
                                        <div class="text-gray-500 text-[10px] uppercase tracking-widest mb-1 font-semibold">Valor</div>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-sm font-bold" style="color: <?php echo e($isBlocked ? '#9ca3af' : $colorPrimary); ?>;">R$</span>
                                            <span class="<?php echo e($isAppVersion ? 'text-2xl' : 'text-2xl md:text-3xl'); ?> font-extrabold leading-none <?php echo e($isBlocked ? 'text-gray-400' : 'text-gray-800'); ?>">
                                                <?php echo e(number_format((int) ($ticketTypeValues->price ?? '0') / 100, 2, ',', '.')); ?>

                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        
                                        <?php if($isSoldOut || $isClosed): ?>
                                            <div class="w-full flex items-center justify-center gap-1.5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-400 border border-gray-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                <?php echo e($isSoldOut ? 'ESGOTADO' : 'ENCERRADO'); ?>

                                            </div>
                                        <?php else: ?>
                                            <button
                                                onclick="scrollTo()"
                                                wire:click="setTicketType('<?php echo e($ticketTypeValues->id); ?>')"
                                                type="button"
                                                class="w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold uppercase tracking-wide transition-all duration-200 hover:opacity-90 hover:scale-[1.02] active:scale-[0.98]"
                                                style="background-color: <?php echo e($colorDefault); ?>; color: <?php echo e($colorInverse); ?>; box-shadow: 0 4px 16px <?php echo e($colorDefault); ?>33;">
                                                <?php echo e($ticketTypeValues->sale_label_btn ?? 'COMPRAR'); ?>

                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <div class="col-span-full rounded-2xl p-10 text-center bg-white border border-dashed border-gray-200">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.069A1 1 0 0121 8.834v6.332a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <div class="text-gray-400 font-semibold uppercase text-sm tracking-wide">Ainda está indisponível</div>
                                <div class="text-gray-300 text-xs mt-1 uppercase tracking-wider">Volte mais tarde</div>
                            </div>

                        <?php endif; ?>

                    </div>

                    
                    <?php if($event->event_text_footer ?? false): ?>
                        <div class="mt-5 rounded-xl px-4 py-3 text-center bg-white border border-gray-100">
                            <p class="text-gray-500 text-sm leading-relaxed"><?php echo e($event->event_text_footer); ?></p>
                        </div>
                    <?php endif; ?>

                </section>

                
                <?php if($event->notification_text_1 ?? false): ?>
                    <section id="session_notificacao" class="w-full mb-10">
                        <div class="rounded-2xl px-5 py-5 text-center bg-red-50 border border-red-100">
                            <div class="<?php echo e($isAppVersion ? 'text-lg' : 'text-xl md:text-2xl'); ?> font-extrabold uppercase text-red-600 mb-1.5">
                                <?php echo e($event->notification_text_1); ?>

                            </div>
                            <?php if($event->notification_text_2 ?? false): ?>
                                <div class="<?php echo e($isAppVersion ? 'text-xs' : 'text-sm'); ?> font-medium text-red-400">
                                    <?php echo e($event->notification_text_2); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>

                
                <?php if($event->google_maps_iframe ?? false): ?>
                    <?php
                        $iframe_google_maps = str_replace('width="600"','width="100%"', $event->google_maps_iframe);

                        // Montar endereço completo
                        $addressParts = array_filter([
                            trim(($event->address ?? '') . ($event->address_number ? ', ' . $event->address_number : '')),
                            $event->address_complement ?? null,
                        ]);
                        $addressLine1 = implode(' — ', $addressParts);

                        $cityParts = array_filter([
                            $event->city_neighborhood ?? null,
                            $event->city ?? null,
                            $event->state ?? null,
                        ]);
                        $addressLine2 = implode(', ', $cityParts);
                        if ($event->zip_code ?? false) {
                            $addressLine2 .= ' · CEP ' . $event->zip_code;
                        }

                        $hasAddress = !empty($addressLine1) || !empty($addressLine2);
                    ?>
                    <section id="session_maps" class="w-full mb-10">
                        <div class="rounded-2xl overflow-hidden bg-white" style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                            
                            <div class="px-5 py-3 flex items-center gap-3" style="background: <?php echo e($colorPrimary); ?>0d; border-bottom: 1px solid #e2e8f0;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: <?php echo e($colorPrimary); ?>;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="font-bold text-gray-700 uppercase text-xs tracking-widest">Localização</span>
                            </div>

                            
                            <?php if($hasAddress): ?>
                                <div class="px-5 py-4" style="border-bottom: 1px solid <?php echo e($colorPrimary); ?>10;">
                                    <?php if($addressLine1): ?>
                                        <p class="text-gray-700 <?php echo e($isAppVersion ? 'text-sm' : 'text-base'); ?> font-semibold leading-snug">
                                            <?php echo e($addressLine1); ?>

                                        </p>
                                    <?php endif; ?>
                                    <?php if($addressLine2): ?>
                                        <p class="text-gray-500 text-sm mt-0.5"><?php echo e($addressLine2); ?></p>
                                    <?php endif; ?>
                                    <?php if($event->address_reference ?? false): ?>
                                        <p class="text-gray-400 text-xs mt-1 italic">Ref: <?php echo e($event->address_reference); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            
                            <div class="w-full">
                                <?php echo $iframe_google_maps; ?>

                            </div>
                        </div>
                    </section>
                <?php endif; ?>

            <?php endif; ?>

        </div>

    </main>



    <script>
        function scrollTo() {
            const el = document.getElementById("image_thumbnail") || document.getElementById("div_comprar_agora");
            if(el) el.scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/app-evento.blade.php ENDPATH**/ ?>