<?php
    
    namespace FefoP\AdminPanel\Console;
    
    use Exception;
    use App\Models\User;
    use Illuminate\Support\Str;
    use Illuminate\Console\Command;
    use FefoP\AdminPanel\Models\Role;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Filesystem\Filesystem;
    use Symfony\Component\Process\Process;
    use FefoP\AdminPanel\Models\Permission;
    use Symfony\Component\Process\PhpExecutableFinder;
    
    class InstallCommand extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'adminpanel:install {--verification : Indicates if user email verification support should be installed}
                                               {--profile-image : Indicates if user profile image support should be installed}
                                               {--with-admin : Indicates if an admin user must be created}';
        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Install the Admin Panel components and resources';
        /**
         * Create an admin user.
         *
         * @var bool
         */
        protected $admin = [];
        
        /**
         * Execute the console command.
         *
         * @return void
         */
        public function handle()
        {
            // Publish...
            $this->info( 'Publishing configuration & migrations.' );
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-config', '--force' => true ] );
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-migrations', '--force' => true ] );
            
            // AdminPanel Policies...
            $this->info( 'Installing policies.' );
            $this->installPolicyAfter( 'protected $policies = [', 'App\Models\User::class => FefoP\AdminPanel\Policies\UserPolicy::class' );
            $this->installPolicyAfter( 'protected $policies = [', 'FefoP\AdminPanel\Models\Role::class => FefoP\AdminPanel\Policies\RolePolicy::class' );
            $this->installPolicyAfter( 'protected $policies = [', 'FefoP\AdminPanel\Models\Permission::class => FefoP\AdminPanel\Policies\PermissionPolicy::class' );
            
            // Configure Email Verification...
            if ( $this->option( 'verification' ) ) {
                $this->info( 'Setting use of email verification' );
                $this->replaceInFile( '// Features::emailVerification(),', 'Features::emailVerification(),', config_path( 'fortify.php' ) );
                $this->replaceInFile( 'class User extends Authenticatable', 'class User extends Authenticatable implements MustVerifyEmail', app_path( 'Models/User.php' ) );
            }
            
            // Configure User Profile image...
            if ( $this->option( 'profile-image' ) ) {
                $this->info( 'Setting use of profile images' );
                $this->replaceInFile( '        // Features::profilePhotos(),', '        Features::profilePhotos(),', config_path( 'jetstream.php' ) );
                
                // Publishing Admin Panel assets
                $this->info( 'Publishing default user image' );
                $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-images', '--force' => true ] );
                
                $this->info( 'Overriding profile url in User model' );
                $last_line = $this->last_line_in_file( app_path( 'Models/User.php' ) );
                $this->replaceInFile( $last_line, $this->override_profile_photo_url() . PHP_EOL . ' } ', app_path( 'Models/User.php' ) );
            }
            
            // Add HasRoles to User model...
            $this->info( 'Updating User model with HasRoles trait.' );
            $this->addLineAfter( 'use TwoFactorAuthenticatable', 'use HasRoles', app_path( 'Models/User.php' ) );
            $this->addLineAfter( 'use Laravel\Fortify\TwoFactorAuthenticatable', 'use Spatie\Permission\Traits\HasRoles', app_path( 'Models/User.php' ) );
            
            // Add SoftDeletes to User model...
            $this->info( 'Updating User model with SoftDeletes trait.' );
            $this->addLineAfter( 'use HasRoles', 'use SoftDeletes', app_path( 'Models/User.php' ) );
            $this->addLineAfter( 'use Spatie\Permission\Traits\HasRoles', 'use Illuminate\Database\Eloquent\SoftDeletes', app_path( 'Models/User.php' ) );
            
            // Publish needed migrations...
            $this->info( 'Publishing Spatie permission migrations.' );
            $this->callSilent( 'vendor:publish', [ '--provider' => 'Spatie\Permission\PermissionServiceProvider', '--force' => false ] );
            
            $this->line( '' );
            $this->call( 'migrate' );
            $this->line( '' );
    
            // Publish needed views for tables & modals...
            $this->info( 'Publishing needed views for tables and modals.' );
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-tables', '--force' => true ] );
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-modal-views', '--force' => true ] );
    
            // Publish needed assets...
            $this->info( 'Publishing needed assets.' );
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-public', '--force' => true ] );
            
            // Admin user creation is optional
            if ( $this->option( 'with-admin' ) ) {
                $this->info( 'Please provide the needed information to create an administrator.' );
                $this->admin[ 'cuil' ]                  = $this->ask( 'CUIL:' );
                $this->admin[ 'name' ]                  = $this->anticipate( 'Name:', [ 'Administrador' ] );
                $this->admin[ 'email' ]                 = $this->anticipate( 'Email:', [ 'admin@admin.com' ] );
                $this->admin[ 'password' ]              = $this->secret( 'Password:' );
                $this->admin[ 'password_confirmation' ] = $this->secret( 'Confirm Password:' );
                
                if ( $this->admin[ 'password' ] != $this->admin[ 'password_confirmation' ] ) {
                    $this->error( 'Passwords are not equal' );
                }
            }
            
            // Install basic permissions and roles
            $this->info( 'Creating permissions and roles' );
            $this->create_roles_and_permissions( $this->admin );
            
            $this->line( '' );
            
            $this->comment( 'Please remember to migrate your DB.' );
            $this->comment( 'You should also rebuild your css & js assets.' );
        }
        
        /**
         * Install policy in the application AuthServiceProvider file.
         *
         * @param  string  $after
         * @param  string  $name
         *
         * @return void
         */
        protected function installPolicyAfter( $after, $name )
        {
            if ( ! Str::contains( $authSPFile = file_get_contents( app_path( 'Providers/AuthServiceProvider.php' ) ), $name ) ) {
                file_put_contents( app_path( 'Providers/AuthServiceProvider.php' ), str_replace(
                    $after,
                    $after . PHP_EOL . '        ' . $name . ',',
                    $authSPFile
                ) );
            }
        }
        
        /**
         * Replace a given string within a given file.
         *
         * @param  string  $search
         * @param  string  $replace
         * @param  string  $path
         *
         * @return void
         */
        protected function replaceInFile( $search, $replace, $file )
        {
            file_put_contents( $file, str_replace( $search, $replace, file_get_contents( $file ) ) );
        }
        
        /**
         * Return the last line in file
         */
        protected function last_line_in_file( $file )
        {
            $data = file( $file );
            
            return $data[ count( $data ) - 1 ];
        }
        
        protected function override_profile_photo_url()
        {
            return <<<'EOF'

/**
 * Get the default profile photo URL if no profile photo has been uploaded.
 *
 * @return string
 */
protected function defaultProfilePhotoUrl()
{
    return asset('images/anonymous_user.png');
}


EOF;
        }
        
        /**
         * Add the line into the supplied file after a specific line.
         *
         * @param  string  $after
         * @param  string  $name
         *
         * @return void
         */
        protected function addLineAfter( $after, $name, $file )
        {
            if ( ! Str::contains( $appConfig = file_get_contents( $file ), $name . ';' ) ) {
                file_put_contents( $file, str_replace(
                    $after . ';',
                    $after . ';' . PHP_EOL . $name . ';',
                    $appConfig
                ) );
            }
            else {
                $this->comment( $name . ' was already in ' . $file . '.' );
            }
        }
        
        protected function create_roles_and_permissions( $user )
        {
            if ( $user ) {
                $user[ 'password' ] = Hash::make( $user[ 'password' ] );
                $administrator      = User::create( $user );
            }
            
            $roles = [
                [ 'name' => 'administrador' ],
            ];
            
            $permissions = [
                [
                    'name'       => 'adminpanel.usuario.administrar',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.usuario.crear',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.usuario.ver',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.usuario.editar',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.usuario.borrar',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.rol.administrar',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.rol.crear',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.rol.ver',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.rol.editar',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.rol.borrar',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.permisos.administrar',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.permisos.crear',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.permisos.ver',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.permisos.editar',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'adminpanel.permisos.borrar',
                    'guard_name' => 'web',
                ],
            ];
            
            foreach ( $roles as $role ) {
                Role::create( $role );
            }
            
            foreach ( $permissions as $permission ) {
                Permission::create( $permission );
            }
            
            $role = Role::first();
            
            foreach ( Permission::all() as $permission ) {
                $role->givePermissionTo( $permission );
            }
            
            if ( $user ) {
                $administrator->assignRole( $role );
            }
            
        }
        
        /**
         * Get the path to the appropriate PHP binary.
         *
         * @return string
         */
        protected function phpBinary()
        {
            return ( new PhpExecutableFinder() )->find( false ) ?: 'php';
        }
        
        /**
         * Install the service provider in the application configuration file.
         *
         * @param  string  $after
         * @param  string  $name
         *
         * @return void
         */
        protected function installServiceProviderAfter( $after, $name )
        {
            if ( ! Str::contains( $appConfig = file_get_contents( config_path( 'app.php' ) ), 'App\\Providers\\' . $name . '::class' ) ) {
                file_put_contents( config_path( 'app.php' ), str_replace(
                    'App\\Providers\\' . $after . '::class,',
                    'App\\Providers\\' . $after . '::class,' . PHP_EOL . '        App\\Providers\\' . $name . '::class,',
                    $appConfig
                ) );
            }
        }
        
        /**
         * Install the middleware to a group in the application Http Kernel.
         *
         * @param  string  $after
         * @param  string  $name
         * @param  string  $group
         *
         * @return void
         */
        protected function installMiddlewareAfter( $after, $name, $group = 'web' )
        {
            $httpKernel = file_get_contents( app_path( 'Http/Kernel.php' ) );
            
            $middlewareGroups = Str::before( Str::after( $httpKernel, '$middlewareGroups = [' ), '];' );
            $middlewareGroup  = Str::before( Str::after( $middlewareGroups, "'$group' => [" ), '],' );
            
            if ( ! Str::contains( $middlewareGroup, $name ) ) {
                $modifiedMiddlewareGroup = str_replace(
                    $after . ',',
                    $after . ',' . PHP_EOL . '            ' . $name . ',',
                    $middlewareGroup,
                );
                
                file_put_contents( app_path( 'Http/Kernel.php' ), str_replace(
                    $middlewareGroups,
                    str_replace( $middlewareGroup, $modifiedMiddlewareGroup, $middlewareGroups ),
                    $httpKernel
                ) );
            }
        }
        
        /**
         * Delete the "node_modules" directory and remove the associated lock files.
         *
         * @return void
         */
        protected static function flushNodeModules()
        {
            tap( new Filesystem, function( $files ) {
                $files->deleteDirectory( base_path( 'node_modules' ) );
                
                $files->delete( base_path( 'yarn.lock' ) );
                $files->delete( base_path( 'package-lock.json' ) );
            } );
        }
    }
