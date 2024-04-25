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
                                <input type="text" wire:model="name" name="name" id="name"
                                       class="dark:bg-gray-700 dark:text-gray-400 mt-1 focus:ring-blue-500 dark:border-gray-700 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <x-jet-input-error for="name"/>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-500">Email
                                    address</label>
                                <input type="text" wire:model="email" name="email" id="email"
                                       class="dark:bg-gray-700 dark:text-gray-400 mt-1 focus:ring-blue-500 dark:border-gray-700 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <x-jet-input-error for="email"/>
                            </div>

                            <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                <label for="cuil" class="block text-sm font-medium text-gray-700 dark:text-gray-500">CUIL</label>
                                <input type="text" wire:model="cuil" name="cuil" id="cuil"
                                       class="dark:bg-gray-700 dark:text-gray-400 mt-1 focus:ring-blue-500 dark:border-gray-700 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <x-jet-input-error for="cuil"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="w-full bg-white dark:bg-gray-800 shadow px-4 py-5 sm:rounded-lg">
            <div class="flex flex-row items-center justify-between space-x-6">
                <div class="w-1/2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Roles</dt>
                    <dd class="mt-1 max-w-prose text-sm text-gray-900 dark:text-gray-200 space-y-1">
                        @foreach($all_roles as $key => $name)
                            <div class="ml-4 pl-4">
                                <input wire:model="selected_roles"
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $key }}"
                                        id="{{ $key }}"
                                        @if($selected_roles->contains($key)) checked @endif
                                />
                                <label class="ml-4">{{ $name }}</label>
                            </div>
                        @endforeach
                    </dd>
                </div>

                <div class="w-1/2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Permisos</dt>
                    <dd class="mt-1 max-w-prose text-sm text-gray-900 dark:text-gray-200 space-y-1">
                        @foreach($all_permissions as $key => $name)
                            <div class="ml-4 pl-4">
                                <input wire:model="selected_permissions"
                                       type="checkbox"
                                       name="permissions[]"
                                       value="{{ $key }}"
                                       id="{{ $key }}"
                                       @if($selected_permissions->contains($key)) checked @endif
                                />
                                <label class="ml-4">{{ $name }}</label>
                            </div>
                        @endforeach
                    </dd>
                </div>
            </div>
        </div>


        {{--<div class="w-full bg-white dark:bg-gray-800 shadow px-4 py-5 sm:rounded-lg">
            <div class="flex flex-row items-center justify-between space-x-6">
                <div class="w-1/2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Roles</dt>
                    <dd class="mt-1 max-w-prose text-sm text-gray-900 dark:text-gray-200 space-y-1">
                        @if(count($selected_roles))
                            @foreach($selected_roles as $key => $name)
                                <p class="ml-4 pl-4">{{$name}}</p>
                            @endforeach
                        @else
                            <p class="text-red-500">Sin roles asignados</p>
                        @endif
                    </dd>
                </div>

                <div class="w-1/2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Permisos</dt>
                    <dd class="mt-1 max-w-prose text-sm text-gray-900 dark:text-gray-200 space-y-1">
                        @if(count($selected_permissions))
                            @foreach($selected_permissions as $key => $name)
                                <p class="ml-4 pl-4">{{$name}}</p>
                            @endforeach
                        @else
                            <p class="text-red-500">Sin permisos asignados</p>
                        @endif
                    </dd>
                </div>
            </div>
        </div>--}}

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
