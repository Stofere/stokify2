<div class="p-6 max-w-7xl mx-auto">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Katalog Barang</h2>
            <p class="text-gray-500 mt-1">Kelola data barang dagangan, harga, dan spesifikasinya di sini.</p>
        </div>
        @if(!$form_open)
            <button wire:click="$set('form_open', true)" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg font-bold flex items-center gap-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Barang Baru
            </button>
        @endif
    </div>

    <!-- Alert Notifikasi -->
    @if(session()->has('sukses'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 mb-6 rounded-xl shadow-sm flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <strong class="font-bold">Berhasil!</strong>
                <span class="block text-sm">{{ session('sukses') }}</span>
            </div>
        </div>
    @endif

    <!-- FORMULIR TAMBAH/EDIT BARANG (RAMAH PENGGUNA) -->
    @if($form_open)
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-100">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                <h3 class="font-bold text-xl text-blue-800">{{ $edit_id ? 'Perbarui Data Barang' : 'Form Barang Baru' }}</h3>
                <button wire:click="resetForm" class="text-gray-400 hover:text-red-500 font-bold text-xl">&times;</button>
            </div>
            
            <div class="p-6">
                <!-- LANGKAH 1 -->
                <div class="mb-8 p-5 bg-gray-50 border rounded-xl relative">
                    <span class="absolute -top-3 -left-3 bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold shadow">1</span>
                    <label class="block text-gray-800 font-bold mb-2">Barang ini masuk kelompok kategori apa?</label>
                    <select wire:model.live="id_kategori" class="w-full md:w-1/2 border-gray-300 rounded-lg p-3 border focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">-- Silakan Pilih Kategori --</option>
                        @foreach($daftarKategori as $kat)
                            <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('id_kategori') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- LANGKAH 2 & 3 (Terbuka Jika Kategori Dipilih) -->
                @if($id_kategori)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        
                        <!-- Kolom Kiri: Info Dasar -->
                        <div class="space-y-5 relative">
                            <span class="absolute -top-3 -left-5 bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold shadow hidden md:flex">2</span>
                            <h4 class="font-bold text-gray-700 border-b pb-2">Informasi Dasar Barang</h4>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-600 mb-1">Kode Barang (Barcode / SKU)</label>
                                <input type="text" wire:model="kode_barang" placeholder="Contoh: FR-12-CN-GRS" class="w-full border-gray-300 rounded-lg p-3 border focus:ring-blue-500 uppercase bg-gray-50">
                                @error('kode_barang') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-600 mb-1">Nama Barang (Sesuai Nota Kasir)</label>
                                <input type="text" wire:model="nama_produk" placeholder="Contoh: Fr 12 in CN Grs (3)" class="w-full border-gray-300 rounded-lg p-3 border focus:ring-blue-500">
                                @error('nama_produk') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-600 mb-1">Satuan Jual</label>
                                    <select wire:model="satuan" class="w-full border-gray-300 rounded-lg p-3 border">
                                        <option value="pcs">Pcs / Biji</option>
                                        <option value="meter">Meter</option>
                                        <option value="kg">Kilogram (Kg)</option>
                                        <option value="rol">Rol</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-600 mb-1">Harga Jual (Rp)</label>
                                    <input type="number" wire:model="harga_jual_satuan" placeholder="Contoh: 150000" class="w-full border-gray-300 rounded-lg p-3 border">
                                    @error('harga_jual_satuan') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Spesifikasi Dinamis -->
                        <div class="space-y-5 relative bg-blue-50 p-5 rounded-xl border border-blue-100">
                            <span class="absolute -top-3 -left-3 bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold shadow md:hidden">3</span>
                            <h4 class="font-bold text-blue-800 border-b border-blue-200 pb-2">Pilih Spesifikasi Barang Ini</h4>
                            
                            @if(count($atributDinamis) == 0)
                                <div class="text-gray-500 text-sm py-4 text-center bg-white rounded-lg border border-dashed">
                                    Kategori ini tidak punya spesifikasi khusus.
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($atributDinamis as $attr)
                                        <div>
                                            <label class="block text-sm font-bold text-blue-900 mb-1">{{ $attr->nama_atribut }}</label>
                                            <select wire:model="metadata_input.{{ $attr->nama_atribut }}" class="w-full border-blue-200 rounded-lg p-3 border bg-white focus:ring-blue-500">
                                                <option value="">-- Pilih --</option>
                                                @foreach($attr->pilihan_opsi as $opsi)
                                                    <option value="{{ $opsi }}">{{ $opsi }}</option>
                                                @endforeach
                                            </select>
                                            @error('metadata_input.' . $attr->nama_atribut) <span class="text-red-500 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-between border-t pt-6">
                        <label class="flex items-center gap-3 cursor-pointer bg-gray-50 p-3 rounded-lg border">
                            <input type="checkbox" wire:model="lacak_stok" class="w-6 h-6 text-blue-600 rounded">
                            <div>
                                <span class="font-bold text-gray-800 block">Lacak Stok Barang Ini</span>
                                <span class="text-xs text-gray-500">Hapus centang jika barang ini adalah Jasa/Ongkir.</span>
                            </div>
                        </label>
                        
                        <div class="flex gap-3">
                            <button wire:click="resetForm" class="bg-white hover:bg-gray-100 border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold transition-all">BATAL</button>
                            <button wire:click="simpan" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-black shadow-lg transition-all">
                                SIMPAN DATA BARANG
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- AREA PENCARIAN & TABEL BARANG -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Search Bar Cantik -->
        <div class="bg-gray-50 p-4 border-b border-gray-200 flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="relative w-full sm:w-2/3 md:w-1/2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                </div>
                <input type="text" wire:model.live.debounce.500ms="keyword" placeholder="Cari Kode, Nama, Merk, Motif..." class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-inner transition-all">
            </div>
        </div>
        
        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b-2 border-gray-100 text-gray-500 text-sm uppercase tracking-wider">
                        <th class="p-4 font-bold">Detail Barang</th>
                        <th class="p-4 font-bold">Kategori</th>
                        <th class="p-4 font-bold text-right">Harga Satuan</th>
                        <th class="p-4 font-bold text-center">Stok Gudang</th>
                        <th class="p-4 font-bold text-center">Status</th>
                        <th class="p-4 font-bold w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($daftarProduk as $prod)
                    <tr class="hover:bg-blue-50 transition-colors {{ !$prod->status_aktif ? 'bg-red-50/50' : '' }}">
                        <td class="p-4">
                            <span class="text-xs font-mono bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $prod->kode_barang }}</span>
                            <p class="font-bold text-gray-800 text-base mt-2">{{ $prod->nama_produk }}</p>
                            @if($prod->metadata)
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    @foreach($prod->metadata as $key => $val)
                                        <span class="bg-blue-100 text-blue-700 text-[11px] px-2 py-0.5 rounded-full font-semibold">{{ $val }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="p-4 font-semibold text-gray-600">{{ $prod->kategori->nama_kategori }}</td>
                        <td class="p-4 text-right">
                            <span class="font-black text-green-700 text-lg">Rp {{ number_format($prod->harga_jual_satuan, 0, ',', '.') }}</span>
                        </td>
                        <td class="p-4 text-center">
                            @if($prod->lacak_stok)
                                <div class="bg-gray-100 inline-block px-3 py-1.5 rounded-lg border {{ $prod->stok_saat_ini <= 0 ? 'border-red-300 bg-red-50 text-red-600' : 'border-gray-200 text-gray-800' }}">
                                    <span class="font-black text-lg">{{ $prod->stok_display }}</span>
                                    <span class="text-xs uppercase ml-1">{{ $prod->satuan }}</span>
                                </div>
                            @else
                                <span class="bg-gray-200 text-gray-500 text-xs px-2 py-1 rounded font-bold uppercase">Tanpa Stok</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if($prod->status_aktif)
                                <button wire:click="toggleAktif({{ $prod->id_produk }})" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm hover:bg-red-100 hover:text-red-700 transition" title="Matikan Produk">Tampil di Kasir</button>
                            @else
                                <button wire:click="toggleAktif({{ $prod->id_produk }})" class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm hover:bg-green-100 hover:text-green-700 transition" title="Hidupkan Produk">Disembunyikan</button>
                            @endif
                        </td>
                        <td class="p-4">
                            <button wire:click="edit({{ $prod->id_produk }})" class="flex items-center gap-1 text-blue-600 hover:text-blue-800 font-bold bg-blue-50 px-3 py-2 rounded-lg hover:bg-blue-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada barang</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan barang baru ke katalog.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Component -->
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            {{ $daftarProduk->links() }}
        </div>
    </div>
</div>