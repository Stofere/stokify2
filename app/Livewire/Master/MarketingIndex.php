<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\Marketing;

class MarketingIndex extends Component
{
    public $keyword = '';
    public $nama, $telepon, $alamat;
    public $edit_id = null;
    public $form_open = false;

    public function simpan()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
        ]);

        if ($this->edit_id) {
            Marketing::find($this->edit_id)->update([
                'nama' => $this->nama,
                'telepon' => $this->telepon,
                'alamat' => $this->alamat,
            ]);
            session()->flash('sukses', 'Data marketing berhasil diubah.');
        } else {
            Marketing::create([
                'nama' => $this->nama,
                'telepon' => $this->telepon,
                'alamat' => $this->alamat,
            ]);
            session()->flash('sukses', 'Marketing baru berhasil ditambahkan.');
        }
        $this->resetForm();
    }

    public function edit($id)
    {
        $mkt = Marketing::find($id);
        $this->edit_id = $mkt->id_marketing;
        $this->nama = $mkt->nama;
        $this->telepon = $mkt->telepon;
        $this->alamat = $mkt->alamat;
        $this->form_open = true;
    }

    public function toggleAktif($id)
    {
        $mkt = Marketing::find($id);
        $mkt->update(['aktif' => !$mkt->aktif]);
    }

    public function resetForm()
    {
        $this->reset(['nama', 'telepon', 'alamat', 'edit_id']);
        $this->form_open = false;
    }

    public function render()
    {
        $data = Marketing::where('nama', 'like', "%{$this->keyword}%")
            ->orderBy('aktif', 'desc')
            ->latest()
            ->get();

        return view('livewire.master.marketing-index', ['daftarMarketing' => $data]);
    }
}