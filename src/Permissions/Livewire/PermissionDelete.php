<?php

    namespace FefoP\AdminPanel\Permissions\Livewire;

    use LivewireUI\Modal\ModalComponent;
    use FefoP\AdminPanel\Models\Permission;

    class PermissionDelete extends ModalComponent
    {
        public     $permission;
        public int $permission_id;
        public     $mensaje_error;

        public function mount( int $permission_id, string $action )
        {
            $this->permission = Permission::find( $permission_id );
        }

        public function confirmar()
        {
            if ( $this->tiene_usuarios() ) {
                $this->mensaje_error = 'Este permiso contiene usuarios, no se puede borrar';

                return false;
            }

            if ( $this->tiene_roles() ) {
                $this->mensaje_error = 'Este permiso contiene roles, no se puede borrar';

                return false;
            }

            $this->permission->delete();

            $this->emitTo( 'adminpanel::permission-table', 'refreshComponent' );
            $this->closeModal();
        }

        protected function tiene_usuarios(): bool
        {
            return $this->permission->users->count();
        }

        protected function tiene_roles(): bool
        {
            return $this->permission->roles->count();
        }

        public function cancel()
        {
            $this->emitTo( 'adminpanel::permission-table', 'refreshComponent' );
            $this->closeModal();
        }

        public function render()
        {
            return view( 'adminpanel::permissions.livewire.permission-delete' );
        }
    }
