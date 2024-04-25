<?php

    namespace FefoP\AdminPanel\Users\Livewire;

    use App\Models\User;
    use FefoP\AdminPanel\Models\Role;
    use LivewireUI\Modal\ModalComponent;
    use Illuminate\Support\Facades\Auth;
    use FefoP\AdminPanel\Models\Permission;

    class UserEdit extends ModalComponent
    {
        public User   $user;
        public string $name;
        public string $email;
        public        $cuil;
        public int    $user_id;
        public        $selected_roles;
        public        $all_roles;
        public        $selected_permissions;
        public        $all_permissions;

        public function mount(int $user_id): void
        {
            Auth::user()->can('editar usuarios');

            $this->user  = User::withTrashed()->with([ "roles", "permissions" ])->find($user_id);
            $this->name  = $this->user->name;
            $this->email = $this->user->email;
            $this->cuil  = $this->user->cuil;


            $this->all_roles            = Role::all()
                                              ->reject(fn($role) => $role->name == 'Super-Admin')
                                              ->pluck('name', 'id');
            $this->all_permissions      = Permission::pluck('name', 'id');
            $this->selected_roles       = $this->user->roles->pluck('id');
            $this->selected_permissions = $this->user->permissions->pluck('id');
        }

        public function updatedSelectedRoles(): void
        {
            $this->selected_roles = collect($this->selected_roles);
        }

        public function updatedSelectedPermissions(): void
        {
            $this->selected_permissions = collect($this->selected_permissions);
        }

        public function actualizar(): void
        {
            $validated = $this->validar();

            $this->user->update([
                                    'name'  => $validated[ 'name' ],
                                    'email' => $validated[ 'email' ],
                                    'cuil'  => $validated[ 'cuil' ],
                                ]);

            $this->user->refresh();
            $this->user->syncRoles($this->selected_roles);
            $this->user->syncPermissions($this->selected_permissions);

            $this->emitTo('adminpanel::user-table', 'refreshComponent');
            $this->closeModal();
        }

        protected function validar(): array
        {
            return $this->validate([
                                       'name'          => [ 'sometimes', 'required', 'string', 'max:255' ],
                                       'email'         => [
                                           'sometimes', 'required', 'string', 'email', 'max:255',
                                           'unique:users,email,'.$this->user->id,
                                       ],
                                       'cuil'          => [ 'sometimes', 'required', 'numeric' ],
                                   ]);
        }

        public function cancelar(): void
        {
            $this->emitTo('adminpanel::user-table', 'refreshComponent');
            $this->closeModal();
        }

        public function render()
        {
            return view('adminpanel::users.livewire.user-edit');
        }
    }
