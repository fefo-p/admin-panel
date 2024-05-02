<div class="px-4 py-2 w-full mx-auto flex flex-row items-center space-x-4">
    <div class="w-full space-y-6">
        <div class="w-full bg-white dark:bg-gray-800 shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">Editar Usuario</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Use a permanent address where you can receive mail.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="#" method="POST">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-500">Nombre
                                    Completo</label>
                                <input type="text"
                                       wire:model="name"
                                       name="name"
                                       id="name"
                                       class="dark:bg-gray-700 dark:text-gray-400 mt-1 focus:ring-blue-500 dark:border-gray-700 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <x-jet-input-error for="name"/>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-500">Email
                                    address</label>
                                <input type="text"
                                       wire:model="email"
                                       name="email"
                                       id="email"
                                       class="dark:bg-gray-700 dark:text-gray-400 mt-1 focus:ring-blue-500 dark:border-gray-700 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <x-jet-input-error for="email"/>
                            </div>

                            <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                <label for="cuil" class="block text-sm font-medium text-gray-700 dark:text-gray-500">CUIL</label>
                                <input type="text"
                                       wire:model="cuil"
                                       name="cuil"
                                       id="cuil"
                                       class="dark:bg-gray-700 dark:text-gray-400 mt-1 focus:ring-blue-500 dark:border-gray-700 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <x-jet-input-error for="cuil"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="w-full bg-white dark:bg-gray-800 shadow px-4 py-5 sm:rounded-lg">
            <h3 class="text-md font-semibold text-gray-700 dark:text-gray-200">Roles</h3>
            <div class="mt-4 font-light grid grid-flow-row grid-cols-2 auto-rows-max gap-x-4 gap-y-2">
                @foreach($all_roles as $key => $name)
                    <div class="flex flex-row items-center justify-start">
                        <label class="inline-flex items-center my-1.5 mr-6 text-sm">
                            <input wire:model="selected_roles"
                                   type="checkbox"
                                   name="roles[]"
                                   class="w-4 h-4 form-checkbox dark:bg-gray-500 dark:text-gray-800"
                                   value="{{ $key }}"
                                   @if($selected_roles->contains($key)) checked @endif>
                            <span class="ml-2 dark:text-gray-400">{{ $name }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="w-full bg-white dark:bg-gray-800 shadow px-4 py-5 sm:rounded-lg">
            <h3 class="text-md font-semibold text-gray-700 dark:text-gray-200">Permisos</h3>
            <div class="mt-4 font-light grid grid-flow-row grid-cols-3 auto-rows-max gap-x-4">
                @foreach($all_permissions as $key => $name)
                    <div class="flex flex-row items-center justify-start">
                        <label class="inline-flex items-center mb-1.5 mr-6 text-sm">
                            <input wire:model="selected_permissions"
                                   type="checkbox"
                                   name="permissions[]"
                                   class="w-4 h-4 form-checkbox dark:bg-gray-500 dark:text-gray-800"
                                   value="{{ $key }}"
                                   @if($selected_permissions->contains($key)) checked @endif>
                            <span class="ml-2 dark:text-gray-400">
                                {{ $name }}
                            </span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <x-ap-secondary-button wire:click="cancelar" type="button">
                Cancelar
            </x-ap-secondary-button>
            <x-ap-button wire:click="actualizar"
                         type="submit"
                         class="ml-3 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Actualizar
            </x-ap-button>
        </div>
    </div>
</div>
