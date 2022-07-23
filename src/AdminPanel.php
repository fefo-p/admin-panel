<?php

    namespace FefoP\AdminPanel;

    use App\Models\User;
    use FefoP\AdminPanel\Models\Role;
    use App\Http\Controllers\Controller;
    use FefoP\AdminPanel\Models\Permission;

    class AdminPanel extends Controller
    {
        public function index()
        {
            $title       = 'Dashboard';
            $description = null;
            $action      = null;

            return view( 'adminpanel::dashboard', [
                'title'       => $title ?? null,
                'description' => $description ?? null,
                'action'      => $action ?? null,
            ] );
        }

        public function key()
        {
            $title       = 'Key Values';
            $description = 'Alguna descripción por acá...';
            $action      = null;
            $keys        = config( 'adminpanel' );;

            return view( 'adminpanel::key.index', [
                'keys'        => $keys,
                'title'       => $title ?? null,
                'description' => $description ?? null,
                'action'      => $action ?? null,
            ] );
        }

        public function users()
        {
            $title       = 'Users Index';
            $description = 'Listado de usuarios definidos en el sistema';
            $action      = [
                'name'      => 'Crear usuario',
                'component' => 'adminpanel::user-create',
            ];
            $users       = User::with( 'roles' )->paginate( 10 );

            return view( 'adminpanel::users.index', [
                'users'       => $users,
                'title'       => $title ?? null,
                'description' => $description ?? null,
                'action'      => $action ?? null,
            ] );
        }

        public function roles()
        {
            $title       = 'Roles Index';
            $description = 'Roles definidos en el sistema';
            $action      = [
                'name'      => 'Crear rol',
                'component' => 'adminpanel::role-create',
            ];
            $roles       = Role::paginate( 10 );

            return view( 'adminpanel::roles.index', [
                'roles'       => $roles,
                'title'       => $title ?? null,
                'description' => $description ?? null,
                'action'      => $action ?? null,
            ] );
        }

        public function permissions()
        {
            $title       = 'Permissions Index';
            $description = 'Permisos definidos en el sistema';
            $action      = [
                'name'      => 'Crear permiso',
                'component' => 'adminpanel::permission-create',
            ];
            $permissions = Permission::paginate( 10 );

            return view( 'adminpanel::permissions.index', [
                'permissions' => $permissions,
                'title'       => $title ?? null,
                'description' => $description ?? null,
                'action'      => $action ?? null,
            ] );
        }
    }
