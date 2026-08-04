<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- بەشی لای ڕاست: لۆگۆ و بەشەکان -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- لیستی بەشەکان -->
                <div class="hidden gap-4 sm:gap-3 sm:-my-px sm:ms-10 sm:flex whitespace-nowrap">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="dark:text-gray-100 dark:hover:text-white">
                        {{ __('بەشی سەرەکی') }}
                    </x-nav-link>
                    @hasanyrole('Admin|Normal_User')
                        <x-nav-link href="#" class="dark:text-gray-100 dark:hover:text-white">خوێندکار</x-nav-link>
                        <x-nav-link href="#" class="dark:text-gray-100 dark:hover:text-white">مامۆستا</x-nav-link>
                        <x-nav-link href="#" class="dark:text-gray-100 dark:hover:text-white">هاتووچۆ</x-nav-link>
                        <x-nav-link href="#" class="dark:text-gray-100 dark:hover:text-white">دارایی</x-nav-link>
                        <x-nav-link href="#" class="dark:text-gray-100 dark:hover:text-white">شەهادە و
                            سەنەد</x-nav-link>
                    @endhasanyrole

                    @hasanyrole('Admin|Normal_User|Teacher')
                        <x-nav-link href="#" class="dark:text-gray-100 dark:hover:text-white">وانەکان</x-nav-link>
                        <x-nav-link href="#" class="dark:text-gray-100 dark:hover:text-white">ئامادەبوون</x-nav-link>
                    @endhasanyrole

                    @role('Admin')
                        <x-nav-link href="#" class="dark:text-gray-100 dark:hover:text-white">تەندروستی</x-nav-link>
                        <x-nav-link href="#" class="dark:text-gray-100 dark:hover:text-white">بەکارهێنەر</x-nav-link>
                        <x-nav-link href="#" class="dark:text-gray-100 dark:hover:text-white">پاشەکاوت</x-nav-link>
                    @endrole
                </div>
            </div>

            <!-- بەشی لای چەپ: مۆدی تاریک و درۆپ داون -->
            <div class="flex items-center sm:ms-6 gap-2 sm:gap-4">

                <!-- دوگمەی مۆدی تاریک (لە کۆتایی بەشەکان و پێش درۆپ داونەکە) -->
                <button id="theme-toggle" type="button"
                    class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none rounded-lg text-sm p-2.5">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- ناوی بەکارهێنەر و دەرچوون -->
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger (بۆ مۆبایل) -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- مینیوی مۆبایل -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden dark:bg-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="dark:text-gray-200">
                {{ __('بەشی سەرەکی') }}
            </x-responsive-nav-link>

            @hasanyrole('Admin|Normal_User')
                <x-responsive-nav-link href="#" class="dark:text-gray-200">خوێندکار</x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="dark:text-gray-200">مامۆستا</x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="dark:text-gray-200">هاتووچۆ</x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="dark:text-gray-200">دارایی</x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="dark:text-gray-200">شەهادە و سەنەد</x-responsive-nav-link>
            @endhasanyrole

            @hasanyrole('Admin|Normal_User|Teacher')
                <x-responsive-nav-link href="#" class="dark:text-gray-200">وانەکان</x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="dark:text-gray-200">ئامادەبوون</x-responsive-nav-link>
            @endhasanyrole

            @role('Admin')
                <x-responsive-nav-link href="#" class="dark:text-gray-200">تەندروستی</x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="dark:text-gray-200">بەکارهێنەر</x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="dark:text-gray-200">پاشەکاوت</x-responsive-nav-link>
            @endrole
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="dark:text-gray-200">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();" class="dark:text-gray-200">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
