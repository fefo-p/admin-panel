<?php

    namespace FefoP\AdminPanel\Roles\Livewire;

    use FefoP\AdminPanel\Models\Role;
    use LivewireUI\Modal\ModalComponent;

    class RoleCreate extends ModalComponent
    {
        public        $name;
        public string $guard_name;

        public function mount()
        {
            auth()->user()->can('crear roles');
    
            $this->guard_name = config( 'adminpanel.guard' );
        }

        public function crear()
        {
            $role = Role::create( $this->validar() );

            // @TODO: Log action to config('adminpanel.table')

            $this->emitTo( 'adminpanel::role-table', 'refreshComponent' );
            $this->closeModal();
        }

        protected function validar(): array
        {
            return $this->validate( [
                                        'name'       => [ 'required', 'string', 'min:3', 'unique:roles,name' ],
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
            $this->emitTo( 'adminpanel::role-table', 'refreshComponent' );
            $this->closeModal();
        }

        public function render()
        {
            return view( 'adminpanel::roles.livewire.role-create' );
        }
    }
