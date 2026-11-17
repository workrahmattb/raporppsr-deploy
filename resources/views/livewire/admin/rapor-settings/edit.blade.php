<div>
    <!-- Header -->
    <div class="mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan Rapor</h1>
            <p class="text-gray-600 mt-1">Kelola logo, judul, subjudul, tanggal, dan kepala sekolah untuk rapor Putra dan Putri</p>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form wire:submit="save">
            @if(auth()->user()->dapatMengaksesKategori('Putri'))
            <section aria-labelledby="judul-putri">
                <h2 id="judul-putri" class="text-lg font-semibold text-gray-800 mb-2">Rapor Putri</h2>
                <p class="text-sm text-gray-600 mb-4">Pengaturan sebelumnya digunakan untuk kelas Putri.</p>
            <!-- Tanggal Rapor -->
            <div class="mb-6">
                <label for="tanggal_rapor" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Rapor <span class="text-red-500">*</span>
                </label>
                <input wire:model="tanggal_rapor" type="text" id="tanggal_rapor" 
                    placeholder="Contoh: ٢ محرم ١٤٤٨, تلوك كوانتن"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_rapor') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Format bebas, bisa menggunakan teks Arabic atau Latin</p>
                @error('tanggal_rapor')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kepala Sekolah MTs -->
            <div class="mb-6">
                <label for="kepala_sekolah_mts" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kepala Sekolah MTs (Tingkat 7-9) <span class="text-red-500">*</span>
                </label>
                <input wire:model="kepala_sekolah_mts" type="text" id="kepala_sekolah_mts" 
                    placeholder="Contoh: S.Pd مارديه روسنيله نينغسيه"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kepala_sekolah_mts') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Nama kepala sekolah untuk siswa kelas 7, 8, dan 9</p>
                @error('kepala_sekolah_mts')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kepala Sekolah MA -->
            <div class="mb-6">
                <label for="kepala_sekolah_ma" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kepala Sekolah MA (Tingkat 10-12) <span class="text-red-500">*</span>
                </label>
                <input wire:model="kepala_sekolah_ma" type="text" id="kepala_sekolah_ma" 
                    placeholder="Contoh: Dina Yulesti, M.Pd"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kepala_sekolah_ma') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Nama kepala sekolah untuk siswa kelas 10, 11, dan 12</p>
                @error('kepala_sekolah_ma')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

                @include('livewire.admin.rapor-settings.tampilan', ['kategori' => 'Putri'])
            </section>

            @endif
            @if(auth()->user()->dapatMengaksesKategori('Putra'))
            <section aria-labelledby="judul-putra" class="border-t border-gray-200 pt-6">
                <h2 id="judul-putra" class="text-lg font-semibold text-gray-800 mb-2">Rapor Putra</h2>
                <p class="text-sm text-gray-600 mb-4">Isi sebelum mencetak rapor kelas Putra. Pengaturan dipilih otomatis sesuai kategori kelas.</p>
                <div class="mb-6">
                    <label for="tanggal_rapor_putra" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Rapor Putra</label>
                    <input wire:model="tanggal_rapor_putra" type="text" id="tanggal_rapor_putra" dir="auto"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tanggal_rapor_putra') border-red-500 @enderror">
                    @error('tanggal_rapor_putra')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="kepala_sekolah_mts_putra" class="block text-sm font-medium text-gray-700 mb-2">Nama Kepala Sekolah MTs (Tingkat 7-9) Putra</label>
                    <input wire:model="kepala_sekolah_mts_putra" type="text" id="kepala_sekolah_mts_putra" dir="auto"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kepala_sekolah_mts_putra') border-red-500 @enderror">
                    @error('kepala_sekolah_mts_putra')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="kepala_sekolah_ma_putra" class="block text-sm font-medium text-gray-700 mb-2">Nama Kepala Sekolah MA (Tingkat 10-12) Putra</label>
                    <input wire:model="kepala_sekolah_ma_putra" type="text" id="kepala_sekolah_ma_putra" dir="auto"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kepala_sekolah_ma_putra') border-red-500 @enderror">
                    @error('kepala_sekolah_ma_putra')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @include('livewire.admin.rapor-settings.tampilan', ['kategori' => 'Putra'])
            </section>

            @endif

            <!-- Actions -->
            <div class="flex justify-end pt-6 border-t border-gray-200">
                <button type="submit" wire:loading.attr="disabled" wire:target="save,logoPutra,logoPutri" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
