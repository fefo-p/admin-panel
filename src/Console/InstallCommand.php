<?php
    
    namespace FefoP\AdminPanel\Console;

    use Exception;
    use Illuminate\Console\Command;
    use Illuminate\Filesystem\Filesystem;
    use Illuminate\Support\Str;
    use Symfony\Component\Process\PhpExecutableFinder;
    use Symfony\Component\Process\Process;
    use App\Models\User;
    use FefoP\AdminPanel\Models\Role;
    use FefoP\AdminPanel\Models\Permission;
    use Illuminate\Support\Facades\Hash;
    
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
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-config', '--force' => true ] );
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-migrations', '--force' => true ] );
            
            // "Home" Route...
            // $this->replaceInFile('/home', '/dashboard', app_path('Providers/RouteServiceProvider.php'));
            
            // if (file_exists(resource_path('views/welcome.blade.php'))) {
            //     $this->replaceInFile('/home', '/dashboard', resource_path('views/welcome.blade.php'));
            //     $this->replaceInFile('Home', 'Dashboard', resource_path('views/welcome.blade.php'));
            // }
    
            // AdminPanel Provider...
            // $this->installServiceProviderAfter('RouteServiceProvider', 'FortifyServiceProvider');
    
            // AdminPanel Policies...
            $this->info( 'Installing policies.' );
            $this->installPolicyAfter('protected $policies = [', 'App\Models\User::class => FefoP\AdminPanel\Policies\UserPolicy::class');
            $this->installPolicyAfter('protected $policies = [', 'FefoP\AdminPanel\Models\Role::class => FefoP\AdminPanel\Policies\RolePolicy::class');
            $this->installPolicyAfter('protected $policies = [', 'FefoP\AdminPanel\Models\Permission::class => FefoP\AdminPanel\Policies\PermissionPolicy::class');
            
            // Configure Session...
            // $this->configureSession();
            
            // Configure API...
            // if ($this->option('api')) {
            //     $this->replaceInFile('// Features::api(),', 'Features::api(),', config_path('jetstream.php'));
            // }
            
            // Configure Email Verification...
            if ( $this->option( 'verification' ) ) {
                $this->replaceInFile( '// Features::emailVerification(),', 'Features::emailVerification(),', config_path( 'fortify.php' ) );
                $this->replaceInFile( 'class User extends Authenticatable', 'class User extends Authenticatable implements MustVerifyEmail', app_path( 'Models/User.php' ) );
            }
            
            // Configure User Profile image...
            if ( $this->option( 'profile-image' ) ) {
                $this->replaceInFile( '        // Features::profilePhotos(),', '        Features::profilePhotos(),', config_path( 'jetstream.php' ) );
            }
            
            // Install Stack...
            // if ($this->argument('stack') === 'livewire') {
            //     $this->installLivewireStack();
            // } elseif ($this->argument('stack') === 'inertia') {
            //     $this->installInertiaStack();
            // }
            
            // Tests...
            // $stubs = $this->getTestStubsPath();
            
            // if ($this->option('pest')) {
            //     $this->requireComposerDevPackages('pestphp/pest:^1.16', 'pestphp/pest-plugin-laravel:^1.1');
            
            //     copy($stubs.'/Pest.php', base_path('tests/Pest.php'));
            //     copy($stubs.'/ExampleTest.php', base_path('tests/Feature/ExampleTest.php'));
            //     copy($stubs.'/ExampleUnitTest.php', base_path('tests/Unit/ExampleTest.php'));
            // }
            
            // copy($stubs.'/AuthenticationTest.php', base_path('tests/Feature/AuthenticationTest.php'));
            // copy($stubs.'/EmailVerificationTest.php', base_path('tests/Feature/EmailVerificationTest.php'));
            // copy($stubs.'/PasswordConfirmationTest.php', base_path('tests/Feature/PasswordConfirmationTest.php'));
            // copy($stubs.'/PasswordResetTest.php', base_path('tests/Feature/PasswordResetTest.php'));
            // copy($stubs.'/RegistrationTest.php', base_path('tests/Feature/RegistrationTest.php'));
            
            // Add HasRoles to User model...
            $this->info( 'Updating User model with HasRoles trait.' );
            $this->addLineAfter( 'use TwoFactorAuthenticatable', 'use HasRoles', app_path( 'Models/User.php' ) );
            $this->addLineAfter( 'use Laravel\Fortify\TwoFactorAuthenticatable', 'use Spatie\Permission\Traits\HasRoles', app_path( 'Models/User.php' ) );
            
            // Add SoftDeletes to User model...
            $this->info( 'Updating User model with SoftDeletes trait.' );
            $this->addLineAfter( 'use HasRoles', 'use SoftDeletes', app_path( 'Models/User.php' ) );
            $this->addLineAfter( 'use Spatie\Permission\Traits\HasRoles', 'use Illuminate\Database\Eloquent\SoftDeletes', app_path( 'Models/User.php' ) );
            
            // Publish needed migrations...
            $this->info( 'Publishing needed migrations.' );
            $this->callSilent( 'vendor:publish', [ '--provider' => 'Spatie\Permission\PermissionServiceProvider', '--force' => true ] );
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-migrations', '--force' => true ] );
            
            $this->line( '' );
            $this->call( 'migrate' );
            $this->line( '' );
            
            // Publish needed views for tables & modals...
            $this->info( 'Publishing needed views for tables and modals.' );
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-tables', '--force' => true ] );
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-modal-views', '--force' => true ] );
            
            // Admin user creation is optional
            if ( $this->option( 'with-admin' ) ) {
                $this->info( 'Please provide the needed information to create an administrator.' );
                $this->admin[ 'cuil' ]                  = $this->anticipate( 'CUIL:', [ 20243136726 ] );
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
            
            $this->info( 'Overriding profile url in User model' );
            $last_line = $this->last_line_in_file( app_path( 'Models/User.php' ) );
            $this->replaceInFile( $last_line, $this->override_profile_photo_url().PHP_EOL.' } ', app_path( 'Models/User.php' ) );
            
            // Publishing Admin Panel assets
            $this->callSilent( 'vendor:publish', [ '--tag' => 'adminpanel-images', '--force' => true ] );
            
            $this->line( '' );
            
            $this->comment( 'Please remember to migrate your DB.' );
            $this->comment( 'You should also rebuild your css & js assets.' );
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
        protected function replaceInFile( $search, $replace, $path )
        {
            file_put_contents( $path, str_replace( $search, $replace, file_get_contents( $path ) ) );
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
            if ( ! Str::contains( $appConfig = file_get_contents( $file ), $name.';' ) ) {
                file_put_contents( $file, str_replace(
                    $after.';',
                    $after.';'.PHP_EOL.$name.';',
                    $appConfig
                ) );
            }
            else {
                $this->info( 'Could not update '.$file.'.' );
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
                    'name'       => 'administrar usuarios',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'crear usuarios',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'ver usuarios',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'editar usuarios',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'borrar usuarios',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'administrar roles',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'crear roles',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'ver roles',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'editar roles',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'borrar roles',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'administrar permisos',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'crear permisos',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'ver permisos',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'editar permisos',
                    'guard_name' => 'web',
                ],
                [
                    'name'       => 'borrar permisos',
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
            
            if ( $administrator ) {
                $administrator->assignRole( $role );
            }
            
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
         * Configure the session driver for Jetstream.
         *
         * @return void
         */
        protected function configureSession()
        {
            if ( ! class_exists( 'CreateSessionsTable' ) ) {
                try {
                    $this->call( 'session:table' );
                } catch ( Exception $e ) {
                    //
                }
            }
            
            $this->replaceInFile( "'SESSION_DRIVER', 'file'", "'SESSION_DRIVER', 'database'", config_path( 'session.php' ) );
            $this->replaceInFile( 'SESSION_DRIVER = file', 'SESSION_DRIVER = database', base_path( '.env' ) );
            $this->replaceInFile( 'SESSION_DRIVER = file', 'SESSION_DRIVER = database', base_path( '.env.example' ) );
        }
        
        /**
         * Install the Livewire stack into the application.
         *
         * @return void
         */
        protected function installLivewireStack()
        {
            // Install Livewire...
            $this->requireComposerPackages( 'livewire/livewire:^2.5' );
            
            // Sanctum...
            ( new Process( [ $this->phpBinary(), 'artisan', 'vendor:publish', '--provider = Laravel\Sanctum\SanctumServiceProvider', '--force' ], base_path() ) )
                ->setTimeout( null )
                ->run( function( $type, $output ) {
                    $this->output->write( $output );
                } );
            
            // Update Configuration...
            $this->replaceInFile( 'inertia', 'livewire', config_path( 'jetstream.php' ) );
            // $this->replaceInFile("'guard' => 'web'", "'guard' => 'sanctum'", config_path('auth.php'));
            
            // NPM Packages...
            $this->updateNodePackages( function( $packages ) {
                return [
                           '@tailwindcss/forms'      => '^0.5.2',
                           '@tailwindcss/typography' => '^0.5.0',
                           'alpinejs'                  => '^3.0.6',
                           'autoprefixer'              => '^10.4.7',
                           'postcss'                   => '^8.4.14',
                           'tailwindcss'               => '^3.1.0',
                       ] + $packages;
            } );
            
            // Tailwind Configuration...
            copy( __DIR__.' /../../stubs/livewire/tailwind.config.js', base_path( 'tailwind.config.js' ) );
            copy( __DIR__.' /../../stubs/livewire/postcss.config.js', base_path( 'postcss.config.js' ) );
            copy( __DIR__.' /../../stubs/livewire/vite.config.js', base_path( 'vite.config.js' ) );
            
            // Directories...
            ( new Filesystem )->ensureDirectoryExists( app_path( 'Actions/Fortify' ) );
            ( new Filesystem )->ensureDirectoryExists( app_path( 'Actions/Jetstream' ) );
            ( new Filesystem )->ensureDirectoryExists( app_path( 'View/Components' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'css' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'markdown' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'views/api' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'views/auth' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'views/layouts' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'views/profile' ) );
            
            ( new Filesystem )->deleteDirectory( resource_path( 'sass' ) );
            
            // Terms Of Service/Privacy Policy...
            copy( __DIR__.' /../../stubs/resources/markdown/terms.md', resource_path( 'markdown/terms.md' ) );
            copy( __DIR__.' /../../stubs/resources/markdown/policy.md', resource_path( 'markdown/policy.md' ) );
            
            // Service Providers...
            copy( __DIR__.' /../../stubs/app/Providers/JetstreamServiceProvider.php', app_path( 'Providers/JetstreamServiceProvider.php' ) );
            $this->installServiceProviderAfter( 'FortifyServiceProvider', 'JetstreamServiceProvider' );
            
            // Models...
            copy( __DIR__.' /../../stubs/app/Models/User.php', app_path( 'Models/User.php' ) );
            
            // Factories...
            copy( __DIR__.' /../../database/factories/UserFactory.php', base_path( 'database/factories/UserFactory.php' ) );
            
            // Actions...
            copy( __DIR__.' /../../stubs/app/Actions/Fortify/CreateNewUser.php', app_path( 'Actions/Fortify/CreateNewUser.php' ) );
            copy( __DIR__.' /../../stubs/app/Actions/Fortify/UpdateUserProfileInformation.php', app_path( 'Actions/Fortify/UpdateUserProfileInformation.php' ) );
            copy( __DIR__.' /../../stubs/app/Actions/Jetstream/DeleteUser.php', app_path( 'Actions/Jetstream/DeleteUser.php' ) );
            
            // View Components...
            copy( __DIR__.' /../../stubs/livewire/app/View/Components/AppLayout.php', app_path( 'View/Components/AppLayout.php' ) );
            copy( __DIR__.' /../../stubs/livewire/app/View/Components/GuestLayout.php', app_path( 'View/Components/GuestLayout.php' ) );
            
            // Layouts...
            ( new Filesystem )->copyDirectory( __DIR__.' /../../stubs/livewire/resources/views/layouts', resource_path( 'views/layouts' ) );
            
            // Single Blade Views...
            copy( __DIR__.' /../../stubs/livewire/resources/views/dashboard.blade.php', resource_path( 'views/dashboard.blade.php' ) );
            copy( __DIR__.' /../../stubs/livewire/resources/views/navigation - menu.blade.php', resource_path( 'views/navigation - menu.blade.php' ) );
            copy( __DIR__.' /../../stubs/livewire/resources/views/terms.blade.php', resource_path( 'views/terms.blade.php' ) );
            copy( __DIR__.' /../../stubs/livewire/resources/views/policy.blade.php', resource_path( 'views/policy.blade.php' ) );
            
            // Other Views...
            ( new Filesystem )->copyDirectory( __DIR__.' /../../stubs/livewire/resources/views/api', resource_path( 'views/api' ) );
            ( new Filesystem )->copyDirectory( __DIR__.' /../../stubs/livewire/resources/views/profile', resource_path( 'views/profile' ) );
            ( new Filesystem )->copyDirectory( __DIR__.' /../../stubs/livewire/resources/views/auth', resource_path( 'views/auth' ) );
            
            // Routes...
            $this->replaceInFile( 'auth:api', 'auth:sanctum', base_path( 'routes/api.php' ) );
            
            if ( ! Str::contains( file_get_contents( base_path( 'routes/web.php' ) ), "'/dashboard'" ) ) {
                ( new Filesystem )->append( base_path( 'routes/web.php' ), $this->livewireRouteDefinition() );
            }
            
            // Assets...
            copy( __DIR__.' /../../stubs/resources/css/app.css', resource_path( 'css/app.css' ) );
            copy( __DIR__.' /../../stubs/livewire/resources/js/app.js', resource_path( 'js/app.js' ) );
            
            // Tests...
            $stubs = $this->getTestStubsPath();
            
            copy( $stubs.'/livewire/ApiTokenPermissionsTest.php', base_path( 'tests/Feature/ApiTokenPermissionsTest.php' ) );
            copy( $stubs.'/livewire/BrowserSessionsTest.php', base_path( 'tests/Feature/BrowserSessionsTest.php' ) );
            copy( $stubs.'/livewire/CreateApiTokenTest.php', base_path( 'tests/Feature/CreateApiTokenTest.php' ) );
            copy( $stubs.'/livewire/DeleteAccountTest.php', base_path( 'tests/Feature/DeleteAccountTest.php' ) );
            copy( $stubs.'/livewire/DeleteApiTokenTest.php', base_path( 'tests/Feature/DeleteApiTokenTest.php' ) );
            copy( $stubs.'/livewire/ProfileInformationTest.php', base_path( 'tests/Feature/ProfileInformationTest.php' ) );
            copy( $stubs.'/livewire/TwoFactorAuthenticationSettingsTest.php', base_path( 'tests/Feature/TwoFactorAuthenticationSettingsTest.php' ) );
            copy( $stubs.'/livewire/UpdatePasswordTest.php', base_path( 'tests/Feature/UpdatePasswordTest.php' ) );
            
            // Teams...
            if ( $this->option( 'teams' ) ) {
                $this->installLivewireTeamStack();
            }
            
            $this->line( '' );
            $this->info( 'Livewire scaffolding installed successfully.' );
            $this->comment( 'Please execute "npm install && npm run dev" to build your assets.' );
        }
        
        /**
         * Installs the given Composer Packages into the application.
         *
         * @param  mixed  $packages
         *
         * @return void
         */
        protected function requireComposerPackages( $packages )
        {
            $composer = $this->option( 'composer' );
            
            if ( $composer !== 'global' ) {
                $command = [ $this->phpBinary(), $composer, 'require ' ];
            }
            
            $command = array_merge(
                $command ?? [ 'composer', 'require ' ],
                is_array( $packages ) ? $packages : func_get_args()
            );
            
            ( new Process( $command, base_path(), [ 'COMPOSER_MEMORY_LIMIT' => ' - 1' ] ) )
                ->setTimeout( null )
                ->run( function( $type, $output ) {
                    $this->output->write( $output );
                } );
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
         * Update the "package.json" file.
         *
         * @param  callable  $callback
         * @param  bool      $dev
         *
         * @return void
         */
        protected static function updateNodePackages( callable $callback, $dev = true )
        {
            if ( ! file_exists( base_path( 'package.json' ) ) ) {
                return;
            }
            
            $configurationKey = $dev ? 'devDependencies' : 'dependencies';
            
            $packages = json_decode( file_get_contents( base_path( 'package.json' ) ), true );
            
            $packages[ $configurationKey ] = $callback(
                array_key_exists( $configurationKey, $packages ) ? $packages[ $configurationKey ] : [],
                $configurationKey
            );
            
            ksort( $packages[ $configurationKey ] );
            
            file_put_contents(
                base_path( 'package.json' ),
                json_encode( $packages, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT ).PHP_EOL
            );
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
                    $after.PHP_EOL.'        '.$name.',',
                    $authSPFile
                ) );
            }
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
            if ( ! Str::contains( $appConfig = file_get_contents( config_path( 'app.php' ) ), 'App\\Providers\\'.$name.'::class' ) ) {
                file_put_contents( config_path( 'app.php' ), str_replace(
                    'App\\Providers\\'.$after.'::class,',
                    'App\\Providers\\'.$after.'::class,'.PHP_EOL.'        App\\Providers\\'.$name.'::class,',
                    $appConfig
                ) );
            }
        }
        
        /**
         *
         * Get the route definition(s) that should be installed for Livewire.
         *
         * @return string
         */
        protected function livewireRouteDefinition()
        {
            return <<<'EOF'

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


EOF;
        }
        
        /**
         * Returns the path to the correct test stubs.
         *
         * @return string
         */
        protected function getTestStubsPath()
        {
            return $this->option( 'pest' )
                ? __DIR__.' /../../stubs/pest - tests'
                : __DIR__.' /../../stubs/tests';
        }
        
        /**
         * Install the Livewire team stack into the application.
         *
         * @return void
         */
        protected function installLivewireTeamStack()
        {
            // Directories...
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'views/teams' ) );
            
            // Other Views...
            ( new Filesystem )->copyDirectory( __DIR__.' /../../stubs/livewire/resources/views/teams', resource_path( 'views/teams' ) );
            
            // Tests...
            $stubs = $this->getTestStubsPath();
            
            copy( $stubs.'/livewire/CreateTeamTest.php', base_path( 'tests/Feature/CreateTeamTest.php' ) );
            copy( $stubs.'/livewire/DeleteTeamTest.php', base_path( 'tests/Feature/DeleteTeamTest.php' ) );
            copy( $stubs.'/livewire/InviteTeamMemberTest.php', base_path( 'tests/Feature/InviteTeamMemberTest.php' ) );
            copy( $stubs.'/livewire/LeaveTeamTest.php', base_path( 'tests/Feature/LeaveTeamTest.php' ) );
            copy( $stubs.'/livewire/RemoveTeamMemberTest.php', base_path( 'tests/Feature/RemoveTeamMemberTest.php' ) );
            copy( $stubs.'/livewire/UpdateTeamMemberRoleTest.php', base_path( 'tests/Feature/UpdateTeamMemberRoleTest.php' ) );
            copy( $stubs.'/livewire/UpdateTeamNameTest.php', base_path( 'tests/Feature/UpdateTeamNameTest.php' ) );
            
            $this->ensureApplicationIsTeamCompatible();
        }
        
        /**
         * Ensure the installed user model is ready for team usage.
         *
         * @return void
         */
        protected function ensureApplicationIsTeamCompatible()
        {
            // Publish Team Migrations...
            $this->callSilent( 'vendor:publish', [ '--tag' => 'jetstream - team - migrations', '--force' => true ] );
            
            // Configuration...
            $this->replaceInFile( '// Features::teams([\'invitations\' => true])', 'Features::teams([\'invitations\' => true])', config_path( 'jetstream.php' ) );
            
            // Directories...
            ( new Filesystem )->ensureDirectoryExists( app_path( 'Actions/Jetstream' ) );
            ( new Filesystem )->ensureDirectoryExists( app_path( 'Events' ) );
            ( new Filesystem )->ensureDirectoryExists( app_path( 'Policies' ) );
            
            // Service Providers...
            copy( __DIR__.'/../../stubs/app/Providers/AuthServiceProvider.php', app_path( 'Providers/AuthServiceProvider.php' ) );
            copy( __DIR__.'/../../stubs/app/Providers/JetstreamWithTeamsServiceProvider.php', app_path( 'Providers/JetstreamServiceProvider.php' ) );
            
            // Models...
            copy( __DIR__.'/../../stubs/app/Models/Membership.php', app_path( 'Models/Membership.php' ) );
            copy( __DIR__.'/../../stubs/app/Models/Team.php', app_path( 'Models/Team.php' ) );
            copy( __DIR__.'/../../stubs/app/Models/TeamInvitation.php', app_path( 'Models/TeamInvitation.php' ) );
            copy( __DIR__.'/../../stubs/app/Models/UserWithTeams.php', app_path( 'Models/User.php' ) );
            
            // Actions...
            copy( __DIR__.'/../../stubs/app/Actions/Jetstream/AddTeamMember.php', app_path( 'Actions/Jetstream/AddTeamMember.php' ) );
            copy( __DIR__.'/../../stubs/app/Actions/Jetstream/CreateTeam.php', app_path( 'Actions/Jetstream/CreateTeam.php' ) );
            copy( __DIR__.'/../../stubs/app/Actions/Jetstream/DeleteTeam.php', app_path( 'Actions/Jetstream/DeleteTeam.php' ) );
            copy( __DIR__.'/../../stubs/app/Actions/Jetstream/DeleteUserWithTeams.php', app_path( 'Actions/Jetstream/DeleteUser.php' ) );
            copy( __DIR__.'/../../stubs/app/Actions/Jetstream/InviteTeamMember.php', app_path( 'Actions/Jetstream/InviteTeamMember.php' ) );
            copy( __DIR__.'/../../stubs/app/Actions/Jetstream/RemoveTeamMember.php', app_path( 'Actions/Jetstream/RemoveTeamMember.php' ) );
            copy( __DIR__.'/../../stubs/app/Actions/Jetstream/UpdateTeamName.php', app_path( 'Actions/Jetstream/UpdateTeamName.php' ) );
            
            copy( __DIR__.'/../../stubs/app/Actions/Fortify/CreateNewUserWithTeams.php', app_path( 'Actions/Fortify/CreateNewUser.php' ) );
            
            // Policies...
            ( new Filesystem )->copyDirectory( __DIR__.'/../../stubs/app/Policies', app_path( 'Policies' ) );
            
            // Factories...
            copy( __DIR__.'/../../database/factories/UserFactory.php', base_path( 'database/factories/UserFactory.php' ) );
            copy( __DIR__.'/../../database/factories/TeamFactory.php', base_path( 'database/factories/TeamFactory.php' ) );
        }
        
        /**
         * Install the Inertia stack into the application.
         *
         * @return void
         */
        protected function installInertiaStack()
        {
            // Install Inertia...
            $this->requireComposerPackages( 'inertiajs/inertia-laravel:^0.5.2', 'tightenco/ziggy:^1.0' );
            
            // Install NPM packages...
            $this->updateNodePackages( function( $packages ) {
                return [
                           '@inertiajs/inertia'      => '^0.11.0',
                           '@inertiajs/inertia-vue3' => '^0.6.0',
                           '@inertiajs/progress'     => '^0.2.7',
                           '@tailwindcss/forms'      => '^0.5.2',
                           '@tailwindcss/typography' => '^0.5.2',
                           '@vitejs/plugin-vue'      => '^3.0.0',
                           'autoprefixer'            => '^10.4.7',
                           'postcss'                 => '^8.4.14',
                           'tailwindcss'             => '^3.1.0',
                           'vue'                     => '^3.2.31',
                       ] + $packages;
            } );
            
            // Sanctum...
            ( new Process( [ $this->phpBinary(), 'artisan', 'vendor:publish', '--provider=Laravel\Sanctum\SanctumServiceProvider', '--force' ], base_path() ) )
                ->setTimeout( null )
                ->run( function( $type, $output ) {
                    $this->output->write( $output );
                } );
            
            // Tailwind Configuration...
            copy( __DIR__.'/../../stubs/inertia/tailwind.config.js', base_path( 'tailwind.config.js' ) );
            copy( __DIR__.'/../../stubs/inertia/postcss.config.js', base_path( 'postcss.config.js' ) );
            copy( __DIR__.'/../../stubs/inertia/vite.config.js', base_path( 'vite.config.js' ) );
            
            // Directories...
            ( new Filesystem )->ensureDirectoryExists( app_path( 'Actions/Fortify' ) );
            ( new Filesystem )->ensureDirectoryExists( app_path( 'Actions/Jetstream' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'css' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'js/Jetstream' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'js/Layouts' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'js/Pages' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'js/Pages/API' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'js/Pages/Auth' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'js/Pages/Profile' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'views' ) );
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'markdown' ) );
            
            ( new Filesystem )->deleteDirectory( resource_path( 'sass' ) );
            
            // Terms Of Service/Privacy Policy...
            copy( __DIR__.'/../../stubs/resources/markdown/terms.md', resource_path( 'markdown/terms.md' ) );
            copy( __DIR__.'/../../stubs/resources/markdown/policy.md', resource_path( 'markdown/policy.md' ) );
            
            // Service Providers...
            copy( __DIR__.'/../../stubs/app/Providers/JetstreamServiceProvider.php', app_path( 'Providers/JetstreamServiceProvider.php' ) );
            
            $this->installServiceProviderAfter( 'FortifyServiceProvider', 'JetstreamServiceProvider' );
            
            // Middleware...
            ( new Process( [ $this->phpBinary(), 'artisan', 'inertia:middleware', 'HandleInertiaRequests', '--force' ], base_path() ) )
                ->setTimeout( null )
                ->run( function( $type, $output ) {
                    $this->output->write( $output );
                } );
            
            $this->installMiddlewareAfter( 'SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class' );
            
            // Models...
            copy( __DIR__.'/../../stubs/app/Models/User.php', app_path( 'Models/User.php' ) );
            
            // Factories...
            copy( __DIR__.'/../../database/factories/UserFactory.php', base_path( 'database/factories/UserFactory.php' ) );
            
            // Actions...
            copy( __DIR__.'/../../stubs/app/Actions/Fortify/CreateNewUser.php', app_path( 'Actions/Fortify/CreateNewUser.php' ) );
            copy( __DIR__.'/../../stubs/app/Actions/Fortify/UpdateUserProfileInformation.php', app_path( 'Actions/Fortify/UpdateUserProfileInformation.php' ) );
            copy( __DIR__.'/../../stubs/app/Actions/Jetstream/DeleteUser.php', app_path( 'Actions/Jetstream/DeleteUser.php' ) );
            
            // Blade Views...
            copy( __DIR__.'/../../stubs/inertia/resources/views/app.blade.php', resource_path( 'views/app.blade.php' ) );
            
            if ( file_exists( resource_path( 'views/welcome.blade.php' ) ) ) {
                unlink( resource_path( 'views/welcome.blade.php' ) );
            }
            
            // Inertia Pages...
            copy( __DIR__.'/../../stubs/inertia/resources/js/Pages/Dashboard.vue', resource_path( 'js/Pages/Dashboard.vue' ) );
            copy( __DIR__.'/../../stubs/inertia/resources/js/Pages/PrivacyPolicy.vue', resource_path( 'js/Pages/PrivacyPolicy.vue' ) );
            copy( __DIR__.'/../../stubs/inertia/resources/js/Pages/TermsOfService.vue', resource_path( 'js/Pages/TermsOfService.vue' ) );
            copy( __DIR__.'/../../stubs/inertia/resources/js/Pages/Welcome.vue', resource_path( 'js/Pages/Welcome.vue' ) );
            
            ( new Filesystem )->copyDirectory( __DIR__.'/../../stubs/inertia/resources/js/Jetstream', resource_path( 'js/Jetstream' ) );
            ( new Filesystem )->copyDirectory( __DIR__.'/../../stubs/inertia/resources/js/Layouts', resource_path( 'js/Layouts' ) );
            ( new Filesystem )->copyDirectory( __DIR__.'/../../stubs/inertia/resources/js/Pages/API', resource_path( 'js/Pages/API' ) );
            ( new Filesystem )->copyDirectory( __DIR__.'/../../stubs/inertia/resources/js/Pages/Auth', resource_path( 'js/Pages/Auth' ) );
            ( new Filesystem )->copyDirectory( __DIR__.'/../../stubs/inertia/resources/js/Pages/Profile', resource_path( 'js/Pages/Profile' ) );
            
            // Routes...
            $this->replaceInFile( 'auth:api', 'auth:sanctum', base_path( 'routes/api.php' ) );
            
            copy( __DIR__.'/../../stubs/inertia/routes/web.php', base_path( 'routes/web.php' ) );
            
            // Assets...
            copy( __DIR__.'/../../stubs/resources/css/app.css', resource_path( 'css/app.css' ) );
            copy( __DIR__.'/../../stubs/inertia/resources/js/app.js', resource_path( 'js/app.js' ) );
            
            // Flush node_modules...
            // static::flushNodeModules();
            
            // Tests...
            $stubs = $this->getTestStubsPath();
            
            copy( $stubs.'/inertia/ApiTokenPermissionsTest.php', base_path( 'tests/Feature/ApiTokenPermissionsTest.php' ) );
            copy( $stubs.'/inertia/BrowserSessionsTest.php', base_path( 'tests/Feature/BrowserSessionsTest.php' ) );
            copy( $stubs.'/inertia/CreateApiTokenTest.php', base_path( 'tests/Feature/CreateApiTokenTest.php' ) );
            copy( $stubs.'/inertia/DeleteAccountTest.php', base_path( 'tests/Feature/DeleteAccountTest.php' ) );
            copy( $stubs.'/inertia/DeleteApiTokenTest.php', base_path( 'tests/Feature/DeleteApiTokenTest.php' ) );
            copy( $stubs.'/inertia/ProfileInformationTest.php', base_path( 'tests/Feature/ProfileInformationTest.php' ) );
            copy( $stubs.'/inertia/TwoFactorAuthenticationSettingsTest.php', base_path( 'tests/Feature/TwoFactorAuthenticationSettingsTest.php' ) );
            copy( $stubs.'/inertia/UpdatePasswordTest.php', base_path( 'tests/Feature/UpdatePasswordTest.php' ) );
            
            // Teams...
            if ( $this->option( 'teams' ) ) {
                $this->installInertiaTeamStack();
            }
            
            if ( $this->option( 'ssr' ) ) {
                $this->installInertiaSsrStack();
            }
            
            $this->line( '' );
            $this->info( 'Inertia scaffolding installed successfully.' );
            $this->comment( 'Please execute "npm install && npm run dev" to build your assets.' );
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
                    $after.',',
                    $after.','.PHP_EOL.'            '.$name.',',
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
         * Install the Inertia team stack into the application.
         *
         * @return void
         */
        protected function installInertiaTeamStack()
        {
            // Directories...
            ( new Filesystem )->ensureDirectoryExists( resource_path( 'js/Pages/Profile' ) );
            
            // Pages...
            ( new Filesystem )->copyDirectory( __DIR__.'/../../stubs/inertia/resources/js/Pages/Teams', resource_path( 'js/Pages/Teams' ) );
            
            // Tests...
            $stubs = $this->getTestStubsPath();
            
            copy( $stubs.'/inertia/CreateTeamTest.php', base_path( 'tests/Feature/CreateTeamTest.php' ) );
            copy( $stubs.'/inertia/DeleteTeamTest.php', base_path( 'tests/Feature/DeleteTeamTest.php' ) );
            copy( $stubs.'/inertia/InviteTeamMemberTest.php', base_path( 'tests/Feature/InviteTeamMemberTest.php' ) );
            copy( $stubs.'/inertia/LeaveTeamTest.php', base_path( 'tests/Feature/LeaveTeamTest.php' ) );
            copy( $stubs.'/inertia/RemoveTeamMemberTest.php', base_path( 'tests/Feature/RemoveTeamMemberTest.php' ) );
            copy( $stubs.'/inertia/UpdateTeamMemberRoleTest.php', base_path( 'tests/Feature/UpdateTeamMemberRoleTest.php' ) );
            copy( $stubs.'/inertia/UpdateTeamNameTest.php', base_path( 'tests/Feature/UpdateTeamNameTest.php' ) );
            
            $this->ensureApplicationIsTeamCompatible();
        }
        
        /**
         * Install the Inertia SSR stack into the application.
         *
         * @return void
         */
        protected function installInertiaSsrStack()
        {
            $this->updateNodePackages( function( $packages ) {
                return [
                           '@inertiajs/server'    => '^0.1.0',
                           '@vue/server-renderer' => '^3.2.31',
                       ] + $packages;
            } );
            
            copy( __DIR__.'/../../stubs/inertia/resources/js/ssr.js', resource_path( 'js/ssr.js' ) );
            $this->replaceInFile( "input: 'resources/js/app.js',", "input: 'resources/js/app.js',".PHP_EOL."            ssr: 'resources/js/ssr.js',",
                                  base_path( 'vite.config.js' ) );
            $this->replaceInFile( '});', '    ssr: {'.PHP_EOL."        noExternal: ['@inertiajs/server'],".PHP_EOL.'    },'.PHP_EOL.'});',
                                  base_path( 'vite.config.js' ) );
            
            ( new Process( [ $this->phpBinary(), 'artisan', 'vendor:publish', '--provider=Inertia\ServiceProvider', '--force' ], base_path() ) )
                ->setTimeout( null )
                ->run( function( $type, $output ) {
                    $this->output->write( $output );
                } );
            
            copy( __DIR__.'/../../stubs/inertia/app/Http/Middleware/HandleInertiaRequests.php', app_path( 'Http/Middleware/HandleInertiaRequests.php' ) );
            
            $this->replaceInFile( "'enabled' => false", "'enabled' => true", config_path( 'inertia.php' ) );
            $this->replaceInFile( 'vite build', 'vite build && vite build --ssr', base_path( 'package.json' ) );
            $this->replaceInFile( '/node_modules', '/bootstrap/ssr'.PHP_EOL.'/node_modules', base_path( '.gitignore' ) );
        }
        
        /**
         * Install the given Composer Packages as "dev" dependencies.
         *
         * @param  mixed  $packages
         *
         * @return void
         */
        protected function requireComposerDevPackages( $packages )
        {
            $composer = $this->option( 'composer' );
            
            if ( $composer !== 'global' ) {
                $command = [ $this->phpBinary(), $composer, 'require', '--dev' ];
            }
            
            $command = array_merge(
                $command ?? [ 'composer', 'require', '--dev' ],
                is_array( $packages ) ? $packages : func_get_args()
            );
            
            ( new Process( $command, base_path(), [ 'COMPOSER_MEMORY_LIMIT' => '-1' ] ) )
                ->setTimeout( null )
                ->run( function( $type, $output ) {
                    $this->output->write( $output );
                } );
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
