<div class="w-full max-w-7xl mx-auto mb-10">

    @if ($target ?? false)

        {{-- HEADER MODERNO COM GRADIENTE --}}
        <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-evento" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-evento)"/>
                </svg>
            </div>
            <div class="relative z-10 p-6 space-y-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">{{ $target->event_name }}</h1>
                                <p class="text-white/90 text-sm">{{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}</p>
                            </div>
                        </div>
                    </div>
                    <x-button flat white icon="switch-horizontal" wire:click="alterarTarget" class="hover:bg-white/20" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <x-button white sm class="hover:bg-white/20" icon="cog" label="Layout Página" href="{{ route('evento-layout-pagina-uuid', $target->id) }}" />
                    <x-button white sm class="hover:bg-white/20" icon="cog" label="Gestão Orçamentária" href="{{ route('dashboard-financeiro-gestao-orcamentaria-uuid', $target->id) }}" />
                    <x-button white sm class="hover:bg-white/20" icon="cog" label="Sumário de Vendas" href="{{ route('dashboard-evento-vendas-sumario-uuid', $target->id) }}" />
                    <x-button white sm class="hover:bg-white/20" icon="cog" label="Notificações" href="{{ route('notifica-uuid', $target->id) }}" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <x-button white outline sm class="hover:bg-white/20" icon="users" label="Participantes" href="{{ route('dashboard-vendas',['target_ref' => 'evento', 'target_slug' => $target->event_slug, 'target_id' => $target->id, 'view_status' => 'participantes']) }}" />
                    <x-button white outline sm class="hover:bg-white/20" icon="currency-dollar" label="Vendas" href="{{ route('dashboard-evento-vendas-uuid', $target->id) }}" />
                    <x-button white outline sm class="hover:bg-white/20" icon="desktop-computer" right-icon="external-link" label="Página Evento" href="{{ eventoUrl($target->event_slug) }}" target="_blank" />
                    <x-button white outline sm class="hover:bg-white/20" icon="desktop-computer" right-icon="external-link" label="Página Patrocínio" href="{{ eventoPatrocinarUrl($target->event_slug) }}" target="_blank" />
                </div>
            </div>
        </div>

        <div class="mb-6">
            <x-jet-validation-errors />
        </div>

        {{-- DADOS DO EVENTO --}}
        <div class="mb-8 bg-white shadow-md border border-gray-200 rounded-lg">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Dados do Evento</h2>
                <x-button flat lime label="ALTERAR" href="{{route('altera-evento')}}" />
            </div>

            @php
                $ticketStatus                 = $target->tickets->whereIn('ticket_status',ticketStatusCapacidade()) ?? 0;
                $ticketStatusCount            = $ticketStatus->count() ?? 0;
                $ticketStatusCountReservaTemp = $ticketStatus->whereIn('ticket_status',ticketStatusTemp())->count() ?? 0;
                //
                $ticketStatusCount = $ticketStatusCount - $ticketStatusCountReservaTemp;
                //
                $ticketDisponiveisCount = ($target->sales_amount_max ?? 0) - $ticketStatusCount;
            @endphp

            <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-2 items-start p-6">

                <div class="col-span-full md:col-span-4 break-words" title="{{ $target->event_name ?? null }}">
                    {!! setLabel('event_name', $target->event_name ?? null) !!}
                </div>

                <div class="col-span-full md:col-span-8 break-words" title="{{ $target->event_description ?? null }}">
                    {!! setLabel('event_description', $target->event_description ?? null) !!}
                </div>

                <div class="col-span-full md:col-span-full mb-2">
                    <hr>
                </div>

                <div class="col-span-full md:col-span-3">
                    {!! setLabel('Total de Vagas',$target->sales_amount_max ?? 0, translate:false) !!}
                </div>

                <div class="col-span-full md:col-span-3">
                    {!! setLabel('Ocupadas',$ticketStatusCount ?? 0, translate:false) !!}
                </div>

                <div class="col-span-full md:col-span-3">
                    {!! setLabel('Disponíveis',$ticketDisponiveisCount ?? 0, translate:false) !!}
                </div>

                <div class="col-span-full md:col-span-3 flex flex-col justify-end md:justify-center items-end">

                    <div class="flex justify-center items-center">
                        <div class="text-3xl font-semibold">{{ $ticketStatusCount }}</div>
                        <div class="text-4xl font-light">/</div>
                        <div class="text-xl ml-0 mt-2 mx-1 font-semibold">{{ $target->sales_amount_max ?? 0 }}</div>
                        <div class="ml-1 mt-2">
                            @if ($ticketStatusCountReservaTemp ?? false)
                                <div class="text-sm bg-white rounded-full border-b shadow-sm uppercase flex justify-center items-center mb-0.5" title="Reservas temporárias">
                                    <div>{{ $ticketStatusCountReservaTemp }}</div>
                                    <x-icon name="clock" class="ml-1 w-3 h-3" />
                                </div>
                            @endif
                            <div class="text-sm font-normal">({{ calculaPorcentagem($target->sales_amount_max, $ticketStatusCount,'%') }})</div>
                        </div>
                    </div>
                </div>

                <div class="col-span-full md:col-span-full mb-2">
                    <hr>
                </div>

                <div class="col-span-full md:col-span-3">
                    {!! setLabel('event_datetime_start',$target->event_datetime_start ? \Carbon\Carbon::parse($target->event_datetime_start)->format('d/m/Y H:i') : '--' ?? null) !!}
                </div>

                <div class="col-span-full md:col-span-3">
                    {!! setLabel('event_datetime_finish',$target->event_datetime_finish ? \Carbon\Carbon::parse($target->event_datetime_finish)->format('d/m/Y H:i') : '--' ?? null) !!}
                </div>

                <div class="col-span-full md:col-span-3">
                    {!! setLabel('type', ucfirst($target->type) ?? null) !!}
                </div>

                <div class="col-span-full md:col-span-3">
                    @if ($target->active ?? false)
                        {!! setLabel('Busca', "<span class='text-green-600 font-bold'>Evento Público</span>") !!}
                    @else
                        {!! setLabel('Busca', "<span class='text-blue-600 font-bold'>Apenas Link Direto </span>") !!}
                    @endif
                </div>

                <div class="col-span-full md:col-span-3">
                    {!! setLabel('category',  ucfirst($target->category) ?? null) !!}
                </div>

                <div class="col-span-full md:col-span-3">
                    {!! setLabel('sales_label',$target->sales_label ?? null) !!}
                </div>

                <div class="col-span-full md:col-span-3">
                    {!! setLabel('sales_btn',$target->sales_btn ?? null) !!}
                </div>

                <div class="col-span-full md:col-span-3">
                    {!! setLabel('event_tickets_nomenclature',$target->event_tickets_nomenclature ?? null) !!}
                </div>

                <div class="col-span-full md:col-span-6">
                    {!! setLabel('Utilizador Tipo', ($target->sales_label_item ?? '---') . ' / ' . ($target->sales_label_item_multiple ?? '---')) !!}
                </div>

            </div>

        </div>

        {{-- PAGAMENTOS --}}
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Método Pagamento</h2>
                @if ($target->pay_gateway_id ?? false)
                    <x-button flat lime label="ALTERAR" href="{{ route('evento-metodo-pagamento') }}" />
                @else
                    <x-button flat lime label="CADASTRAR" href="{{ route('evento-metodo-pagamento') }}" />
                @endif
            </div>

            <div class="p-6">
                <div class="w-full grid grid-cols-12 gap-2">

                @if ($target->pay_gateway_id ?? false)

                    <div class="col-span-full md:col-span-10">
                        {!! setLabel($target->gatewayPay->appGateway->gateway_name ?? 'Conta Pagamentos', $target->gatewayPay->pay_gateway_label ?? null) !!}
                    </div>

                    <div class="col-span-full md:col-span-2">
                        @if ($target->pay_sandbox ?? false)
                            {!! setLabel('Processamento', "<span class='text-blue-600 font-bold'>EM MODO TESTE</span>") !!}
                        @else
                            {!! setLabel('Processamento', "<span class='text-green-600 font-bold'>ATIVADO</span>") !!}
                        @endif
                    </div>

                    @if ($target->pay_boleto ?? false)
                        <div class="col-span-full md:col-span-4 bg-green-50 p-2 border border-gray-300 rounded shadow">
                            <div class="w-full flex justify-between items-center gap-1 font-semibold">
                                <div class="text-xl">BOLETO</div>
                                <x-icon name="check-circle" class="w-7 h-7 text-green-700" solid />
                            </div>
                            <div class="w-full flex justify-start gap-1 mt-1">
                                <div class="text-sm font-light">DATA LIMITE <span class="font-semibold">{{ $target->pay_boleto_date_end ? $target->pay_boleto_date_end->format('d/m/Y') : '' }}</span></div>
                            </div>
                        </div>
                    @endif

                    @if ($target->pay_pix ?? false)
                        <div class="col-span-full md:col-span-4 bg-green-50 p-2 border border-gray-300 rounded shadow">
                            <div class="w-full flex justify-between items-center gap-1 font-semibold">
                                @if ($target->pay_slip_pix ?? false)
                                    <div class="text-xl">PIX + CARNÊ ONLINE</div>
                                @else
                                    <div class="text-xl">PIX</div>
                                @endif
                                <x-icon name="check-circle" class="w-7 h-7 text-green-700" solid />
                            </div>
                            @if ($target->pay_slip_pix ?? false)
                                <div class="w-full flex justify-start gap-1 mt-1">
                                    <div class="text-sm font-light">MÁXIMO <span class="font-semibold">{{ $target->pay_slip_pix_installment_max . 'x' }}</span></div>
                                    <div class="text-sm font-light">PARCELA MÍNIMA <span class="font-semibold">{{ toMoney($target->pay_slip_pix_installment_amount_min ?? 0, 'R$ ') }}</span></div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($target->pay_card_credit ?? false)
                        <div class="col-span-full md:col-span-4 bg-green-50 p-2 border border-gray-300 rounded shadow">

                            <div class="w-full flex justify-between items-center gap-1 font-semibold">
                                <div class="text-xl">CARTÃO DE CRÉDITO</div>
                                <x-icon name="check-circle" class="w-7 h-7 text-green-700" solid />
                            </div>

                            <div class="w-full flex justify-start gap-1 mt-1">
                                <div class="text-sm font-light">MÁXIMO <span class="font-semibold">{{ $target->pay_card_credit_installment_max . 'x' }}</span></div>
                                <div class="text-sm font-light">PARCELA MÍNIMA <span class="font-semibold">{{ toMoney($target->pay_card_credit_installment_amount_min ?? 0, 'R$ ') }}</span></div>
                            </div>
                        </div>
                    @endif

                @else

                    <div class="col-span-full flex justify-between">
                        <div class="text-lg font-light">NÃO POSSUI</div>
                    </div>

                @endif

                </div>
            </div>
        </div>

        {{-- LOTES --}}
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Lotes</h2>
                <x-button flat lime label="CADASTRAR" href="{{ route('evento-lote') }}" />
            </div>
            <div class="p-6">

            <div class="w-full flex flex-col gap-6">

                @forelse ($target->ticketsTypes->sortBy('created_at') ?? [] as $ticketsType)

                    <div class="border border-gray-300 rounded-r">

                        @if ($ticketsType->visible ?? false)
                            <div class="{{ setClass('divForItem') }} bg-green-50 hover:bg-gray-100">
                        @else
                            <div class="{{ setClass('divForItem') }} bg-red-200 opacity-70">
                        @endif
                            <div class="w-full grid grid-cols-12 items-center gap-4">

                                <div class="col-span-full md:col-span-10">
                                    <div class="text-xl font-semibold break-words flex items-center gap-2">
                                        <div class="break-words">{{ $ticketsType->ticket_name ?? '---' }}</div>
                                        <div>
                                            @if ($ticketsType->lote_publico ?? false)
                                                <x-icon name="eye" class="w-5 h-5" />
                                            @else
                                                <x-icon name="eye-off" class="w-5 h-5" />
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-base font-normal break-words">{{ $ticketsType->ticket_description ?? '---' }}</div>
                                </div>

                                <div class="col-span-full md:col-span-2">
                                    @if ($ticketsType->visible ?? false)
                                        <div class="w-full flex justify-center md:justify-end">
                                            <x-button flat primary label="ALTERAR" href="{{ route('evento-lote',['ticket_type_id' => $ticketsType->id]) }}" class="w-auto -mt-4" />
                                        </div>
                                    @else
                                        <div class="w-full bg-white text-red-800 rounded-sm shadow-sm px-2 py-1">
                                            <div class="text-sm font-semibold">REMOVIDO EM</div>
                                            <div class="text-sm font-normal">{{ $ticketsType->updated_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-span-full md:col-span-full mb-2">
                                    <hr>
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('sale_start_datetime', $ticketsType->sale_start_datetime ? $ticketsType->sale_start_datetime->format('d/m/Y H:i') : '---' ?? null) !!}
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('sale_finish_datetime', $ticketsType->sale_finish_datetime ? $ticketsType->sale_finish_datetime->format('d/m/Y H:i') : '---' ?? null) !!}
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('Inscritos x Qtd.Lote',  ($ticketsType->tickets->whereIn('ticket_status',['utilizado','disponivel'])->count()  ?? null) . ' de ' . ($ticketsType->amount ?? '---'), bodyU:false) !!}
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('price', toMoney($ticketsType->price, 'R$ ') ?? null) !!}
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('sale_period_type', $ticketsType->sale_period_type ?? null) !!}
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('sale_ticket_availability', $ticketsType->sale_ticket_availability ?? null) !!}
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('sale_label_title', $ticketsType->sale_label_title ?? null) !!}
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('sale_label_btn', $ticketsType->sale_label_btn ?? null) !!}
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex justify-between">
                        <div class="text-lg font-light">NÃO POSSUI</div>
                    </div>
                @endforelse

            </div>
            </div>
        </div>

        {{-- LOCAL --}}
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Local</h2>
            </div>
            <div class="p-6">
                <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4">

                    <div class="col-span-full md:col-span-6">
                        {!! setLabel('address', $target->address ?? null) !!}
                    </div>

                    <div class="col-span-full md:col-span-3">
                        {!! setLabel('address_number', $target->address_number ?? null) !!}
                    </div>

                    <div class="col-span-full md:col-span-3">
                        {!! setLabel('address_complement', $target->address_complement ?? null) !!}
                    </div>

                    <div class="col-span-full md:col-span-3">
                        {!! setLabel('city_neighborhood', $target->city_neighborhood ?? null) !!}
                    </div>

                    <div class="col-span-full md:col-span-3">
                        {!! setLabel('city', $target->city ?? null) !!}
                    </div>

                    <div class="col-span-full md:col-span-3">
                        {!! setLabel('state', $target->state ?? null) !!}
                    </div>

                    <div class="col-span-full md:col-span-3">
                        {!! setLabel('zip_code', $target->zip_code ?? null) !!}
                    </div>

                    <div class="col-span-full md:col-span-3">
                        {!! setLabel('address_reference', $target->address_reference ?? null) !!}
                    </div>

                    @if ($target->google_maps_iframe ?? false)

                        <div class="col-span-full md:col-span-full mb-2">
                            <hr>
                        </div>
                        <div class="col-span-full">
                            {!! setLabel('iframe_google_maps', ' ') !!}
                            @php
                                // $iframe_google_maps = str_replace('width="600"','width="100%"', $target->place->iframe_google_maps);
                                $iframe_google_maps = str_replace('width="600"','width="100%"', $target->google_maps_iframe);
                            @endphp
                            {!! $iframe_google_maps !!}
                        </div>

                    @endif

                </div>
            </div>
        </div>

        {{-- FORMULARIO --}}
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Campos Adicionais Formulário</h2>
                @if ( $target->questions_user_json ?? false )
                    <x-button flat lime label="ALTERAR" href="{{ route('evento-campo-adicional') }}" />
                @else
                    <x-button flat lime label="CADASTRAR" href="{{ route('evento-campo-adicional') }}" />
                @endif
            </div>
            <div class="p-6">
                <div class="w-full flex flex-col gap-6">

                    {{-- {!!  viewByGrid(collect($this->questions_user_json ?? [])->sortBy('input_order'),false) !!} --}}

                @forelse (collect($questions_user_json ?? [])->sortBy('input_order') as $question_values)

                    <div class="border border-gray-200">

                        <div class="{{ setClass('divForItem') }} bg-green-50 hover:bg-gray-100">

                            <div class="w-full grid grid-cols-12 gap-4">

                                <div class="col-span-full md:col-span-full">
                                    <div class="text-xl font-semibold break-words">{{ $question_values['input_label'] ?? '---' }}</div>
                                    <div class="text-base font-normal break-words">{{ $question_values['input_placeholder'] ?? '---' }}</div>
                                </div>

                                <div class="col-span-full md:col-span-full">
                                    <hr>
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('input_type', $question_values['input_type'] ?? null) !!}
                                </div>

                                <div class="col-span-full md:col-span-1">
                                    {!! setLabel('input_required', boolSimNao($question_values['input_required'] ?? false)) !!}
                                </div>

                            </div>

                        </div>

                    </div>
                @empty
                    <div class="flex justify-between">
                        <div class="text-lg font-light">NÃO POSSUI</div>
                    </div>
                @endforelse

                </div>
            </div>
        </div>


        @if (isAdmin())

            <div class="mb-6 font-semibold px-4">
                <h2>SOMENTE ADMINISTRADORES PODEM VER ESSE CONTEÚDO</h2>
            </div>

            {{-- PATROCINIOS --}}
            <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Planos de Patrocínios</h2>
                    <div class="flex justify-between">
                        <x-button flat lime label="CADASTRAR" href="{{ route('evento-plano-patrocinio') }}" />
                        <x-button flat lg primary title="Ver Patrocínios" icon="external-link" href="{{ route('evento-patrocinios') }}" />
                    </div>
                </div>
                <div class="p-6">

                    @forelse ($target->sponsorshipPlans->sortBy('slug') ?? [] as $plan_item)

                        <div class="border border-gray-200">

                            @if ($plan_item->plan_active ?? false)
                                <div class="{{ setClass('divForItem') }} bg-green-50 hover:bg-gray-100">
                            @else
                                <div class="{{ setClass('divForItem') }} bg-red-200 opacity-70">
                            @endif

                            <div class="w-full grid grid-cols-12">

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('name', $plan_item->name ?? null) !!}
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('price', toMoney($plan_item->price ?? null,'R$ ')) !!}
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('data_finish', convertToDate($plan_item->data_finish ?? false)) !!}
                                </div>

                                <div class="col-span-full md:col-span-1">
                                    {!! setLabel('adesões', $plan_item->orders->count() ?? null) !!}
                                </div>

                                <div class="col-span-full md:col-span-2">
                                    <div class="w-full flex justify-center md:justify-end">
                                        <x-button flat primary label="ALTERAR" href="{{ route('evento-plano-patrocinio',['patrocinio_id' => $plan_item->id]) }}" class="w-auto" />
                                    </div>
                                </div>

                                <div class="col-span-full md:col-span-full mb-2">
                                    <hr>
                                </div>

                                <div class="col-span-full md:col-span-2">
                                    {!! setLabel('installments_fees_pay', boolSimNao($plan_item->installments_fees_pay ?? false)) !!}
                                </div>

                                <div class="col-span-full md:col-span-2">
                                    {!! setLabel('installments_max', $plan_item->installments_max ?? null) !!}
                                </div>

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('pay_credit', boolSimNao($plan_item->pay_credit ?? false)) !!}
                                </div>

                                {{-- <div class="col-span-full md:col-span-2">
                                    {!! setLabel('pay_debit', boolSimNao($plan_item->pay_debit ?? false)) !!}
                                </div> --}}

                                <div class="col-span-full md:col-span-3">
                                    {!! setLabel('pay_boleto', boolSimNao($plan_item->pay_boleto ?? false)) !!}
                                </div>

                                <div class="col-span-full md:col-span-2">
                                    {!! setLabel('pay_pix', boolSimNao($plan_item->pay_pix ?? false)) !!}
                                </div>

                                <div class="col-span-full md:col-span-full mb-2">
                                    <hr>
                                </div>

                                <div class="col-span-full">
                                    {!! setLabel('description', $plan_item->description ?? null) !!}
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="flex justify-between">
                            <div class="text-lg font-light">NÃO POSSUI</div>
                        </div>
                    @endforelse

                </div>
            </div>

            {{-- SUMÁRIO --}}
            <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Sumário</h2>
                    <x-button flat lg primary title="SUMÁRIO" icon="external-link" href="{{ route('dashboard-evento-vendas-sumario') }}" />
                </div>
                <div class="p-6">
                    <div class="w-full flex flex-col gap-4">

                        <div class="w-full grid grid-cols-12">

                        <div class="col-span-full md:col-span-3">
                            {!! setLabel('preview_summary_update', $target->preview_summary_update ? $target->preview_summary_update->format('d/m/Y H:i') : '---' ?? null) !!}
                        </div>

                        <div class="col-span-full md:col-span-6">
                            {!! setLabel('preview_summary', toMoney($target->preview_summary, 'R$ ') ?? null) !!}
                        </div>

                        @if ($target->preview_summary_json ?? false)

                            <div class="col-span-full md:col-span-full mb-2">
                                <hr>
                            </div>

                            <div class="col-span-full">
                                <div class="w-full grid grid-cols-12">
                                    @php
                                        $preview_summary_json = json_decode($target->preview_summary_json, true)
                                    @endphp
                                    <div class="{{ setClass('divForItem') }} col-span-full">
                                        {!! setLabel('json', 'dados' ?? null) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        </div>
                    </div>
                </div>
            </div>

            {{-- GESTÃO ORÇAMENTÁRIA --}}
            <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Gestão Orçamentária</h2>
                    <x-button flat lg primary title="GESTÃO ORÇAMENTÁRIA" icon="external-link" href="{{ route('dashboard-financeiro-gestao-orcamentaria') }}" />
                </div>
                <div class="p-6">
                    <div class="w-full flex flex-col gap-4">

                        <div class="w-full grid grid-cols-12">
                        <div class="col-span-full md:col-span-3">
                            {!! setLabel('preview_summary_update', $target->preview_summary_update ? $target->preview_summary_update->format('d/m/Y H:i') : '---' ?? null) !!}
                        </div>

                        <div class="col-span-full md:col-span-3">
                            {!! setLabel('Saldo Previsto', toMoney(($target->preview_budget_management_entries - $target->preview_budget_management_outputs), 'R$ ') ?? null) !!}
                        </div>

                        <div class="col-span-full md:col-span-3">
                            {!! setLabel('preview_budget_management_entries', toMoney($target->preview_budget_management_entries, 'R$ ') ?? null) !!}
                        </div>

                        <div class="col-span-full md:col-span-3">
                            {!! setLabel('preview_budget_management_outputs', toMoney($target->preview_budget_management_outputs, 'R$ ') ?? null) !!}
                        </div>

                        @if ($target->preview_budget_management_json ?? false)
                            <div class="col-span-full md:col-span-full mb-2">
                                <hr>
                            </div>

                            <div class="col-span-full">
                                <div class="w-full grid grid-cols-12">
                                    @php
                                        $preview_budget_management_json = json_decode($target->preview_budget_management_json, true)
                                    @endphp
                                    <div class="{{ setClass('divForItem') }} col-span-full">
                                        {!! setLabel('json', 'dados' ?? null) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- key --}}
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-8 p-4">
            <div class="text-xs text-gray-500">
                {{ $target->event_slug }} : {{ $target->id}}
            </div>
        </div>
    @else
        <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="relative z-10 p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Ops!</h1>
                                <p class="text-white/90 text-sm">É preciso selecionar um evento</p>
                                <div class="mt-2"><a href="{{ route('dashboard') }}" class="text-white/90 text-sm hover:text-white/70 border border-white mt-4 p-2 rounded shadow hover:bg-gray-50 hover:text-blue-500">Página Principal</a></div>
                            </div>
                        </div>
                    </div>
                    <x-button flat white icon="switch-horizontal" wire:click="alterarTarget" class="hover:bg-white/20" />
                </div>
            </div>
        </div>
    @endif

</div>
