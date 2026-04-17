<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Master Atribut (Spesifikasi Dinamis)</h2>
        <button wire:click="$set('form_open', true)" class="bg-blue-600 text-white px-4 py-2 rounded shadow font-bold">
            + Tambah Atribut
        </button>
    </div>

    @if(session()->has('sukses'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('sukses') }}</div>
    @endif

    @if($form_open)
        <div class="bg-white p-6 rounded shadow mb-6 border-t-4 border-blue-500">
            <h3 class="font-bold text-lg mb-4">{{ $edit_id ? 'Edit Atribut' : 'Buat Atribut Baru' }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold mb-1">Nama Atribut</label>
                    <input type="text" wire:model="nama_atribut" placeholder="Contoh: Merk, Motif, Ring..." class="w-full border rounded p-2">
                    @error('nama_atribut') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- JSON Builder UI -->
                <div>
                    <label class="block text-sm font-bold mb-1">Pilihan Opsi (Minimal 1)</label>
                    <div class="flex gap-2 mb-2">
                        <input type="text" wire:model="opsi_baru" wire:keydown.enter="tambahOpsi" placeholder="Ketik lalu Enter (Misal: CN)" class="w-full border rounded p-2 text-sm">
                        <button type="button" wire:click="tambahOpsi" class="bg-gray-800 text-white px-3 py-2 rounded text-sm font-bold">Tambah</button>
                    </div>
                    @error('pilihan_opsi') <span class="text-red-500 text-xs mb-2 block">{{ $message }}</span> @enderror

                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($pilihan_opsi as $index => $opsi)
                            <span class="bg-blue-100 text-blue-800 border border-blue-300 px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-2">
                                {{ $opsi }}
                                <button type="button" wire:click="hapusOpsi({{ $index }})" class="text-red-500 hover:text-red-700 font-bold">x</button>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex gap-2 mt-6">
                <button wire:click="simpan" class="bg-blue-600 text-white px-6 py-2 rounded font-bold">SIMPAN ATRIBUT</button>
                <button wire:click="resetForm" class="bg-gray-300 text-gray-800 px-6 py-2 rounded font-bold">BATAL</button>
            </div>
        </div>
    @endif

    <div class="bg-white rounded shadow p-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-sm border-b">
                    <th class="p-3 w-1/3">Nama Atribut</th>
                    <th class="p-3">Daftar Pilihan (JSON Opsi)</th>
                    <th class="p-3 w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($daftarAtribut as $attr)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-bold text-gray-800">{{ $attr->nama_atribut }}</td>
                    <td class="p-3 flex flex-wrap gap-1">
                        @foreach($attr->pilihan_opsi as $o)
                            <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">{{ $o }}</span>
                        @endforeach
                    </td>
                    <td class="p-3">
                        <button wire:click="edit({{ $attr->id_atribut }})" class="text-blue-600 hover:underline font-bold text-sm">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>