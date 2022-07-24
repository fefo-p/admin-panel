<?php
    
    namespace FefoP\AdminPanel;
    
    use App\Models\User;
    use FefoP\AdminPanel\Models\Role;
    use App\Http\Controllers\Controller;
    use FefoP\AdminPanel\Models\Permission;
    use Illuminate\Support\Facades\Artisan;
    
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
        
        public function about()
        {
            $title       = 'About';
            $description = 'Alguna descripción por acá...';
            $action      = null;
            $config      = config( 'adminpanel' );
            Artisan::call( 'about' );
            $about = Artisan::output();
            
            return view( 'adminpanel::about.index', [
                'config'       => $config,
                'about'       => $about,
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
                'action'      => auth()->user()?->can('crear usuarios', 'App\Models\User') ? $action : null,
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
                'action'      => auth()->user()?->can('crear roles', 'FefoP\AdminPanel\Models\Role') ? $action : null,
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
                'action'      => auth()->user()?->can('crear permisos', 'FefoP\AdminPanel\Models\Permission') ? $action : null,
            ] );
        }
    }
