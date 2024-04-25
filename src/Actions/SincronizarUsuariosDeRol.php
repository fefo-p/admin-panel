<?php

    namespace FefoP\AdminPanel\Actions;

    use App\Models\User;
    use Spatie\Permission\PermissionRegistrar;

    class SincronizarUsuariosDeRol
    {
        protected $accion = 'Actualización';

        public function __invoke( $selected_users, $rol ): bool|string
        {
            //$originales = $rol->users->pluck( 'id' );

            $originales = User::whereHas("roles", function ($query) use ($rol) {
                return $query->where("id", $rol);
            })->pluck( 'id' );

            //dump($originales);

            $finales = collect(array_keys( $selected_users ));
            //dump($finales);

            if ( $originales == $finales ) {
                return false;
            }

            // Borrar
            //$a_borrar = $originales->diff( $finales );

            // Agregar
            //$a_agregar = $finales->diff( $originales );

            //dump($a_borrar);
            //dd($a_agregar);

            $rol->syncUsers( $finales );
            app()->make( PermissionRegistrar::class)->forgetCachedPermissions();

            //if ( $a_borrar->isEmpty() ) {
            //    $this->accion = 'Agregado';
            //}

            //if ( $a_agregar->isEmpty() ) {
            //    $this->accion = 'Borrado';
            //}

            $this->accion = 'Sincronizados';

            return $this->accion . ' de usuarios en el rol ' . $rol->name;
        }
    }
