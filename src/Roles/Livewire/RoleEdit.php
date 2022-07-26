<?php

    namespace FefoP\AdminPanel\Roles\Livewire;

    use App\Models\User;
    use FefoP\AdminPanel\Models\Role;
    use LivewireUI\Modal\ModalComponent;
    use FefoP\AdminPanel\Models\Permission;
    use FefoP\AdminPanel\Actions\SincronizarPermisosDeRol;
    use FefoP\AdminPanel\Actions\SincronizarUsuariosDeRol;

    class RoleEdit extends ModalComponent
    {
        public     $role;
        public     $name;
        public     $guard_name;
        public int $role_id;
        //
        public       $available_permissions;
        public array $selected_permissions;
        public       $available_users;
        public array $selected_users;
        //
        public $usuario_a_agregar;
        public $permiso_a_agregar;

        public function mount( int $role_id )
        {
            auth()->user()->can('editar roles');
    
            $this->role       = Role::find( $role_id );
            $this->name       = $this->role->name;
            $this->guard_name = $this->role->guard_name;

            $this->selected_permissions = $this->role->permissions->pluck( 'name', 'id' )->toArray();
            $this->refresh_available_permissions();
            $this->selected_users = $this->role->users->pluck( 'name', 'id' )->toArray();
            $this->refresh_available_users();

            $this->permiso_a_agregar = null;
            $this->usuario_a_agregar = null;
        }

        /**
         * @return void
         */
        protected function refresh_available_permissions(): void
        {
            $permissions = Permission::whereNotIn( 'id', array_keys( $this->selected_permissions ) )->pluck( 'name', 'id' );

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
            $users = User::whereNotIn( 'id', array_keys( $this->selected_users ) )->pluck( 'name', 'id' );

            foreach ( $users as $key => $name ) {
                $available_users[] = [ 'value' => (int) $key, 'label' => $name ];
            }

            $this->available_users = $available_users ?? [];
        }

        public function actualizar()
        {
            $this->role->update( $this->validar() );

            $output_permisos = ( new SincronizarPermisosDeRol )( $this->selected_permissions, $this->role );
            $output_usuarios = ( new SincronizarUsuariosDeRol )( $this->selected_users, $this->role );

            $this->emitTo( 'adminpanel::role-table', 'refreshComponent' );
            $this->closeModal();
        }

        protected function validar(): array
        {
            return $this->validate( [
                                        'name'       => [ 'required', 'string', 'min:3', 'unique:roles,name,' . $this->role->id ],
                                        'guard_name' => [ 'nullable', 'string' ],
                                    ] );
        }

        public function updatedUsuarioAAgregar()
        {
            if ( ! is_array( $this->usuario_a_agregar ) ) {
                return;
            }

            $usuario = User::find( (int) $this->usuario_a_agregar[ 'value' ] );
            $this->agregar_usuario( $usuario );
        }

        protected function agregar_usuario( $usuario )
        {
            // Agregar nuevo usuario al array de usuarios elegidos
            $this->selected_users[ $usuario->id ] = $usuario->name;

            // Quitar el usuario de la colección de usuarios disponibles
            foreach ( $this->available_users as $key => $user ) {
                if ( $user[ 'value' ] == $usuario->id ) {
                    unset( $this->available_users[ $key ] );
                }
            }

            $this->usuario_a_agregar = null;
        }

        public function quitar_usuario( $user_id )
        {
            // Quitar el permiso del array de permisos seleccionados
            unset( $this->selected_users[ $user_id ] );

            // Refrescar la colección de permisos disponibles
            $this->usuario_a_agregar = null;
            $this->refresh_available_users();
        }

        public function updatedPermisoAAgregar()
        {
            if ( ! is_array( $this->permiso_a_agregar ) ) {
                return;
            }

            $permiso = Permission::find( (int) $this->permiso_a_agregar[ 'value' ] );
            $this->agregar_permiso( $permiso );
        }

        protected function agregar_permiso( $permiso )
        {
            // Agregar nuevo permiso al array de permisos elegidos
            $this->selected_permissions[ $permiso->id ] = $permiso->name;

            // Quitar el permiso de la colección de permisos disponibles
            unset( $this->available_permissions[ $permiso->id ] );
        }

        public function quitar_permiso( $permiso_id )
        {
            // Quitar el permiso del array de permisos seleccionados
            unset( $this->selected_permissions[ $permiso_id ] );

            // Agregar nuevo permiso a la colección de permisos disponibles
            $this->refresh_available_permissions();
        }

        public function updatedGuardName()
        {
            if ( empty( $this->guard_name ) ) {
                $this->guard_name = config( 'adminpanel.guard' );
            }
        }

        public function cancelar()
        {
            $this->emitTo( 'adminpanel::role-table', 'refreshComponent' );
            $this->closeModal();
        }

        public function render()
        {
            return view( 'adminpanel::roles.livewire.role-edit' );
        }
    }
