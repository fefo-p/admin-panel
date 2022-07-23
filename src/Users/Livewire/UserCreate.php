<?php

    namespace FefoP\AdminPanel\Users\Livewire;

    use LivewireUI\Modal\ModalComponent;
    use App\Actions\Fortify\CreateNewUser;

    class UserCreate extends ModalComponent
    {
        public $name;
        public $email;
        public $cuil;
        public $password;
        public $password_confirmation;

        public function crear()
        {
            $user = ( new CreateNewUser() )->create( [
                                                         'name'                  => $this->name,
                                                         'email'                 => $this->email,
                                                         'cuil'                  => $this->cuil,
                                                         'password'              => $this->password,
                                                         'password_confirmation' => $this->password_confirmation,
                                                     ] );

            $this->emitTo( 'adminpanel::user-table', 'refreshComponent' );
            $this->closeModal();
        }

        public function cancelar()
        {
            $this->emitTo( 'adminpanel::user-table', 'refreshComponent' );
            $this->closeModal();
        }

        public function render()
        {
            return view( 'adminpanel::users.livewire.user-create' );
        }
    }
