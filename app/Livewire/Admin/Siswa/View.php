<?php

namespace App\Livewire\Admin\Siswa;

use App\Models\SiswaRapor;
use Livewire\Component;

class View extends Component
{
    public $siswa;

    public function mount($id)
    {
        $this->siswa = SiswaRapor::findOrFail($id);
    }

    public function render()
    {
        $this->siswa = SiswaRapor::findOrFail($this->siswa->id);

        return view('livewire.admin.siswa.view')
            ->layout('layouts.app', ['title' => 'Detail Siswa']);
    }
}
