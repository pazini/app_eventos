<div>

    <div class="{{ setClass('divContentHeader') }} ">
        <div class="w-full flex justify-between items-center">
            <div>
                {!! setLabelHeader('Meu', 'Perfil') !!}
            </div>
            <div class="p-0">
            </div>
        </div>
    </div>

    <div class="w-full max-w-7xl mx-auto">
        <x-jet-validation-errors />
    </div>

    {{-- DADOS --}}
    <div class="{{ setClass('divContentTitleDiv') }}">

        <div>

            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    @livewire('profile.update-profile-information-form')

                    <x-jet-section-border />
                @endif

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.update-password-form')
                    </div>
                    <x-jet-section-border />
                @endif


                <div class="mt-10 sm:mt-0">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>

            </div>
        </div>

    </div>

</div>
