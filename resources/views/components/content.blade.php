<x-ap-adminpanel-layout>
    <div class="py-6 sm:rounded-xl dark:bg-gray-900">
        <div class="max-w-12xl mx-auto md:col-span-1 flex items-center justify-between">
            <div class="px-4 sm:px-6 md:px-8">
                @if (isset($title))
                    <x-ap-section-title>{{ $title }}</x-ap-section-title>
                @endif

                @if (isset($description))
                    <x-ap-section-description>{{ $description }}</x-ap-section-description>
                @endif
            </div>

            @if (isset($action))
                <x-ap-section-action :action="$action" />
            @endif
        </div>

        <div class="max-w-12xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-ap-adminpanel-layout>
