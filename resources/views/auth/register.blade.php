<x-guest-layout>
    <x-jet-authentication-card>

        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        {{-- ERRORS --}}
        @if ($errors->any())
            <div class="flex w-full p-0.5 mx-auto bg-red-700 text-white">
                <div class="font-bold px-2 text-center">{{ __('OPS!') }}</div>
                <div class="w-full">
                    @foreach ($errors->all() as $error)
                    <p class="w-full m-0 px-2 text-sm text-white bg-red-500 border border-red-700 uppercase">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ERROR --}}
        @if (session('error'))
            <div class="w-full mx-auto text-center bg-red-700 text-white px-1 py-2">
                <h3 class="font-bold uppercase">{{ __(session('error')) }}</h3>
                @if (session('error_sub'))
                    <h5 class="text-xs font-normal py-0 uppercase">
                        {{ __(session('error_sub')) }}
                    </h5>
                @endif
            </div>
        @endif

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="w-full mx-auto text-center bg-green-700 text-white px-1 py-2">
                <h3 class="font-bold uppercase">{{ __(session('success')) }}</h3>
                @if (session('success_sub'))
                    <h5 class="text-xs font-normal py-0 uppercase">
                        {{ __(session('success_sub')) }}
                    </h5>
                @endif
            </div>
        @endif

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">{{ session('status') }}</div>
        @endif


        <form method="POST" action="{{ route('register') }}" class="mt-4">
            @csrf

            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-jet-button class="ml-4">
                    {{ __('Register') }}
                </x-jet-button>

            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
