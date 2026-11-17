<?php

namespace App\Livewire\Admin\Guru;

use App\Models\GuruRapor;
use Illuminate\View\View as BladeView;
use Livewire\Component;

class View extends Component
{
    public GuruRapor $guru;

    public function mount(int $id): void
    {
        $this->guru = GuruRapor::with('user')
            ->where('sekolah_id', auth()->user()->sekolah_id)
            ->findOrFail($id);
    }

    public function render(): BladeView
    {
        return view('livewire.admin.guru.view')
            ->layout('layouts.app', ['title' => 'Detail Guru']);
    }
}
