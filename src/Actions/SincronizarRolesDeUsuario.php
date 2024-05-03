<?php

    namespace FefoP\AdminPanel\Actions;

    use App\Models\User;
    use Spatie\Permission\PermissionRegistrar;

    class SincronizarRolesDeUsuario
    {
        protected $accion = 'Actualización';

        public function __invoke( $selected_roles, $user ): bool|string|array
        {
            if (is_int($user)) {
                $user = User::find($user);
            }

            $originales = $user->roles->pluck( 'id' );
            $finales = collect(array_values( $selected_roles->toArray() ));

            if ( $originales == $finales ) {
                return false;
            }

            // Borrar
            $a_borrar = $originales->diff( $finales );

            // Agregar
            $a_agregar = $finales->diff( $originales )->map( function ( $a ) {
                return (int) $a;
            });

            //dump($a_borrar);
            //dd($a_agregar);

            $user->syncRoles( $finales );
//            $rol->syncUsers( $finales );
            app()->make( PermissionRegistrar::class)->forgetCachedPermissions();

            if ( $a_borrar->isEmpty() ) {
                $this->accion = 'Agregado';
            }

            if ( $a_agregar->isEmpty() ) {
                $this->accion = 'Borrado';
            }

            $this->accion = 'Sincronizados';

            return [
                $this->accion.' de roles de usuario ' . $user->cuil, array_values($a_borrar->toArray()),
                array_values($a_agregar->toArray()),
            ];
        }
    }
