<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Guru</h1>
            <p class="mt-1 text-gray-600">Informasi lengkap data guru</p>
        </div>
        <div class="flex items-center gap-3">
            <flux:button :href="route('admin.guru.index')" wire:navigate>Kembali</flux:button>
            <flux:button :href="route('admin.guru.edit', $guru->id)" wire:navigate variant="primary">Edit Data</flux:button>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow-md">
        <section class="border-b border-gray-200 p-6 last:border-b-0">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">Data Guru</h2>
            <dl class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">NIP</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->nip) ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Nama Lengkap</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->nama) ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Nama Arabic</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900" dir="auto">{{ ($guru->nama_arabic) ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ (match ($guru->jenis_kelamin) { 'L' => 'Laki-laki', 'P' => 'Perempuan', default => '-' }) ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Tempat Lahir</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->tempat_lahir) ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Tanggal Lahir</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->tanggal_lahir?->format('d F Y')) ?: '-' }}</dd>
                </div>
            </dl>
        </section>
        <section class="border-b border-gray-200 p-6 last:border-b-0">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">Kontak</h2>
            <dl class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Alamat</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->alamat) ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Telepon</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->telepon) ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Email</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->user?->email ?: $guru->email) ?: '-' }}</dd>
                </div>
            </dl>
        </section>
        <section class="border-b border-gray-200 p-6 last:border-b-0">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">Data Kepegawaian</h2>
            <dl class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Pendidikan Terakhir</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->pendidikan_terakhir) ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Tanggal Mulai Mengajar</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->tanggal_mulai_mengajar?->format('d F Y')) ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Status</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->status) ?: '-' }}</dd>
                </div>
            </dl>
        </section>
        <section class="border-b border-gray-200 p-6 last:border-b-0">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">Akun Pengguna</h2>
            <dl class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Username</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ ($guru->user?->username) ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-sm font-medium text-gray-500">Role</dt>
                    <dd class="whitespace-pre-line break-words text-gray-900">{{ $guru->user?->roleLabel() ?? '-' }}</dd>
                </div>
            </dl>
        </section>
    </div>
</div>
