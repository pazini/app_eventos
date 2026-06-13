{{-- MODAL CONCLUSAO SUCESSO --}}
@if (session('modal_pagamento_success'))
    {{session('modal_pagamento_success')}}
    <x-modal.card blur wire:model.defer="modal_pagamento_success">

        <div class="flex flex-col justify-center items-center px-6">

            <div>
                <img src="{{ asset('images/icones/icon-success-animate.gif') }}" alt="Sucesso na Conclusão" class="h-32">
            </div>

            <div class="w-full text-center text-2xl text-green-700 mx-1 mb-2 font-normal">{{ __(session('modal_pagamento_success')) }}</div>

            @if (session('modal_pagamento_success_sub'))
                <div class="w-full text-center text-xl text-green-600 mx-8 font-light">{{ __(session('modal_pagamento_success_sub')) }}</div>
            @endif

            <div class="flex flex-col justify-center items-center gap-2 pt-2 my-2">
                <div class="text-sm font-normal">Pagamentos processados por</div>
                <img src="{{ asset('assets/safe2pay-logo.png') }}" alt="Sucesso na Conclusão" class="h-10">
            </div>

        </div>

        <x-slot name="footer">
            <div class="flex justify-center gap-x-4">
                <x-button flat label="Fechar" x-on:click="close" />
            </div>
        </x-slot>
    </x-modal.card>
@endif
{{-- MODAL CONCLUSAO success - FIM --}}
{{-- MODAL CONCLUSAO ERROR --}}
@if (session('modal_error'))
    <x-modal.card blur wire:model.defer="modal_error">

        <div class="flex flex-col justify-center items-center px-6">

            <div>
                <img src="{{ asset('images/icones/icon-error-animate.gif') }}" alt="Erro na Conclusão" class="h-32">
            </div>

            <div class="w-full text-center text-2xl text-red-700 mx-1 mb-2 font-normal">{{ __(session('modal_error')) }}</div>

            @if (session('modal_error_sub'))
                <div class="w-full text-center text-xl text-red-600 mx-8 font-light">{{ __(session('modal_error_sub')) }}</div>
            @endif

        </div>

        <x-slot name="footer">
            <div class="flex justify-center gap-x-4">
                <x-button flat label="Fechar" x-on:click="close" />
            </div>
        </x-slot>
    </x-modal.card>
@endif
{{-- MODAL CONCLUSAO ERROR - FIM --}}
{{-- MODAL CONCLUSAO INFO --}}
@if (session('modal_info'))
    <x-modal.card blur wire:model.defer="modal_info">

        <div class="flex flex-col justify-center items-center px-6">

            <div>
                <img src="{{ asset('images/icones/icon-alert-animate.gif') }}" alt="Erro na Conclusão" class="h-32">
            </div>

            <div class="w-full text-center text-2xl text-blue-700 mx-1 mb-2 font-normal">{{ __(session('modal_info')) }}</div>

            @if (session('modal_info_sub'))
                <div class="w-full text-center text-xl text-blue-600 mx-8 font-light">{{ __(session('modal_info_sub')) }}</div>
            @endif

        </div>

        <x-slot name="footer">
            <div class="flex justify-center gap-x-4">
                <x-button flat label="Fechar" x-on:click="close" />
            </div>
        </x-slot>
    </x-modal.card>
@endif
{{-- MODAL CONCLUSAO INFO - FIM --}}
{{-- MODAL CONCLUSAO ERROR --}}
@if (session('modal_error'))
    <x-modal.card blur wire:model.defer="modal_error">

        <div class="flex flex-col justify-center items-center px-6">

            <div>
                <img src="{{ asset('images/icones/icon-error-animate.gif') }}" alt="Erro na Conclusão" class="h-32">
            </div>

            <div class="w-full text-center text-2xl text-red-700 mx-1 mb-2 font-normal">{{ __(session('modal_error')) }}</div>

            @if (session('modal_error_sub'))
                <div class="w-full text-center text-xl text-red-600 mx-8 font-light">{{ __(session('modal_error_sub')) }}</div>
            @endif

        </div>

        <x-slot name="footer">
            <div class="flex justify-center gap-x-4">
                <x-button flat label="Fechar" x-on:click="close" />
            </div>
        </x-slot>
    </x-modal.card>
@endif
{{-- MODAL CONCLUSAO ERROR - FIM --}}
