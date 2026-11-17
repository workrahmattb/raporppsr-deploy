<?php

namespace App\Livewire\Admin\PenugasanGuru;

use App\Models\GuruRapor;
use App\Models\MataPelajaran;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $assignmentId;

    public $guru_id = '';

    public $mata_pelajaran_id = '';

    public $tingkat = '';

    public string $kategori = 'Putri';

    protected $rules = [
        'kategori' => 'required|in:Putra,Putri',
        'guru_id' => 'required|exists:gurus_rapor,id',
        'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
        'tingkat' => 'required|in:VII,VIII,IX,X,XI,XII',
    ];

    protected $messages = [
        'kategori.required' => 'Pilih kategori kelas',
        'kategori.in' => 'Kategori kelas harus Putra atau Putri',
        'guru_id.required' => 'Guru harus dipilih',
        'mata_pelajaran_id.required' => 'Mata pelajaran harus dipilih',
        'tingkat.required' => 'Tingkat harus dipilih',
        'tingkat.in' => 'Pilih tingkat yang valid (VII, VIII, IX, X, XI, atau XII)',
    ];

    public function mount($id)
    {
        $assignment = DB::table('guru_mata_pelajaran')->where('id', $id)->first();

        if (! $assignment) {
            abort(404);
        }

        abort_unless(auth()->user()->dapatMengaksesKategori($assignment->kategori), 403);
        abort_unless(GuruRapor::where('sekolah_id', auth()->user()->sekolah_id)->whereKey($assignment->guru_id)->exists(), 403);
        $this->assignmentId = $id;
        $this->guru_id = $assignment->guru_id;
        $this->mata_pelajaran_id = $assignment->mata_pelajaran_id;
        $this->tingkat = $assignment->tingkat;
        $this->kategori = $assignment->kategori;
    }

    public function save()
    {
        abort_unless(auth()->user()->dapatMengaksesKategori($this->kategori), 403);
        $existing = DB::table('guru_mata_pelajaran')->find($this->assignmentId);
        abort_unless($existing && auth()->user()->dapatMengaksesKategori($existing->kategori), 403);
        $this->validate();
        abort_unless(GuruRapor::where('sekolah_id', auth()->user()->sekolah_id)->whereKey($this->guru_id)->exists(), 403);
        abort_unless(MataPelajaran::where('sekolah_id', auth()->user()->sekolah_id)->whereKey($this->mata_pelajaran_id)->exists(), 403);

        // Cek apakah kombinasi yang sama sudah ada (selain record ini sendiri)
        $exists = DB::table('guru_mata_pelajaran')
            ->where('guru_id', $this->guru_id)
            ->where('mata_pelajaran_id', $this->mata_pelajaran_id)
            ->where('tingkat', $this->tingkat)
            ->where('kategori', $this->kategori)
            ->where('id', '!=', $this->assignmentId)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Penugasan dengan kombinasi yang sama sudah ada!');

            return;
        }

        DB::table('guru_mata_pelajaran')
            ->where('id', $this->assignmentId)
            ->update([
                'guru_id' => $this->guru_id,
                'mata_pelajaran_id' => $this->mata_pelajaran_id,
                'tingkat' => $this->tingkat,
                'kategori' => $this->kategori,
                'updated_at' => now(),
            ]);

        session()->flash('message', 'Penugasan guru berhasil diperbarui.');

        return redirect()->route('admin.penugasan-guru.index');
    }

    public function render()
    {
        $gurus = GuruRapor::where('sekolah_id', auth()->user()->sekolah_id)
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get();

        $mataPelajarans = MataPelajaran::where('sekolah_id', auth()->user()->sekolah_id)
            ->orderBy('nama')
            ->get();

        $tingkats = ['VII' => 'VII', 'VIII' => 'VIII', 'IX' => 'IX', 'X' => 'X', 'XI' => 'XI', 'XII' => 'XII'];

        $tahunAjaran = TahunAjaran::where('sekolah_id', auth()->user()->sekolah_id)
            ->where('is_active', true)
            ->first();

        $semester = Semester::where('is_active', true)->first();

        return view('livewire.admin.penugasan-guru.edit', [
            'gurus' => $gurus,
            'mataPelajarans' => $mataPelajarans,
            'tingkats' => $tingkats,
            'tahunAjaran' => $tahunAjaran,
            'semester' => $semester,
        ])->layout('layouts.app', ['title' => 'Edit Penugasan Guru']);
    }
}
