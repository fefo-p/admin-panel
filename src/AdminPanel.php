<?php

    namespace FefoP\AdminPanel;

    use App\Models\User;
    use Illuminate\Http\Request;
    use FefoP\AdminPanel\Models\Role;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Auth;
    use FefoP\AdminPanel\Models\Permission;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Auth\Access\AuthorizationException;

    class AdminPanel extends Controller
    {
        public function index()
        {
            $title       = 'Escritorio';
            $description = null;
            $action      = null;

            return view('adminpanel::dashboard', [
                'title'       => $title ?? null,
                'description' => $description ?? null,
                'action'      => $action ?? null,
            ]);
        }

        public function about()
        {
            $title       = 'Configuración';
            $description = 'Variables de estado';
            $action      = null;
            $config      = config('adminpanel');
            Artisan::call('about');
            $about = Artisan::output();

            return view('adminpanel::about.index', [
                'config'      => $config,
                'about'       => $about,
                'title'       => $title ?? null,
                'description' => $description ?? null,
                'action'      => $action ?? null,
            ]);
        }

        public function users(Request $request)
        {
            $this->authorize('viewAny', App\Models\User::class);

            $title       = 'Listado de Usuarios';
            $description = 'Listado de usuarios definidos en el sistema';
            $action      = [
                'name'      => 'Crear usuario',
                'component' => 'adminpanel::user-create',
            ];

            if ( $request->has('search') ) {
                $users = User::where('name', 'like', "%{$request->get('search')}%")->paginate(10);
            } else {
                $users = User::with('roles')->paginate(10);
            }

            return view('adminpanel::users.index', [
                'users'       => $users,
                'title'       => $title ?? null,
                'description' => $description ?? null,
                'action'      => Auth::user()?->can('adminpanel.usuario.crear', 'App\Models\User') ? $action : null,
            ]);
        }

        public function roles()
        {
            $this->authorize('viewAny', FefoP\AdminPanel\Models\Role::class);
            /*if (Auth::user()->cannot('adminpanel.rol.ver')) {
                throw new AuthorizationException('No tienes permisos para acceder a este panel.');
            }*/

            $title       = 'Listado de Roles';
            $description = 'Roles definidos en el sistema';
            $action      = [
                'name'      => 'Crear rol',
                'component' => 'adminpanel::role-create',
            ];
            $roles       = Role::paginate(10);

            return view('adminpanel::roles.index', [
                'roles'       => $roles,
                'title'       => $title ?? null,
                'description' => $description ?? null,
                'action'      => Auth::user()?->can('adminpanel.rol.crear', 'FefoP\AdminPanel\Models\Role') ? $action : null,
            ]);
        }

        public function permissions()
        {
            $this->authorize('viewAny', FefoP\AdminPanel\Models\Permission::class);
            /*if (Auth::user()->cannot('adminpanel.permiso.ver')) {
                throw new AuthorizationException('No tienes permisos para acceder a este panel.');
            }*/

            $title       = 'Listado de Permisos';
            $description = 'Permisos definidos en el sistema';
            $action      = [
                'name'      => 'Crear permiso',
                'component' => 'adminpanel::permission-create',
            ];
            $permissions = Permission::paginate(10);

            return view('adminpanel::permissions.index', [
                'permissions' => $permissions,
                'title'       => $title ?? null,
                'description' => $description ?? null,
                'action'      => Auth::user()?->can('adminpanel.permiso.crear',
                                                    'FefoP\AdminPanel\Models\Permission') ? $action : null,
            ]);
        }

        public function search(Request $request)
        {
            $this->users(User::where('name', 'like', "%{$request->get('search')}%")->get());
        }
    }
