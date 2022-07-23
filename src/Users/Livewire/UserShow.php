<?php

    namespace FefoP\AdminPanel\Users\Livewire;

    use App\Models\User;
    use LivewireUI\Modal\ModalComponent;

    class UserShow extends ModalComponent
    {
        public     $user;
        public int $user_id;

        public function mount( int $user_id )
        {
            $this->user = User::withTrashed()->find( $user_id );
        }

        public function render()
        {
            return view( 'adminpanel::users.livewire.user-show' );
        }
    }
