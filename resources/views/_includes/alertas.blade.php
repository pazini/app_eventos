<div class="block" {{ $attributes ?? false }}>
    @if ($errors->any())
        <div class="{{ setClass('divContentErros') }}">
            @if (count($errors->all()) > 1)
                <div class="flex justify-center w-full mx-auto bg-red-600 text-white p-2 my-1 rounded-none shadow-md">
                    <div class="w-full m-0 px-2 text-white uppercase">{{ count($errors->all()) }} erros foram encontrados</p>
                </div>
            @else
                @foreach ($errors->all() as $error)
                    <div class="flex w-full mx-auto bg-red-600 text-white p-2 my-1 rounded shadow-md">
                        <p class="w-full m-0 px-2 text-sm text-white uppercase">{{ $error }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    @endif

    {{-- ERROR --}}
    @if (session('errorLw'))
    <div class="w-full mx-auto text-center bg-red-700 text-white px-1 py-2 rounded-sm shadow-md">
        <h3 class="text-xl font-bold uppercase">
            {{ __(session('errorLw')) }}
        </h3>
        @if (session('error_sub'))
        <h5 class="text-sm font-normal py-0 uppercase">
            {{ __(session('error_sub_lw')) }}
        </h5>
        @endif
    </div>
    @endif

    {{-- SUCCESS --}}
    @if (session('successLw'))
    <div class="w-full mx-auto text-center bg-green-700 text-white px-1 py-2 rounded-sm shadow-md">
        <h3 class="text-xl font-bold uppercase">
            {{ __(session('successLw')) }}
        </h3>
        @if (session('success_sub_lw'))
        <h5 class="text-sm font-normal py-0 uppercase">
            {{ __(session('success_sub_lw')) }}
        </h5>
        @endif
    </div>
    @endif

    {{-- ERROR --}}
    @if (session('error'))
    <div class="w-full mx-auto text-center bg-red-700 text-white px-1 py-2 rounded-sm shadow-md">
        <h3 class="text-xl font-bold uppercase">
            {{ __(session('error')) }}
        </h3>
        @if (session('error_sub'))
        <h5 class="text-sm font-normal py-0 uppercase">
            {{ __(session('error_sub')) }}
        </h5>
        @endif
    </div>
    @endif

    {{-- SUCCESS --}}
    @if (session('success'))
    <div class="w-full mx-auto text-center bg-green-700 text-white px-1 py-2 rounded-sm shadow-md">
        <h3 class="text-xl font-bold uppercase">
            {{ __(session('success')) }}
        </h3>
        @if (session('success_sub'))
            <h5 class="text-sm font-normal py-0 uppercase">
                {{ __(session('success_sub')) }}
            </h5>
        @endif
        @if (session('success_sub_lc'))
            <h5 class="text-sm font-normal py-0">
                {{ __(session('success_sub_lc')) }}
            </h5>
        @endif
    </div>
    @endif

    {{-- WARNING --}}
    @if (session('warning'))
        <div class="w-full mx-auto text-center bg-yellow-500 text-white px-1 py-2 rounded-sm shadow-md">
            <h3 class="text-xl font-bold uppercase">
                {{ __(session('warning')) }}
            </h3>
            @if (session('warning_sub'))
            <h5 class="text-sm font-normal py-0 uppercase">
                {{ __(session('warning_sub')) }}
            </h5>
            @endif
        </div>
    @endif

    {{-- MSG --}}
    @if (session('msg'))
    <div class="w-full mx-auto text-center px-1 py-2 bg-gray-700 text-white">
        <h3 class="text-xl font-bold uppercase">
            {{ __(session('msg')) }}
        </h3>
        @if (session('msg_sub'))
        <h5 class="text-sm font-normal py-0 uppercase">
            {{ __(session('msg_sub')) }}
        </h5>
        @endif
    </div>
    @endif

    {{-- STATUS --}}
    @if (session('status'))
    <div class="w-full mx-auto text-center px-1 py-2 bg-gray-700 text-white">
        <h3 class="text-xl font-bold uppercase">
            {{ __(session('status')) }}
        </h3>
        @if (session('status_sub'))
        <h5 class="text-sm font-normal py-0 uppercase">
            {{ __(session('status_sub')) }}
        </h5>
        @endif
    </div>
    @endif
</div>
