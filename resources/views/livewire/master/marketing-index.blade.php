<div class="p-6 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Master Data Marketing / Sales</h2>
        <button wire:click="$set('form_open', true)" class="bg-blue-600 text-white px-4 py-2 rounded-xl shadow-lg font-bold hover:bg-blue-700 transition">
            + Tambah Marketing
        </button>
    </div>

    @if(session()->has('sukses'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 font-bold">{{ session('sukses') }}</div>
    @endif

    @if($form_open)
        <div class="bg-white p-6 rounded-2xl shadow-xl mb-6 border-t-4 border-blue-500">
            <h3 class="font-bold text-xl mb-4 text-blue-800">{{ $edit_id ? 'Edit Marketing' : 'Tambah Marketing Baru' }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold mb-1">Nama Lengkap *</label>
                    <input type="text" wire:model="nama" class="w-full border-gray-300 rounded-lg p-2 border focus:ring-blue-500">
                    @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">No. Telepon / WA</label>
                    <input type="text" wire:model="telepon" class="w-full border-gray-300 rounded-lg p-2 border focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Alamat</label>
                    <input type="text" wire:model="alamat" class="w-full border-gray-300 rounded-lg p-2 border focus:ring-blue-500">
                </div>
            </div>
            <div class="flex gap-2">
                <button wire:click="simpan" class="bg-green-600 text-white px-6 py-2 rounded-lg font-black shadow transition">SIMPAN</button>
                <button wire:click="resetForm" class="bg-gray-100 text-gray-800 border px-6 py-2 rounded-lg font-bold">BATAL</button>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
        <div class="p-4 bg-gray-50 border-b">
            <input type="text" wire:model.live.debounce.300ms="keyword" placeholder="🔍 Cari nama marketing..." class="w-full md:w-1/3 border-gray-300 rounded-lg p-2 border focus:ring-blue-500">
        </div>
        
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b">
                    <th class="p-4 font-bold">Nama</th>
                    <th class="p-4 font-bold">Telepon</th>
                    <th class="p-4 font-bold">Alamat</th>
                    <th class="p-4 font-bold text-center">Status</th>
                    <th class="p-4 font-bold w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($daftarMarketing as $mkt)
                <tr class="hover:bg-blue-50 {{ !$mkt->aktif ? 'bg-red-50/50' : '' }}">
                    <td class="p-4 font-bold text-gray-800">{{ $mkt->nama }}</td>
                    <td class="p-4 font-semibold text-gray-600">{{ $mkt->telepon ?? '-' }}</td>
                    <td class="p-4">{{ $mkt->alamat ?? '-' }}</td>
                    <td class="p-4 text-center">
                        @if($mkt->aktif)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">AKTIF</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">NONAKTIF</span>
                        @endif
                    </td>
                    <td class="p-4 flex gap-3">
                        <button wire:click="edit({{ $mkt->id_marketing }})" class="text-blue-600 font-bold hover:underline">Edit</button>
                        <button wire:click="toggleAktif({{ $mkt->id_marketing }})" class="{{ $mkt->aktif ? 'text-red-600' : 'text-green-600' }} font-bold hover:underline">
                            {{ $mkt->aktif ? 'Matikan' : 'Hidupkan' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>