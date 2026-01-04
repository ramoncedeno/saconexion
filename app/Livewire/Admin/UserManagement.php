<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Branch; // Assuming you'll need branches for user creation
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $user_id, $name, $email, $password, $password_confirmation, $role_name, $is_active_status;
    public $editing = false;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'role_name' => 'required',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.min' => 'El nombre debe tener al menos 3 caracteres.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico debe ser una dirección válida.',
        'email.unique' => 'Este correo electrónico ya está en uso.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password_confirmation.required' => 'Confirma la contraseña.',
        'password.same' => 'Las contraseñas no coinciden.',
        'role_name.required' => 'El rol es obligatorio.',
    ];

    public function render()
    {
        return view('livewire.admin.user-management', [
            'users' => User::with('roles')->paginate(10),
            'roles' => Role::all(),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
        $this->editing = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|same:password_confirmation',
            'role_name' => 'required',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_active' => true, // New users are active by default
        ]);

        $user->assignRole($this->role_name);

        session()->flash('message', 'Usuario creado exitosamente.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_name = $user->roles->first()->name ?? null;
        $this->is_active_status = $user->is_active;

        $this->isModalOpen = true;
        $this->editing = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$this->user_id,
            'role_name' => 'required',
        ]);

        $user = User::find($this->user_id);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);
        
        $user->syncRoles([$this->role_name]);

        session()->flash('message', 'Usuario actualizado exitosamente.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Usuario eliminado exitosamente.');
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
        session()->flash('message', 'Estado de usuario actualizado.');
    }

    public function sendPasswordResetLink($id)
    {
        $user = User::findOrFail($id);
        Password::sendResetLink(['email' => $user->email]);
        session()->flash('message', 'Enlace de restablecimiento de contraseña enviado.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetErrorBag();
    }

    private function resetInputFields()
    {
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role_name = null;
        $this->is_active_status = true;
    }
}
