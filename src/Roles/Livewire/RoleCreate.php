<?php

    namespace FefoP\AdminPanel\Roles\Livewire;

    use Illuminate\Http\Request;
    use FefoP\AdminPanel\Models\Role;
    use LivewireUI\Modal\ModalComponent;
    use Illuminate\Support\Facades\Auth;
    use FefoP\AdminPanel\Models\Activity;

    class RoleCreate extends ModalComponent
    {
        public        $name;
        public string $guard_name;

        public function mount()
        {
            Auth::user()->can('crear roles');

            $this->guard_name = config('adminpanel.guard');
            $this->separator = config( 'adminpanel.log.separator' );
        }

        public function crear(Request $request)
        {
            $role = Role::create($this->validar());

            // @TODO: Log action to config('adminpanel.table')
            $act = Activity::write([
                                       'ip'           => $request->getClientIp(),
                                       'subject_type' => config('permission.models.role'), //FefoP\AdminPanel\Models\Role
                                       'subject_id'   => $role->id,
                                       'subject_cuil' => null,
                                       'event'        => 'role created',
                                       'properties'   => json_encode([
                                                                         'id'          => $role->id,
                                                                         'name'        => $role->name,
                                                                         'created_at'  => $role->created_at,
                                                                         'permissions' => $role->permissions,
                                                                     ]),
                                   ]);

            $act->log(
                $act->created_at.$this->separator.
                $act->ip.$this->separator.
                $act->user_cuil.$this->separator.
                $act->event.$this->separator.
                $act->properties
            );

            $this->emitTo('adminpanel::role-table', 'refreshComponent');
            $this->closeModal();
        }

        protected function validar(): array
        {
            return $this->validate([
                                       'name'       => [ 'required', 'string', 'min:3', 'unique:roles,name' ],
                                       'guard_name' => [ 'nullable', 'string' ],
                                   ]);
        }

        public function updatedGuardName()
        {
            if ( empty($this->guard_name) ) {
                $this->guard_name = config('adminpanel.guard');
            }
        }

        public function cancelar()
        {
            $this->emitTo('adminpanel::role-table', 'refreshComponent');
            $this->closeModal();
        }

        public function render()
        {
            return view('adminpanel::roles.livewire.role-create');
        }
    }
