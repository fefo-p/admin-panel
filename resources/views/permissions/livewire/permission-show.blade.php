<div class="relative px-4 py-2 w-full flex flex-row items-center space-x-4">
    <div class="absolute top-0 right-0 pt-4 pr-4">
        <button
            wire:click="$emit('closeModal')"
            type="button"
            class="bg-white dark:bg-gray-700 rounded-md text-gray-400 dark:text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="sr-only">Close</span>
            <!-- Heroicon name: outline/x -->
            <svg class="h-4 w-4 sm:h-6 sm:w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <article class="w-full">
        <!-- Profile header -->
        <div class="w-full">
            <div class="max-w-12xl w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center sm:space-x-5">
                    <div class="sm:flex-1 sm:min-w-0 sm:flex sm:items-center sm:justify-end sm:space-x-6 sm:pb-1">
                        <div class="mt-2 sm:mt-0 2xl:block min-w-0 flex-1">
                            <h1 class="text-md sm:text-2xl font-bold text-gray-900 dark:text-gray-200 truncate">
                                {{ $permission->name }}
                            </h1>
                            <small class="text-sm sm:text-md font-semibold text-gray-600 dark:text-gray-400 truncate">{{ $permission->guard_name }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="mt-6 sm:mt-2 2xl:mt-5">
            <div class="border-b border-gray-200 dark:border-gray-500"></div>
        </div>

        <!-- Description list -->
        <div class="mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-8">
                <div class="col-span-1 sm:col-span-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Roles
                    </dt>
                    <dd class="mt-1 max-w-prose text-sm text-gray-900 dark:text-gray-200 space-y-1">
                        @if($permission->roles?->count())
                            @foreach($permission->roles as $role)
                                <p>{{$role->name}}</p>
                            @endforeach
                        @else
                            <p class="text-red-500">Sin roles asignados</p>
                        @endif
                    </dd>
                </div>

                <div class="col-span-1 sm:col-span-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Usuarios
                    </dt>
                    <dd class="mt-1 max-w-prose text-sm text-gray-900 dark:text-gray-200 space-y-1">
                        @if($users->count())
                            @foreach($users as $user)
                                <div class="flex flex-row items-center space-x-3">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <div class="shrink-0">
                                            <img class="h-8 w-8 dark:bg-white rounded-full object-cover sm:h-6 sm:w-6" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                                        </div>
                                    @endif
                                    <div>{{$user->name}}</div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-red-500">Sin usuarios asignados</p>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </article>
</div>
