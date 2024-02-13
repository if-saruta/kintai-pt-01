<nav x-data="{ open: false }" class="nav">
    <!-- Primary Navigation Menu -->
    <div class="nav__top">
        <div class="nav__top__link">
            <!-- Logo -->
            <div class="logo-block">
                <a href="{{ route('shift.') }}" class="logo-block__inner">
                    <div class="logo-block__inner__img">
                        <img class="" src="{{ asset('img/logo.png') }}" alt="">
                    </div>
                    <p class="logo-block__inner__txt">Caramel</p>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="nav-block">
                {{-- <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    {{ __('ダッシュボード') }}
                </a> --}}
                <a href="{{ route('shift.') }}" class="{{ request()->routeIs('shift.*') ? 'active' : '' }} nav-item">
                    <div class="icon-wrap">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <span class="nav-item__txt">{{ __('ダッシュボード') }}</span>
                </a>
                <a href="{{ route('project.') }}" class="{{ request()->routeIs('project.*') ? 'active' : '' }} nav-item">
                    <div class="icon-wrap">
                        <i class="fa-regular fa-file"></i>
                    </div>
                    <span class="nav-item__txt">{{ __('クライアント管理') }}</span>
                </a>
                <a href="{{ route('employee.') }}" class="{{ request()->routeIs('employee.*') ? 'active' : '' }} nav-item">
                    <div class="icon-wrap">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span class="nav-item__txt">{{ __('ドライバー管理') }}</span>
                </a>
                <a href="{{ route('invoice.driverShift') }}" class="{{ request()->routeIs('invoice.*') ? 'active' : '' }} nav-item">
                    <div class="icon-wrap">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <span class="nav-item__txt">{{ __('請求書') }}</span>
                </a>
                <a href="{{ route('company.') }}" class="{{ request()->routeIs('vehicle.*', 'company.*') ? 'active' : '' }} nav-item">
                    <div class="icon-wrap">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <span class="nav-item__txt">{{ __('情報管理') }}</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }} nav-item">
                    <div class="icon-wrap">
                        <i class="fa-solid fa-gear"></i>
                    </div>
                    <span class="nav-item__txt">{{ __('設定') }}</span>
                </a>
                {{-- <a href="{{ route('company.') }}" class="{{ request()->routeIs('company.*') ? 'active' : '' }}">
                    {{ __('所属先') }}
                </a> --}}
                {{-- <a href="{{ route('vehicle.') }}" class="{{ request()->routeIs('vehicle.*') ? 'active' : '' }}">
                    {{ __('車両') }}
                </a> --}}
            </div>
        </div>

        <!-- Settings Dropdown -->
        {{-- <div class="hidden sm:flex sm:items-center sm:ml-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::user()->name }}</div>

                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div> --}}

        <!-- Hamburger -->
        <div class="-mr-2 flex items-center sm:hidden">
            <button @click="open = ! open"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
