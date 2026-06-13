<div class="min-h-screen bg-white">

    <?php
        $colorPrimary   = $event->color_primary   ?? $event->color_default ?? '#6366f1';
        $colorSecondary = $event->color_secondary  ?? $event->color_default ?? '#8b5cf6';
        $colorDefault   = $event->color_default    ?? '#6366f1';
        $colorInverse   = $event->color_default_inverse ?? '#ffffff';

        // Logo do evento
        $urlImageLogo = null;
        if ($event->url_image_logo ?? false)
            $urlImageLogo = str_starts_with($event->url_image_logo, '/storage/') ? asset($event->url_image_logo) : tenantAsset($event->url_image_logo, true);
        elseif ($event->customer->url_image_logo ?? false)
            $urlImageLogo = str_starts_with($event->customer->url_image_logo, '/storage/') ? asset($event->customer->url_image_logo) : tenantAsset($event->customer->url_image_logo, true);

        // BG do evento
        $urlImageBg = null;
        if ($event->url_image_bg ?? false)
            $urlImageBg = str_starts_with($event->url_image_bg, '/storage/') ? asset($event->url_image_bg) : tenantAsset($event->url_image_bg, true);
        elseif ($event->url_image ?? false)
            $urlImageBg = str_starts_with($event->url_image, '/storage/') ? asset($event->url_image) : tenantAsset($event->url_image, true);
    ?>

    
    <div wire:loading.class.remove="hidden" class="hidden fixed inset-0 z-[999] flex items-center justify-center" style="background:rgba(255,255,255,0.80);backdrop-filter:blur(6px);">
        <div class="flex flex-col items-center gap-4">
            <img src="<?php echo e(asset('/assets/loader.v2.svg')); ?>" alt="" class="w-16 h-16">
            <span class="text-gray-500 text-sm uppercase tracking-widest font-light">Aguarde...</span>
        </div>
    </div>
    

    <style>@keyframes heroBgDrift{0%{transform:scale(1.08) translate(0%,0%)}25%{transform:scale(1.13) translate(-1.5%,-1%)}50%{transform:scale(1.10) translate(1%,-2%)}75%{transform:scale(1.14) translate(-0.5%,1%)}100%{transform:scale(1.08) translate(0%,0%)}}.hero-bg-animate{animation:heroBgDrift 24s ease-in-out infinite;will-change:transform;}</style>

    
    <section class="relative w-full overflow-hidden" style="min-height: 260px;">

        
        <?php if($urlImageBg): ?>
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hero-bg-animate" style="background-image:url('<?php echo e($urlImageBg); ?>');filter:blur(2px) brightness(0.35);"></div>
            <div class="absolute inset-0" style="background:linear-gradient(160deg,<?php echo e($colorPrimary); ?>88 0%,rgba(10,10,20,0.92) 100%);"></div>
        <?php else: ?>
            <div class="absolute inset-0" style="background:linear-gradient(135deg,<?php echo e($colorPrimary); ?> 0%,<?php echo e($colorSecondary); ?> 50%,rgba(10,10,20,1) 100%);"></div>
        <?php endif; ?>

        
        <div class="absolute rounded-full pointer-events-none" style="top:-5rem;left:-5rem;width:24rem;height:24rem;opacity:0.2;filter:blur(60px);background:<?php echo e($colorPrimary); ?>;"></div>
        <div class="absolute rounded-full pointer-events-none" style="bottom:-2.5rem;right:-2.5rem;width:18rem;height:18rem;opacity:0.15;filter:blur(60px);background:<?php echo e($colorSecondary); ?>;"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 md:px-10" style="padding-top:2rem;padding-bottom:7rem;">

            
            <div class="flex items-center justify-between w-full gap-3 mb-6">
                <div class="flex items-center gap-4">
                    <?php if($urlImageLogo): ?>
                        <img class="w-auto drop-shadow-lg" style="height:3.5rem;" src="<?php echo e($urlImageLogo); ?>" alt="">
                    <?php else: ?>
                        <img class="w-auto drop-shadow-lg" style="height:3.5rem;" src="<?php echo e(appLogo(true)); ?>" alt="<?php echo e(appName()); ?>">
                    <?php endif; ?>
                </div>
                <div>
                    <span class="inline-block px-5 py-2 text-xs font-semibold uppercase tracking-wider rounded-full shadow-lg" style="background-color:<?php echo e($colorDefault); ?>;color:<?php echo e($colorInverse); ?>;">PATROCÍNIO</span>
                </div>
            </div>

            
            <?php $heroCidade = collect([$event->city ?? null, $event->state ?? null])->filter()->implode(', '); ?>
            <?php if($heroCidade): ?>
                <div class="inline-flex items-center gap-1.5 mb-3 px-3 py-1 rounded-full text-xs font-medium uppercase" style="background:<?php echo e($colorPrimary); ?>33;color:<?php echo e($colorInverse); ?>;border:1px solid <?php echo e($colorPrimary); ?>55;letter-spacing:0.12em;">
                    <svg class="w-3 h-3" style="opacity:0.8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <?php echo e($heroCidade); ?>

                </div>
            <?php endif; ?>

            
            <h1 class="text-white font-extrabold uppercase leading-tight" style="font-size:clamp(1.5rem,4vw,2.5rem);letter-spacing:-0.01em;"><?php echo e($event->event_name ?? '--'); ?></h1>

            <?php if($event->event_description ?? false): ?>
                <p class="mt-1 font-medium uppercase tracking-wide leading-relaxed" style="font-size:clamp(0.9rem,2vw,1.1rem);color:rgba(255,255,255,0.6);"><?php echo e($event->event_description); ?></p>
            <?php endif; ?>

            
            <?php if($event->event_datetime_start ?? false): ?>
                <div class="mt-3 flex items-center gap-2" style="color:rgba(255,255,255,0.6);font-size:0.8rem;font-weight:500;text-transform:uppercase;letter-spacing:0.1em;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <?php echo e(formatDateStartFinish($event->event_datetime_start, $event->event_datetime_finish)); ?>

                </div>
            <?php endif; ?>

            
            <?php if($plano ?? false): ?>
                <style>.pln-banner-row{display:flex;align-items:center;justify-content:space-between;gap:0.75rem;}@media(max-width:639px){.pln-banner-row{flex-direction:column;align-items:flex-start;gap:0.25rem;}}</style>
                <div style="margin-top:1.5rem;display:block;width:100%;background:rgba(255,255,255,0.12);border:2px solid rgba(255,255,255,0.28);border-radius:1rem;padding:1rem 1.5rem;box-sizing:border-box;">
                    <div style="color:rgba(255,255,255,0.55);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.18em;margin-bottom:0.3rem;">PLANO DE PATROCÍNIO SELECIONADO</div>
                    <div class="pln-banner-row">
                        <div>
                            <div style="color:#ffffff;font-size:clamp(1.2rem,3.5vw,1.8rem);font-weight:900;text-transform:uppercase;line-height:1.1;"><?php echo e($plano->name); ?></div>
                            <?php if($plano->description ?? false): ?>
                                <div style="color:rgba(255,255,255,0.5);font-size:0.75rem;font-weight:400;text-transform:uppercase;letter-spacing:0.08em;margin-top:0.2rem;"><?php echo e(strip_tags($plano->description)); ?></div>
                            <?php endif; ?>
                        </div>
                        <div style="flex-shrink:0;">
                            <span style="color:rgba(255,255,255,0.9);font-size:clamp(1rem,2.5vw,1.4rem);font-weight:700;"><?php echo e(toMoney($plano->price ?? 0, 'R$ ')); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>

    
    <div class="w-full max-w-4xl mx-auto px-4 md:px-10 relative z-20" style="margin-top:-3.5rem;">

        <?php if($plano ?? false): ?>

            
            <div class="w-full rounded-2xl bg-white shadow-xl overflow-hidden" style="border:1px solid <?php echo e($colorPrimary); ?>18;">

                
                <div class="px-5 md:px-8 py-4 md:py-5 flex items-center justify-between gap-4" style="background:<?php echo e($colorPrimary); ?>08;border-bottom:1px solid <?php echo e($colorPrimary); ?>15;">
                    <div>
                        <div class="uppercase text-xs tracking-widest font-light text-gray-400">PATROCÍNIO</div>
                        <div class="uppercase text-xl md:text-2xl font-bold text-gray-800" style="margin-top:-2px;">ADESÃO AO PLANO</div>
                    </div>
                    <button wire:click="cancelarPlano" type="button" class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-lg transition-colors uppercase font-medium">
                        ← Voltar
                    </button>
                </div>

                <div class="px-5 md:px-8 py-5">

                    <?php
                        $listaDdd = ['21','11','12','13','14','15','16','17','18','19','22','24','27','28','31','32','33','34','35','37','38','41','42','43','44','45','46','47','48','49','51','53','54','55','61','62','63','64','65','66','67','68','69','71','73','74','75','77','79','81','82','83','84','85','86','87','88','89','91','92','93','94','95','96','97','98','99'];
                    ?>

                    
                    <div class="w-full md:w-1/2 mb-4">
                        <?php if (isset($component)) { $__componentOriginale644eb2797c754d297bfd720474f3ddc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale644eb2797c754d297bfd720474f3ddc = $attributes; } ?>
