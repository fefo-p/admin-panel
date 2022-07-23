<?php

    namespace FefoP\AdminPanel\Users\Livewire;

    use App\Models\User;
    use LivewireUI\Modal\ModalComponent;

    class UserEdit extends ModalComponent
    {
        public User   $user;
        public string $name;
        public string $email;
        public        $cuil;
        public int    $user_id;

        public function mount( int $user_id )
        {
            $this->user  = User::withTrashed()->find( $user_id );
            $this->name  = $this->user->name;
            $this->email = $this->user->email;
            $this->cuil  = $this->user->cuil;
        }

        public function actualizar()
        {
            $this->user->update( $this->validar() );

            $this->emitTo( 'adminpanel::user-table', 'refreshComponent' );
            $this->closeModal();
        }

        protected function validar(): array
        {
            return $this->validate( [
                                        'name'  => [ 'sometimes', 'required', 'string', 'max:255' ],
                                        'email' => [ 'sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id ],
                                        'cuil'  => [ 'sometimes', 'required', 'numeric' ],
                                    ] );
        }

        public function cancelar()
        {
            $this->emitTo( 'adminpanel::user-table', 'refreshComponent' );
            $this->closeModal();
        }

        public function render()
        {
            return view( 'adminpanel::users.livewire.user-edit' );
        }
    }
