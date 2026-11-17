<?php

namespace App\Livewire\Admin\Kelas;

use App\Models\KelasRapor;
use App\Models\SiswaRapor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ManageSiswa extends Component
{
    use WithPagination;

    public $kelasId;

    public $kelas;

    public $siswaId;

    public $confirmingDeletion = false;

    public $siswaToDelete = null;

    public $editingNamaArabic = [];

    public $namaArabicValues = [];

    // Search properties
    public $searchSiswa = '';

    public $selectedSiswa = null;

    public $showDropdown = false;

    protected $rules = [
        'siswaId' => 'required|exists:siswas_rapor,id',
    ];

    protected $messages = [
        'siswaId.required' => 'Pilih siswa terlebih dahulu',
        'siswaId.exists' => 'Siswa tidak ditemukan',
    ];

    public function mount($kelasId)
    {
        $this->kelasId = $kelasId;
        $this->kelas = KelasRapor::with('tahunAjaran')->findOrFail($kelasId);
    }

    public function updatedSearchSiswa()
    {
        $this->showDropdown = true;
        $this->selectedSiswa = null;
        $this->resetPage('searchSiswaPage');
    }

    public function selectSiswa($siswaId, $siswaNama)
    {
        $this->siswaId = $siswaId;
        $this->selectedSiswa = ['id' => $siswaId, 'nama' => $siswaNama];
        $this->searchSiswa = '';
        $this->showDropdown = false;
    }

    public function addSiswa()
    {
        $this->kelas = KelasRapor::findOrFail($this->kelasId);
        $this->validate();
        SiswaRapor::where('kategori', $this->kelas->kategori)->where('sekolah_id', $this->kelas->tahunAjaran->sekolah_id)->findOrFail($this->siswaId);

        // Check if siswa already in this kelas
        $exists = DB::table('kelas_siswa')
            ->where('kelas_id', $this->kelasId)
            ->where('siswa_id', $this->siswaId)
            ->exists();

        if ($exists) {
            $this->addError('siswaId', 'Siswa sudah ada di kelas ini');

            return;
        }

        // Add siswa to kelas
        DB::table('kelas_siswa')->insert([
            'kelas_id' => $this->kelasId,
            'siswa_id' => $this->siswaId,
            'nomor_absen' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session()->flash('message', 'Siswa berhasil ditambahkan ke kelas.');

        // Reset form
        $this->reset(['siswaId', 'searchSiswa', 'selectedSiswa']);
    }

    public function confirmDelete($siswaId)
    {
        $this->siswaToDelete = $siswaId;
        $this->confirmingDeletion = true;
    }

    public function deleteSiswa()
    {
        $this->kelas = KelasRapor::findOrFail($this->kelasId);
        DB::table('kelas_siswa')
            ->where('kelas_id', $this->kelasId)
            ->where('siswa_id', $this->siswaToDelete)
            ->delete();

        $this->confirmingDeletion = false;
        $this->siswaToDelete = null;

        session()->flash('message', 'Siswa berhasil dihapus dari kelas.');
    }

    public function editNamaArabic($siswaId, $currentValue)
    {
        $this->editingNamaArabic[$siswaId] = true;
        $this->namaArabicValues[$siswaId] = $currentValue;
    }

    public function updateNamaArabic($siswaId)
    {
        $this->kelas = KelasRapor::findOrFail($this->kelasId);
        $siswa = SiswaRapor::whereHas('kelas', fn ($query) => $query->where('kelas_rapor.id', $this->kelasId))->findOrFail($siswaId);
        $siswa->update([
            'nama_arabic' => $this->namaArabicValues[$siswaId] ?? null,
        ]);

        $this->editingNamaArabic[$siswaId] = false;
        session()->flash('message', 'Nama Arabic berhasil diperbarui.');
    }

    public function cancelEditNamaArabic($siswaId)
    {
        $this->editingNamaArabic[$siswaId] = false;
        unset($this->namaArabicValues[$siswaId]);
    }

    public function getAvailableSiswasProperty()
    {
        $this->kelas = KelasRapor::findOrFail($this->kelasId);
        // Get siswa IDs already in any kelas
        $siswaIdsInAnyKelas = DB::table('kelas_siswa')
            ->pluck('siswa_id')
            ->toArray();

        // Get available students with search
        $query = SiswaRapor::where('sekolah_id', auth()->user()->sekolah_id)
            ->where('kategori', $this->kelas->kategori)
            ->whereNotIn('id', $siswaIdsInAnyKelas);

        // Apply search filter
        if ($this->searchSiswa) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%'.$this->searchSiswa.'%')
                    ->orWhere('nisn', 'like', '%'.$this->searchSiswa.'%');
            });
        }

        return $query->orderBy('nama')->paginate(10, pageName: 'searchSiswaPage');
    }

    public function render()
    {
        $this->kelas = KelasRapor::findOrFail($this->kelasId);
        // Get students already in this class - sorted alphabetically with pagination
        $siswasDiKelas = DB::table('kelas_siswa')
            ->join('siswas_rapor', 'kelas_siswa.siswa_id', '=', 'siswas_rapor.id')
            ->where('kelas_siswa.kelas_id', $this->kelasId)
            ->select('siswas_rapor.*')
            ->orderBy('siswas_rapor.nama')
            ->paginate(10);

        return view('livewire.admin.kelas.manage-siswa', [
            'siswasDiKelas' => $siswasDiKelas,
        ])->layout('layouts.app', ['title' => 'Kelola Siswa - '.$this->kelas->nama]);
    }
}
