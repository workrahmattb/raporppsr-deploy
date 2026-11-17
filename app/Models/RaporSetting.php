<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RaporSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tampilan_putra',
        'tampilan_putri',
        'logo_putra',
        'logo_putri',
        'tanggal_rapor_putra',
        'kepala_sekolah_mts_putra',
        'kepala_sekolah_ma_putra',
        'tanggal_rapor',
        'kepala_sekolah_mts',
        'kepala_sekolah_ma',
    ];

    protected function casts(): array
    {
        return ['tampilan_putra' => 'array', 'tampilan_putri' => 'array'];
    }

    /** @return array<string, array{label: string, default: string}> */
    public static function kolomTampilan(): array
    {
        return [
            'judul' => ['label' => 'Judul rapor', 'default' => 'كشف الدرجات'],
            'subjudul' => ['label' => 'Subjudul / nama pondok', 'default' => 'لطلبة معهد شفاعة الرسول الإسلامي'],
        ];
    }

    /** @return array<string, string> */
    public function tampilanUntuk(string $kategori): array
    {
        $defaults = array_map(fn (array $field): string => $field['default'], self::kolomTampilan());
        $saved = $kategori === 'Putra' ? $this->tampilan_putra : $this->tampilan_putri;

        return array_replace($defaults, array_intersect_key($saved ?? [], $defaults));
    }

    public function logoDataUntuk(string $kategori): string
    {
        $path = $kategori === 'Putra' ? $this->logo_putra : $this->logo_putri;
        $disk = Storage::disk('local');
        $bytes = $path && str_starts_with($path, 'rapor-logos/') && $disk->exists($path)
            ? $disk->get($path)
            : file_get_contents(public_path('img/logo-ppsr.png'));
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->buffer($bytes);

        if (! in_array($mime, ['image/png', 'image/jpeg'], true)) {
            $bytes = file_get_contents(public_path('img/logo-ppsr.png'));
            $mime = 'image/png';
        }

        return 'data:'.$mime.';base64,'.base64_encode($bytes);
    }

    public function tanggalRaporUntuk(string $kategori): ?string
    {
        return $kategori === 'Putra' ? $this->tanggal_rapor_putra : $this->tanggal_rapor;
    }

    public function kepalaSekolahUntuk(string $kategori, string $tingkat): ?string
    {
        $jenjang = in_array($tingkat, ['7', '8', '9', 'VII', 'VIII', 'IX'], true) ? 'mts' : 'ma';
        $kolom = 'kepala_sekolah_'.$jenjang.($kategori === 'Putra' ? '_putra' : '');

        return $this->{$kolom};
    }

    /**
     * Get the single settings record (singleton pattern)
     */
    public static function getSettings()
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'tanggal_rapor' => '٢ محرم ١٤٤٨, تلوك كوانتن',
                'kepala_sekolah_mts' => 'S.Pd مارديه روسنيله نينغسيه',
                'kepala_sekolah_ma' => 'Dina Yulesti, M.Pd',
            ]
        );
    }
}
