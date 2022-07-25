<?php
    
    namespace FefoP\AdminPanel\Roles\Livewire;
    
    use FefoP\AdminPanel\Models\Role;
    use Rappasoft\LaravelLivewireTables\Views\Column;
    use Rappasoft\LaravelLivewireTables\DataTableComponent;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    
    class RoleTable extends DataTableComponent
    {
        use AuthorizesRequests;
        
        public string $tableName    = 'roles';
        public ?string $pageName    = 'page';
        public array  $roles        = [];
        public        $columnSearch = [
            'name'       => null,
            'guard_name' => null,
        ];
        protected     $model        = Role::class;
        protected     $debug        = false;
        protected     $listeners    = [ 'refreshComponent' => '$refresh' ];
        
        public function mount()
        {
            $this->authorize('viewAny', Role::class);
            
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
                      ->view( 'adminpanel::livewire-tables.cells.role-actions' ),
            ];
        }
        
        public function updatedPage()
        {
            return redirect('/adminpanel/roles?page='.$this->page);
        }
    }
