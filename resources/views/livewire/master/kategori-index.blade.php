<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Master Kategori & Relasi Atribut</h2>
        <button wire:click="$set('form_open', true)" class="bg-blue-600 text-white px-4 py-2 rounded shadow font-bold">
            + Tambah Kategori
        </button>
    </div>

    @if(session()->has('sukses'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('sukses') }}</div>
    @endif

    @if($form_open)
        <div class="bg-white p-6 rounded shadow mb-6 border-t-4 border-blue-500">
            <h3 class="font-bold text-lg mb-4 border-b pb-2">{{ $edit_id ? 'Edit Kategori' : 'Buat Kategori Baru' }}</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1">Nama Kategori</label>
                <input type="text" wire:model="nama_kategori" placeholder="Contoh: Woofer, Fullring, Kabel..." class="w-full md:w-1/2 border rounded p-2">
                @error('nama_kategori') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6 p-4 bg-gray-50 border rounded">
                <label class="block text-sm font-bold mb-3 text-blue-800">Atribut yang Aktif di Kategori Ini (Buku Aturan)</label>
                <p class="text-xs text-gray-500 mb-3">Centang atribut di bawah ini. Atribut yang dicentang akan otomatis muncul sebagai form spesifikasi saat admin menambahkan produk di kategori ini.</p>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($daftarAtribut as $attr)
                        <label class="flex items-center gap-2 cursor-pointer bg-white p-2 border rounded shadow-sm hover:bg-blue-50">
                            <!-- Array Binding Livewire ke selectedAtribut -->
                            <input type="checkbox" wire:model="selectedAtribut" value="{{ $attr->id_atribut }}" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                            <span class="text-sm font-semibold text-gray-700">{{ $attr->nama_atribut }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-2">
                <button wire:click="simpan" class="bg-blue-600 text-white px-6 py-2 rounded font-bold">SIMPAN KATEGORI</button>
                <button wire:click="resetForm" class="bg-gray-300 text-gray-800 px-6 py-2 rounded font-bold">BATAL</button>
            </div>
        </div>
    @endif

    <div class="bg-white rounded shadow p-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-sm border-b">
                    <th class="p-3 w-1/4">Kategori</th>
                    <th class="p-3">Atribut / Spesifikasi Wajib</th>
                    <th class="p-3 w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($daftarKategori as $kat)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-bold text-gray-800">{{ $kat->nama_kategori }}</td>
                    <td class="p-3 flex flex-wrap gap-2">
                        @forelse($kat->atribut as $a)
                            <span class="bg-blue-100 text-blue-800 border border-blue-200 text-xs px-2 py-1 rounded font-semibold">{{ $a->nama_atribut }}</span>
                        @empty
                            <span class="text-gray-400 text-xs italic">Tidak ada atribut khusus (Bebas)</span>
                        @endforelse
                    </td>
                    <td class="p-3">
                        <button wire:click="edit({{ $kat->id_kategori }})" class="text-blue-600 hover:underline font-bold text-sm">Edit / Atur Atribut</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>