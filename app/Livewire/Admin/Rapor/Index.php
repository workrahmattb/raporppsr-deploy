<?php

namespace App\Livewire\Admin\Rapor;

use App\Models\KelasRapor;
use App\Models\Semester;
use App\Models\TahunAjaran;
use DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $tahunAjaranId;

    public $kelasId;

    public $semesterId;

    public $search = '';

    public function mount(): void
    {
        // Get active semester by default
        $activeSemester = Semester::where('is_active', true)->first();
        $this->semesterId = $activeSemester?->id;
        $this->tahunAjaranId = $activeSemester?->tahun_ajaran_id;
    }

    public function updatedTahunAjaranId(): void
    {
        $this->kelasId = null;
        $this->semesterId = null;
        $this->resetPage();
    }

    public function updatedKelasId()
    {
        $this->resetPage();
    }

    public function updatedSemesterId()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function printAllByClass()
    {
        $kelasId = request()->query('kelasId');
        $semesterId = request()->query('semesterId');

        if (! $kelasId || ! $semesterId) {
            abort(400, 'Pilih kelas dan semester terlebih dahulu');
        }

        $kelas = KelasRapor::with('waliKelas')->findOrFail($kelasId);

        // Get all students in the selected class
        $siswas = DB::table('kelas_siswa')
            ->whereIn('kelas_siswa.kelas_id', KelasRapor::query()->select('id'))
            ->whereIn('kelas_siswa.siswa_id', \App\Models\SiswaRapor::query()->select('id'))
            ->join('siswas_rapor', 'kelas_siswa.siswa_id', '=', 'siswas_rapor.id')
            ->where('kelas_siswa.kelas_id', $kelasId)
            ->orderBy('kelas_siswa.nomor_absen')
            ->get();

        if ($siswas->isEmpty()) {
            abort(404, 'Tidak ada siswa di kelas ini');
        }

        // Get kelas info

        $semester = Semester::with('tahunAjaran')->find($semesterId);
        $settings = \App\Models\RaporSetting::getSettings();

        // Prepare data for all students
        $allRaporData = [];
        foreach ($siswas as $siswa) {
            $nilais = \App\Models\Nilai::where('siswa_id', $siswa->id)
                ->where('semester_id', $semesterId)
                ->with('mataPelajaran')
                ->get();

            $nilaiSikap = \App\Models\NilaiSikap::where('siswa_id', $siswa->id)
                ->where('semester_id', $semesterId)
                ->first();

            $kehadiran = \App\Models\Kehadiran::where('siswa_id', $siswa->id)
                ->where('semester_id', $semesterId)
                ->first();

            $catatan = \App\Models\CatatanWaliKelas::where('siswa_id', $siswa->id)
                ->where('semester_id', $semesterId)
                ->first();

            $allRaporData[] = [
                'siswa' => $siswa,
                'nilais' => $nilais,
                'nilaiSikap' => $nilaiSikap,
                'kehadiran' => $kehadiran,
                'catatan' => $catatan,
            ];
        }

        // Generate PDF with all students
        $pdf = \Mccarlosen\LaravelMpdf\Facades\LaravelMpdf::loadView('livewire.admin.rapor.print-all', [
            'allRaporData' => $allRaporData,
            'kelas' => $kelas,
            'semester' => $semester,
            'settings' => $settings,
        ], [], [
            'format' => [215, 330], // F4 size
            'orientation' => 'P',
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'rapor-'.$kelas->nama.'-'.$semester->nama.'.pdf');
    }

    public function render()
    {
        $tahunAjarans = TahunAjaran::orderBy('tanggal_mulai', 'desc')->get();
        $semesters = Semester::where('tahun_ajaran_id', $this->tahunAjaranId)->orderBy('nama')->get();

        // Get kelas based on selected tahun ajaran
        $kelas = KelasRapor::query()
            ->when($this->tahunAjaranId, function ($query) {
                $query->where('tahun_ajaran_id', $this->tahunAjaranId);
            })
            ->orderBy('nama')
            ->get();

        // Get paginated students
        $siswas = DB::table('kelas_siswa')
            ->whereIn('kelas_siswa.kelas_id', KelasRapor::query()->select('id'))
            ->whereIn('kelas_siswa.siswa_id', \App\Models\SiswaRapor::query()->select('id'))
            ->join('siswas_rapor', 'kelas_siswa.siswa_id', '=', 'siswas_rapor.id')
            ->join('kelas_rapor', 'kelas_siswa.kelas_id', '=', 'kelas_rapor.id')
            ->select(
                'siswas_rapor.*',
                'kelas_siswa.nomor_absen',
                'kelas_siswa.kelas_id',
                'kelas_rapor.nama as kelas_nama',
                'kelas_rapor.tingkat as kelas_tingkat'
            )
            ->when($this->kelasId, function ($query) {
                $query->where('kelas_siswa.kelas_id', $this->kelasId);
            })
            ->when($this->tahunAjaranId, function ($query) {
                $query->where('kelas_rapor.tahun_ajaran_id', $this->tahunAjaranId);
            })
            ->when($this->search, function ($query) {
                $query->where('siswas_rapor.nama', 'like', '%'.$this->search.'%');
            })
            ->orderBy('kelas_rapor.nama')
            ->orderBy('kelas_siswa.nomor_absen')
            ->paginate(10);

        return view('livewire.admin.rapor.index', [
            'tahunAjarans' => $tahunAjarans,
            'kelas' => $kelas,
            'semesters' => $semesters,
            'siswas' => $siswas,
        ])->layout('layouts.app', ['title' => 'Cetak Rapor']);
    }
}
