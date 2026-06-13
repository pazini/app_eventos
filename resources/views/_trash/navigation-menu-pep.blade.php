<nav x-data="{ open: false }" class="bg-white border-gray-100 border shadow mt-4">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8 shadow-md border-b">
        <div class="flex justify-between t-16">
            <div class="flex">

                <!-- Logo 4 -->
                <div class="shrink-0 flex items-center pt-2">
                    <a href="/">
                        <x-jet-application-mark class="block h-8 w-auto" />
                    </a>
                </div>
            </div>
            {{--  --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    @auth
                    @else
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
