<?php

    namespace FefoP\AdminPanel\Users\Livewire;

    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Database\Eloquent\Builder;
    use Rappasoft\LaravelLivewireTables\Views\Column;
    use Illuminate\Auth\Access\AuthorizationException;
    use Rappasoft\LaravelLivewireTables\DataTableComponent;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

    class UserTable extends DataTableComponent
    {
        use AuthorizesRequests;

        public string  $tableName = 'users';
        public ?string $pageName  = 'page';
        public         $users;
        //protected $model     = User::class;
        protected $debug     = false;
        protected $listeners = [ 'refreshComponent' => '$refresh' ];

        public function mount()
        {
            // $this->authorize('viewAny', User::class);
            if ( Auth::user()?->cannot('ver usuarios', 'App\Models\User') ) {
                throw new AuthorizationException('No tiene permiso para ver listado de usuarios');
            }
        }

        public function configure(): void
        {
            $this->setDebugStatus($this->debug ?? config('app.debug'))
                 ->setPrimaryKey('id')
                 ->setPaginationEnabled()
                 ->setPaginationVisibilityEnabled()
                 ->setPerPageAccepted([ 10, 25, 50, 100 ])
                 ->setSingleSortingDisabled()
                 ->setSearchDisabled()
                 ->setEagerLoadAllRelationsStatus(true);
        }

        public function builder(): Builder
        {
            $query = User::query()->with('roles')->withTrashed();

            if ( config('adminpanel.users.external') ) {
                return $query->where(config('adminpanel.users.is_external_column'), false);
            }

            return $query;
        }

        public function columns(): array
        {
            return [
                Column::make('ID', 'id')->hideIf(true),
                Column::make('Nombre', 'name'),
                /*->searchable(fn(Builder $query, $searchTerm) => $query->orWhere('name', 'LIKE',
                                                                                "%{$searchTerm}%")),*/
                Column::make('Email')
                    /*->searchable(fn(Builder $query, $searchTerm) => $query->orWhere('email', 'LIKE',
                                                                                    "%{$searchTerm}%"))*/
                      ->collapseOnTablet(),
                BooleanColumn::make('Verificado', 'email_verified_at')
                             ->sortable(),
                Column::make('Roles')
                      ->collapseOnTablet()
                      ->label(fn($row) => $row->roles->pluck('name')->implode(', ')),
                Column::make('Acciones', 'deleted_at')
                      ->collapseOnTablet()
                      ->view('adminpanel::livewire-tables.cells.user-actions')->html(),
            ];
        }

        /*public function filters(): array
        {
            return [
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
        }*/

        //        public function updatedPage()
        //        {
        //            return redirect('/adminpanel/users?page='.$this->page);
        //        }
    }