<?php $component = WireUi\View\Components\Inputs\MaskableInput::resolve(['label' => '* CNPJ ou CPF Patrocinador','mask' => '[\'###.###.###-##\',\'##.###.###/####-##\']'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('inputs.maskable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Inputs\MaskableInput::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.lazy' => 'buyer_doc_num','required' => true]); ?>
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

                    <div class="my-4" style="border-top:1px solid <?php echo e($colorPrimary); ?>12;"></div>

                    <div class="w-full flex gap-x-4">
                        <div class="w-1/2 mb-4">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => '* Nome Patrocinador'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'buyer_name','class' => 'rounded uppercase','required' => true]); ?>
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
                        <div class="w-1/2 mb-4">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Descrição'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Breve descrição do seu negócio','wire:model.defer' => 'buyer_description','class' => 'rounded uppercase']); ?>
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

                    <div class="w-full flex gap-x-4">
                        <div class="w-1/2 mb-4">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Segmento'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Qual é o seu segmento?','wire:model.defer' => 'buyer_segment','class' => 'rounded uppercase']); ?>
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
                        <div class="w-1/2 mb-4">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => '* Nome do Contato'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'buyer_contact_name','class' => 'rounded uppercase','required' => true]); ?>
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

                    <div class="w-full flex-none md:flex gap-x-4">
                        <div class="w-1/2 md:w-1/2 mb-4">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => '* Email Contato'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'email','wire:model.defer' => 'buyer_email','class' => 'rounded lowercase','required' => true]); ?>
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
                        <div class="w-full md:w-1/2 mb-4">
                            <div class="<?php echo e(setClass('divContentLabel')); ?>">* Telefone Contato</div>
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
<?php $component->withAttributes(['wire:model.defer' => 'buyer_contact_ddd','class' => 'rounded-r-none','required' => true]); ?>
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
<?php $component->withAttributes(['placeholder' => 'Número','wire:model.defer' => 'buyer_contact_num','class' => 'rounded-l-none','required' => true]); ?>
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

                    <div class="w-full flex-none md:flex gap-x-4">
                        <div class="w-full md:w-1/2 mb-4">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Website'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'buyer_url_website','class' => 'rounded lowercase']); ?>
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
                        <div class="w-full md:w-1/2 mb-4">
                            <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve(['label' => 'Instagram','prefix' => '@'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'buyer_url_instagram','class' => 'rounded lowercase']); ?>
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

                    <div class="my-4" style="border-top:1px solid <?php echo e($colorPrimary); ?>12;"></div>

                    
                    <div class="w-full">
                        <div class="<?php echo e(setClass('divContentLabel')); ?> mt-2"><?php echo e(__('buyer_url_logo')); ?> <span class="<?php echo e(setClass('divContentLabelSmall')); ?>">Tamanho max: 5Mb</span></div>
                        <div class="w-full border rounded-xl shadow-sm bg-gray-50 flex justify-center items-end pb-4" style="background:url(<?php echo e($this->buyer_url_logo ? tenantAsset($this->buyer_url_logo) : ''); ?>) center/cover no-repeat <?php echo e($this->buyer_url_logo ? '' : '#f9fafb'); ?>;height:220px;">
                            <?php if($this->buyer_url_logo ?? false): ?>
                                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'Remover'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['xs' => true,'negative' => true,'wire:click' => '$set(\'buyer_url_logo\',false)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                            <?php else: ?>
                                <?php if (isset($component)) { $__componentOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2cba1c7f87bbadef8ee9a6866b4816e = $attributes; } ?>
<?php $component = WireUi\View\Components\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Input::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'buyer_url_logo','type' => 'file']); ?>
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
                        <div wire:loading wire:target="buyer_url_logo" class="text-xs text-gray-400 mt-1">Carregando arquivo...</div>
                    </div>

                    
                    <?php if($patrocinio->buyer_json_questions ?? false): ?>
                        <div class="my-4" style="border-top:1px solid <?php echo e($colorPrimary); ?>12;"></div>
                        <div class="flex flex-col gap-y-3">
                            <?php $__currentLoopData = collect($patrocinio->buyer_json_questions ?? [])->sortBy('input_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $questions_key => $questions_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $name        = $participante_prefix . '_' . $participanteInput . '_' . $questions_key;
                                    $label       = $questions_item['input_label'] ?? $questions_key;
                                    $placeholder = $questions_item['input_placeholder'] ?? '';
                                    $type        = $questions_item['input_type'] ?? 'text';
                                    $options     = $questions_item['input_type_options'] ?? [];
                                    if ($questions_item['input_required'] ?? false) $label = '* ' . $label;
                                ?>
                                <div class="w-full">
                                    <?php if($type == 'select'): ?>
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
                    <?php endif; ?>

                    <div class="my-4" style="border-top:1px solid <?php echo e($colorPrimary); ?>12;"></div>

                    
                    <div class="text-xs text-gray-400 mb-4 text-right"><span class="text-red-500">*</span> Após 7 dias da compra, valor não reembolsável</div>

                    
                    <?php if(session('error')): ?>
                        <div class="w-full mb-3 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center uppercase font-semibold">
                            <?php echo e(__(session('error'))); ?>

                            <?php if(session('error_sub')): ?><div class="text-xs font-normal mt-0.5"><?php echo e(__(session('error_sub'))); ?></div><?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if(session('conclusao_error')): ?>
                        <div class="w-full mb-3 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center uppercase font-semibold">
                            <?php echo e(__(session('conclusao_error'))); ?>

                            <?php if(session('conclusao_error_sub')): ?><div class="text-xs font-normal mt-0.5"><?php echo e(__(session('conclusao_error_sub'))); ?></div><?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if($errors->any()): ?>
                        <div class="w-full mb-3 p-3 rounded-xl bg-red-600 text-white text-center uppercase font-bold text-sm">
                            <?php if(count($errors->all()) > 1): ?>
                                <?php echo e(count($errors->all())); ?> erros foram encontrados
                            <?php else: ?>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($error); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'label' => 'AVANÇAR PARA PAGAMENTO','rightIcon' => 'arrow-right','spinner' => 'concluirAdesao'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['positive' => true,'class' => 'w-full text-base font-bold shadow-lg','wire:click' => 'concluirAdesao']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>

                    
                    <?php
                        $numWhatsapp = false;
                        if (($event->organizer->owner_phone_country ?? false) && ($event->organizer->owner_phone_ddd ?? false) && ($event->organizer->owner_phone_num ?? false)) {
                            $numWhatsapp  = $event->organizer->owner_phone_country . $event->organizer->owner_phone_ddd . $event->organizer->owner_phone_num;
                            $linkWhatsapp = "https://api.whatsapp.com/send?phone=" . $numWhatsapp . "&text=Fazendo contato sobre o evento " . $event->event_name . '.';
                        }
                    ?>
                    <?php if($numWhatsapp ?? false): ?>
                        <div class="mt-6 text-center text-sm text-gray-400">
                            Precisa de ajuda? <a href="<?php echo e($linkWhatsapp); ?>" class="text-indigo-600 hover:underline font-medium" target="_blank">Fale conosco pelo WhatsApp</a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        <?php else: ?>

            
            <div class="w-full rounded-2xl bg-white shadow-xl overflow-hidden" style="border:1px solid <?php echo e($colorPrimary); ?>18;">

                
                <div class="px-5 md:px-8 py-4 md:py-5" style="background:<?php echo e($colorPrimary); ?>08;border-bottom:1px solid <?php echo e($colorPrimary); ?>15;">
                    <div class="uppercase text-xs tracking-widest font-light text-gray-400">PATROCÍNIO</div>
                    <div class="uppercase text-xl md:text-2xl font-bold text-gray-800" style="margin-top:-2px;">PLANOS DISPONÍVEIS</div>
                </div>

                
                <?php if(($event->event_datetime_start ?? false) || ($event->address ?? false) || ($event->url_document_plan ?? false)): ?>
                    <div class="px-5 md:px-8 py-4 flex flex-wrap gap-4 text-sm text-gray-500" style="border-bottom:1px solid <?php echo e($colorPrimary); ?>10;">
                        <?php if($event->event_datetime_start ?? false): ?>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span><?php echo e(formatDateStartFinish($event->event_datetime_start, $event->event_datetime_finish)); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if($event->address ?? false): ?>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span><?php echo e(formatAddress(address:$event->address,city_neighborhood:$event->city_neighborhood,city:$event->city,state:$event->state)); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if($event->url_document_plan ?? false): ?>
                            <a href="<?php echo e(asset($event->url_document_plan)); ?>" class="flex items-center gap-1.5 font-medium transition-colors" style="color:<?php echo e($colorPrimary); ?>;" target="_blank">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Baixar plano de patrocínio (PDF)
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                
                <?php if(session('conclusao_error')): ?>
                    <div class="mx-5 md:mx-8 mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center uppercase font-semibold">
                        <?php echo e(__(session('conclusao_error'))); ?>

                        <?php if(session('conclusao_error_sub')): ?><div class="text-xs font-normal mt-0.5"><?php echo e(__(session('conclusao_error_sub'))); ?></div><?php endif; ?>
                    </div>
                <?php endif; ?>

                
                <div class="px-5 md:px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                        <?php $__empty_1 = true; $__currentLoopData = $planos ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plano_id => $plano_values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <div class="rounded-xl overflow-hidden flex flex-col <?php echo e(($plano_values->loteFechado ?? false) || ($plano_values->esgotado ?? false) ? 'opacity-60' : ''); ?>" style="border:1px solid <?php echo e($colorPrimary); ?>30;box-shadow:0 2px 8px rgba(0,0,0,0.06);">

                                
                                <div class="px-4 py-3" style="background:<?php echo e($colorPrimary); ?>10;border-bottom:1px solid <?php echo e($colorPrimary); ?>20;">
                                    <div class="text-xs uppercase font-semibold tracking-widest text-gray-400">PLANO</div>
                                    <div class="font-extrabold uppercase leading-tight" style="font-size:clamp(1rem,2.5vw,1.3rem);color:<?php echo e($colorPrimary); ?>;"><?php echo e($plano_values->name); ?></div>
                                </div>

                                
                                <div class="px-4 py-3 flex-1 flex flex-col justify-between gap-3">

                                    <?php if($plano_values->description ?? false): ?>
                                        <div class="text-sm text-gray-500 leading-relaxed"><?php echo $plano_values->description; ?></div>
                                    <?php endif; ?>

                                    <div>
                                        
                                        <div class="mb-3">
                                            <div class="text-xs uppercase font-semibold tracking-widest text-gray-400 mb-0.5">INVESTIMENTO</div>
                                            <div class="font-extrabold" style="font-size:clamp(1.3rem,4vw,2rem);color:<?php echo e($colorPrimary); ?>;">
                                                <?php echo e(toMoney($plano_values->price ?? 0, 'R$ ')); ?>

                                            </div>
                                        </div>

                                        
                                        <?php if($plano_values->esgotado ?? false): ?>
                                            <div class="w-full py-2 text-center text-white text-sm font-bold uppercase rounded-lg bg-red-500">ESGOTADO</div>
                                        <?php elseif($plano_values->loteFechado ?? false): ?>
                                            <div class="w-full py-2 text-center text-white text-sm font-bold uppercase rounded-lg bg-red-500">VENDAS ENCERRADAS</div>
                                        <?php else: ?>
                                            <button
                                                wire:click="selecionarPlano('<?php echo e($plano_values->id); ?>')"
                                                type="button"
                                                class="w-full py-2.5 text-center text-sm font-bold uppercase rounded-lg shadow transition-opacity hover:opacity-80"
                                                style="background-color:<?php echo e($colorDefault); ?>;color:<?php echo e($colorInverse); ?>;"
                                            >
                                                ADERIR AO PLANO
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                </div>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full text-center text-gray-400 text-sm uppercase py-8 font-medium">
                                SEM PLANOS DE PATROCÍNIO DISPONÍVEIS
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                
                <?php if($event->event_text_footer ?? false): ?>
                    <div class="px-5 md:px-8 py-4" style="border-top:1px solid <?php echo e($colorPrimary); ?>10;background:<?php echo e($colorPrimary); ?>04;">
                        <div class="text-center text-sm text-gray-500"><?php echo e($event->event_text_footer); ?></div>
                    </div>
                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div>

    
    <div class="pb-12"></div>

</div>

<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/app-evento-patrocinar.blade.php ENDPATH**/ ?>