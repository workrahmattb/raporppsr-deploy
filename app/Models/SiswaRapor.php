<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SiswaRapor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'siswas_rapor';

    protected $fillable = [
        'sekolah_id',
        'kategori',
        'nisn',
        'nis',
        'nama',
        'nama_arabic',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'telepon',
        'email',
        'nama_ayah',
        'nama_ibu',
        'telepon_ortu',
        'tanggal_masuk',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('kategori_admin', function (Builder $query): void {
            $user = auth()->user();
            if ($user?->role === 'admin') {
                $query->where('siswas_rapor.sekolah_id', $user->sekolah_id)
                    ->whereIn('siswas_rapor.kategori', $user->kategoriDiizinkan());
            }
        });

        static::saving(function (SiswaRapor $model): void {
            $user = auth()->user();
            if ($user?->role === 'admin') {
                abort_unless($user->dapatMengaksesKategori((string) $model->kategori), 403);
                if ($model->exists) {
                    abort_unless($user->dapatMengaksesKategori((string) $model->getRawOriginal('kategori')), 403);
                }
                abort_unless($model->sekolah_id == $user->sekolah_id, 403);
            }
            if ($model->exists && $model->isDirty('kategori') && DB::table('kelas_siswa')
                ->join('kelas_rapor', 'kelas_siswa.kelas_id', '=', 'kelas_rapor.id')
                ->where('kelas_siswa.siswa_id', $model->id)
                ->where('kelas_rapor.kategori', '!=', $model->kategori)->exists()) {
                throw ValidationException::withMessages(['kategori' => 'Bagian siswa dan kelas harus sama. Sesuaikan keanggotaan kelas terlebih dahulu.']);
            }
        });
    }

    // Relationships
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function kelas()
    {
        return $this->belongsToMany(KelasRapor::class, 'kelas_siswa', 'siswa_id', 'kelas_id')
            ->withPivot('nomor_absen')
            ->withTimestamps();
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }

    public function nilaiSikaps()
    {
        return $this->hasMany(NilaiSikap::class, 'siswa_id');
    }

    public function kehadirans()
    {
        return $this->hasMany(Kehadiran::class, 'siswa_id');
    }

    public function catatanWaliKelas()
    {
        return $this->hasMany(CatatanWaliKelas::class, 'siswa_id');
    }

    public function kenaikanKelas()
    {
        return $this->hasMany(KenaikanKelas::class, 'siswa_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Aktif');
    }

    // Accessors
    public function getNamaLengkapAttribute()
    {
        return $this->nama.' ('.$this->nisn.')';
    }
}
