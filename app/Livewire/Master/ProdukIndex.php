<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\RiwayatStok;
use App\Services\StockService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProdukIndex extends Component
{
    use WithPagination;

    public $keyword = '';
    public $form_open = false;
    public $edit_id = null;

    // Data Master
    public $daftarKategori = [];
    public $atributDinamis = [];

    // Field Form Produk
    public $id_kategori = '';
    public $kode_barang = '';
    public $nama_produk = '';
    public $satuan = 'pcs';
    public $harga_jual_satuan = 0;
    public $lacak_stok = true;
    public $metadata_input = [];

    // --- STATE UNTUK MODAL STOK (ADJUST & RIWAYAT) ---
    public $stok_modal_open = false;
    public $produk_stok_aktif = null;
    public $riwayat_stok = [];
    // Form Adjust Stok
    public $tipe_penyesuaian = 'KOREKSI_MINUS';
    public $jumlah_adjust = 0;
    public $keterangan_adjust = '';
    public $password_admin = '';

    public function mount()
    {
        $this->daftarKategori = Kategori::orderBy('nama_kategori')->get();
    }

    public function updatedIdKategori($value)
    {
        $this->metadata_input = [];
        $this->atributDinamis = [];

        if ($value) {
            $kategori = Kategori::with('atribut')->find($value);
            if ($kategori) {
                $this->atributDinamis = $kategori->atribut;
                foreach ($this->atributDinamis as $attr) {
                    $this->metadata_input[$attr->nama_atribut] = ''; // Inisialisasi awal
                }
            }
        }
    }

    public function simpan()
    {
        $this->validate([
            'id_kategori' => 'required',
            'kode_barang' => 'required|unique:produk,kode_barang,' . $this->edit_id . ',id_produk',
            'nama_produk' => 'required|string|max:255',
            'satuan' => 'required|string',
            'harga_jual_satuan' => 'required|numeric|min:0',
            'lacak_stok' => 'boolean',
        ]);

        // FIX: Atribut dinamis dikumpulkan, yang kosong (Null) diabaikan/dibuang. (Tidak lagi required)
        $metadataFinal = [];
        foreach ($this->atributDinamis as $attr) {
            if (!empty($this->metadata_input[$attr->nama_atribut])) {
                $metadataFinal[$attr->nama_atribut] = $this->metadata_input[$attr->nama_atribut];
            }
        }

        $metaString = !empty($metadataFinal) ? implode(' ', array_values($metadataFinal)) : '';
        $indexPencarian = strtolower($this->kode_barang . ' ' . $this->nama_produk . ' ' . $metaString);

        if ($this->edit_id) {
            Produk::find($this->edit_id)->update([
                'id_kategori' => $this->id_kategori,
                'kode_barang' => $this->kode_barang,
                'nama_produk' => $this->nama_produk,
                'satuan' => $this->satuan,
                'harga_jual_satuan' => $this->harga_jual_satuan,
                'lacak_stok' => $this->lacak_stok,
                'metadata' => empty($metadataFinal) ? null : $metadataFinal,
                'index_pencarian' => $indexPencarian,
            ]);
            session()->flash('sukses', 'Data barang berhasil diperbarui.');
        } else {
            Produk::create([
                'id_kategori' => $this->id_kategori,
                'kode_barang' => $this->kode_barang,
                'nama_produk' => $this->nama_produk,
                'satuan' => $this->satuan,
                'harga_jual_satuan' => $this->harga_jual_satuan,
                'lacak_stok' => $this->lacak_stok,
                'stok_saat_ini' => 0,
                'metadata' => empty($metadataFinal) ? null : $metadataFinal,
                'index_pencarian' => $indexPencarian,
            ]);
            session()->flash('sukses', 'Barang baru berhasil ditambahkan! Stok awal adalah 0.');
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
        
        $this->updatedIdKategori($this->id_kategori);
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

    public function updatingKeyword()
    {
        $this->resetPage();
    }

    // =========================================================================
    // FITUR BARU: MANAJEMEN STOK (RIWAYAT & ADJUST) DI DALAM KATALOG PRODUK
    // =========================================================================

    public function bukaModalStok($id_produk)
    {
        $this->produk_stok_aktif = Produk::find($id_produk);
        $this->loadRiwayatStok();
        $this->stok_modal_open = true;
        $this->resetFormAdjust();
    }

    public function tutupModalStok()
    {
        $this->stok_modal_open = false;
        $this->produk_stok_aktif = null;
        $this->resetFormAdjust();
    }

    private function loadRiwayatStok()
    {
        if ($this->produk_stok_aktif) {
            $this->riwayat_stok = RiwayatStok::with(['user', 'transaksiPenjualan', 'transaksiRetur'])
                ->where('id_produk', $this->produk_stok_aktif->id_produk)
                ->orderBy('created_at', 'desc')
                ->limit(50) // Tampilkan 50 riwayat terakhir agar tidak lemot
                ->get();
        }
    }

    private function resetFormAdjust()
    {
        $this->reset(['tipe_penyesuaian', 'jumlah_adjust', 'keterangan_adjust', 'password_admin']);
        $this->tipe_penyesuaian = 'KOREKSI_MINUS';
    }

    public function prosesAdjustStok(StockService $stockService)
    {
        $this->validate([
            'tipe_penyesuaian' => 'required|in:KOREKSI_PLUS,KOREKSI_MINUS',
            'jumlah_adjust' => 'required|numeric|min:0.01',
            'keterangan_adjust' => 'required|string|min:5',
            'password_admin' => 'required',
        ]);

        if (!Hash::check($this->password_admin, Auth::user()->password)) {
            $this->addError('password_admin', 'Password otorisasi salah!');
            return;
        }

        try {
            // Panggil StockService (Core Business Logic yang kita buat di Fase 5)
            $stockService->adjustStokManual(
                $this->produk_stok_aktif->id_produk,
                Auth::id(),
                $this->tipe_penyesuaian,
                $this->jumlah_adjust,
                $this->keterangan_adjust
            );

            session()->flash('sukses_stok', "Stok fisik barang berhasil diperbarui.");
            
            // Refresh data produk dan riwayat stok di modal
            $this->produk_stok_aktif->refresh();
            $this->loadRiwayatStok();
            $this->resetFormAdjust();

        } catch (Exception $e) {
            $this->addError('sistem_stok', $e->getMessage());
        }
    }

    public function render()
    {
        $query = Produk::with('kategori')->orderBy('status_aktif', 'desc')->latest();
        
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