{{--
    {{ __('adminpanel::messages.welcome') }}
--}}

<x-ap-content :title="$title ?? null" :description="$description ?? null" :action="$action ?? null">
    <div class="py-4">
        <div class="border-4 border-dashed border-gray-200 dark:border-gray-600 rounded-lg h-96">

        </div>
    </div>
</x-ap-content>
