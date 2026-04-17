<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\Atribut;

class AtributIndex extends Component
{
    public $nama_atribut = '';
    public $pilihan_opsi = []; // Array untuk menampung JSON
    public $opsi_baru = '';
    
    public $edit_id = null;
    public $form_open = false;

    public function tambahOpsi()
    {
        $this->validate(['opsi_baru' => 'required|string|max:100']);
        if (!in_array($this->opsi_baru, $this->pilihan_opsi)) {
            $this->pilihan_opsi[] = $this->opsi_baru;
        }
        $this->opsi_baru = '';
    }

    public function hapusOpsi($index)
    {
        unset($this->pilihan_opsi[$index]);
        $this->pilihan_opsi = array_values($this->pilihan_opsi);
    }

    public function simpan()
    {
        $this->validate([
            'nama_atribut' => 'required|string|max:255',
            'pilihan_opsi' => 'required|array|min:1',
        ]);

        if ($this->edit_id) {
            Atribut::find($this->edit_id)->update([
                'nama_atribut' => $this->nama_atribut,
                'pilihan_opsi' => $this->pilihan_opsi,
            ]);
            session()->flash('sukses', 'Atribut berhasil diubah.');
        } else {
            Atribut::create([
                'nama_atribut' => $this->nama_atribut,
                'pilihan_opsi' => $this->pilihan_opsi,
            ]);
            session()->flash('sukses', 'Atribut baru berhasil dibuat.');
        }

        $this->resetForm();
    }

    public function edit($id)
    {
        $attr = Atribut::find($id);
        $this->edit_id = $attr->id_atribut;
        $this->nama_atribut = $attr->nama_atribut;
        $this->pilihan_opsi = $attr->pilihan_opsi ?? [];
        $this->form_open = true;
    }

    public function resetForm()
    {
        $this->reset(['nama_atribut', 'pilihan_opsi', 'opsi_baru', 'edit_id']);
        $this->form_open = false;
    }

    public function render()
    {
        return view('livewire.master.atribut-index', [
            'daftarAtribut' => Atribut::latest()->get()
        ]);
    }
}