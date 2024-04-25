<?php

    namespace FefoP\AdminPanel\Users\Livewire;

    use Ramsey\Uuid\Uuid;
    use LivewireUI\Modal\ModalComponent;
    use App\Actions\Fortify\CreateNewUser;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    class UserCreate extends ModalComponent
    {
        use AuthorizesRequests;

        public $nombre;
        public $email;
        public $cuil;
        public $password;
        public $password_confirmation;

        public function mount(): void
        {
            $this->authorize('create', App\Models\User::class);
        }

        public function crear(): void
        {
            $pwd = Uuid::uuid4()->toString();

            $this->validate([
                                'nombre' => ['required'],
                                'email'  => ['required', 'unique:App\Models\User,email'],
                                'cuil'   => ['required'],
                            ]);

            $user = ( new CreateNewUser() )->create([
                                                        'name'                  => $this->nombre,
                                                        'email'                 => $this->email,
                                                        'cuil'                  => $this->cuil,
                                                        'password'              => $pwd,
                                                        'password_confirmation' => $pwd,
                                                    ]);

            $this->emitTo('adminpanel::user-table', 'refreshComponent');
            $this->closeModal();
        }

        public function cancelar(): void
        {
            $this->emitTo('adminpanel::user-table', 'refreshComponent');
            $this->closeModal();
        }

        public function render()
        {
            return view('adminpanel::users.livewire.user-create');
        }
    }
