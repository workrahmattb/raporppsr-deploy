<?php

namespace App\Livewire\Admin\MataPelajaran;

use Livewire\Component;
use App\Models\MataPelajaran;

class Create extends Component
{
    public $kode = '';
    public $nama = '';
    public $namapelajaran_arabic = '';
    public $kelompok = 'A';
    public $tingkat = '';
    public $kkm = 75;

    protected $rules = [
        'kode' => 'required|string|max:10',
        'nama' => 'required|string|max:255',
        'namapelajaran_arabic' => 'nullable|string|max:255',
        'kelompok' => 'required|in:A,B,C',
        'tingkat' => 'nullable|in:7,8,9,10,11,12',
        'kkm' => 'required|integer|min:0|max:100',
    ];

    protected $messages = [
        'kode.required' => 'Kode mata pelajaran harus diisi',
        'nama.required' => 'Nama mata pelajaran harus diisi',
        'kelompok.required' => 'Kelompok harus dipilih',
        'kkm.required' => 'KKM harus diisi',
        'kkm.min' => 'KKM minimal 0',
        'kkm.max' => 'KKM maksimal 100',
    ];

    public function save()
    {
        $this->validate();

        MataPelajaran::create([
            'sekolah_id' => auth()->user()->sekolah_id,
            'kode' => $this->kode,
            'nama' => $this->nama,
            'namapelajaran_arabic' => $this->namapelajaran_arabic,
            'kelompok' => $this->kelompok,
            'tingkat' => $this->tingkat,
            'kkm' => $this->kkm,
        ]);

        session()->flash('message', 'Mata pelajaran berhasil ditambahkan.');
        return redirect()->route('admin.mata-pelajaran.index');
    }

    public function render()
    {
        return view('livewire.admin.mata-pelajaran.create')
            ->layout('layouts.app', ['title' => 'Tambah Mata Pelajaran']);
    }
}
