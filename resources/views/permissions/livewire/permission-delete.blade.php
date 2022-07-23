<div wire:model="mensaje_error" class="px-4 py-2 flex flex-row items-center space-x-4">
    <x-ap-delete-confirmation :object="$permission" object_name="permiso" action_name="Borrar" :mensaje_error="$mensaje_error"/>
</div>
