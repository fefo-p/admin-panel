<?php

    namespace FefoP\AdminPanel\Roles\Livewire;

    use FefoP\AdminPanel\Models\Role;
    use LivewireUI\Modal\ModalComponent;

    class RoleDelete extends ModalComponent
    {
        public     $role;
        public int $role_id;
        public     $mensaje_error;

        public function mount( int $role_id, string $action )
        {
            $this->role = Role::find( $role_id );
        }

        public function confirmar()
        {
            if ( $this->tiene_usuarios() ) {
                $this->mensaje_error = 'Este rol contiene usuarios, no se puede borrar';
                return false;
            }

            if ( $this->tiene_permisos() ) {
                $this->mensaje_error = 'Este rol contiene permisos, no se puede borrar';
                return false;
            }

            $this->role->delete();

            $this->emitTo( 'adminpanel::role-table', 'refreshComponent' );
            $this->closeModal();
        }

        protected function tiene_usuarios(): bool
        {
            return $this->role->users->count();
        }

        protected function tiene_permisos(): bool
        {
            return $this->role->permissions->count();
        }

        public function cancel()
        {
            $this->emitTo( 'adminpanel::role-table', 'refreshComponent' );
            $this->closeModal();
        }

        public function render()
        {
            return view( 'adminpanel::roles.livewire.role-delete' );
        }
    }
