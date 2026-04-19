@php $isOwnerRole = Auth::user()->peran === 'OWNER'; @endphp

<div class="p-4 md:p-8 max-w-7xl mx-auto fade-in">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="font-headline text-2xl md:text-3xl font-bold {{ $isOwnerRole ? 'text-charcoal' : 'text-sage-dark' }}">Master Data Pelanggan</h2>
            <p class="text-slate-400 text-sm mt-1">Kelola data pelanggan toko Anda.</p>
        </div>
        <button wire:click="$set('form_open', true)"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white shadow-md hover:shadow-lg transition-all
                       {{ $isOwnerRole ? 'bg-gradient-to-r from-blue-pro to-blue-600' : 'bg-gradient-to-r from-sage-dark to-sage' }}">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Pelanggan
        </button>
    </div>

    @if(session()->has('sukses'))
        <div class="bg-emerald-50 text-emerald-700 p-3 mb-5 rounded-xl text-sm font-semibold flex items-center gap-2 border border-emerald-100">
            <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('sukses') }}
        </div>
    @endif

    @if($form_open)
        <div class="bg-white rounded-2xl overflow-hidden mb-6 {{ $isOwnerRole ? 'border border-slate-200' : '' }}">
            <div class="px-6 py-4 border-b flex justify-between items-center {{ $isOwnerRole ? 'bg-slate-50 border-slate-200' : 'bg-sage-light/50 border-sage/10' }}">
                <h3 class="font-headline text-lg font-bold {{ $isOwnerRole ? 'text-charcoal' : 'text-sage-dark' }}">{{ $edit_id ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru' }}</h3>
                <button wire:click="resetForm" class="text-slate-400 hover:text-red-500 transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Nama Lengkap *</label>
                        <input type="text" wire:model="nama" class="w-full border-0 rounded-lg p-2.5 text-sm bg-slate-50 focus:ring-2 {{ $isOwnerRole ? 'focus:ring-blue-pro/20' : 'focus:ring-sage/20' }}">
                        @error('nama') <span class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">No. Telepon / WA</label>
                        <input type="text" wire:model="telepon" class="w-full border-0 rounded-lg p-2.5 text-sm bg-slate-50 focus:ring-2 {{ $isOwnerRole ? 'focus:ring-blue-pro/20' : 'focus:ring-sage/20' }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Alamat</label>
                        <input type="text" wire:model="alamat" class="w-full border-0 rounded-lg p-2.5 text-sm bg-slate-50 focus:ring-2 {{ $isOwnerRole ? 'focus:ring-blue-pro/20' : 'focus:ring-sage/20' }}">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button wire:click="simpan" class="px-6 py-2.5 rounded-xl font-bold text-sm text-white shadow-md {{ $isOwnerRole ? 'bg-blue-pro hover:bg-blue-800' : 'bg-sage-dark hover:bg-sage' }} transition-colors">Simpan</button>
                    <button wire:click="resetForm" class="px-5 py-2.5 rounded-xl font-semibold text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl overflow-hidden {{ $isOwnerRole ? 'border border-slate-200' : '' }}">
        <div class="p-4 {{ $isOwnerRole ? 'bg-slate-50 border-b border-slate-200' : 'bg-[#F8F9FA] border-b border-sage/10' }}">
            <div class="relative w-full md:w-1/3">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                <input type="text" wire:model.live.debounce.300ms="keyword" placeholder="Cari nama pelanggan..."
                       class="w-full pl-10 pr-4 py-2.5 border-0 rounded-xl text-sm bg-white focus:ring-2 {{ $isOwnerRole ? 'focus:ring-blue-pro/20' : 'focus:ring-sage/20' }} shadow-sm">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-label font-bold uppercase tracking-widest text-slate-400 {{ $isOwnerRole ? 'border-b border-slate-100' : 'border-b border-sage/10' }}">
                        <th class="p-4">Nama</th><th class="p-4">Telepon</th><th class="p-4">Alamat</th><th class="p-4 text-center">Status</th><th class="p-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($daftarPelanggan as $plg)
                    <tr class="transition-colors {{ !$plg->aktif ? 'opacity-50' : '' }} {{ $isOwnerRole ? 'hover:bg-slate-50 border-b border-slate-50' : 'hover:bg-sage-light/20 border-b border-sage/5' }}">
                        <td class="p-4 font-semibold {{ $isOwnerRole ? 'text-charcoal' : 'text-sage-dark' }}">{{ $plg->nama }}</td>
                        <td class="p-4 text-slate-600">{{ $plg->telepon ?? '-' }}</td>
                        <td class="p-4 text-slate-600">{{ $plg->alamat ?? '-' }}</td>
                        <td class="p-4 text-center">
                            @if($plg->aktif)
                                <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-bold">AKTIF</span>
                            @else
                                <span class="bg-red-50 text-red-500 px-3 py-1 rounded-full text-[10px] font-bold">NONAKTIF</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <button wire:click="edit({{ $plg->id_pelanggan }})" class="text-xs font-bold {{ $isOwnerRole ? 'text-blue-pro hover:text-blue-800' : 'text-sage hover:text-sage-dark' }}">Edit</button>
                                <button wire:click="toggleAktif({{ $plg->id_pelanggan }})" class="{{ $plg->aktif ? 'text-red-500' : 'text-emerald-600' }} text-xs font-bold hover:underline">{{ $plg->aktif ? 'Matikan' : 'Hidupkan' }}</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($daftarPelanggan->isEmpty())
            <div class="p-8 text-center text-slate-400 font-semibold">Data pelanggan tidak ditemukan.</div>
        @endif
    </div>
</div>