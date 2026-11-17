<?php

namespace App\Livewire\SuperAdmin\Admins;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Index extends Component
{
    #[Locked]
    public ?int $adminId = null;

    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $password = '';

    public string $kategori_admin = 'Putri';

    public function mount(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }

    public function edit(int $id): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
        $admin = User::where('role', 'admin')->where('sekolah_id', auth()->user()->sekolah_id)->findOrFail($id);
        $this->adminId = $admin->id;
        $this->name = $admin->name;
        $this->username = $admin->username ?? '';
        $this->email = $admin->email;
        $this->kategori_admin = $admin->kategori_admin ?? 'Putri';
        $this->password = '';
        $this->resetValidation();
    }

    public function cancel(): void
    {
        $this->reset('adminId', 'name', 'username', 'email', 'password', 'kategori_admin');
        $this->resetValidation();
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
        $admin = $this->adminId
            ? User::where('role', 'admin')->where('sekolah_id', auth()->user()->sekolah_id)->findOrFail($this->adminId)
            : new User;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($admin)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin)],
            'kategori_admin' => ['required', Rule::in(['Putra', 'Putri'])],
            'password' => [$admin->exists ? 'nullable' : 'required', 'string', Password::default()],
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => 'admin',
            'kategori_admin' => $validated['kategori_admin'],
            'sekolah_id' => auth()->user()->sekolah_id,
        ];
        if ($this->password !== '') {
            $data['password'] = $validated['password'];
        }
        $admin->fill($data)->save();
        $this->cancel();
        session()->flash('message', 'Akun admin berhasil disimpan.');
    }

    public function render(): View
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);

        return view('livewire.super-admin.admins.index', [
            'admins' => User::where('role', 'admin')->where('sekolah_id', auth()->user()->sekolah_id)->orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Kelola Admin']);
    }
}
