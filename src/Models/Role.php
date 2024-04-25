<?php

    namespace FefoP\AdminPanel\Models;

    use App\Models\User;
    use Livewire\WithPagination;
    use Spatie\Permission\Models\Role as SpatieRole;

    class Role extends SpatieRole
    {
        use WithPagination;

        public function syncUsers($users): array
        {
            foreach ( $users as $user_id ) {
                User::find($user_id)->assignRole($this->name);
            }
            return $users->toArray();

            //return $this->users()->sync($users);
        }
    }
