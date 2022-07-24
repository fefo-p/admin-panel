<?php

    namespace FefoP\AdminPanel\Permissions\Livewire;

    use LivewireUI\Modal\ModalComponent;
    use FefoP\AdminPanel\Models\Permission;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    
    class PermissionShow extends ModalComponent
    {
        use AuthorizesRequests;
        
        public     $permission;
        public int $permission_id;

        public function mount( int $permission_id )
        {
            $this->permission = Permission::find( $permission_id );
            $this->authorize('view', $this->permission);
        }

        public function render()
        {
            return view( 'adminpanel::permissions.livewire.permission-show' );
        }
    }
