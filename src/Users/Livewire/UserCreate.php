<?php

    namespace FefoP\AdminPanel\Users\Livewire;

    use Ramsey\Uuid\Uuid;
    use Illuminate\Http\Request;
    use LivewireUI\Modal\ModalComponent;
    use FefoP\AdminPanel\Models\Activity;
    use App\Actions\Fortify\CreateNewUser;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    class UserCreate extends ModalComponent
    {
        use AuthorizesRequests;

        public    $nombre;
        public    $email;
        public    $cuil;

        public function mount(): void
        {
            $this->authorize('create', App\Models\User::class);
            $this->separator = config( 'adminpanel.log.separator' );
        }

        public function crear(Request $request): void
        {
            $pwd = Uuid::uuid4()->toString();

            $this->validate([
                                'nombre' => [ 'required' ],
                                'email'  => [ 'required', 'unique:App\Models\User,email' ],
                                'cuil'   => [ 'required' ],
                            ]);

            $user = ( new CreateNewUser() )->create([
                                                        'name'                  => $this->nombre,
                                                        'email'                 => $this->email,
                                                        'cuil'                  => $this->cuil,
                                                        'password'              => $pwd,
                                                        'password_confirmation' => $pwd,
                                                    ]);

            $act = Activity::write([
                                       'ip'           => $request->getClientIp(),
                                       'subject_type' => 'App\Models\User',
                                       'subject_id'   => $user->id,
                                       'subject_cuil' => $user->cuil,
                                       'event'        => 'user created',
                                       'properties'   => json_encode([
                                                                         'id'         => $user->id,
                                                                         'uuid'       => $user->uuid,
                                                                         'name'       => $user->name,
                                                                         'email'      => $user->email,
                                                                         'cuil'       => $user->cuil,
                                                                         'created_at' => $user->created_at,
                                                                     ]),
                                   ]);

            $act->log(
                $act->created_at.$this->separator.
                $act->ip.$this->separator.
                $act->user_cuil.$this->separator.
                $act->event.$this->separator.
                $act->properties
            );


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
