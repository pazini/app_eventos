<div class="min-h-screen bg-white">

    @php
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
    @endphp

    {{-- LIVEWIRE - LOADER --}}
    <div wire:loading.class.remove="hidden" class="hidden fixed inset-0 z-[999] flex items-center justify-center" style="background:rgba(255,255,255,0.80);backdrop-filter:blur(6px);">
        <div class="flex flex-col items-center gap-4">
            <img src="{{ asset('/assets/loader.v2.svg') }}" alt="" class="w-16 h-16">
            <span class="text-gray-500 text-sm uppercase tracking-widest font-light">Aguarde...</span>
        </div>
    </div>
    {{-- LIVEWIRE - LOADER FIM --}}

    <style>@keyframes heroBgDrift{0%{transform:scale(1.08) translate(0%,0%)}25%{transform:scale(1.13) translate(-1.5%,-1%)}50%{transform:scale(1.10) translate(1%,-2%)}75%{transform:scale(1.14) translate(-0.5%,1%)}100%{transform:scale(1.08) translate(0%,0%)}}.hero-bg-animate{animation:heroBgDrift 24s ease-in-out infinite;will-change:transform;}</style>

    {{-- ═══════════════════════════════════════
        HERO
    ════════════════════════════════════════ --}}
    <section class="relative w-full overflow-hidden" style="min-height: 260px;">

        {{-- Background --}}
        @if ($urlImageBg)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hero-bg-animate" style="background-image:url('{{ $urlImageBg }}');filter:blur(2px) brightness(0.35);"></div>
            <div class="absolute inset-0" style="background:linear-gradient(160deg,{{ $colorPrimary }}88 0%,rgba(10,10,20,0.92) 100%);"></div>
        @else
            <div class="absolute inset-0" style="background:linear-gradient(135deg,{{ $colorPrimary }} 0%,{{ $colorSecondary }} 50%,rgba(10,10,20,1) 100%);"></div>
        @endif

        {{-- Glow --}}
        <div class="absolute rounded-full pointer-events-none" style="top:-5rem;left:-5rem;width:24rem;height:24rem;opacity:0.2;filter:blur(60px);background:{{ $colorPrimary }};"></div>
        <div class="absolute rounded-full pointer-events-none" style="bottom:-2.5rem;right:-2.5rem;width:18rem;height:18rem;opacity:0.15;filter:blur(60px);background:{{ $colorSecondary }};"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 md:px-10" style="padding-top:2rem;padding-bottom:7rem;">

            {{-- Logo + badge status --}}
            <div class="flex items-center justify-between w-full gap-3 mb-6">
                <div class="flex items-center gap-4">
                    @if ($urlImageLogo)
                        <img class="w-auto drop-shadow-lg" style="height:3.5rem;" src="{{ $urlImageLogo }}" alt="">
                    @else
                        <img class="w-auto drop-shadow-lg" style="height:3.5rem;" src="{{ appLogo(true) }}" alt="{{ appName() }}">
                    @endif
                </div>
                <div>
                    <span class="inline-block px-5 py-2 text-xs font-semibold uppercase tracking-wider rounded-full shadow-lg" style="background-color:{{ $colorDefault }};color:{{ $colorInverse }};">PATROCÍNIO</span>
                </div>
            </div>

            {{-- Localização --}}
            @php $heroCidade = collect([$event->city ?? null, $event->state ?? null])->filter()->implode(', '); @endphp
            @if ($heroCidade)
                <div class="inline-flex items-center gap-1.5 mb-3 px-3 py-1 rounded-full text-xs font-medium uppercase" style="background:{{ $colorPrimary }}33;color:{{ $colorInverse }};border:1px solid {{ $colorPrimary }}55;letter-spacing:0.12em;">
                    <svg class="w-3 h-3" style="opacity:0.8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $heroCidade }}
                </div>
            @endif

            {{-- Nome do evento --}}
            <h1 class="text-white font-extrabold uppercase leading-tight" style="font-size:clamp(1.5rem,4vw,2.5rem);letter-spacing:-0.01em;">{{ $event->event_name ?? '--' }}</h1>

            @if ($event->event_description ?? false)
                <p class="mt-1 font-medium uppercase tracking-wide leading-relaxed" style="font-size:clamp(0.9rem,2vw,1.1rem);color:rgba(255,255,255,0.6);">{{ $event->event_description }}</p>
            @endif

            {{-- Quando / Local --}}
            @if ($event->event_datetime_start ?? false)
                <div class="mt-3 flex items-center gap-2" style="color:rgba(255,255,255,0.6);font-size:0.8rem;font-weight:500;text-transform:uppercase;letter-spacing:0.1em;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ formatDateStartFinish($event->event_datetime_start, $event->event_datetime_finish) }}
                </div>
            @endif

            {{-- Plano selecionado (no hero, quando plano escolhido) --}}
            @if ($plano ?? false)
                <style>.pln-banner-row{display:flex;align-items:center;justify-content:space-between;gap:0.75rem;}@media(max-width:639px){.pln-banner-row{flex-direction:column;align-items:flex-start;gap:0.25rem;}}</style>
                <div style="margin-top:1.5rem;display:block;width:100%;background:rgba(255,255,255,0.12);border:2px solid rgba(255,255,255,0.28);border-radius:1rem;padding:1rem 1.5rem;box-sizing:border-box;">
                    <div style="color:rgba(255,255,255,0.55);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.18em;margin-bottom:0.3rem;">PLANO DE PATROCÍNIO SELECIONADO</div>
                    <div class="pln-banner-row">
                        <div>
                            <div style="color:#ffffff;font-size:clamp(1.2rem,3.5vw,1.8rem);font-weight:900;text-transform:uppercase;line-height:1.1;">{{ $plano->name }}</div>
                            @if ($plano->description ?? false)
                                <div style="color:rgba(255,255,255,0.5);font-size:0.75rem;font-weight:400;text-transform:uppercase;letter-spacing:0.08em;margin-top:0.2rem;">{{ strip_tags($plano->description) }}</div>
                            @endif
                        </div>
                        <div style="flex-shrink:0;">
                            <span style="color:rgba(255,255,255,0.9);font-size:clamp(1rem,2.5vw,1.4rem);font-weight:700;">{{ toMoney($plano->price ?? 0, 'R$ ') }}</span>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

    {{-- ═══════════════════════════════════════
        CONTEÚDO PRINCIPAL (overlap hero)
    ════════════════════════════════════════ --}}
    <div class="w-full max-w-4xl mx-auto px-4 md:px-10 relative z-20" style="margin-top:-3.5rem;">

        @if ($plano ?? false)

            {{-- ─── FORMULÁRIO DE ADESÃO ─── --}}
            <div class="w-full rounded-2xl bg-white shadow-xl overflow-hidden" style="border:1px solid {{ $colorPrimary }}18;">

                {{-- Header do card --}}
                <div class="px-5 md:px-8 py-4 md:py-5 flex items-center justify-between gap-4" style="background:{{ $colorPrimary }}08;border-bottom:1px solid {{ $colorPrimary }}15;">
                    <div>
                        <div class="uppercase text-xs tracking-widest font-light text-gray-400">PATROCÍNIO</div>
                        <div class="uppercase text-xl md:text-2xl font-bold text-gray-800" style="margin-top:-2px;">ADESÃO AO PLANO</div>
                    </div>
                    <button wire:click="cancelarPlano" type="button" class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-lg transition-colors uppercase font-medium">
                        ← Voltar
                    </button>
                </div>

                <div class="px-5 md:px-8 py-5">

                    @php
                        $listaDdd = ['21','11','12','13','14','15','16','17','18','19','22','24','27','28','31','32','33','34','35','37','38','41','42','43','44','45','46','47','48','49','51','53','54','55','61','62','63','64','65','66','67','68','69','71','73','74','75','77','79','81','82','83','84','85','86','87','88','89','91','92','93','94','95','96','97','98','99'];
                    @endphp

                    {{-- CNPJ/CPF --}}
                    <div class="w-full md:w-1/2 mb-4">
                        <x-inputs.maskable
                            label="* CNPJ ou CPF Patrocinador"
                            mask="['###.###.###-##','##.###.###/####-##']"
                            wire:model.lazy="buyer_doc_num"
                            required
                        />
                    </div>

                    <div class="my-4" style="border-top:1px solid {{ $colorPrimary }}12;"></div>

                    <div class="w-full flex gap-x-4">
                        <div class="w-1/2 mb-4">
                            <x-input label="* Nome Patrocinador" wire:model.defer="buyer_name" class="rounded uppercase" required />
                        </div>
                        <div class="w-1/2 mb-4">
                            <x-input label="Descrição" placeholder="Breve descrição do seu negócio" wire:model.defer="buyer_description" class="rounded uppercase" />
                        </div>
                    </div>

                    <div class="w-full flex gap-x-4">
                        <div class="w-1/2 mb-4">
                            <x-input label="Segmento" placeholder="Qual é o seu segmento?" wire:model.defer="buyer_segment" class="rounded uppercase" />
                        </div>
                        <div class="w-1/2 mb-4">
                            <x-input label="* Nome do Contato" wire:model.defer="buyer_contact_name" class="rounded uppercase" required />
                        </div>
                    </div>

                    <div class="w-full flex-none md:flex gap-x-4">
                        <div class="w-1/2 md:w-1/2 mb-4">
                            <x-input label="* Email Contato" type="email" wire:model.defer="buyer_email" class="rounded lowercase" required />
                        </div>
                        <div class="w-full md:w-1/2 mb-4">
                            <div class="{{ setClass('divContentLabel') }}">* Telefone Contato</div>
                            <div class="w-full flex mt-1">
                                <div class="w-1/2">
                                    <x-native-select placeholder="DDD" :options="$listaDdd ?? []" wire:model.defer="buyer_contact_ddd" class="rounded-r-none" required />
                                </div>
                                <div class="w-1/2">
                                    <x-inputs.maskable mask="['####-####','#####-####']" placeholder="Número" wire:model.defer="buyer_contact_num" class="rounded-l-none" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full flex-none md:flex gap-x-4">
                        <div class="w-full md:w-1/2 mb-4">
                            <x-input wire:model.defer="buyer_url_website" label="Website" class="rounded lowercase" />
                        </div>
                        <div class="w-full md:w-1/2 mb-4">
                            <x-input wire:model.defer="buyer_url_instagram" label="Instagram" prefix="@" class="rounded lowercase" />
                        </div>
                    </div>

                    <div class="my-4" style="border-top:1px solid {{ $colorPrimary }}12;"></div>

                    {{-- LOGO --}}
                    <div class="w-full">
                        <div class="{{ setClass('divContentLabel') }} mt-2">{{ __('buyer_url_logo') }} <span class="{{ setClass('divContentLabelSmall') }}">Tamanho max: 5Mb</span></div>
                        <div class="w-full border rounded-xl shadow-sm bg-gray-50 flex justify-center items-end pb-4" style="background:url({{ $this->buyer_url_logo ? tenantAsset($this->buyer_url_logo) : '' }}) center/cover no-repeat {{ $this->buyer_url_logo ? '' : '#f9fafb' }};height:220px;">
                            @if ($this->buyer_url_logo ?? false)
                                <x-button xs negative label="Remover" wire:click="$set('buyer_url_logo',false)" />
                            @else
                                <x-input wire:model="buyer_url_logo" type="file" />
                            @endif
                        </div>
                        <div wire:loading wire:target="buyer_url_logo" class="text-xs text-gray-400 mt-1">Carregando arquivo...</div>
                    </div>

                    {{-- Perguntas customizadas --}}
                    @if ($patrocinio->buyer_json_questions ?? false)
                        <div class="my-4" style="border-top:1px solid {{ $colorPrimary }}12;"></div>
                        <div class="flex flex-col gap-y-3">
                            @foreach (collect($patrocinio->buyer_json_questions ?? [])->sortBy('input_order') as $questions_key => $questions_item)
                                @php
                                    $name        = $participante_prefix . '_' . $participanteInput . '_' . $questions_key;
                                    $label       = $questions_item['input_label'] ?? $questions_key;
                                    $placeholder = $questions_item['input_placeholder'] ?? '';
                                    $type        = $questions_item['input_type'] ?? 'text';
                                    $options     = $questions_item['input_type_options'] ?? [];
                                    if ($questions_item['input_required'] ?? false) $label = '* ' . $label;
                                @endphp
                                <div class="w-full">
                                    @if ($type == 'select')
                                        <x-native-select label="{{ $label }}" wire:model.defer="{{ $name }}" title="{{ $placeholder }}" class="w-full uppercase">
                                            <option value="">---</option>
                                            @foreach ($options ?? [] as $option_item)
                                                <option value="{{ $option_item }}">{{ $option_item }}</option>
                                            @endforeach
                                        </x-native-select>
                                    @else
                                        <x-input label="{{ $label }}" wire:model.defer="{{ $name }}" placeholder="{{ $placeholder }}" class="w-full uppercase" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="my-4" style="border-top:1px solid {{ $colorPrimary }}12;"></div>

                    {{-- Mensagem de aviso --}}
                    <div class="text-xs text-gray-400 mb-4 text-right"><span class="text-red-500">*</span> Após 7 dias da compra, valor não reembolsável</div>

                    {{-- Erros --}}
                    @if (session('error'))
                        <div class="w-full mb-3 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center uppercase font-semibold">
                            {{ __(session('error')) }}
                            @if (session('error_sub'))<div class="text-xs font-normal mt-0.5">{{ __(session('error_sub')) }}</div>@endif
                        </div>
                    @endif
                    @if (session('conclusao_error'))
                        <div class="w-full mb-3 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center uppercase font-semibold">
                            {{ __(session('conclusao_error')) }}
                            @if (session('conclusao_error_sub'))<div class="text-xs font-normal mt-0.5">{{ __(session('conclusao_error_sub')) }}</div>@endif
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="w-full mb-3 p-3 rounded-xl bg-red-600 text-white text-center uppercase font-bold text-sm">
                            @if (count($errors->all()) > 1)
                                {{ count($errors->all()) }} erros foram encontrados
                            @else
                                @foreach ($errors->all() as $error){{ $error }}@endforeach
                            @endif
                        </div>
                    @endif

                    {{-- Botão confirmar --}}
                    <x-button rounded positive label="AVANÇAR PARA PAGAMENTO" right-icon="arrow-right" class="w-full text-base font-bold shadow-lg" wire:click="concluirAdesao" spinner="concluirAdesao" />

                    {{-- WhatsApp --}}
                    @php
                        $numWhatsapp = false;
                        if (($event->organizer->owner_phone_country ?? false) && ($event->organizer->owner_phone_ddd ?? false) && ($event->organizer->owner_phone_num ?? false)) {
                            $numWhatsapp  = $event->organizer->owner_phone_country . $event->organizer->owner_phone_ddd . $event->organizer->owner_phone_num;
                            $linkWhatsapp = "https://api.whatsapp.com/send?phone=" . $numWhatsapp . "&text=Fazendo contato sobre o evento " . $event->event_name . '.';
                        }
                    @endphp
                    @if ($numWhatsapp ?? false)
                        <div class="mt-6 text-center text-sm text-gray-400">
                            Precisa de ajuda? <a href="{{ $linkWhatsapp }}" class="text-indigo-600 hover:underline font-medium" target="_blank">Fale conosco pelo WhatsApp</a>
                        </div>
                    @endif

                </div>
            </div>

        @else

            {{-- ─── LISTAGEM DE PLANOS ─── --}}
            <div class="w-full rounded-2xl bg-white shadow-xl overflow-hidden" style="border:1px solid {{ $colorPrimary }}18;">

                {{-- Header --}}
                <div class="px-5 md:px-8 py-4 md:py-5" style="background:{{ $colorPrimary }}08;border-bottom:1px solid {{ $colorPrimary }}15;">
                    <div class="uppercase text-xs tracking-widest font-light text-gray-400">PATROCÍNIO</div>
                    <div class="uppercase text-xl md:text-2xl font-bold text-gray-800" style="margin-top:-2px;">PLANOS DISPONÍVEIS</div>
                </div>

                {{-- Info do evento --}}
                @if (($event->event_datetime_start ?? false) || ($event->address ?? false) || ($event->url_document_plan ?? false))
                    <div class="px-5 md:px-8 py-4 flex flex-wrap gap-4 text-sm text-gray-500" style="border-bottom:1px solid {{ $colorPrimary }}10;">
                        @if ($event->event_datetime_start ?? false)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ formatDateStartFinish($event->event_datetime_start, $event->event_datetime_finish) }}</span>
                            </div>
                        @endif
                        @if ($event->address ?? false)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span>{{ formatAddress(address:$event->address,city_neighborhood:$event->city_neighborhood,city:$event->city,state:$event->state) }}</span>
                            </div>
                        @endif
                        @if ($event->url_document_plan ?? false)
                            <a href="{{ asset($event->url_document_plan) }}" class="flex items-center gap-1.5 font-medium transition-colors" style="color:{{ $colorPrimary }};" target="_blank">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Baixar plano de patrocínio (PDF)
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Erros de sessão --}}
                @if (session('conclusao_error'))
                    <div class="mx-5 md:mx-8 mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center uppercase font-semibold">
                        {{ __(session('conclusao_error')) }}
                        @if (session('conclusao_error_sub'))<div class="text-xs font-normal mt-0.5">{{ __(session('conclusao_error_sub')) }}</div>@endif
                    </div>
                @endif

                {{-- Grid de planos --}}
                <div class="px-5 md:px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                        @forelse ($planos ?? [] as $plano_id => $plano_values)

                            <div class="rounded-xl overflow-hidden flex flex-col {{ ($plano_values->loteFechado ?? false) || ($plano_values->esgotado ?? false) ? 'opacity-60' : '' }}" style="border:1px solid {{ $colorPrimary }}30;box-shadow:0 2px 8px rgba(0,0,0,0.06);">

                                {{-- Topo colorido --}}
                                <div class="px-4 py-3" style="background:{{ $colorPrimary }}10;border-bottom:1px solid {{ $colorPrimary }}20;">
                                    <div class="text-xs uppercase font-semibold tracking-widest text-gray-400">PLANO</div>
                                    <div class="font-extrabold uppercase leading-tight" style="font-size:clamp(1rem,2.5vw,1.3rem);color:{{ $colorPrimary }};">{{ $plano_values->name }}</div>
                                </div>

                                {{-- Corpo --}}
                                <div class="px-4 py-3 flex-1 flex flex-col justify-between gap-3">

                                    @if ($plano_values->description ?? false)
                                        <div class="text-sm text-gray-500 leading-relaxed">{!! $plano_values->description !!}</div>
                                    @endif

                                    <div>
                                        {{-- Preco --}}
                                        <div class="mb-3">
                                            <div class="text-xs uppercase font-semibold tracking-widest text-gray-400 mb-0.5">INVESTIMENTO</div>
                                            <div class="font-extrabold" style="font-size:clamp(1.3rem,4vw,2rem);color:{{ $colorPrimary }};">
                                                {{ toMoney($plano_values->price ?? 0, 'R$ ') }}
                                            </div>
                                        </div>

                                        {{-- Botao --}}
                                        @if ($plano_values->esgotado ?? false)
                                            <div class="w-full py-2 text-center text-white text-sm font-bold uppercase rounded-lg bg-red-500">ESGOTADO</div>
                                        @elseif ($plano_values->loteFechado ?? false)
                                            <div class="w-full py-2 text-center text-white text-sm font-bold uppercase rounded-lg bg-red-500">VENDAS ENCERRADAS</div>
                                        @else
                                            <button
                                                wire:click="selecionarPlano('{{ $plano_values->id }}')"
                                                type="button"
                                                class="w-full py-2.5 text-center text-sm font-bold uppercase rounded-lg shadow transition-opacity hover:opacity-80"
                                                style="background-color:{{ $colorDefault }};color:{{ $colorInverse }};"
                                            >
                                                ADERIR AO PLANO
                                            </button>
                                        @endif
                                    </div>

                                </div>

                            </div>

                        @empty
                            <div class="col-span-full text-center text-gray-400 text-sm uppercase py-8 font-medium">
                                SEM PLANOS DE PATROCÍNIO DISPONÍVEIS
                            </div>
                        @endforelse

                    </div>
                </div>

                {{-- Footer do evento --}}
                @if ($event->event_text_footer ?? false)
                    <div class="px-5 md:px-8 py-4" style="border-top:1px solid {{ $colorPrimary }}10;background:{{ $colorPrimary }}04;">
                        <div class="text-center text-sm text-gray-500">{{ $event->event_text_footer }}</div>
                    </div>
                @endif

            </div>

        @endif

    </div>

    {{-- Espaco inferior --}}
    <div class="pb-12"></div>

</div>

