<?php

namespace App\Livewire\Admin\MataPelajaran;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MataPelajaran;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $kelompok = '';
    public $tingkat = '';
    public $confirmingDeletion = false;
    public $mataPelajaranToDelete = null;
    public $showCreateModal = false;

    // Create form
    public $kode = '';
    public $nama = '';
    public $namapelajaran_arabic = '';
    public $kelompokForm = 'A';
    public $tingkatForm = '';
    public $kkm = 75;

    // Inline editing for Arabic names
    public $editingNamaArabic = [];
    public $namaArabicValues = [];

    protected $listeners = ['$refresh'];

    public function resetForm()
    {
        $this->kode = '';
        $this->nama = '';
        $this->namapelajaran_arabic = '';
        $this->kelompokForm = 'A';
        $this->tingkatForm = '';
        $this->kkm = 75;
        $this->resetValidation();
        $this->showCreateModal = true;
    }

    public function save()
    {
        // Validasi
        $validated = $this->validate([
            'kode' => 'required|string|max:10',
            'nama' => 'required|string|max:255',
            'namapelajaran_arabic' => 'nullable|string|max:255',
            'kelompokForm' => 'required|in:A,B,C',
            'tingkatForm' => 'nullable|in:7,8,9,10,11,12',
            'kkm' => 'required|integer|min:0|max:100',
        ]);

        // Cek duplikasi kode
        $existing = MataPelajaran::where('kode', $this->kode)
            ->where('sekolah_id', auth()->user()->sekolah_id)
            ->first();

        if ($existing) {
            $this->addError('kode', 'Kode mata pelajaran sudah digunakan, tidak bisa menyimpan kode yang sama');
            return;
        }

        MataPelajaran::create([
            'sekolah_id' => auth()->user()->sekolah_id,
            'kode' => $this->kode,
            'nama' => $this->nama,
            'namapelajaran_arabic' => $this->namapelajaran_arabic,
            'kelompok' => $this->kelompokForm,
            'tingkat' => $this->tingkatForm,
            'kkm' => $this->kkm,
        ]);

        $this->resetForm();
        $this->showCreateModal = false;
        session()->flash('message', 'Mata pelajaran berhasil ditambahkan.');
    }

    protected $queryString = ['search', 'kelompok', 'tingkat'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKelompok()
    {
        $this->resetPage();
    }

    public function updatingTingkat()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->mataPelajaranToDelete = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        $mataPelajaran = MataPelajaran::findOrFail($this->mataPelajaranToDelete);
        $mataPelajaran->delete();
        
        $this->confirmingDeletion = false;
        $this->mataPelajaranToDelete = null;
        
        session()->flash('message', 'Mata pelajaran berhasil dihapus.');
    }

    public function editNamaArabic($mataPelajaranId)
    {
        $this->editingNamaArabic[$mataPelajaranId] = true;
        $mataPelajaran = MataPelajaran::find($mataPelajaranId);
        $this->namaArabicValues[$mataPelajaranId] = $mataPelajaran->namapelajaran_arabic ?? '';
    }

    public function cancelEditNamaArabic($mataPelajaranId)
    {
        $this->editingNamaArabic[$mataPelajaranId] = false;
        unset($this->namaArabicValues[$mataPelajaranId]);
    }

    public function updateNamaArabic($mataPelajaranId)
    {
        $mataPelajaran = MataPelajaran::findOrFail($mataPelajaranId);
        $mataPelajaran->update([
            'namapelajaran_arabic' => $this->namaArabicValues[$mataPelajaranId] ?? null
        ]);

        $this->editingNamaArabic[$mataPelajaranId] = false;
        session()->flash('message', 'Nama Arabic berhasil diperbarui.');
    }

    public function render()
    {
        $mataPelajarans = MataPelajaran::where('sekolah_id', auth()->user()->sekolah_id)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('kode', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->kelompok, function($query) {
                $query->where('kelompok', $this->kelompok);
            })
            ->when($this->tingkat, function($query) {
                $query->where('tingkat', $this->tingkat);
            })
            ->orderBy('kelompok')
            ->orderBy('nama')
            ->paginate(15);

        return view('livewire.admin.mata-pelajaran.index', [
            'mataPelajarans' => $mataPelajarans
        ])->layout('layouts.app', ['title' => 'Kelola Mata Pelajaran']);
    }
}
