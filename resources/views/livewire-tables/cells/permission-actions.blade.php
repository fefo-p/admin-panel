<div class="flex flex-row items-center justify-end space-x-4">
    {{-- View --}}
    <button class="text-blue-500 dark:text-blue-400" onclick="Livewire.emit('openModal', 'adminpanel::permission-show', {{ json_encode(['permission_id' => $row->id]) }})">
        {{-- Dark mode eye heroicon --}}
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                  clip-rule="evenodd"></path>
        </svg>
    </button>

    {{-- Edit --}}
    <button class="text-indigo-500 dark:text-indigo-400" onclick="Livewire.emit('openModal', 'adminpanel::permission-edit', {{ json_encode(['permission_id' => $row->id]) }})">
        {{-- Dark mode pencil heroicon --}}
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
        </svg>
    </button>

    {{-- Delete --}}
    <button class="text-red-500 dark:text-red-400" onclick="Livewire.emit('openModal', 'adminpanel::permission-delete', {{ json_encode(['permission_id' => $row->id, 'action' => 'delete']) }})">
        {{-- Dark mode trash heroicon --}}
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
    </button>
</div>
