<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukIndex extends Component
{

    use WithPagination;
    
    public $keyword = '';
    public $form_open = false;
    public $edit_id = null;

    // Data Master
    public $daftarKategori = [];
    public $atributDinamis = []; // Menampung atribut apa saja yang wajib diisi berdasarkan kategori

    // Field Form
    public $id_kategori = '';
    public $kode_barang = '';
    public $nama_produk = '';
    public $satuan = 'pcs';
    public $harga_jual_satuan = 0;
    public $lacak_stok = true;
    
    // Field JSON
    public $metadata_input = []; // Format: ['Merk' => 'CN', 'Ring' => '3']

    public function mount()
    {
        $this->daftarKategori = Kategori::orderBy('nama_kategori')->get();
    }

    // MAGIC HOOK LIVEWIRE: Otomatis terpanggil saat dropdown Kategori berubah
    public function updatedIdKategori($value)
    {
        $this->metadata_input = []; // Reset inputan JSON
        $this->atributDinamis = [];

        if ($value) {
            $kategori = Kategori::with('atribut')->find($value);
            if ($kategori) {
                $this->atributDinamis = $kategori->atribut;
                // Inisialisasi key array untuk wire:model
                foreach ($this->atributDinamis as $attr) {
                    $this->metadata_input[$attr->nama_atribut] = '';
                }
            }
        }
    }

    public function simpan()
    {
        // 1. Validasi Dasar
        $this->validate([
            'id_kategori' => 'required',
            'kode_barang' => 'required|unique:produk,kode_barang,' . $this->edit_id . ',id_produk',
            'nama_produk' => 'required|string|max:255',
            'satuan' => 'required|string',
            'harga_jual_satuan' => 'required|numeric|min:0',
            'lacak_stok' => 'boolean',
        ]);

        // 2. Validasi Atribut Dinamis (Pastikan semua spesifikasi terpilih)
        foreach ($this->atributDinamis as $attr) {
            if (empty($this->metadata_input[$attr->nama_atribut])) {
                $this->addError('metadata_input.' . $attr->nama_atribut, "Atribut {$attr->nama_atribut} belum dipilih!");
                return;
            }
        }

        // 3. Gabungkan teks untuk mesin pencari POS (Fulltext Search Index)
        $metaString = !empty($this->metadata_input) ? implode(' ', array_values($this->metadata_input)) : '';
        $indexPencarian = strtolower($this->kode_barang . ' ' . $this->nama_produk . ' ' . $metaString);

        if ($this->edit_id) {
            // EDIT PRODUK (Stok tidak ikut diupdate karena dikunci sesuai PRD)
            Produk::find($this->edit_id)->update([
                'id_kategori' => $this->id_kategori,
                'kode_barang' => $this->kode_barang,
                'nama_produk' => $this->nama_produk,
                'satuan' => $this->satuan,
                'harga_jual_satuan' => $this->harga_jual_satuan,
                'lacak_stok' => $this->lacak_stok,
                'metadata' => empty($this->metadata_input) ? null : $this->metadata_input,
                'index_pencarian' => $indexPencarian,
            ]);
            session()->flash('sukses', 'Data produk berhasil diubah.');
        } else {
            // TAMBAH PRODUK BARU
            Produk::create([
                'id_kategori' => $this->id_kategori,
                'kode_barang' => $this->kode_barang,
                'nama_produk' => $this->nama_produk,
                'satuan' => $this->satuan,
                'harga_jual_satuan' => $this->harga_jual_satuan,
                'lacak_stok' => $this->lacak_stok,
                'stok_saat_ini' => 0, // Stok awal wajib 0, diisi lewat menu Adjust Stok!
                'metadata' => empty($this->metadata_input) ? null : $this->metadata_input,
                'index_pencarian' => $indexPencarian,
            ]);
            session()->flash('sukses', 'Barang baru berhasil ditambahkan! Stok awal adalah 0. Masuk ke menu Adjust Stok untuk mengisi jumlah fisiknya.');
        }

        $this->resetForm();
    }

    public function edit($id)
    {
        $produk = Produk::find($id);
        $this->edit_id = $produk->id_produk;
        $this->id_kategori = $produk->id_kategori;
        $this->kode_barang = $produk->kode_barang;
        $this->nama_produk = $produk->nama_produk;
        $this->satuan = $produk->satuan;
        $this->harga_jual_satuan = $produk->harga_jual_satuan;
        $this->lacak_stok = $produk->lacak_stok;
        $this->metadata_input = $produk->metadata ?? [];
        
        // Pancing hook kategori untuk meload UI Atribut
        $this->updatedIdKategori($this->id_kategori);
        
        // Tumpuk ulang nilai metadata setelah hook berjalan
        $this->metadata_input = $produk->metadata ?? [];

        $this->form_open = true;
    }

    public function toggleAktif($id)
    {
        $produk = Produk::find($id);
        $produk->update(['status_aktif' => !$produk->status_aktif]);
    }

    public function resetForm()
    {
        $this->reset(['edit_id', 'id_kategori', 'kode_barang', 'nama_produk', 'satuan', 'harga_jual_satuan', 'metadata_input', 'atributDinamis']);
        $this->lacak_stok = true;
        $this->form_open = false;
        $this->resetValidation();
    }

    // Reset halaman jika user mengetik pencarian baru
    public function updatingKeyword()
    {
        $this->resetPage();
    }


    public function render()
    {
        $query = Produk::with('kategori')->orderBy('status_aktif', 'desc')->latest();
        
        // FIX: Algoritma Pencarian Split-LIKE agar FR, CN, (2) bisa terdeteksi
        if (!empty(trim($this->keyword))) {
            $terms = explode(' ', trim(strtolower($this->keyword)));
            foreach ($terms as $term) {
                $query->where('index_pencarian', 'LIKE', '%' . $term . '%');
            }
        }

        return view('livewire.master.produk-index', [
            'daftarProduk' => $query->paginate(15)
        ]);
    }
}