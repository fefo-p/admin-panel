<?php
    
    namespace FefoP\AdminPanel\Users\Livewire;
    
    use App\Models\User;
    use FefoP\AdminPanel\Models\Role;
    use Illuminate\Database\Eloquent\Builder;
    use Rappasoft\LaravelLivewireTables\Views\Column;
    use Illuminate\Auth\Access\AuthorizationException;
    use Rappasoft\LaravelLivewireTables\DataTableComponent;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
    use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
    use Rappasoft\LaravelLivewireTables\Views\Filters\DateTimeFilter;
    use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
    
    class UserTable extends DataTableComponent
    {
        use AuthorizesRequests;
        
        public string $tableName    = 'users';
        public array  $users        = [];
        public        $columnSearch = [
            'name'  => null,
            'email' => null,
        ];
        protected     $model        = User::class;
        protected     $debug        = false;
        protected     $listeners    = [ 'refreshComponent' => '$refresh' ];
        
        public function mount()
        {
            // $this->authorize('viewAny', User::class);
            if ( auth()->user()?->cannot( 'ver usuarios', 'App\Models\User' ) ) {
                throw new AuthorizationException( 'No tiene permiso para ver listado de usuarios' );
            }
            
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
        
        public function builder(): Builder
        {
            return User::query()->withTrashed(); // Select some things
        }
        
        public function columns(): array
        {
            return [
                Column::make( 'ID', 'id' )
                      ->hideIf( true ),
                Column::make( 'Nombre', 'name' )
                      ->searchable(),
                Column::make( 'Email' )
                      ->searchable()
                      ->collapseOnTablet(),
                BooleanColumn::make( 'Verificado', 'email_verified_at' )
                             ->sortable(),
                Column::make( 'Roles' )
                      ->collapseOnTablet()
                      ->label( fn( $row ) => $row->roles->pluck( 'name' )->implode( ', ' ) ),
                Column::make( 'Acciones', 'deleted_at' )
                      ->collapseOnTablet()
                      ->view( 'adminpanel::livewire-tables.cells.user-actions' )->html(),
            ];
        }
        
        public function filters(): array
        {
            return [
                MultiSelectFilter::make( 'Roles' )
                                 ->options(
                                     Role::query()
                                         ->orderBy( 'name' )
                                         ->get()
                                         ->keyBy( 'id' )
                                         ->map( fn( $role ) => $role->name )
                                         ->toArray() )
                                 ->filter( function( Builder $builder, array $values ) {
                                     $builder->whereHas( 'roles', fn( $query ) => $query->whereIn( 'roles.id', $values ) );
                                 } )
                                 ->setFilterPillValues( [
                                                            '1' => 'Administrador',
                                                            '2' => 'Otro Rol',
                                                        ] ),
                SelectFilter::make( 'E-mail Verificado', 'email_verified_at' )
                            ->setFilterPillTitle( 'Verificado' )
                            ->options( [
                                           ''    => 'Todos',
                                           'yes' => 'Si',
                                           'no'  => 'No',
                                       ] )
                            ->filter( function( Builder $builder, string $value ) {
                                if ( $value === 'yes' ) {
                                    $builder->whereNotNull( 'email_verified_at' );
                                }
                                elseif ( $value === 'no' ) {
                                    $builder->whereNull( 'email_verified_at' );
                                }
                            } ),
                DateTimeFilter::make( 'Verificado desde' )
                              ->filter( function( Builder $builder, string $value ) {
                                  $builder->where( 'email_verified_at', '>=', $value );
                              } ),
                DateTimeFilter::make( 'Verificado hasta' )
                              ->filter( function( Builder $builder, string $value ) {
                                  $builder->where( 'email_verified_at', '<=', $value );
                              } ),
            ];
        }
    }
