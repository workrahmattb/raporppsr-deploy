<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Kelola Admin</h1>
    <p class="text-gray-600 mb-6">Tentukan akses admin Putra atau Putri. Guru dan mata pelajaran dipakai bersama.</p>
    @if(session()->has('message'))
        <p class="mb-6 p-4 rounded-lg bg-green-50 text-green-800">{{ session('message') }}</p>
    @endif
    <form wire:submit="save" class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">{{ $adminId ? 'Edit Admin' : 'Tambah Admin' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input wire:model="name" label="Nama" required />
            <flux:input wire:model="username" label="Username" autocomplete="off" required />
            <flux:input wire:model="email" label="Email" type="email" required />
            <flux:select wire:model="kategori_admin" label="Bagian Admin">
                <flux:select.option value="Putra">Putra</flux:select.option>
                <flux:select.option value="Putri">Putri</flux:select.option>
            </flux:select>
            <flux:input wire:model="password" label="Password" type="password" autocomplete="new-password" description="Minimal 8 karakter. Saat edit, kosongkan untuk mempertahankan password." />
        </div>
        <div class="flex justify-end gap-3 mt-6">
            @if($adminId)<flux:button wire:click="cancel">Batal</flux:button>@endif
            <flux:button type="submit" variant="primary">Simpan Admin</flux:button>
        </div>
    </form>
    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="p-4 text-left">Nama</th><th class="p-4 text-left">Username</th><th class="p-4 text-left">Bagian</th><th class="p-4 text-right">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($admins as $admin)
                    <tr wire:key="admin-{{ $admin->id }}">
                        <td class="p-4">{{ $admin->name }}</td><td class="p-4">{{ $admin->username ?? '-' }}</td><td class="p-4">{{ $admin->kategori_admin ?? 'Belum ditentukan' }}</td>
                        <td class="p-4 text-right"><flux:button wire:click="edit({{ $admin->id }})" size="sm">Edit</flux:button></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">Belum ada akun admin bagian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
