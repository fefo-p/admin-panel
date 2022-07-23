<?php

    namespace FefoP\AdminPanel\Permissions\Livewire;

    use LivewireUI\Modal\ModalComponent;
    use FefoP\AdminPanel\Models\Permission;

    class PermissionCreate extends ModalComponent
    {
        public        $name;
        public string $guard_name;

        public function mount()
        {
            $this->guard_name = config( 'adminpanel.guard' );
        }

        public function crear()
        {
            $permission = Permission::create( $this->validar() );

            $this->emitTo( 'adminpanel::permission-table', 'refreshComponent' );
            $this->closeModal();
        }

        protected function validar(): array
        {
            return $this->validate( [
                                        'name'       => [ 'required', 'string', 'min:3', 'unique:permissions,name' ],
                                        'guard_name' => [ 'nullable', 'string' ],
                                    ] );
        }

        public function updatedGuardName()
        {
            if ( empty( $this->guard_name ) ) {
                $this->guard_name = config( 'adminpanel.guard' );
            }
        }

        public function cancelar()
        {
            $this->emitTo( 'adminpanel::permission-table', 'refreshComponent' );
            $this->closeModal();
        }

        public function render()
        {
            return view( 'adminpanel::permissions.livewire.permission-create' );
        }
    }
