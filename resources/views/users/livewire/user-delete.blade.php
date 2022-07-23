<div wire:model="mensaje_error" class="px-4 py-2 flex flex-row items-center space-x-4">
    @if(is_null($user->deleted_at))
        <x-ap-delete-confirmation :object="$user" object_name="usuario" action_name="Deshabilitar" :mensaje_error="$mensaje_error"/>
    @else
        <x-ap-confirmation :object="$user" object_name="usuario" action_name="Habilitar" />
    @endif
</div>
