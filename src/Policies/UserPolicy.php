<?php

    namespace FefoP\AdminPanel\Policies;

    use App\Models\User;
    use Illuminate\Auth\Access\Response;
    use Illuminate\Auth\Access\HandlesAuthorization;

    class UserPolicy
    {
        use HandlesAuthorization;

        /**
         * Determine whether the user can administer the model.
         *
         * @param  User  $user
         *
         * @return Response|bool
         */
        public function administer(User $user)
        {
            //if ( $user->getAllPermissions()->pluck('name')->contains('adminpanel.usuario.editar') ) {
            if ($user->can('adminpanel.usuario.editar')) {
                return Response::allow('You can administer users.');
            }

            Response::deny('You cannot administer users.');
        }

        /**
         * Determine whether the user can view any models.
         *
         * @param  User  $user
         *
         * @return Response|bool
         */
        public function viewAny(User $user)
        {
            //if ( $user->getAllPermissions()->pluck('name')->contains('adminpanel.usuario.ver') ) {
            if ($user->can('adminpanel.usuario.ver')) {
                return Response::allow('You can see the user list.');
            }

            Response::deny('You cannot see the user list.');
        }

        /**
         * Determine whether the user can view the model.
         *
         * @param  User  $user
         * @param  User  $model
         *
         * @return Response|bool
         */
        public function view(User $user, User $model)
        {
            //if ( $user->getAllPermissions()->pluck('name')->contains('adminpanel.usuario.ver') ) {
            if ($user->can('adminpanel.usuario.ver')) {
                return Response::allow('You can see this user.');
            }

            Response::deny('You cannot see this user.');
        }

        /**
         * Determine whether the user can create models.
         *
         * @param  User  $user
         *
         * @return Response|bool
         */
        public function create(User $user)
        {
            //if ( $user->getAllPermissions()->pluck( 'name' )->contains( 'adminpanel.usuario.crear' ) ) {
            if ( $user->can('adminpanel.usuario.crear') ) {
                return Response::allow('You can create a user.');
            }

            Response::deny('You cannot create a user.');
        }

        /**
         * Determine whether the user can update the model.
         *
         * @param  User  $user
         * @param  User  $model
         *
         * @return Response|bool
         */
        public function update(User $user, User $model)
        {
            //if ( $user->getAllPermissions()->pluck( 'name' )->contains( 'adminpanel.usuario.editar' ) ) {
            if ( $user->can('adminpanel.usuario.editar') ) {
                return Response::allow('You can edit this user.');
            }

            Response::deny('You cannot edit this user.');
        }

        /**
         * Determine whether the user can delete the model.
         *
         * @param  User  $user
         * @param  User  $model
         *
         * @return Response|bool
         */
        public function delete(User $user, User $model)
        {
            //if ( $user->getAllPermissions()->pluck('name')->contains('adminpanel.usuario.borrar') ) {
            if ($user->can('adminpanel.usuario.borrar')) {
                return Response::allow('You can delete this user.');
            }

            Response::deny('You cannot delete this user.');
        }

        /**
         * Determine whether the user can restore the model.
         *
         * @param  User  $user
         * @param  User  $model
         *
         * @return Response|bool
         */
        public function restore(User $user, User $model)
        {
            Response::deny('You cannot restore this user.');
        }

        /**
         * Determine whether the user can permanently delete the model.
         *
         * @param  User  $user
         * @param  User  $model
         *
         * @return Response|bool
         */
        public function forceDelete(User $user, User $model)
        {
            Response::deny('You cannot force delete this user.');
        }
    }