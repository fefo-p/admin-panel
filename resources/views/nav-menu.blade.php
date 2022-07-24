<div x-show="open" class="relative z-40 md:hidden" x-description="Off-canvas menu for mobile, show/hide based on off-canvas menu state." x-ref="dialog"
     aria-modal="true">
    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75" x-description="Off-canvas menu backdrop, show/hide based on off-canvas menu state."></div>

    <div class="fixed inset-0 flex z-40">
        <!-- Mobile menu -->
        <div x-show="open" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full" x-description="Off-canvas menu, show/hide based on off-canvas menu state."
             class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-gray-800" @click.away="open = false">
            <div x-show="open" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 x-description="Close button, show/hide based on off-canvas menu state." class="absolute top-0 right-0 -mr-12 pt-2">
                <button type="button"
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        @click="open = false"><span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" x-description="Heroicon name: outline/x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-shrink-0 flex items-center px-4">
                <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-500-mark-white-text.svg" alt="Workflow">
            </div>
            <div class="mt-5 flex-1 h-0 overflow-y-auto">
                <nav class="mt-5 px-2 space-y-1">
                    <a href="{{ route('adminpanel.dashboard') }}#"
                       class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md @if(request()->routeIs('adminpanel.dashboard')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif">
                        <svg class="text-gray-300 mr-4 flex-shrink-0 h-6 w-6 @if(request()->routeIs('adminpanel.dashboard')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif"
                             x-description="Heroicon name: outline/home" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>

                    <div class="py-1">
                        <x-ap-separador />
                    </div>

                    <a href="{{ route('adminpanel.users') }}#"
                       class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md @if(request()->routeIs('adminpanel.users')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif">
                        <svg class="text-gray-300 mr-4 flex-shrink-0 h-6 w-6 @if(request()->routeIs('adminpanel.users')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif"
                             x-description="Heroicon name: outline/folder" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            {{--<path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>--}}
                        </svg>
                        Users
                    </a>

                    <a href="{{ route('adminpanel.roles') }}#"
                       class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md @if(request()->routeIs('adminpanel.roles')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif">
                        <svg class="text-gray-300 mr-4 flex-shrink-0 h-6 w-6 @if(request()->routeIs('adminpanel.roles')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif"
                             x-description="Heroicon name: outline/calendar" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            {{--<path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>--}}
                        </svg>
                        Roles
                    </a>

                    <a href="{{ route('adminpanel.permissions') }}#"
                       class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md @if(request()->routeIs('adminpanel.permissions')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif">
                        <svg class="text-gray-300 mr-4 flex-shrink-0 h-6 w-6 @if(request()->routeIs('adminpanel.permissions')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif"
                             x-description="Heroicon name: outline/inbox" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                            {{--<path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>--}}
                        </svg>
                        Permissions
                    </a>

                    <div class="py-1">
                        <x-ap-separador />
                    </div>

                    <a href="{{ route('adminpanel.about') }}#"
                       class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md @if(request()->routeIs('adminpanel.about')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif">
                        <svg class="text-gray-300 mr-4 flex-shrink-0 h-6 w-6 @if(request()->routeIs('adminpanel.about')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif"
                             x-description="Heroicon name: outline/users" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                             stroke="currentColor" aria-hidden="true">
                            {{--<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>--}}
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            {{--<path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>--}}
                        </svg>
                        About
                    </a>
                </nav>
            </div>
        </div>

        <div class="flex-shrink-0 w-14" aria-hidden="true">
            <!-- Dummy element to force sidebar to shrink to fit close icon -->
        </div>
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 dark:sm:shadow-gray-600">
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <div class="flex-1 flex flex-col min-h-0 bg-gray-800">
        <div class="flex items-center sm:space-x-2 h-16 flex-shrink-0 px-4 bg-gray-800">
            {{--<img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-500-mark-white-text.svg" alt="Workflow">--}}
            <svg class="h-8 w-auto text-white">
                <text class="text-2xl" x="0" y="26" fill="currentColor">Admin Panel</text>
            </svg>
        </div>
        <div class="flex-1 flex flex-col overflow-y-auto">
            <nav class="mt-5 flex-1 px-2 space-y-1">
                @auth()
                    <a href="#" class="mb-4 cursor-text bg-gray-600 text-white group flex items-center justify-center px-2 py-2 text-sm font-medium rounded-md">
                        {{ Auth::user()->name }}
                    </a>
                @endauth

                <a href="{{ route('adminpanel.dashboard') }}"
                   class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md @if(request()->routeIs('adminpanel.dashboard')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif">
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6 @if(request()->routeIs('adminpanel.dashboard')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif"
                         x-description="Heroicon name: outline/home" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                <div class="py-1">
                    <x-ap-separador />
                </div>

                <a href="{{ route('adminpanel.users') }}"
                   class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md @if(request()->routeIs('adminpanel.users')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif">
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6 @if(request()->routeIs('adminpanel.users')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif"
                         x-description="Heroicon name: outline/folder" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        {{--<path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>--}}
                    </svg>
                    Users
                </a>

                <a href="{{ route('adminpanel.roles') }}"
                   class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md @if(request()->routeIs('adminpanel.roles')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif">
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6 @if(request()->routeIs('adminpanel.roles')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif"
                         x-description="Heroicon name: outline/calendar" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        {{--<path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>--}}
                    </svg>
                    Roles
                </a>

                <a href="{{ route('adminpanel.permissions') }}"
                   class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md @if(request()->routeIs('adminpanel.permissions')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif">
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6 @if(request()->routeIs('adminpanel.permissions')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif"
                         x-description="Heroicon name: outline/inbox" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                        {{--<path stroke-linecap="round" stroke-linejoin="round"
                              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>--}}
                    </svg>
                    Permissions
                </a>

                <div class="py-1">
                    <x-ap-separador />
                </div>

                <a href="{{ route('adminpanel.about') }}"
                   class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md @if(request()->routeIs('adminpanel.about')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif">
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6 @if(request()->routeIs('adminpanel.about')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif"
                         x-description="Heroicon name: outline/users" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        {{--<path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>--}}
                    </svg>
                    About
                </a>
            </nav>
        </div>
    </div>
</div>
