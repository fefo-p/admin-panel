<?php

    namespace FefoP\AdminPanel\Roles\Livewire;

    use FefoP\AdminPanel\Models\Role;
    use LivewireUI\Modal\ModalComponent;

    class RoleShow extends ModalComponent
    {
        public     $role;
        public int $role_id;

        public function mount( int $role_id )
        {
            auth()->user()->can('ver roles');
    
            $this->role       = Role::find( $role_id );
        }

        public function render()
        {
            return view( 'adminpanel::roles.livewire.role-show' );
        }
    }
