<?php

    namespace FefoP\AdminPanel\Permissions\Livewire;

    use LivewireUI\Modal\ModalComponent;
    use FefoP\AdminPanel\Models\Permission;

    class PermissionShow extends ModalComponent
    {
        public     $permission;
        public int $permission_id;

        public function mount( int $permission_id )
        {
            $this->permission = Permission::find( $permission_id );
        }

        public function render()
        {
            return view( 'adminpanel::permissions.livewire.permission-show' );
        }
    }
