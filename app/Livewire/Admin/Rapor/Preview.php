<?php

namespace App\Livewire\Admin\Rapor;

use App\Models\CatatanWaliKelas;
use App\Models\Kehadiran;
use App\Models\Nilai;
use App\Models\NilaiSikap;
use App\Models\RaporSetting;
use App\Models\Semester;
use App\Models\SiswaRapor;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class Preview extends Component
{
    #[Locked]
    public $siswaId;

    public $semesterId;

    public $siswa;

    public $semester;

    public $kelas;

    public $nilais = [];

    public $nilaiSikap;

    public $kehadiran;

    public $catatan;

    public function mount($siswaId, $semesterId = null)
    {
        $this->siswaId = $siswaId;
        $this->siswa = SiswaRapor::findOrFail($siswaId);

        // Get active semester if not provided
        if ($semesterId) {
            $this->semesterId = $semesterId;
            $this->semester = Semester::with('tahunAjaran')->findOrFail($semesterId);
        } else {
            $this->semester = Semester::with('tahunAjaran')->where('is_active', true)->first();
            $this->semesterId = $this->semester->id ?? null;
        }

        $this->kelas = $this->siswa->kelas()->with('waliKelas')
            ->when($this->semester, fn ($query) => $query->where('tahun_ajaran_id', $this->semester->tahun_ajaran_id))
            ->firstOrFail();

        $this->loadNilais();
        $this->loadNilaiSikap();
        $this->loadKehadiran();
        $this->loadCatatan();
    }

    public function loadNilais()
    {
        SiswaRapor::findOrFail($this->siswaId);
        if (! $this->semesterId) {
            return;
        }

        $this->nilais = Nilai::where('siswa_id', $this->siswaId)
            ->whereIn('kelas_id', \App\Models\KelasRapor::query()->select('id'))
            ->where('semester_id', $this->semesterId)
            ->with('mataPelajaran')
            ->get();
    }

    public function loadNilaiSikap()
    {
        SiswaRapor::findOrFail($this->siswaId);
        if (! $this->semesterId) {
            return;
        }

        $this->nilaiSikap = NilaiSikap::where('siswa_id', $this->siswaId)
            ->where('semester_id', $this->semesterId)
            ->first();
    }

    public function loadKehadiran()
    {
        SiswaRapor::findOrFail($this->siswaId);
        if (! $this->semesterId) {
            return;
        }

        $this->kehadiran = Kehadiran::where('siswa_id', $this->siswaId)
            ->where('semester_id', $this->semesterId)
            ->first();
    }

    public function loadCatatan()
    {
        SiswaRapor::findOrFail($this->siswaId);
        if (! $this->semesterId) {
            return;
        }

        $this->catatan = CatatanWaliKelas::where('siswa_id', $this->siswaId)
            ->where('semester_id', $this->semesterId)
            ->first();
    }

    public function print($siswaId)
    {
        $semesterId = request()->query('semesterId');
        $this->mount($siswaId, $semesterId);

        // Load rapor settings
        $settings = RaporSetting::getSettings();

        $pdf = LaravelMpdf::loadView('livewire.admin.rapor.print', [
            'siswa' => $this->siswa,
            'semester' => $this->semester,
            'kelas' => $this->kelas,
            'nilais' => $this->nilais,
            'nilaiSikap' => $this->nilaiSikap,
            'kehadiran' => $this->kehadiran,
            'catatan' => $this->catatan,
            'settings' => $settings,
        ], [], [
            'format' => [215, 330], // F4 size
            'orientation' => 'P',
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'rapor-'.$this->siswa->nama.'-'.$this->semester->nama.'.pdf');
    }

    public function render()
    {
        $this->siswa = SiswaRapor::findOrFail($this->siswaId);
        $this->kelas = \App\Models\KelasRapor::findOrFail($this->kelas->id);

        return view('livewire.admin.rapor.preview')
            ->layout('layouts.app', ['title' => 'Preview Rapor']);
    }
}
