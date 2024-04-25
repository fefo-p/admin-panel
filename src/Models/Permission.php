<?php

    namespace FefoP\AdminPanel\Models;

    use App\Models\User;
    use Livewire\WithPagination;
    use Spatie\Permission\Models\Permission as SpatiePermission;

    class Permission extends SpatiePermission
    {
        use WithPagination;

        public function syncUsers($users): array
        {
            foreach ( $users as $user_id ) {
                User::find($user_id)->givePermissionTo($this->name);
            }
            return $users->toArray();

            //return $this->users()->sync($users);
        }
    }
