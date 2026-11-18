<?php

namespace App\Livewire\Admin\Siswa;

use App\Models\SiswaRapor;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $status_filter = '';

    public $confirmingDeletion = false;

    public $siswaToDelete = null;

    /** @var array<int, string> */
    public array $namaArabic = [];

    public ?int $namaArabicSavedId = null;

    protected $queryString = ['search', 'status_filter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->siswaToDelete = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->siswaToDelete) {
            SiswaRapor::findOrFail($this->siswaToDelete)->forceDelete();
            session()->flash('message', 'Siswa berhasil dihapus permanen.');
            $this->confirmingDeletion = false;
            $this->siswaToDelete = null;
        }
    }

    public function simpanNamaArabic(int $siswaId): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $siswa = SiswaRapor::where('sekolah_id', auth()->user()->sekolah_id)->findOrFail($siswaId);
        $this->validate(['namaArabic.'.$siswaId => 'nullable|string|max:255']);
        $nama = trim($this->namaArabic[$siswaId] ?? '');
        $siswa->update(['nama_arabic' => $nama !== '' ? $nama : null]);
        $this->namaArabic[$siswaId] = $nama;
        $this->namaArabicSavedId = $siswaId;
    }

    public function render()
    {
        $siswas = SiswaRapor::where('sekolah_id', auth()->user()->sekolah_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%'.$this->search.'%')
                        ->orWhere('nisn', 'like', '%'.$this->search.'%')
                        ->orWhere('nis', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->status_filter, function ($query) {
                $query->where('status', $this->status_filter);
            })
            ->orderBy('nama')
            ->paginate(10);

        foreach ($siswas as $siswa) {
            if (! array_key_exists($siswa->id, $this->namaArabic)) {
                $this->namaArabic[$siswa->id] = $siswa->nama_arabic ?? '';
            }
        }

        return view('livewire.admin.siswa.index', [
            'siswas' => $siswas,
        ])->layout('layouts.app', ['title' => 'Kelola Siswa']);
    }
}
