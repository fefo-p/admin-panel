<?php

    namespace FefoP\AdminPanel\Models;

    use Livewire\WithPagination;
    use Spatie\Permission\Models\Role as SpatieRole;

    class Role extends SpatieRole
    {
        use WithPagination;

        public function syncUsers($users): array
        {
            return $this->users()->sync($users);
        }
    }
