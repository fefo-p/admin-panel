<div class="px-4 py-2 w-full mx-auto flex flex-row items-center space-x-4">
    <div class="w-full space-y-6">
        <div class="w-full bg-white dark:bg-gray-800 shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">Editar Permiso</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Use a permanent address where you can receive mail.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="#" method="POST">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-500">Nombre</label>
                                <input type="text" wire:model="name" name="name" id="name"
                                       class="dark:bg-gray-700 dark:text-gray-400 mt-1 focus:ring-blue-500 dark:border-gray-700 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <x-jet-input-error for="name"/>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="guard_name"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-500">Guard</label>
                                <input type="text" wire:model="guard_name" name="guard_name" id="guard_name"
                                       class="dark:bg-gray-700 dark:text-gray-400 mt-1 focus:ring-blue-500 dark:border-gray-700 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <x-jet-input-error for="guard_name"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Description list -->
        <div class="px-4 py-2 dark:bg-gray-800 rounded-lg mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-8">
                <div class="col-span-1 sm:col-span-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Usuarios
                    </dt>
                    <dd class="mt-1 max-w-prose text-sm text-gray-900 dark:text-gray-200 space-y-1">
                        @if(count($selected_users))
                            @foreach($selected_users as $key => $name)
                                <div class="flex flex-row items-center space-x-3">
                                    <div>{{ $name }}</div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-red-500">Sin usuarios asignados</p>
                        @endif
                    </dd>

                    <x-ap-separador/>
                </div>
            </dl>
        </div>

        <div class="flex justify-end">
            <x-ap-secondary-button wire:click="cancelar" type="button">
                Cancelar
            </x-ap-secondary-button>
            <x-ap-button wire:click="actualizar" type="submit"
                         class="ml-3 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Actualizar
            </x-ap-button>
        </div>
    </div>
</div>
