<?php

    namespace FefoP\AdminPanel\Roles\Livewire;

    use Illuminate\Http\Request;
    use FefoP\AdminPanel\Models\Role;
    use LivewireUI\Modal\ModalComponent;
    use Illuminate\Support\Facades\Auth;
    use FefoP\AdminPanel\Models\Activity;

    class RoleDelete extends ModalComponent
    {
        public     $role;
        public int $role_id;
        public     $mensaje_error;

        public function mount(int $role_id, string $action)
        {
            Auth::user()->can('adminpanel.rol.borrar');

            $this->role      = Role::find($role_id);
            $this->separator = config('adminpanel.log.separator');
        }

        public function confirmar(Request $request)
        {
            if ( $this->tiene_usuarios() ) {
                $this->mensaje_error = 'Este rol contiene usuarios, no se puede borrar';
                return false;
            }

            if ( $this->tiene_permisos() ) {
                $this->mensaje_error = 'Este rol contiene permisos, no se puede borrar';
                return false;
            }

            $id = $this->role->id;
            $this->role->delete();

            $act = Activity::write([
                                       'ip'           => $request->getClientIp(),
                                       'subject_type' => config('permission.models.role'),
                                       'subject_id'   => $id,
                                       'subject_cuil' => null,
                                       'event'        => 'role deleted',
                                       'properties'   => json_encode([
                                                                         'id' => $id,
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

        protected function tiene_usuarios(): bool
        {
            return $this->role->users->count();
        }

        protected function tiene_permisos(): bool
        {
            return $this->role->permissions->count();
        }

        public function cancel()
        {
            $this->emitTo('adminpanel::role-table', 'refreshComponent');
            $this->closeModal();
        }

        public function render()
        {
            return view('adminpanel::roles.livewire.role-delete');
        }
    }
