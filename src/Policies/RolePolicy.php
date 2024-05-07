<?php
    
    namespace FefoP\AdminPanel\Policies;
    
    use App\Models\User;
    use FefoP\AdminPanel\Models\Role;
    use Illuminate\Auth\Access\Response;
    use Illuminate\Auth\Access\HandlesAuthorization;
    
    class RolePolicy
    {
        use HandlesAuthorization;
        
        /**
         * Determine whether the user can administer the model.
         *
         * @param  User  $user
         *
         * @return Response|bool
         */
        public function administer( User $user )
        {
            if ( $user->getAllPermissions()->pluck( 'name' )->contains( 'adminpanel.rol.administrar' ) ) {
                return Response::allow( 'You can administer roles.' );
            }
            
            Response::deny( 'You cannot administer roles.' );
        }
        
        /**
         * Determine whether the user can view any models.
         *
         * @param  User  $user
         *
         * @return Response|bool
         */
        public function viewAny( User $user )
        {
            if ( $user->getAllPermissions()->pluck( 'name' )->contains( 'adminpanel.rol.ver' ) ) {
                return Response::allow( 'You can see the role list.' );
            }
            
            Response::deny( 'You cannot see the role list.' );
        }
        
        /**
         * Determine whether the user can view the model.
         *
         * @param  User  $user
         * @param  Role  $role
         *
         * @return Response|bool
         */
        public function view( User $user, Role $role )
        {
            if ( $user->getAllPermissions()->pluck( 'name' )->contains( 'adminpanel.rol.ver' ) ) {
                return Response::allow( 'You can see this role.' );
            }
            
            Response::deny( 'You cannot see this role.' );
        }
        
        /**
         * Determine whether the user can create models.
         *
         * @param  User  $user
         *
         * @return Response|bool
         */
        public function create( User $user )
        {
            if ( $user->getAllPermissions()->pluck( 'name' )->contains( 'adminpanel.rol.crear' ) ) {
                return Response::allow( 'You can create a role.' );
            }
            
            Response::deny( 'You cannot create a role.' );
        }
        
        /**
         * Determine whether the user can update the model.
         *
         * @param  User  $user
         * @param  Role  $role
         *
         * @return Response|bool
         */
        public function update( User $user, Role $role )
        {
            if ( $user->getAllPermissions()->pluck( 'name' )->contains( 'adminpanel.rol.editar' ) ) {
                return Response::allow( 'You can edit this role.' );
            }
            
            Response::deny( 'You cannot edit this role.' );
        }
        
        /**
         * Determine whether the user can delete the model.
         *
         * @param  User  $user
         * @param  Role  $role
         *
         * @return Response|bool
         */
        public function delete( User $user, Role $role )
        {
            if ( $user->getAllPermissions()->pluck( 'name' )->contains( 'adminpanel.rol.borrar' ) ) {
                return Response::allow( 'You can delete this role.' );
            }
            
            Response::deny( 'You cannot delete this role.' );
        }
        
        /**
         * Determine whether the user can restore the model.
         *
         * @param  User  $user
         * @param  Role  $role
         *
         * @return Response|bool
         */
        public function restore( User $user, Role $role )
        {
            Response::deny( 'You cannot restore this role.' );
        }
        
        /**
         * Determine whether the user can permanently delete the model.
         *
         * @param  User  $user
         * @param  Role  $role
         *
         * @return Response|bool
         */
        public function forceDelete( User $user, Role $role )
        {
            Response::deny( 'You cannot force delete this role.' );
        }
    }