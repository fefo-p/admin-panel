<?php

    namespace FefoP\AdminPanel;

    use Livewire\Livewire;
    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\ServiceProvider;
    use Illuminate\View\Compilers\BladeCompiler;
    use Illuminate\Foundation\Console\AboutCommand;
    use Illuminate\Filesystem\Filesystem;
    use Illuminate\Support\Collection;

    class AdminPanelServiceProvider extends ServiceProvider
    {
        public function boot(): void
        {
            // $this->loadMigrationsFrom( __DIR__ . '/../database/migrations' );
            $this->loadTranslationsFrom( __DIR__ . '/../lang', 'adminpanel' );
            $this->loadViewsFrom( __DIR__ . '/../resources/views', 'adminpanel' );

            $this->configureComponents();
            $this->registerLayoutView();
            $this->configurePublishing();
            $this->configureRoutes();
            $this->registerLivewireComponents();

            $this->configureCommands();
        }

        /**
         * Configure the Blade components.
         *
         * @return void
         */
        protected function configureComponents(): void
        {
            $this->callAfterResolving( BladeCompiler::class, function() {
                foreach ( config( 'adminpanel.blade-components' ) as $component ) {
                    $this->registerComponent( $component );
                }
            } );
        }

        /**
         * Register the layout view template.
         *
         * @return void
         */
        protected function registerLayoutView(): void
        {
            Blade::component( config( 'adminpanel.alias' ) . '::layouts.adminpanel', 'ap-adminpanel-layout' );
        }

        /**
         * Configure publishing for the package.
         *
         * @return void
         */
        protected function configurePublishing(): void
        {
            if ( ! $this->app->runningInConsole() ) {
                return;
            }

            $this->publishes( [
                                  __DIR__ . '/../config/adminpanel.php' => config_path( 'adminpanel.php' ),
                              ], [ 'adminpanel', 'adminpanel-config' ] );

            $this->publishes( [
                                  __DIR__ . '/../routes/adminpanel.php' => base_path( 'routes/adminpanel.php' ),
                              ], [ 'adminpanel', 'adminpanel-routes' ] );

            // $this->publishes( [
            //                       __DIR__ . '/../database/migrations' => database_path( 'migrations' ),
            //                   ], [ 'adminpanel', 'adminpanel-migrations' ] );

            $this->publishes([
                                __DIR__.'/../database/migrations/add_deleted_at_field_to_users_table.php' => $this->getMigrationFileName('add_deleted_at_field_to_users_table.php'),
                            ], [ 'adminpanel', 'adminpanel-migrations' ]);

            $this->publishes( [
                                  __DIR__ . '/../resources/views' => resource_path( 'views/vendor/adminpanel' ),
                              ], [ 'adminpanel', 'adminpanel-views' ] );

            $this->publishes( [
                                  __DIR__ . '/../resources/views/livewire-ui-modal/modal.blade.php' => resource_path( 'views/vendor/livewire-ui-modal/modal.blade.php' ),
                              ], [ 'adminpanel', 'adminpanel-modal-views' ] );

            $this->publishes( [
                                  __DIR__ . '/../resources/views/livewire-tables/components' => resource_path( 'views/vendor/livewire-tables/components' ),
                              ], [ 'adminpanel', 'adminpanel-livewire-tables' ] );

            $this->publishes( [
                                  __DIR__ . '/../lang' => $this->app->langPath( 'vendor/adminpanel' ),
                              ], [ 'adminpanel', 'adminpanel-lang' ] );

            $this->publishes( [
                                  __DIR__ . '/../resources/images' => public_path( 'images' ),
                              ], [ 'adminpanel', 'adminpanel-images' ] );
        }

        /**
         * Configure the routes offered by the application.
         *
         * @return void
         */
        protected function configureRoutes(): void
        {
            $this->loadRoutesFrom( __DIR__ . '/../routes/adminpanel.php' );
        }

        protected function registerLivewireComponents(): void
        {
            foreach ( config( 'adminpanel.livewire-components' ) as $name => $component_class ) {
                Livewire::component( config( 'adminpanel.alias' ) . "::$name", $component_class );
            }
        }

        /**
         * Configure the commands offered by the application.
         *
         * @return void
         */
        protected function configureCommands(): void
        {
            if ( ! $this->app->runningInConsole() ) {
                return;
            }

            AboutCommand::add( 'Admin Panel', [
                'Version' => fn() => config( 'adminpanel.version' ),
                'Driver'  => fn() => config( 'adminpanel.driver' ),
            ] );

            $this->commands([ Console\InstallCommand::class ]);
        }

        /**
         * Register the given component.
         *
         * @param  string  $component
         *
         * @return void
         */
        protected function registerComponent( string $component ): void
        {
            Blade::component( config( 'adminpanel.alias' ) . '::components.' . $component, 'ap-' . $component );
        }

        public function register(): void
        {
            $this->mergeConfigFrom( __DIR__ . '/../config/adminpanel.php', 'adminpanel' );

            $this->app->singleton( AdminPanel::class );
            $this->app->alias( AdminPanel::class, config( 'adminpanel.alias' ) );
        }


        /**
         * Returns existing migration file if found, else uses the current timestamp.
         *
         * @return string
         */
        protected function getMigrationFileName($migrationFileName): string
        {
            $timestamp = date('Y_m_d_His');

            $filesystem = $this->app->make(Filesystem::class);

            return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
                ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                    return $filesystem->glob($path.'*_'.$migrationFileName);
                })
                ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
                ->first();
        }
    }
