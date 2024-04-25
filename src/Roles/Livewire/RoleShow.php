<?php

    namespace FefoP\AdminPanel\Roles\Livewire;

    use App\Models\User;
    use FefoP\AdminPanel\Models\Role;
    use LivewireUI\Modal\ModalComponent;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    class RoleShow extends ModalComponent
    {
        use AuthorizesRequests;

        public            $role;
        public int        $role_id;
        public Collection $users;

        public function mount(int $role_id): void
        {
            $this->role  = Role::with('permissions')->find($role_id);
            $this->authorize('view', $this->role);

            $this->users = User::whereHas("roles", function ($query) use ($role_id) {
                return $query->where("id", $role_id);
            })->get();
        }

        public function render()
        {
            return view('adminpanel::roles.livewire.role-show');
        }
    }
