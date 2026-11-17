<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KelasRapor extends Model
{
    use HasFactory;

    protected $table = 'kelas_rapor';

    protected $fillable = [
        'tahun_ajaran_id',
        'wali_kelas_id',
        'nama',
        'tingkat',
        'kategori',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('kategori_admin', function (Builder $query): void {
            $user = auth()->user();
            if ($user?->role === 'admin') {
                $query->whereIn('kelas_rapor.kategori', $user->kategoriDiizinkan())
                    ->whereHas('tahunAjaran', fn (Builder $year) => $year->where('sekolah_id', $user->sekolah_id));
            }
        });

        static::saving(function (KelasRapor $model): void {
            $user = auth()->user();
            if ($user?->role === 'admin') {
                abort_unless($user->dapatMengaksesKategori((string) $model->kategori), 403);
                if ($model->exists) {
                    abort_unless($user->dapatMengaksesKategori((string) $model->getRawOriginal('kategori')), 403);
                }
                abort_unless(TahunAjaran::where('sekolah_id', $user->sekolah_id)->whereKey($model->tahun_ajaran_id)->exists(), 403);
            }
            if ($model->exists && $model->isDirty('kategori') && DB::table('kelas_siswa')
                ->join('siswas_rapor', 'kelas_siswa.siswa_id', '=', 'siswas_rapor.id')
                ->where('kelas_siswa.kelas_id', $model->id)
                ->where('siswas_rapor.kategori', '!=', $model->kategori)->exists()) {
                throw ValidationException::withMessages(['kategori' => 'Bagian siswa dan kelas harus sama. Sesuaikan keanggotaan kelas terlebih dahulu.']);
            }
        });
    }

    // Relationships
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(GuruRapor::class, 'wali_kelas_id');
    }

    public function siswas()
    {
        return $this->belongsToMany(SiswaRapor::class, 'kelas_siswa', 'kelas_id', 'siswa_id')
            ->withPivot('nomor_absen')
            ->withTimestamps();
    }

    public function guruMataPelajaran()
    {
        return $this->hasMany(GuruMataPelajaran::class, 'tingkat', 'tingkat');
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'kelas_id');
    }

    // Accessors
    public function getNamaLengkapAttribute()
    {
        return $this->nama.' - '.$this->tahunAjaran->tahun;
    }
}
