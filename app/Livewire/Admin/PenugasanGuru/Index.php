<?php

namespace App\Livewire\Admin\PenugasanGuru;

use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $tingkat = '';

    public string $kategori = '';

    public $assignmentToDelete = null;

    public $showDeleteModal = false;

    protected $queryString = ['search', 'tingkat', 'kategori'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTingkat()
    {
        $this->resetPage();
    }

    public function updatingKategori(): void
    {
        $this->resetPage();
    }

    public function deleteAssignment()
    {
        if ($this->assignmentToDelete) {
            $assignment = DB::table('guru_mata_pelajaran')->find($this->assignmentToDelete);
            abort_unless($assignment && auth()->user()->dapatMengaksesKategori($assignment->kategori), 403);
            abort_unless(\App\Models\GuruRapor::where('sekolah_id', auth()->user()->sekolah_id)->whereKey($assignment->guru_id)->exists(), 403);
            DB::table('guru_mata_pelajaran')
                ->where('id', $this->assignmentToDelete)
                ->delete();

            $this->showDeleteModal = false;
            $this->assignmentToDelete = null;
            session()->flash('message', 'Penugasan berhasil dihapus.');
        }
    }

    public function confirmDelete($id)
    {
        $this->assignmentToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function render()
    {
        $assignments = DB::table('guru_mata_pelajaran')
            ->join('gurus_rapor', 'guru_mata_pelajaran.guru_id', '=', 'gurus_rapor.id')
            ->where('gurus_rapor.sekolah_id', auth()->user()->sekolah_id)
            ->whereIn('guru_mata_pelajaran.kategori', auth()->user()->kategoriDiizinkan())
            ->join('mata_pelajarans', 'guru_mata_pelajaran.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->select(
                'guru_mata_pelajaran.id',
                'gurus_rapor.nama as guru_nama',
                'gurus_rapor.nip',
                'mata_pelajarans.nama as mapel_nama',
                'guru_mata_pelajaran.tingkat',
                'guru_mata_pelajaran.kategori'
            )
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('gurus_rapor.nama', 'like', '%'.$this->search.'%')
                        ->orWhere('gurus_rapor.nip', 'like', '%'.$this->search.'%')
                        ->orWhere('mata_pelajarans.nama', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->tingkat, function ($query) {
                $query->where('guru_mata_pelajaran.tingkat', $this->tingkat);
            })
            ->when($this->kategori, function ($query) {
                $query->where('guru_mata_pelajaran.kategori', $this->kategori);
            })
            ->orderBy('gurus_rapor.nama')
            ->paginate(15);

        $tahunAjaran = TahunAjaran::where('sekolah_id', auth()->user()->sekolah_id)
            ->where('is_active', true)
            ->first();

        $semester = Semester::where('is_active', true)->first();

        return view('livewire.admin.penugasan-guru.index', [
            'assignments' => $assignments,
            'tahunAjaran' => $tahunAjaran,
            'semester' => $semester,
        ])->layout('layouts.app', ['title' => 'Penugasan Guru']);
    }
}
