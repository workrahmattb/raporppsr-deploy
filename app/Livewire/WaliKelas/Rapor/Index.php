<?php

namespace App\Livewire\WaliKelas\Rapor;

use Livewire\Component;
use App\Models\KelasRapor;
use App\Models\Semester;

class Index extends Component
{
    public $semesterId;
    public $kelas;
    public $siswas = [];
    public $catatan = [];

    public function mount()
    {
        // Get kelas where current user is wali kelas
        $this->kelas = KelasRapor::whereHas('waliKelas', function($query) {
            $query->where('user_id', auth()->id());
        })->with('tahunAjaran')->first();

        // Get active semester or specific semester if ID is set
        if ($this->semesterId) {
            $this->semester = Semester::find($this->semesterId);
        } else {
            $this->semester = Semester::where('is_active', true)->first();
            $this->semesterId = $this->semester->id ?? null;
        }

        if ($this->kelas && $this->semesterId) {
            $this->loadSiswas();
        }
    }

    public function loadSiswas()
    {
        if (!$this->kelas || !$this->semesterId) {
            $this->siswas = [];
            return;
        }

        // Get students in this class via kelas_siswa pivot
        $this->siswas = \DB::table('kelas_siswa')
            ->join('siswas_rapor', 'kelas_siswa.siswa_id', '=', 'siswas_rapor.id')
            ->where('kelas_siswa.kelas_id', $this->kelas->id)
            ->select('siswas_rapor.*', 'kelas_siswa.nomor_absen')
            ->orderBy('kelas_siswa.nomor_absen')
            ->get();

        // Load existing catatan
        $catatans = \App\Models\CatatanWaliKelas::where('semester_id', $this->semesterId)
            ->whereIn('siswa_id', $this->siswas->pluck('id'))
            ->get();

        foreach ($this->siswas as $siswa) {
            $note = $catatans->firstWhere('siswa_id', $siswa->id);
            $this->catatan[$siswa->id] = $note ? $note->catatan : '';
        }
    }

    public function updatedSemesterId()
    {
        $this->loadSiswas();
    }

    public function updatedCatatan($value, $siswaId)
    {
        if (!$this->semesterId) return;

        // Ensure we have the wali kelas ID (Guru ID)
        $guru = \App\Models\GuruRapor::where('user_id', auth()->id())->first();
        if (!$guru) return;

        \App\Models\CatatanWaliKelas::updateOrCreate(
            [
                'siswa_id' => $siswaId,
                'semester_id' => $this->semesterId,
            ],
            [
                'wali_kelas_id' => $guru->id,
                'catatan' => $value
            ]
        );

        $this->dispatch('saved-catatan');
    }

    public function render()
    {
        $semesters = Semester::orderBy('tahun_ajaran_id', 'desc')->orderBy('nama')->get();

        return view('livewire.wali-kelas.rapor.index', [
            'semesters' => $semesters
        ])->layout('layouts.app', ['title' => 'Cetak Rapor']);
    }
}
