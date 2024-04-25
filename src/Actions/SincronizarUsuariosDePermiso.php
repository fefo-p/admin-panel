<?php

    namespace FefoP\AdminPanel\Actions;

    use App\Models\User;
    use Spatie\Permission\PermissionRegistrar;

    class SincronizarUsuariosDePermiso
    {
        protected $accion = 'Actualización';

        public function __invoke( $selected_users, $permiso ): bool|string
        {
            //$originales = $permiso->users->pluck( 'id' );

            $originales = User::whereHas("permissions", function ($query) use ($permiso) {
                return $query->where("id", $permiso);
            })->pluck( 'id' );

            $finales = collect(array_keys( $selected_users ));

            if ( $originales == $finales ) {
                return false;
            }

            // Borrar
            $a_borrar = $originales->diff( $finales );

            // Agregar
            $a_agregar = $finales->diff( $originales );

            $permiso->syncUsers( $finales );
            app()->make( PermissionRegistrar::class)->forgetCachedPermissions();

            if ( $a_borrar->isEmpty() ) {
                $this->accion = 'Agregado';
            }

            if ( $a_agregar->isEmpty() ) {
                $this->accion = 'Borrado';
            }

            return $this->accion . ' de usuarios con el permiso ' . $permiso->name;
        }
    }
