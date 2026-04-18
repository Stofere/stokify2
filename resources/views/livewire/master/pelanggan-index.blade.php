<div class="p-6 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Master Data Pelanggan</h2>
        <button wire:click="$set('form_open', true)" class="bg-blue-600 text-white px-4 py-2 rounded-xl shadow-lg font-bold hover:bg-blue-700 transition">
            + Tambah Pelanggan
        </button>
    </div>

    @if(session()->has('sukses'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 font-bold shadow-sm border border-green-200">
            {{ session('sukses') }}
        </div>
    @endif

    @if($form_open)
        <div class="bg-white p-6 rounded-2xl shadow-xl mb-6 border-t-4 border-blue-500">
            <h3 class="font-bold text-xl mb-4 text-blue-800">
                {{ $edit_id ? 'Edit Data Pelanggan' : 'Tambah Pelanggan Baru' }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold mb-1 text-gray-700">Nama Lengkap *</label>
                    <input type="text" wire:model="nama" 
                        class="w-full border-gray-300 rounded-lg p-2 border focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    @error('nama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1 text-gray-700">No. Telepon / WA</label>
                    <input type="text" wire:model="telepon" 
                        class="w-full border-gray-300 rounded-lg p-2 border focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1 text-gray-700">Alamat</label>
                    <input type="text" wire:model="alamat" 
                        class="w-full border-gray-300 rounded-lg p-2 border focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>
            </div>

            <div class="flex gap-2">
                <button wire:click="simpan" class="bg-green-600 text-white px-6 py-2 rounded-lg font-black shadow-md hover:bg-green-700 transition">
                    SIMPAN
                </button>
                <button wire:click="resetForm" class="bg-gray-100 text-gray-800 border px-6 py-2 rounded-lg font-bold hover:bg-gray-200 transition">
                    BATAL
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
        <div class="p-4 bg-gray-50 border-b">
            <div class="relative w-full md:w-1/3">
                <input type="text" wire:model.live.debounce.300ms="keyword" 
                    placeholder="🔍 Cari nama pelanggan..." 
                    class="w-full border-gray-300 rounded-lg p-2 pl-4 border focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b">
                        <th class="p-4 font-bold">Nama</th>
                        <th class="p-4 font-bold">Telepon</th>
                        <th class="p-4 font-bold">Alamat</th>
                        <th class="p-4 font-bold text-center">Status</th>
                        <th class="p-4 font-bold w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($daftarPelanggan as $plg)
                    <tr class="hover:bg-blue-50 transition-colors {{ !$plg->aktif ? 'bg-red-50/40' : '' }}">
                        <td class="p-4 font-bold text-gray-800">{{ $plg->nama }}</td>
                        <td class="p-4 font-semibold text-gray-600">{{ $plg->telepon ?? '-' }}</td>
                        <td class="p-4 text-gray-600">{{ $plg->alamat ?? '-' }}</td>
                        <td class="p-4 text-center">
                            @if($plg->aktif)
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-black">AKTIF</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-black">NONAKTIF</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <button wire:click="edit({{ $plg->id_pelanggan }})" class="text-blue-600 font-black hover:underline">
                                    Edit
                                </button>
                                <button wire:click="toggleAktif({{ $plg->id_pelanggan }})" 
                                    class="{{ $plg->aktif ? 'text-red-600' : 'text-green-600' }} font-black hover:underline">
                                    {{ $plg->aktif ? 'Matikan' : 'Hidupkan' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($daftarPelanggan->isEmpty())
            <div class="p-8 text-center text-gray-500 font-medium">
                Data pelanggan tidak ditemukan.
            </div>
        @endif
    </div>
</div>