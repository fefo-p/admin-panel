<?php
    
    namespace FefoP\AdminPanel\Permissions\Livewire;
    
    use LivewireUI\Modal\ModalComponent;
    use FefoP\AdminPanel\Models\Permission;
    use FefoP\AdminPanel\Actions\SincronizarUsuariosDePermiso;
    use App\Models\User;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    
    class PermissionEdit extends ModalComponent
    {
        use AuthorizesRequests;
        
        public     $permission;
        public     $name;
        public     $guard_name;
        public int $permission_id;
        //
        // public       $available_roles;
        // public array $selected_roles;
        public       $available_users;
        public array $selected_users;
        //
        public $usuario_a_agregar;
        // public $rol_a_agregar;
        
        public function mount( int $permission_id )
        {
            $this->permission = Permission::find( $permission_id );
            $this->authorize('update', $this->permission);
            
            $this->name       = $this->permission->name;
            $this->guard_name = $this->permission->guard_name;
            
            // $this->selected_roles = $this->permission->roles->pluck( 'name', 'id' )->toArray();
            // $this->refresh_available_roles();
            $this->selected_users = $this->permission->users->pluck( 'name', 'id' )->toArray();
            $this->refresh_available_users();
            
            // $this->rol_a_agregar     = null;
            $this->usuario_a_agregar = null;
        }
        
        /**
         * @return void
         */
        /*protected function refresh_available_roles(): void
        {
            $roles = Role::whereNotIn( 'id', array_keys( $this->selected_roles ) )->pluck( 'name', 'id' );

            foreach ( $roles as $key => $name ) {
                $available_roles[] = [ 'value' => (int) $key, 'label' => $name ];
            }

            $this->available_roles = $available_roles ?? [];
        }*/
        
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
            $this->permission->update( $this->validar() );
            
            // $output_roles    = ( new SincronizarRolesDePermiso )( $this->selected_roles, $this->permission );
            $output_usuarios = ( new SincronizarUsuariosDePermiso )( $this->selected_users, $this->permission );
            
            $this->emitTo( 'adminpanel::permission-table', 'refreshComponent' );
            $this->closeModal();
        }
        
        protected function validar(): array
        {
            return $this->validate( [
                                        'name'       => [ 'required', 'string', 'min:3', 'unique:permissions,name,' . $this->permission->id ],
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
        
        /*public function updatedRolAAgregar()
        {
            if ( ! is_array( $this->rol_a_agregar ) ) {
                return;
            }

            $rol = Role::find( (int) $this->rol_a_agregar[ 'value' ] );
            $this->agregar_rol( $rol );
        }*/
        
        /*protected function agregar_rol( $rol )
        {
            // Agregar nuevo rol al array de roles elegidos
            $this->selected_roles[ $rol->id ] = $rol->name;

            // Quitar el permiso de la colección de roles disponibles
            unset( $this->available_roles[ $rol->id ] );
        }*/
        
        /*public function quitar_rol( $rol_id )
        {
            // Quitar el rol del array de roles seleccionados
            unset( $this->selected_roles[ $rol_id ] );

            // Agregar nuevo rol a la colección de roles disponibles
            $this->refresh_available_roles();
        }*/
        
        public function updatedGuardName()
        {
            if ( empty( $this->guard_name ) ) {
                $this->guard_name = config( 'adminpanel.guard' );
            }
        }
        
        public function cancelar()
        {
            $this->emitTo( 'adminpanel::permission-table', 'refreshComponent' );
            $this->closeModal();
        }
        
        public function render()
        {
            return view( 'adminpanel::permissions.livewire.permission-edit' );
        }
    }
