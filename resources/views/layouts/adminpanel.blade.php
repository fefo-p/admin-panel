<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100 dark:bg-gray-800">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ 'AP' . (config('app.name') ? ' de '.config('app.name') : null) }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Choices.js -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

        <!-- Styles -->
        @livewireStyles

        <!-- Scripts -->
        <link rel="stylesheet" href="/css/adminpanel_app.css">
        <script src="/js/adminpanel_app.js" defer></script>
        {{--@vite(['resources/css/app.css', 'resources/js/app.js'])--}}

        <style>
            [x-cloak] {
                display: none;
            }
        </style>
    </head>
    <body class="font-sans antialiased h-full">
        <x-ap-banner />

        <div class="min-h-[640px] bg-gray-100 dark:bg-gray-900">
            @env('local')
                @guest()
                    <div class="pl-72 pr-4 py-2 text-white bg-red-500 rounded-lg">Sin hacer login</div>
                @endguest
            @endenv

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-700 shadow">
                    <div class="text-gray-900 ml-64 max-w-12xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="bg-white dark:bg-gray-800">
                <div class="w-full" x-data="{ open: false }" @keydown.window.escape="open = false">
                    @include('adminpanel::nav-menu')
                    <!-- Content -->
                    <div class="md:pl-64 flex flex-col">
                        <!-- Top Bar for desktop -->
                        @include('adminpanel::top-bar')

                        <!-- main content -->
                        {{ $slot }}
                        <!-- end main content -->
                    </div>
                </div>
            </main>
        </div>

        @stack('modals')

        @livewire('livewire-ui-modal')
        @livewireScripts
    </body>
</html>
