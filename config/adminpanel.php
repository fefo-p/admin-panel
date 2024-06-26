<?php

    return [
        /**
         * Component version
         */
        'version'          => '1.0.9',
        'version_comments' => [
            'Primer versión completamente funcional',
        ],

        'users' => [
            'external'           => env('ADMINPANEL_EXTERNAL_USERS', false),
            'is_external_column' => env('ADMINPANEL_EXTERNAL_COLUMN', 'externo'),
        ],

        /**
         * Admin Panel's Service Provider alias
         */
        'alias' => 'adminpanel',

        /**
         * Admin Panel's administrator role name
         */
        'admin' => [
            'role_name' => env('ADMINPANEL_ADMIN_ROLE_NAME', 'administrador'),
        ],

        /**
         * Admin Panel's Service Provider alias
         */
        'guard'               => env('ADMIN_PANEL_DEFAULT_GUARD', 'web'),

        /**
         * Database table where audit data will be stored
         */
        'database_connection' => env('ADMINPANEL_DATABASE_CONNECTION', 'mysql'),
        'table'               => env('ADMINPANEL_TABLE', 'auditoria_adminpanel'),

        'log'                 => [
            'separator' => env('ADMINPANEL_LOG_SEPARATOR', '|'),
        ],

        /**
         * Livewire components that should be registered
         */
        'livewire-components' => [
            // User related
            'user-table'        => FefoP\AdminPanel\Users\Livewire\UserTable::class,
            'user-create'       => FefoP\AdminPanel\Users\Livewire\UserCreate::class,
            'user-show'         => FefoP\AdminPanel\Users\Livewire\UserShow::class,
            'user-edit'         => FefoP\AdminPanel\Users\Livewire\UserEdit::class,
            'user-delete'       => FefoP\AdminPanel\Users\Livewire\UserDelete::class,

            // Role related
            'role-table'        => FefoP\AdminPanel\Roles\Livewire\RoleTable::class,
            'role-create'       => FefoP\AdminPanel\Roles\Livewire\RoleCreate::class,
            'role-show'         => FefoP\AdminPanel\Roles\Livewire\RoleShow::class,
            'role-edit'         => FefoP\AdminPanel\Roles\Livewire\RoleEdit::class,
            'role-delete'       => FefoP\AdminPanel\Roles\Livewire\RoleDelete::class,

            // Permission related
            'permission-table'  => FefoP\AdminPanel\Permissions\Livewire\PermissionTable::class,
            'permission-create' => FefoP\AdminPanel\Permissions\Livewire\PermissionCreate::class,
            'permission-show'   => FefoP\AdminPanel\Permissions\Livewire\PermissionShow::class,
            'permission-edit'   => FefoP\AdminPanel\Permissions\Livewire\PermissionEdit::class,
            'permission-delete' => FefoP\AdminPanel\Permissions\Livewire\PermissionDelete::class,
        ],

        'blade-components' => [
            'banner',
            'button',
            'content',
            'confirmation',
            'danger-button',
            'delete-confirmation',
            'dropdown-link',
            'secondary-button',
            'section-title',
            'section-description',
            'section-action',
            'separador',
        ],

        /**
         * Dummy values for testing
         */
        'key'              => env('ADMIN_PANEL_KEY', 'some-key'),
        'driver'           => 'Algo por acá',
    ];
