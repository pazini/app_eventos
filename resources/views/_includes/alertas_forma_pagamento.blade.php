@if (session('forma_pagamento_success'))
    <div class="w-full my-4">
        <div class="w-full mx-auto text-center bg-green-700 text-white px-2 py-2 shadow-lg rounded-md">
            <div class="font-bold uppercase">
                {{ __(session('forma_pagamento_success')) }}
            </div>
            @if (session('forma_pagamento_success_sub'))
                <div class="font-normal uppercase">
                    {{ __(session('forma_pagamento_success_sub')) }}
                </div>
            @endif
        </div>
    </div>
@endif

@if (session('forma_pagamento_error'))
    <div class="w-full my-4">
        <div class="mx-auto text-center bg-red-700 text-white px-2 py-2 shadow-lg rounded-md">
            <div class="w-full">
                <div class="font-bold uppercase">
                    {{ __(session('forma_pagamento_error')) }}
                </div>
                <div class="uppercase">
                    @if (session('forma_pagamento_error_sub'))
                        <span class="font-normal">{{ __(session('forma_pagamento_error_sub')) }}</span>    </span>
                    @endif
                    <span class="font-light">{{ now()->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session('forma_pagamento_info'))
    <div class="w-full my-4">
        <div class="w-full mx-auto text-center bg-blue-700 text-white px-2 py-2 shadow-lg rounded-md">
            <div class="font-bold uppercase">
                {{ __(session('forma_pagamento_info')) }}
            </div>
            @if (session('forma_pagamento_info_sub'))
                <div class="font-normal uppercase">
                    {{ __(session('forma_pagamento_info_sub')) }}
                </div>
            @endif
        </div>
    </div>
@endif

@if (session('forma_pagamento_warning'))
    <div class="w-full my-4">
        <div class="w-full mx-auto text-center bg-yellow-700 text-white px-2 py-2 shadow-lg rounded-md">
            <div class="font-bold uppercase">ATENÇÃO</div>
            <div class="font-normal uppercase">
                {{ __(session('forma_pagamento_warning')) }}
            </div>
            @if (session('forma_pagamento_warning_sub'))
                <div class="font-light uppercase">
                    {{ __(session('forma_pagamento_warning_sub')) }}
                </div>
            @endif
        </div>
    </div>
@endif

@if (session('forma_pagamento_pix_alert'))
    <div class="w-full my-4">
        <div class="w-full flex justify-center items-center gap-1 mx-auto text-center bg-blue-700 text-white px-2 py-2 shadow-lg rounded-md">
            <div class="font-bold uppercase">
                {{ __(session('forma_pagamento_pix_alert')) }}
            </div>
            @if (session('forma_pagamento_pix_alert_sub'))
                <div class="font-normal uppercase">
                    {{ __(session('forma_pagamento_pix_alert_sub')) }}
                </div>
            @endif
        </div>
    </div>
@endif
