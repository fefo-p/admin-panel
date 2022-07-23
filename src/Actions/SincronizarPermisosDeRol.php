<?php

    namespace FefoP\AdminPanel\Actions;

    use Spatie\Permission\PermissionRegistrar;

    class SincronizarPermisosDeRol
    {
        protected $accion = 'Actualización';

        public function __invoke( $selected_permissions, $rol ): bool|string
        {
            $originales = $rol->permissions->pluck( 'id' );
            $finales = collect(array_keys( $selected_permissions ));

            if ( $originales == $finales ) {
                return false;
            }

            // Borrar
            $a_borrar = $originales->diff( $finales );

            // Agregar
            $a_agregar = $finales->diff( $originales );

            $rol->syncPermissions( $finales );
            app()->make( PermissionRegistrar::class )->forgetCachedPermissions();

            if ( $a_borrar->isEmpty() ) {
                $this->accion = 'Agregado';
            }

            if ( $a_agregar->isEmpty() ) {
                $this->accion = 'Borrado';
            }

            return $this->accion . ' de permisos en el rol ' . $rol->name;
        }
    }
