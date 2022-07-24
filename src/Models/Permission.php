<?php

    namespace FefoP\AdminPanel\Models;

    use Livewire\WithPagination;
    use Spatie\Permission\Models\Permission as SpatiePermission;

    class Permission extends SpatiePermission
    {
        use WithPagination;
    
        public function syncUsers($users): array
        {
            return $this->users()->sync($users);
        }
    }
