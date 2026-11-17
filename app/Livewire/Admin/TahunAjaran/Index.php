<?php

namespace App\Livewire\Admin\TahunAjaran;

use App\Models\Semester;
use App\Models\TahunAjaran;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $confirmingDeletion = false;

    public $tahunAjaranToDelete = null;

    public function toggleActive($id)
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
        $tahunAjaran = TahunAjaran::findOrFail($id);

        if (! $tahunAjaran->is_active) {
            // Deactivate all other tahun ajaran
            TahunAjaran::where('sekolah_id', auth()->user()->sekolah_id)
                ->update(['is_active' => false]);

            // Deactivate all semesters
            Semester::whereHas('tahunAjaran', function ($q) {
                $q->where('sekolah_id', auth()->user()->sekolah_id);
            })->update(['is_active' => false]);
        }

        $tahunAjaran->is_active = ! $tahunAjaran->is_active;
        $tahunAjaran->save();

        session()->flash('message', 'Status tahun ajaran berhasil diubah.');
    }

    public function toggleSemester($semesterId)
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
        $semester = Semester::findOrFail($semesterId);

        if (! $semester->is_active) {
            // Deactivate all other semesters in the same tahun ajaran
            Semester::where('tahun_ajaran_id', $semester->tahun_ajaran_id)
                ->update(['is_active' => false]);
        }

        $semester->is_active = ! $semester->is_active;
        $semester->save();

        session()->flash('message', 'Status semester berhasil diubah.');
    }

    public function confirmDelete($id)
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
        $this->tahunAjaranToDelete = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
        $tahunAjaran = TahunAjaran::findOrFail($this->tahunAjaranToDelete);
        $tahunAjaran->delete();

        $this->confirmingDeletion = false;
        $this->tahunAjaranToDelete = null;

        session()->flash('message', 'Tahun ajaran berhasil dihapus.');
    }

    public function render()
    {
        $tahunAjarans = TahunAjaran::where('sekolah_id', auth()->user()->sekolah_id)
            ->orderBy('tahun', 'desc')
            ->paginate(10);

        return view('livewire.admin.tahun-ajaran.index', [
            'tahunAjarans' => $tahunAjarans,
        ])->layout('layouts.app', ['title' => 'Kelola Tahun Ajaran']);
    }
}
