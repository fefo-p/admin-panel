<?php

    namespace FefoP\AdminPanel\Permissions\Livewire;

    use FefoP\AdminPanel\Models\Role;
    use FefoP\AdminPanel\Models\Permission;
    use Rappasoft\LaravelLivewireTables\Views\Column;
    use Rappasoft\LaravelLivewireTables\DataTableComponent;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    
    class PermissionTable extends DataTableComponent
    {
        use AuthorizesRequests;
        
        public string $tableName    = 'permissions';
        public array  $permissions  = [];
        public        $columnSearch = [
            'name'       => null,
            'guard_name' => null,
        ];
        protected     $model        = Permission::class;
        protected     $debug        = false;
        protected     $listeners    = [ 'refreshComponent' => '$refresh' ];

        public function mount()
        {
            $this->authorize('viewAny', Permission::class);

            if ( request()->get( 'debug' ) ) {
                $this->debug = request()->get( 'debug' );
            }
        }

        public function configure(): void
        {
            $this->setDebugStatus( $this->debug ?? config( 'app.debug' ) )
                 ->setPrimaryKey( 'id' )
                 ->setSingleSortingDisabled()
                 ->setFilterLayoutSlideDown()
                 ->setEagerLoadAllRelationsStatus( true );
        }

        public function columns(): array
        {
            return [
                Column::make( 'Nombre', 'name' )
                      ->searchable(),
                Column::make( 'Guard', 'guard_name' )
                      ->searchable(),
                Column::make( 'Acciones', 'id' )
                      ->collapseOnTablet()
                      ->view( 'adminpanel::livewire-tables.cells.permission-actions' ),
            ];
        }
    }
