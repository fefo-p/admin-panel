<div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white dark:bg-gray-800 shadow">
    <button type="button"
            @click="open = true"
            class="px-4 border-r border-gray-200 dark:border-gray-700 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" x-description="Heroicon name: outline/menu-alt-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"></path>
        </svg>
    </button>
    <div class="flex-1 px-4 flex justify-between">
        <div class="flex-1 flex">
            <form class="w-full flex md:ml-0" action="#" method="GET">
                <label for="search-field" class="sr-only">Search</label>
                <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                        <svg class="h-5 w-5" x-description="Heroicon name: solid/search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input id="search-field"
                           class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 bg-white dark:bg-gray-800 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-sm"
                           placeholder="Search"
                           type="search"
                           name="search">
                </div>
            </form>
        </div>
        <div class="ml-4 flex items-center md:ml-6">
            <button type="button"
                    class="bg-white dark:bg-gray-800 p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span class="sr-only">View notifications</span>
                <svg class="h-6 w-6" x-description="Heroicon name: outline/bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </button>

            <!-- Profile dropdown -->
            <div x-data="Components.menu({open: false })" x-init="init()" @keydown.escape.stop="open = false; focusButton()" @click.away="onClickAway($event)"
                 class="ml-3 relative">
                <div>
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <button
                            id="user-menu-button" x-ref="button" @click="open = ! open" @keyup.space.prevent="onButtonEnter()"
                            @keydown.enter.prevent="onButtonEnter()" aria-expanded="false" aria-haspopup="true" x-bind:aria-expanded="open.toString()"
                            @keydown.arrow-up.prevent="onArrowUp()" @keydown.arrow-down.prevent="onArrowDown()"
                            class="flex text-sm transition duration-150 ease-in-out border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300">
                            <img class="object-cover w-8 h-8 rounded-full"
                                 src="{{ Auth::user()->profile_photo_url }}"
                                 alt="{{ Auth::user()->name }}" />
                        </button>
                    @else
                        <button
                            id="user-menu-button"
                            x-ref="button"
                            @click="open = ! open"
                            @keyup.space.prevent="onButtonEnter()"
                            @keydown.enter.prevent="onButtonEnter()"
                            aria-expanded="false"
                            aria-haspopup="true"
                            x-bind:aria-expanded="open.toString()"
                            @keydown.arrow-up.prevent="onArrowUp()"
                            @keydown.arrow-down.prevent="onArrowDown()"
                            class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-100 transition duration-150 ease-in-out hover:text-gray-700 dark:hover:text-gray-100 dark:hover:font-semibold hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                            <div>
                                {{ Auth::user()->name }}
                            </div>

                            <div class="ml-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    @endif
                </div>

                <div x-cloak x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                     x-ref="menu-items"
                     x-description="Dropdown menu, show/hide based on menu state."
                     x-bind:aria-activedescendant="activeDescendant" role="menu"
                     aria-orientation="vertical"
                     aria-labelledby="user-menu-button"
                     tabindex="-1"
                     @keydown.arrow-up.prevent="onArrowUp()"
                     @keydown.arrow-down.prevent="onArrowDown()"
                     @keydown.tab="open = false"
                     @keydown.enter.prevent="open = false; focusButton()"
                     @keyup.space.prevent="open = false; focusButton()">

                    <a href="{{ route('profile.show') }}"
                       class="block px-4 py-2 text-sm text-gray-700"
                       x-state:on="Active"
                       x-state:off="Not Active"
                       :class="{ 'bg-gray-100': activeIndex === 0 }"
                       role="menuitem"
                       tabindex="-1"
                       id="user-menu-item-0"
                       @mouseenter="activeIndex = 0"
                       @mouseleave="activeIndex = -1"
                       @click="open = false; focusButton()">
                        Your Profile
                    </a>

                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" :class="{ 'bg-gray-100': activeIndex === 1 }" role="menuitem" tabindex="-1"
                       id="user-menu-item-1" @mouseenter="activeIndex = 1" @mouseleave="activeIndex = -1" @click="open = false; focusButton()">Settings</a>

                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-ap-dropdown-link href="{{ route('logout') }}"
                                            @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-ap-dropdown-link>

                        {{--<a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700" :class="{ 'bg-gray-100': activeIndex === 2 }" role="menuitem" tabindex="-1"
                           id="user-menu-item-2" @mouseenter="activeIndex = 2" @mouseleave="activeIndex = -1" @click.prevent="$root.submit()">Sign out</a>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
