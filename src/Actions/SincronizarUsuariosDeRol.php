<?php

    namespace FefoP\AdminPanel\Actions;

    use Spatie\Permission\PermissionRegistrar;

    class SincronizarUsuariosDeRol
    {
        protected $accion = 'Actualización';

        public function __invoke( $selected_users, $rol ): bool|string
        {
            $originales = $rol->users->pluck( 'id' );
            $finales = collect(array_keys( $selected_users ));

            if ( $originales == $finales ) {
                return false;
            }

            // Borrar
            $a_borrar = $originales->diff( $finales );

            // Agregar
            $a_agregar = $finales->diff( $originales );

            $rol->syncUsers( $finales );
            app()->make( PermissionRegistrar::class)->forgetCachedPermissions();

            if ( $a_borrar->isEmpty() ) {
                $this->accion = 'Agregado';
            }

            if ( $a_agregar->isEmpty() ) {
                $this->accion = 'Borrado';
            }

            return $this->accion . ' de usuarios en el rol ' . $rol->name;
        }
    }
