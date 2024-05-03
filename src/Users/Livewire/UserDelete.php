<?php

    namespace FefoP\AdminPanel\Users\Livewire;

    use App\Models\User;
    use Illuminate\Http\Request;
    use LivewireUI\Modal\ModalComponent;
    use Illuminate\Support\Facades\Auth;
    use FefoP\AdminPanel\Models\Activity;

    class UserDelete extends ModalComponent
    {
        public        $user;
        public int    $user_id;
        public string $action;
        public        $mensaje_error;

        public function mount(int $user_id, string $action)
        {
            Auth::user()->can('borrar usuarios');

            $this->user      = User::withTrashed()->find($user_id);
            $this->action    = $action;
            $this->separator = config('adminpanel.log.separator');
        }

        public function confirmar(Request $request)
        {
            $id   = $this->user->id;
            $cuil = $this->user->cuil;

            switch ( $this->action ) {
                case 'unban':
                    // TODO: Limpiar email_verified_at???
                    $this->user->restore();
                    break;
                default:
                    $this->user->delete();
                    break;
            }

            $act = Activity::write([
                                       'ip'           => $request->getClientIp(),
                                       'subject_type' => 'App\Models\User',
                                       'subject_id'   => $id,
                                       'subject_cuil' => $cuil,
                                       'event'        => 'user '.($this->action == 'unban' ? 'restored' : 'deleted'),
                                       'properties'   => json_encode([
                                                                         'id'   => $id,
                                                                         'cuil' => $cuil,
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

        public function cancel()
        {
            $this->emitTo('adminpanel::user-table', 'refreshComponent');
            $this->closeModal();
        }

        public function render()
        {
            return view('adminpanel::users.livewire.user-delete');
        }
    }
