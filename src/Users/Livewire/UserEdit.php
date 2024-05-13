<?php

    namespace FefoP\AdminPanel\Users\Livewire;

    use App\Models\User;
    use Illuminate\Http\Request;
    use FefoP\AdminPanel\Models\Role;
    use LivewireUI\Modal\ModalComponent;
    use FefoP\AdminPanel\Models\Activity;
    use FefoP\AdminPanel\Models\Permission;
    use FefoP\AdminPanel\Actions\SincronizarRolesDeUsuario;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use FefoP\AdminPanel\Actions\SincronizarPermisosDeUsuario;

    class UserEdit extends ModalComponent
    {
        use AuthorizesRequests;

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

            $this->user  = User::withTrashed()
                               ->with([ "roles", "permissions" ])
                               ->find($user_id);
            $this->authorize('update', $this->user);
            $this->name  = $this->user->name;
            $this->email = $this->user->email;
            $this->cuil  = $this->user->cuil;


            $this->all_roles            = Role::all()
                                              ->reject(fn($role) => $role->name == 'Super-Admin')
                                              ->pluck('name', 'id');
            $this->all_permissions      = Permission::pluck('name', 'id');
            $this->selected_roles       = $this->user->roles->pluck('id');
            $this->selected_permissions = $this->user->permissions->pluck('id');
            $this->separator            = config('adminpanel.log.separator');
        }

        public function updatedSelectedRoles(): void
        {
            $this->selected_roles = collect($this->selected_roles);
        }

        public function updatedSelectedPermissions(): void
        {
            $this->selected_permissions = collect($this->selected_permissions);
        }

        public function actualizar(Request $request): void
        {
            $validated         = $this->validar();
            $this->user->name  = $validated[ 'name' ];
            $this->user->email = $validated[ 'email' ];
            $this->user->cuil  = $validated[ 'cuil' ];

            $old = [];
            $new = [];

            $dirtyAttributes = $this->user->getDirty();

            if ( !empty($dirtyAttributes) ) {
                // Log the changes to an audit table
                foreach ( $dirtyAttributes as $attribute => $newValue ) {
                    $oldValue = $this->user->getOriginal($attribute); // Get the original value

                    $old[ $attribute ] = $oldValue;
                    $new[ $attribute ] = $newValue;
                }
            }

            $this->user->save();
            $this->user->refresh();

            [
                $roles_accion, $roles_borrados, $roles_agregados,
            ] = ( new SincronizarRolesDeUsuario )($this->selected_roles, $this->user);
            [
                $permisos_accion, $permisos_borrados, $permisos_agregados,
            ] = ( new SincronizarPermisosDeUsuario )($this->selected_permissions, $this->user);

            $act = Activity::write([
                                       'ip'           => $request->getClientIp(),
                                       'subject_type' => 'App\Models\User',
                                       'subject_id'   => $this->user->id,
                                       'subject_cuil' => $this->user->cuil,
                                       'event'        => 'user updated',
                                       'properties'   => json_encode([
                                                                         'id'      => $this->user->id,
                                                                         'cuil'    => $this->user->cuil,
                                                                         'deleted' => [
                                                                             ...$old,
                                                                             'roles'       => $roles_borrados,
                                                                             'permissions' => $permisos_borrados,
                                                                         ],
                                                                         'added'   => [
                                                                             ...$new,
                                                                             'roles'       => $roles_agregados,
                                                                             'permissions' => $permisos_agregados,
                                                                         ],
                                                                     ]),
                                   ]);

            $act->log(
                $act->created_at.$this->separator.
                $act->ip.$this->separator.
                $act->user_cuil.$this->separator.
                $act->event.$this->separator.
                $act->properties
            );
            $this->emitTo('adminpanel::user-table', 'refreshComponent');
            $this->closeModal();
        }

        protected function validar(): array
        {
            return $this->validate([
                                       'name'  => [ 'sometimes', 'required', 'string', 'max:255' ],
                                       'email' => [
                                           'sometimes', 'required', 'string', 'email', 'max:255',
                                           'unique:users,email,'.$this->user->id,
                                       ],
                                       'cuil'  => [ 'sometimes', 'required', 'numeric' ],
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
