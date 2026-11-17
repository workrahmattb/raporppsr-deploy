<?php

namespace App\Livewire\Admin\RaporSettings;

use App\Models\RaporSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    /** @var array<string, string> */
    public array $tampilanPutra = [];

    /** @var array<string, string> */
    public array $tampilanPutri = [];

    public $logoPutra;

    public $logoPutri;

    public $tanggal_rapor = '';

    public string $tanggal_rapor_putra = '';

    public $kepala_sekolah_mts = '';

    public string $kepala_sekolah_mts_putra = '';

    public $kepala_sekolah_ma = '';

    public string $kepala_sekolah_ma_putra = '';

    protected function rules(): array
    {
        $rules = [];
        foreach (auth()->user()->kategoriDiizinkan() as $kategori) {
            foreach ($this->fieldsUntuk($kategori) as $column => $property) {
                $rules[$property] = ($kategori === 'Putra' ? 'nullable' : 'required').'|string|max:255';
            }
        }
        $keys = implode(',', array_keys(RaporSetting::kolomTampilan()));
        foreach (auth()->user()->kategoriDiizinkan() as $kategori) {
            $property = 'tampilan'.$kategori;
            $rules[$property] = 'required|array:'.$keys;
            foreach (array_keys(RaporSetting::kolomTampilan()) as $key) {
                $rules[$property.'.'.$key] = 'required|string|max:500';
            }
        }
        foreach (auth()->user()->kategoriDiizinkan() as $kategori) {
            $property = 'logo'.$kategori;
            $rules[$property] = 'nullable|image|mimes:png,jpg,jpeg|max:2048|dimensions:max_width=2000,max_height=2000';
        }

        return $rules;
    }

    protected $messages = [
        'tanggal_rapor.required' => 'Tanggal rapor harus diisi',
        'kepala_sekolah_mts.required' => 'Nama Kepala Sekolah MTs harus diisi',
        'kepala_sekolah_ma.required' => 'Nama Kepala Sekolah MA harus diisi',
    ];

    public function mount(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $settings = RaporSetting::getSettings();

        foreach (auth()->user()->kategoriDiizinkan() as $kategori) {
            $this->{'tampilan'.$kategori} = $settings->tampilanUntuk($kategori);
            foreach ($this->fieldsUntuk($kategori) as $column => $property) {
                $this->{$property} = $settings->{$column} ?? '';
            }
        }
    }

    /** @return array<string, string> */
    private function fieldsUntuk(string $kategori): array
    {
        $fields = ['tanggal_rapor', 'kepala_sekolah_mts', 'kepala_sekolah_ma'];
        $suffix = $kategori === 'Putra' ? '_putra' : '';
        $result = [];
        foreach ($fields as $field) {
            $result[$field.$suffix] = $field.$suffix;
        }

        return $result;
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->validate();

        $settings = RaporSetting::getSettings();
        $data = [];
        foreach (auth()->user()->kategoriDiizinkan() as $kategori) {
            $data['tampilan_'.strtolower($kategori)] = $this->{'tampilan'.$kategori};
            foreach ($this->fieldsUntuk($kategori) as $column => $property) {
                $data[$column] = $this->{$property} ?: null;
            }
        }

        $newPaths = [];
        try {
            foreach (auth()->user()->kategoriDiizinkan() as $kategori) {
                $upload = $this->{'logo'.$kategori};
                if ($upload) {
                    $path = $upload->store('rapor-logos', 'local');
                    if (! $path) {
                        throw new \RuntimeException('Logo gagal disimpan.');
                    }
                    $newPaths[] = $path;
                    $data['logo_'.strtolower($kategori)] = $path;
                }
            }
            $settings->update($data);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($newPaths);
            throw $exception;
        }
        $this->reset('logoPutra', 'logoPutri');

        session()->flash('message', 'Pengaturan rapor berhasil disimpan.');
        session()->flash('message_type', 'success');
    }

    public function render()
    {
        $settings = RaporSetting::getSettings();

        return view('livewire.admin.rapor-settings.edit', [
            'kolomTampilan' => RaporSetting::kolomTampilan(),
            'logoPutraTersimpan' => auth()->user()->dapatMengaksesKategori('Putra') ? $settings->logoDataUntuk('Putra') : '',
            'logoPutriTersimpan' => auth()->user()->dapatMengaksesKategori('Putri') ? $settings->logoDataUntuk('Putri') : '',
        ])
            ->layout('layouts.app', ['title' => 'Pengaturan Rapor']);
    }
}
