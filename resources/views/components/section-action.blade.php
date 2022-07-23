<x-ap-button
    onclick="Livewire.emit('openModal', '{{ $action['component'] }}')"
    type="button"
    class="mr-8 px-2 py-1 sm:px-6 md:px-8">
    {{ $action['name'] }}
</x-ap-button>
