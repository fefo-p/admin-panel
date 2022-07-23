<div class="flex flex-row items-center justify-end space-x-4">
    {{-- View --}}
    <button class="text-blue-500 dark:text-blue-400" onclick="Livewire.emit('openModal', 'adminpanel::user-show', {{ json_encode(['user_id' => $row->id]) }})">
        {{-- Dark mode eye heroicon --}}
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                  clip-rule="evenodd"></path>
        </svg>
    </button>

    {{-- Edit --}}
    <button class="text-indigo-500 dark:text-indigo-400" onclick="Livewire.emit('openModal', 'adminpanel::user-edit', {{ json_encode(['user_id' => $row->id]) }})">
        {{-- Dark mode pencil heroicon --}}
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
        </svg>
    </button>

    @if(!is_null($row->deleted_at))
        {{-- Unlock --}}
        <button class="text-green-500 dark:text-green-400" onclick="Livewire.emit('openModal', 'adminpanel::user-delete', {{ json_encode(['user_id' => $row->id, 'action' => 'unban']) }})">
            {{-- Dark mode lock-open heroicon --}}
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z"></path>
            </svg>
        </button>
    @else
        {{-- Lock --}}
        <button class="text-red-500 dark:text-red-400" onclick="Livewire.emit('openModal', 'adminpanel::user-delete', {{ json_encode(['user_id' => $row->id, 'action' => 'ban']) }})">
            {{-- Dark mode lock heroicon --}}
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
            </svg>
        </button>
    @endif
</div>
