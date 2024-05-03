<?php

    namespace FefoP\AdminPanel\Roles\Livewire;

    use App\Models\User;
    use Illuminate\Http\Request;
    use FefoP\AdminPanel\Models\Role;
    use LivewireUI\Modal\ModalComponent;
    use FefoP\AdminPanel\Models\Activity;
    use FefoP\AdminPanel\Models\Permission;
    use Illuminate\Database\Eloquent\Collection;
    use FefoP\AdminPanel\Actions\SincronizarPermisosDeRol;
    use FefoP\AdminPanel\Actions\SincronizarUsuariosDeRol;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    class RoleEdit extends ModalComponent
    {
        use AuthorizesRequests;

        public            $role;
        public Collection $users;
        public            $name;
        public            $guard_name;
        public int        $role_id;
        //
        public       $available_permissions;
        public array $selected_permissions;
        public       $available_users;
        public array $selected_users;
        //
        public $usuario_a_agregar;
        public $permiso_a_agregar;

        public function mount(int $role_id)
        {
            //Auth::user()->can('editar roles');
            $this->role = Role::find($role_id);
            $this->authorize('update', $this->role);

            $this->users = User::whereHas("roles", function ($query) use ($role_id) {
                return $query->where("id", $role_id);
            })->get();

            $this->name       = $this->role->name;
            $this->guard_name = $this->role->guard_name;

            $this->selected_permissions = $this->role->permissions->pluck('name', 'id')->toArray();
            $this->refresh_available_permissions();
            $this->selected_users = $this->users->pluck('name', 'id')->toArray();
            $this->refresh_available_users();

            $this->permiso_a_agregar = null;
            $this->usuario_a_agregar = null;
            $this->separator         = config('adminpanel.log.separator');
        }

        /**
         * @return void
         */
        protected function refresh_available_permissions(): void
        {
            $permissions = Permission::whereNotIn('id', array_keys($this->selected_permissions))->pluck('name', 'id');

            foreach ( $permissions as $key => $name ) {
                $available_permissions[] = [ 'value' => (int) $key, 'label' => $name ];
            }

            $this->available_permissions = $available_permissions ?? [];
        }

        /**
         * @return void
         */
        protected function refresh_available_users(): void
        {
            $users = User::whereNotIn('id', array_keys($this->selected_users))->pluck('name', 'id');

            foreach ( $users as $key => $name ) {
                $available_users[] = [ 'value' => (int) $key, 'label' => $name ];
            }

            $this->available_users = $available_users ?? [];
        }

        public function actualizar(Request $request): void
        {
            $validated              = $this->validar();
            $this->role->name       = $validated[ 'name' ];
            $this->role->guard_name = $validated[ 'guard_name' ];

            $old = [];
            $new = [];

            $dirtyAttributes = $this->role->getDirty();

            if ( !empty($dirtyAttributes) ) {
                // Log the changes to an audit table
                foreach ( $dirtyAttributes as $attribute => $newValue ) {
                    $oldValue = $this->role->getOriginal($attribute); // Get the original value

                    $old[ $attribute ] = $oldValue;
                    $new[ $attribute ] = $newValue;
                }
            }

            $this->role->save();

            [ $accion, $borrados, $agregados ] = ( new SincronizarPermisosDeRol )($this->selected_permissions,
                                                                                  $this->role);
            $output_usuarios = ( new SincronizarUsuariosDeRol )($this->selected_users, $this->role);

            $act = Activity::write([
                                       'ip'           => $request->getClientIp(),
                                       'subject_type' => config('permission.models.role'),
                                       'subject_id'   => $this->role->id,
                                       'subject_cuil' => null,
                                       'event'        => 'role updated',
                                       'properties'   => json_encode([
                                                                         'id'  => $this->role->id,
                                                                         'deleted' => [
                                                                             ...$old,
                                                                             'permissions' => $borrados,
                                                                         ],
                                                                         'added' => [
                                                                             ...$new,
                                                                             'permissions' => $agregados,
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

            $this->emitTo('adminpanel::role-table', 'refreshComponent');
            $this->closeModal();
        }

        protected function validar(): array
        {
            return $this->validate([
                                       'name'       => [
                                           'required', 'string', 'min:3', 'unique:roles,name,'.$this->role->id,
                                       ],
                                       'guard_name' => [ 'nullable', 'string' ],
                                   ]);
        }

        public function updatedUsuarioAAgregar()
        {
            if ( !is_array($this->usuario_a_agregar) ) {
                return;
            }

            $usuario = User::find((int) $this->usuario_a_agregar[ 'value' ]);
            $this->agregar_usuario($usuario);
        }

        protected function agregar_usuario($usuario)
        {
            // Agregar nuevo usuario al array de usuarios elegidos
            $this->selected_users[ $usuario->id ] = $usuario->name;

            // Quitar el usuario de la colección de usuarios disponibles
            foreach ( $this->available_users as $key => $user ) {
                if ( $user[ 'value' ] == $usuario->id ) {
                    unset($this->available_users[ $key ]);
                }
            }

            $this->usuario_a_agregar = null;
        }

        public function quitar_usuario($user_id)
        {
            // Quitar el permiso del array de permisos seleccionados
            unset($this->selected_users[ $user_id ]);

            // Refrescar la colección de permisos disponibles
            $this->usuario_a_agregar = null;
            $this->refresh_available_users();
        }

        public function updatedPermisoAAgregar()
        {
            if ( !is_array($this->permiso_a_agregar) ) {
                return;
            }

            $permiso = Permission::find((int) $this->permiso_a_agregar[ 'value' ]);
            $this->agregar_permiso($permiso);
        }

        protected function agregar_permiso($permiso)
        {
            // Agregar nuevo permiso al array de permisos elegidos
            $this->selected_permissions[ $permiso->id ] = $permiso->name;

            // Quitar el permiso de la colección de permisos disponibles
            unset($this->available_permissions[ $permiso->id ]);
            $this->refresh_available_permissions();
        }

        public function quitar_permiso($permiso_id)
        {
            // Quitar el permiso del array de permisos seleccionados
            unset($this->selected_permissions[ $permiso_id ]);

            // Agregar nuevo permiso a la colección de permisos disponibles
            $this->refresh_available_permissions();
        }

        public function updatedGuardName()
        {
            if ( empty($this->guard_name) ) {
                $this->guard_name = config('adminpanel.guard');
            }
        }

        public function cancelar()
        {
            $this->emitTo('adminpanel::role-table', 'refreshComponent');
            $this->closeModal();
        }

        public function render()
        {
            return view('adminpanel::roles.livewire.role-edit');
        }
    }
