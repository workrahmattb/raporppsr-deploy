<?php

namespace App\Livewire\WaliKelas\Rapor;

use App\Models\CatatanWaliKelas;
use App\Models\KelasRapor;
use App\Models\Semester;
use App\Models\SiswaRapor;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $semesterId;

    public $kelas;

    public $semester;

    /** @var array<int, string> */
    public array $namaArabic = [];

    public ?int $namaArabicSavedId = null;

    public $catatan = [];

    protected function kelasWali(): ?KelasRapor
    {
        return KelasRapor::whereHas('waliKelas', function ($query): void {
            $query->where('user_id', auth()->id());
        })->whereHas('tahunAjaran', function ($query): void {
            $query->where('sekolah_id', auth()->user()->sekolah_id);
        })->with('tahunAjaran')->first();
    }

    protected function siswaWali(int $siswaId): SiswaRapor
    {
        $kelas = $this->kelasWali();
        abort_unless($kelas, 403);

        return SiswaRapor::where('sekolah_id', auth()->user()->sekolah_id)
            ->where('kategori', $kelas->kategori)
            ->whereHas('kelas', fn ($query) => $query->where('kelas_rapor.id', $kelas->id))
            ->findOrFail($siswaId);
    }

    public function mount(): void
    {
        $this->kelas = $this->kelasWali();
        if ($this->kelas) {
            $this->semester = Semester::where('tahun_ajaran_id', $this->kelas->tahun_ajaran_id)
                ->where('is_active', true)->first();
            $this->semesterId = $this->semester?->id;
        }
    }

    public function updatedSemesterId(): void
    {
        $this->resetPage();
        $this->catatan = [];
        $this->namaArabic = [];
        $this->namaArabicSavedId = null;
    }

    public function simpanNamaArabic(int $siswaId): void
    {
        $siswa = $this->siswaWali($siswaId);
        $this->validate(['namaArabic.'.$siswaId => 'nullable|string|max:255']);
        $nama = trim($this->namaArabic[$siswaId] ?? '');
        $siswa->update(['nama_arabic' => $nama !== '' ? $nama : null]);
        $this->namaArabic[$siswaId] = $nama;
        $this->namaArabicSavedId = $siswaId;
    }

    public function updatedCatatan(?string $value, string $siswaId): void
    {
        $siswa = $this->siswaWali((int) $siswaId);
        $kelas = $this->kelasWali();
        $semester = Semester::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)->findOrFail($this->semesterId);
        $this->validate(['catatan.'.$siswaId => 'nullable|string|max:1000']);

        CatatanWaliKelas::updateOrCreate(
            ['siswa_id' => $siswa->id, 'semester_id' => $semester->id],
            ['wali_kelas_id' => $kelas->wali_kelas_id, 'catatan' => $value]
        );
        $this->dispatch('saved-catatan');
    }

    public function printAllByClass()
    {
        // Get semesterId from query string
        $semesterId = request()->query('semesterId');

        // Load kelas if not already loaded
        $kelas = KelasRapor::whereHas('waliKelas', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('tahunAjaran')->first();

        if (! $kelas || ! $semesterId) {
            abort(400, 'Pilih kelas dan semester terlebih dahulu');
        }

        // Verify this user is indeed the wali kelas for this class
        $guru = \App\Models\GuruRapor::where('user_id', auth()->id())->first();
        if (! $guru || $kelas->wali_kelas_id != $guru->id) {
            abort(403, 'Anda bukan wali kelas kelas ini');
        }

        // Get all students in the selected class
        $siswas = \DB::table('kelas_siswa')
            ->join('siswas_rapor', 'kelas_siswa.siswa_id', '=', 'siswas_rapor.id')
            ->where('kelas_siswa.kelas_id', $kelas->id)
            ->orderBy('kelas_siswa.nomor_absen')
            ->get();

        if ($siswas->isEmpty()) {
            abort(404, 'Tidak ada siswa di kelas ini');
        }

        $semester = Semester::with('tahunAjaran')->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)->findOrFail($semesterId);
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

        // Generate PDF with all students - use admin's print-all view
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

    public function render(): View
    {
        $this->kelas = $this->kelasWali();
        $semesters = Semester::where('tahun_ajaran_id', $this->kelas?->tahun_ajaran_id)
            ->orderBy('nama')->get();
        if ($this->kelas && $this->semesterId) {
            $this->semester = Semester::where('tahun_ajaran_id', $this->kelas->tahun_ajaran_id)
                ->findOrFail($this->semesterId);
        }

        $siswas = SiswaRapor::query()
            ->join('kelas_siswa', 'siswas_rapor.id', '=', 'kelas_siswa.siswa_id')
            ->where('kelas_siswa.kelas_id', $this->kelas?->id)
            ->when(! $this->semesterId, fn ($query) => $query->whereRaw('1 = 0'))
            ->select('siswas_rapor.*', 'kelas_siswa.nomor_absen')
            ->orderBy('kelas_siswa.nomor_absen')->orderBy('siswas_rapor.nama')->orderBy('siswas_rapor.id')
            ->paginate(10);

        $catatans = CatatanWaliKelas::where('semester_id', $this->semesterId)
            ->whereIn('siswa_id', $siswas->pluck('id'))->pluck('catatan', 'siswa_id');
        foreach ($siswas as $siswa) {
            if (! array_key_exists($siswa->id, $this->catatan)) {
                $this->catatan[$siswa->id] = $catatans->get($siswa->id) ?? '';
            }
            if (! array_key_exists($siswa->id, $this->namaArabic)) {
                $this->namaArabic[$siswa->id] = $siswa->nama_arabic ?? '';
            }
        }

        return view('livewire.wali-kelas.rapor.index', [
            'semesters' => $semesters,
            'siswas' => $siswas,
        ])->layout('layouts.app', ['title' => 'Cetak Rapor']);
    }
}
