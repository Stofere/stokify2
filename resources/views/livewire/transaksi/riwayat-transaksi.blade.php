<div class="p-6 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Riwayat Dokumen Transaksi</h2>
            <p class="text-gray-500 mt-1">Lacak seluruh riwayat penjualan kasir dan proses tukar retur barang.</p>
        </div>
    </div>

    <!-- TAB NAVIGATION -->
    <div class="flex gap-2 border-b border-gray-300 mb-6">
        <button wire:click="switchTab('POS')" class="px-6 py-3 font-black text-lg transition-colors border-b-4 {{ $activeTab === 'POS' ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-gray-400 hover:text-gray-700' }}">
            🛒 Nota Penjualan
        </button>
        <button wire:click="switchTab('RETUR')" class="px-6 py-3 font-black text-lg transition-colors border-b-4 {{ $activeTab === 'RETUR' ? 'border-purple-600 text-purple-700' : 'border-transparent text-gray-400 hover:text-gray-700' }}">
            🔄 Nota Retur & Tukar
        </button>
    </div>

    <!-- FILTER BERSAMA -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dari Tgl</label>
            <input wire:model.live="tgl_mulai" type="date" class="border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sampai Tgl</label>
            <input wire:model.live="tgl_akhir" type="date" class="border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500">
        </div>
        <div class="flex-1">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pencarian Pintar</label>
            <input wire:model.live.debounce.500ms="keyword" type="text" placeholder="🔍 Cari Nomor Nota, Nama Pelanggan, atau Nama Sales..." class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 shadow-inner">
        </div>
    </div>

    <!-- TAB 1: ISI TABEL POS -->
    @if($activeTab === 'POS')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden animate-[fadeIn_0.3s_ease-in-out]">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr class="text-gray-500 uppercase tracking-wider text-[11px]">
                            <th class="p-4 font-bold">Waktu & Nomor Nota</th>
                            <th class="p-4 font-bold">Pelanggan & Sales</th>
                            <th class="p-4 font-bold">Kasir</th>
                            <th class="p-4 font-bold text-right">Total Transaksi</th>
                            <th class="p-4 font-bold text-center">Menu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($daftarPos as $pos)
                            <tr class="hover:bg-indigo-50 transition-colors">
                                <td class="p-4">
                                    <p class="text-xs text-gray-500 font-bold">{{ $pos->tanggal_transaksi->format('d/m/Y H:i') }}</p>
                                    <p class="text-base font-black text-indigo-700 mt-1">{{ $pos->kode_nota }}</p>
                                    @if($pos->status_penjualan === 'DIRETUR')
                                        <span class="bg-orange-100 text-orange-700 text-[10px] px-2 py-0.5 rounded font-bold uppercase mt-1 inline-block">Ada Retur</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <p class="font-bold text-gray-800">👤 {{ $pos->pelanggan->nama ?? 'Walk-in (Umum)' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">👔 Sales: {{ $pos->marketing->nama ?? '-' }}</p>
                                </td>
                                <td class="p-4 font-bold text-gray-600">{{ $pos->pengguna->nama ?? '-' }}</td>
                                <td class="p-4 text-right font-black text-green-700 text-lg">Rp {{ number_format($pos->total_harga, 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    <button wire:click="lihatDetail({{ $pos->id_transaksi_penjualan }})" class="bg-indigo-100 text-indigo-700 hover:bg-indigo-600 hover:text-white px-4 py-2 rounded-lg font-bold text-xs border border-indigo-200 transition-colors">
                                        Lihat Detail Belanja
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-10 text-center text-gray-400 font-bold">Tidak ada Nota Penjualan ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-gray-50 border-t">{{ $daftarPos->links() }}</div>
        </div>
    @endif

    <!-- TAB 2: ISI TABEL RETUR -->
    @if($activeTab === 'RETUR')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden animate-[fadeIn_0.3s_ease-in-out]">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr class="text-gray-500 uppercase tracking-wider text-[11px]">
                            <th class="p-4 font-bold">Waktu & Nomor Retur</th>
                            <th class="p-4 font-bold">Asal Nota & Pelanggan</th>
                            <th class="p-4 font-bold">Diinput Oleh</th>
                            <th class="p-4 font-bold text-right">Selisih Keuangan</th>
                            <th class="p-4 font-bold text-center">Menu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($daftarRetur as $retur)
                            <tr class="hover:bg-purple-50 transition-colors">
                                <td class="p-4">
                                    <p class="text-xs text-gray-500 font-bold">{{ $retur->tanggal_retur->format('d/m/Y H:i') }}</p>
                                    <p class="text-base font-black text-purple-700 mt-1">{{ $retur->kode_retur }}</p>
                                </td>
                                <td class="p-4">
                                    <p class="font-bold text-gray-600 text-xs uppercase mb-1">Nota: {{ $retur->transaksiPenjualan->kode_nota ?? '-' }}</p>
                                    <p class="font-bold text-gray-800">👤 {{ $retur->transaksiPenjualan->pelanggan->nama ?? 'Walk-in (Umum)' }}</p>
                                </td>
                                <td class="p-4 font-bold text-gray-600">{{ $retur->user->name ?? '-' }}</td>
                                <td class="p-4 text-right">
                                    @if($retur->total_biaya_retur > 0)
                                        <p class="text-orange-700 font-black text-lg">+ Rp {{ number_format(abs($retur->total_biaya_retur), 0, ',', '.') }}</p>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Pelanggan Nambah</p>
                                    @elseif($retur->total_biaya_retur < 0)
                                        <p class="text-green-700 font-black text-lg">- Rp {{ number_format(abs($retur->total_biaya_retur), 0, ',', '.') }}</p>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Toko Kembalikan Uang</p>
                                    @else
                                        <p class="text-gray-700 font-black text-lg">Rp 0</p>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Tukar Guling</p>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <button wire:click="lihatDetail({{ $retur->id_retur }})" class="bg-purple-100 text-purple-700 hover:bg-purple-600 hover:text-white px-4 py-2 rounded-lg font-bold text-xs border border-purple-200 transition-colors">
                                        Rincian Tukar Barang
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-10 text-center text-gray-400 font-bold">Tidak ada Nota Retur ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-gray-50 border-t">{{ $daftarRetur->links() }}</div>
        </div>
    @endif

    <!-- MODAL POP-UP BACA DETAIL NOTA (Kita bisa menggunakan/meng-copy struktur modal dari ProdukIndex di sini agar sama cantiknya) -->
    @if($modal_open && $detail_nota)
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[60] p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
                
                <div class="bg-gray-800 text-white px-6 py-4 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-xl font-bold flex items-center gap-2">
                            @if($activeTab == 'POS') 🛒 Detail Nota Penjualan @else 🔄 Detail Nota Retur @endif
                        </h3>
                        <p class="text-gray-300 text-sm mt-1">Kode: <span class="font-bold text-white">{{ $detail_nota->kode_nota ?? $detail_nota->kode_retur }}</span></p>
                    </div>
                    <button wire:click="tutupModal" class="bg-gray-700 hover:bg-red-600 text-white px-4 py-2 rounded font-bold transition">&times; TUTUP</button>
                </div>

                <div class="p-6 overflow-y-auto bg-gray-50 flex-1">
                    @if($activeTab == 'POS')
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <div><p class="text-[10px] font-bold text-gray-500 uppercase">Waktu Transaksi</p><p class="font-bold text-gray-800">{{ $detail_nota->tanggal_transaksi->format('d M Y, H:i') }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-500 uppercase">Kasir</p><p class="font-bold text-gray-800">{{ $detail_nota->pengguna->nama ?? '-' }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-500 uppercase">Pelanggan</p><p class="font-bold text-blue-700">{{ $detail_nota->pelanggan->nama ?? 'Walk-in (Umum)' }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-500 uppercase">Marketing</p><p class="font-bold text-gray-800">{{ $detail_nota->marketing->nama ?? '-' }}</p></div>
                        </div>

                        <h4 class="font-bold text-gray-800 mb-2 border-b pb-2">Daftar Barang Terjual</h4>
                        <table class="w-full text-left text-sm bg-white border rounded shadow-sm">
                            <thead class="bg-gray-100 text-gray-600"><tr><th class="p-3">Barang</th><th class="p-3 text-center">Beli</th><th class="p-3 text-right">Harga</th><th class="p-3 text-right">Subtotal</th></tr></thead>
                            <tbody class="divide-y">
                                @foreach($detail_nota->detailPenjualan as $det)
                                    <tr>
                                        <td class="p-3 font-bold text-gray-800">{{ $det->produk->nama_produk }}</td>
                                        <td class="p-3 text-center">{{ fmod($det->jumlah, 1) == 0 ? (int)$det->jumlah : $det->jumlah }} {{ $det->satuan_saat_jual }}</td>
                                        <td class="p-3 text-right text-gray-600">Rp {{ number_format($det->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right font-bold text-green-700">Rp {{ number_format($det->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr><td colspan="3" class="p-3 text-right font-bold uppercase text-gray-600">Total Harga:</td><td class="p-3 text-right font-black text-xl text-green-700">Rp {{ number_format($detail_nota->total_harga, 0, ',', '.') }}</td></tr>
                            </tfoot>
                        </table>
                    @else
                        <!-- Rincian Retur sama seperti di Modul ProdukIndex -->
                        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <p class="text-sm font-bold text-gray-800 mb-1">Catatan Retur:</p>
                            <p class="text-gray-600 italic">"{{ $detail_nota->catatan ?? 'Tidak ada catatan.' }}"</p>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-2 border-b pb-2">Rincian Penukaran Barang</h4>
                        <div class="space-y-3">
                            @foreach($detail_nota->detailRetur as $detRetur)
                                <div class="bg-white border rounded p-4 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                                    <div class="flex-1 w-full bg-red-50 p-3 rounded border border-red-100 text-sm">
                                        <p class="text-[10px] font-bold text-red-500 uppercase mb-1">Kembali dari Pelanggan</p>
                                        <p class="font-bold text-red-800">{{ $detRetur->produkDikembalikan->nama_produk }}</p>
                                        <p class="text-xs mt-1">Qty: <span class="font-bold">{{ fmod($detRetur->jumlah, 1) == 0 ? (int)$detRetur->jumlah : $detRetur->jumlah }}</span> | Kondisi: <span class="font-bold">{{ $detRetur->kondisi_barang_dikembalikan }}</span></p>
                                    </div>
                                    <div class="text-gray-400 font-bold text-xl">&rarr;</div>
                                    <div class="flex-1 w-full bg-green-50 p-3 rounded border border-green-100 text-sm">
                                        <p class="text-[10px] font-bold text-green-600 uppercase mb-1">Pengganti dari Toko</p>
                                        <p class="font-bold text-green-800">{{ $detRetur->produkPengganti->nama_produk }}</p>
                                        <p class="text-xs mt-1">Qty: <span class="font-bold">{{ fmod($detRetur->jumlah, 1) == 0 ? (int)$detRetur->jumlah : $detRetur->jumlah }}</span></p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>