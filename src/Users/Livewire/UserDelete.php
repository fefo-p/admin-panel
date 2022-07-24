<?php

    namespace FefoP\AdminPanel\Users\Livewire;

    use App\Models\User;
    use LivewireUI\Modal\ModalComponent;

    class UserDelete extends ModalComponent
    {
        public        $user;
        public int    $user_id;
        public string $action;
        public        $mensaje_error;

        public function mount( int $user_id, string $action )
        {
            auth()->user()->can('borrar usuarios');
            
            $this->user   = User::withTrashed()->find( $user_id );
            $this->action = $action;
        }

        public function confirmar()
        {
            switch ( $this->action ) {
                case 'unban':
                    // TODO: Limpiar email_verified_at???
                    $this->user->restore();
                    break;
                default:
                    $this->user->delete();
                    break;
            }

            $this->emitTo( 'adminpanel::user-table', 'refreshComponent' );
            $this->closeModal();
        }

        public function cancel()
        {
            $this->emitTo( 'adminpanel::user-table', 'refreshComponent' );
            $this->closeModal();
        }

        public function render()
        {
            return view( 'adminpanel::users.livewire.user-delete' );
        }
    }
