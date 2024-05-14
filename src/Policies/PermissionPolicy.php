<?php
    
    namespace FefoP\AdminPanel\Policies;
    
    use App\Models\User;
    use Illuminate\Auth\Access\Response;
    use FefoP\AdminPanel\Models\Permission;
    use Illuminate\Auth\Access\HandlesAuthorization;
    
    class PermissionPolicy
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
            if ($user->getAllPermissions()->pluck('name')->contains('adminpanel.permiso.editar')) {
                return Response::allow('You can administer permissions.');
            }
        
            Response::deny('You cannot administer permissions.');
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
            if ($user->getAllPermissions()->pluck('name')->contains('adminpanel.permiso.ver')) {
                return Response::allow('You can see the permission list.');
            }
            
            Response::deny('You cannot see the permission list.');
        }
        
        /**
         * Determine whether the user can view the model.
         *
         * @param  User        $user
         * @param  Permission  $permission
         *
         * @return Response|bool
         */
        public function view( User $user, Permission $permission )
        {
            if ($user->getAllPermissions()->pluck('name')->contains('adminpanel.permiso.ver')) {
                return Response::allow('You can see this permission.');
            }
    
            Response::deny('You cannot see this permission.');
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
            Response::deny('You cannot create a permission.');

            if ($user->getAllPermissions()->pluck('name')->contains('adminpanel.permiso.crear')) {
                return Response::allow('You can create a permission.');
            }
    
            Response::deny('You cannot create a permission.');
        }
        
        /**
         * Determine whether the user can update the model.
         *
         * @param  User        $user
         * @param  Permission  $permission
         *
         * @return Response|bool
         */
        public function update( User $user, Permission $permission )
        {
            Response::deny('You cannot edit this permission.');

            if ($user->getAllPermissions()->pluck('name')->contains('adminpanel.permiso.editar')) {
                return Response::allow('You can edit this permission.');
            }
    
            Response::deny('You cannot edit this permission.');
        }
        
        /**
         * Determine whether the user can delete the model.
         *
         * @param  User        $user
         * @param  Permission  $permission
         *
         * @return Response|bool
         */
        public function delete( User $user, Permission $permission )
        {
            Response::deny('You cannot delete this permission.');

            if ($user->getAllPermissions()->pluck('name')->contains('adminpanel.permiso.borrar')) {
                return Response::allow('You can delete this permission.');
            }
    
            Response::deny('You cannot delete this permission.');
        }
        
        /**
         * Determine whether the user can restore the model.
         *
         * @param  User        $user
         * @param  Permission  $permission
         *
         * @return Response|bool
         */
        public function restore( User $user, Permission $permission )
        {
            Response::deny('You cannot restore this permission.');
        }
        
        /**
         * Determine whether the user can permanently delete the model.
         *
         * @param  User        $user
         * @param  Permission  $permission
         *
         * @return Response|bool
         */
        public function forceDelete( User $user, Permission $permission )
        {
            Response::deny('You cannot force delete this permission.');
        }
    }