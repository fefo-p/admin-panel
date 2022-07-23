@props([
    'object',
    'object_name',
    'action_name' => 'Confirmar',
])
<div class="w-full mx-auto">
    <div class="bg-white dark:bg-gray-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                <!-- Heroicon name: outline/check -->
                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-200" id="modal-title">¿{{ ucfirst($action_name) }} <span class="mx-2 font-mono font-semibold">{{ $object_name }}</span> {{ $object->name }}?</h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to activate this account? All the existing data will be available.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button
                wire:click="confirmar"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">{{ $action_name }}
        </button>
        <x-ap-secondary-button type="button"
                wire:click="cancel">Cancelar
        </x-ap-secondary-button>
    </div>
</div>
