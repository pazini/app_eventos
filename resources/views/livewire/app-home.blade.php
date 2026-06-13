<div class="min-h-screen bg-stone-50 shadow-md">

    <div class="w-full max-w-7xl mx-auto">
        <x-jet-validation-errors />
    </div>

    @if ($eventList->count())

        <main class="max-w-7xl mx-auto h-full w-full p-4">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-10 gap-x-4 text-center">

                @foreach ($eventList as $event)

                    @php
                        if ($event->url_image_thumbnail ?? false)
                            $urlImage = str_starts_with($event->url_image_thumbnail ?? '', '/storage/') ? asset($event->url_image_thumbnail) : tenantAsset($event->url_image_thumbnail, true);
                        elseif ($event->customer->url_image_logo ?? false)
                            $urlImage = tenantAsset($event->customer->url_image_logo, true);
                        else
                            $urlImage = config('domains.eventos') . '/images/logo/logo-lagoinha-black.svg';
                    @endphp

                    <a href="{{ eventoUrl($event->event_slug) }}" class="hover:opacity-70">
                        <div class="flex flex-col justify-end h-60 md:h-80 bg-sky-200 shadow-md border rounded rounded-b-none" style="background-position: center; background-repeat: no-repeat; background-size: cover; background-image: url('{{ $urlImage }}');">
                            <div class="hidden -mb-20 mx-3 py-2 px-4 bg-white shadow-md border rounded rounded-t-none">
                                <div class="text-base md:text-2xl font-bold uppercase truncate">{{ $event->event_name }}</div>
                                @if ($event->event_datetime_start ?? false)
                                    <div class="text-lg md:text-xl font-normal uppercase truncate">{{ \Carbon\Carbon::parse($event->event_datetime_start)->format('d/m/Y H:i') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 hover:bg-gray-100 py-1 shadow-md border rounded rounded-t-none">
                            <div class="text-2xl font-bold font-serif uppercase tracking-wide">{{ $event->event_name }}</div>
                            <div class="text-lg font-light -mt-2">{{ $event->event_description }}</div>
                            <div class="text-base font-normal -mt-1">DIA {{ convertToDateTime($event->event_datetime_start)}}</div>
                        </div>
                    </a>

                @endforeach
            </div>
        </main>

    @else
        <div class="p-6">
            <div class="w-full max-w-7xl mx-auto flex justify-center items-center p-4 md:p-10 bg-red-100 rounded-md shadow-lg">
                <div class="font-light text-xl md:text-3xl text-center">Poxa, nenhum evento por aqui!</div>
            </div>
        </div>
    @endif

</div>
{{--  --}}
