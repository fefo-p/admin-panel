<div class="px-4 py-2 w-full flex flex-row items-center space-x-4">
    <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
        <button
                wire:click="$emit('closeModal')"
                type="button"
                class="bg-white dark:bg-gray-700 rounded-md text-gray-400 dark:text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="sr-only">Close</span>
            <!-- Heroicon name: outline/x -->
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                 stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <article class="w-full">
        <!-- Profile header -->
        <div class="w-full">
            <div class="max-w-12xl w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center sm:space-x-5">
                    <div class="flex">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <div class="shrink-0 mr-3">
                                <img class="h-24 w-24 dark:bg-white rounded-full object-cover sm:h-32 sm:w-32"
                                     src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"/>
                            </div>
                        @endif
                        {{--<img class="h-24 w-24 rounded-full ring-4 ring-white sm:h-32 sm:w-32" src="https://images.unsplash.com/photo-1463453091185-61582044d556?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80" alt="">--}}
                    </div>
                    <div class="sm:flex-1 sm:min-w-0 sm:flex sm:items-center sm:justify-end sm:space-x-6 sm:pb-1">
                        <div class="mt-2 sm:mt-0 2xl:block min-w-0 flex-1">
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-200 truncate">
                                {{ $user->name }}
                            </h1>
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

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Cuil
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                        {{ $user->cuil ?? '-' }}
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Email
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                        {{ $user->email }}
                    </dd>
                </div>

                <div class="sm:col-span-3">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Verificado
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                        {{ $user->email_verified_at?->isoFormat('LLLL') }} hs.
                    </dd>
                </div>

                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Habilitado
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                        @if($user->deleted_at)
                            No
                        @else
                            Si
                        @endif
                    </dd>
                </div>

                <div class="col-span-1 sm:col-span-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Roles
                    </dt>
                    <dd class="mt-1 max-w-prose text-sm text-gray-900 dark:text-gray-200 space-y-1">
                        @if($user->roles->count())
                            @foreach($user->roles as $rol)
                                <p>{{$rol->name}}</p>
                            @endforeach
                        @else
                            <p class="text-red-500">Sin roles asignados</p>
                        @endif

                    </dd>
                </div>

                <div class="col-span-1 sm:col-span-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Permisos especiales
                    </dt>
                    <dd class="mt-1 max-w-prose text-sm text-gray-900 dark:text-gray-200 space-y-1">
                        @if($user->permissions->count())
                            @foreach($user->permissions as $perm)
                                <p>{{$perm->name}}</p>
                            @endforeach
                        @else
                            <p class="text-red-500">Sin permisos especiales asignados</p>
                        @endif

                    </dd>
                </div>
            </dl>
        </div>
    </article>
</div>
