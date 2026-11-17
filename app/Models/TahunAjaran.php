<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'sekolah_id',
        'tahun',
        'is_active',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('sekolah_admin', function (Builder $query): void {
            $user = auth()->user();
            if ($user?->role === 'admin') {
                $query->where('sekolah_id', $user->sekolah_id);
            }
        });
    }

    // Relationships
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function kelas()
    {
        return $this->hasMany(KelasRapor::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
