<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold">Listado de Usuarios</h3>
                        <x-button wire:click="create">Crear Nuevo Usuario</x-button>
                    </div>

                    @if (session()->has('message'))
                        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                    <div class="mt-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Activo</th>
                                    <th class="px-6 py-3 bg-gray-50"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $user->id }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $user->roles->first()->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            @if ($user->is_active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                            <x-button wire:click="edit({{ $user->id }})" class="mr-2">Editar</x-button>
                                            <x-danger-button wire:click="delete({{ $user->id }})" class="mr-2">Eliminar</x-danger-button>
                                            <x-button wire:click="toggleActive({{ $user->id }})" class="mr-2">
                                                {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                                            </x-button>
                                            <x-secondary-button wire:click="sendPasswordResetLink({{ $user->id }})">Resetear Contraseña</x-secondary-button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Creación/Edición de Usuario -->
    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">
            {{ $editing ? 'Editar Usuario' : 'Crear Usuario' }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-label for="name" value="Nombre" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" />
                <x-input-error for="name" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="Email" />
                <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="email" />
                <x-input-error for="email" class="mt-2" />
            </div>

            @if (!$editing)
                <div class="mt-4">
                    <x-label for="password" value="Contraseña" />
                    <x-input id="password" type="password" class="mt-1 block w-full" wire:model.defer="password" />
                    <x-input-error for="password" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="Confirmar Contraseña" />
                    <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model.defer="password_confirmation" />
                    <x-input-error for="password_confirmation" class="mt-2" />
                </div>
            @endif

            <div class="mt-4">
                <x-label for="role_name" value="Rol" />
                <select id="role_name" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model.defer="role_name">
                    <option value="">Selecciona un rol</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <x-input-error for="role_name" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" class="mr-4">
                Cancelar
            </x-secondary-button>

            <x-button wire:click.prevent="{{ $editing ? 'update' : 'store' }}">
                {{ $editing ? 'Guardar Cambios' : 'Crear Usuario' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>