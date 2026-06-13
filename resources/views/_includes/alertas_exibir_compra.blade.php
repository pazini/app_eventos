@if (session('success'))
    <div class="w-full my-4">
        <div class="w-full flex-none md:flex justify-center items-center gap-1 mx-auto text-center bg-green-700 text-white px-1 py-2 shadow-md rounded-sm">
            <div class="font-bold uppercase">
                {{ __(session('success')) }}
            </div>
            @if (session('success_sub'))
                <div class="font-normal uppercase">
                    {{ __(session('success_sub')) }}
                </div>
            @endif
        </div>
    </div>
@endif

@if (session('error'))
    <div class="w-full my-4">
        <div class="w-full flex-none md:flex justify-center items-center gap-1 mx-auto text-center bg-red-700 text-white px-1 py-2 shadow-md rounded-sm">
            <div class="font-bold uppercase">
                {{ __(session('error')) }}
            </div>
            @if (session('error_sub'))
                <div class="font-normal uppercase">
                    {{ __(session('error_sub')) }}
                </div>
            @endif
        </div>
    </div>
@endif

@if (session('info'))
    <div class="w-full my-4">
        <div class="w-full flex-none md:flex justify-center items-center gap-1 mx-auto text-center bg-blue-700 text-white px-1 py-2 shadow-md rounded-sm">
            <div class="font-bold uppercase">
                {{ __(session('info')) }}
            </div>
            @if (session('info_sub'))
                <div class="font-normal uppercase">
                    {{ __(session('info_sub')) }}
                </div>
            @endif
        </div>
    </div>
@endif
