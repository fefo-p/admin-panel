<?php

    namespace FefoP\AdminPanel\Actions;

    use FefoP\AdminPanel\Models\Role;
    use FefoP\AdminPanel\Models\Permission;
    use Spatie\Permission\PermissionRegistrar;

    class SincronizarPermisosDeAdministrador
    {
        public function __invoke(): void
        {
            $rol = Role::where('name', config('adminpanel.admin.role_name'))->first();
            $permisos = Permission::where('guard_name', 'web')->pluck('id');
            $rol->syncPermissions($permisos);

            app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
