<div>
    <!-- === ANIME HERO SECTION - HANYA UNTUK ADMIN === -->
    @if(!$isOwner)
    @php
        $hour = \Carbon\Carbon::now('Asia/Jakarta')->hour;
        
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Pagi';
            $subText = 'Sudahkah kamu menginput penjualan hari ini?';
        } elseif ($hour >= 12 && $hour < 15) {
            $greeting = 'Siang';
            $subText = 'Semangat terus ya, Master! Sudah makan siang?';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Sore';
            $subText = 'Sudahkah kamu menginput penjualan hari ini?';
        } else {
            $greeting = 'Malam';
            $subText = 'Jangan lembur terlalu lama ya~ Istirahat yang cukup!';
        }
    @endphp

    <div class="px-6 pt-6 max-w-7xl mx-auto">
        <div class="w-full bg-gradient-to-r from-[#E0F2F1] to-[#F1F5F9] rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between shadow-sm relative overflow-hidden group">
            
            <!-- Chibi Frieren Container (Kiri) - BISA DIKLIK -->
            <div id="frieren-chibi" class="relative w-48 h-48 md:w-56 md:h-56 shrink-0 frieren-float z-10 hidden md:block cursor-pointer">
                <img src="/images/chibi-frieren-open.png" alt="Frieren" 
                    class="absolute inset-0 w-full h-full object-contain frieren-wave transition-transform duration-500 group-hover:scale-105 frieren-eye-open">
                <img src="/images/chibi-frieren-blink.png" alt="Frieren Blink" 
                    class="absolute inset-0 w-full h-full object-contain frieren-wave transition-transform duration-500 group-hover:scale-105 frieren-eye-closed opacity-0">
            </div>

            <!-- Teks Welcome Frieren (Kanan) -->
            <div class="flex-1 md:ml-8 text-center md:text-left z-10 mt-4 md:mt-0">
                <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight flex items-center justify-center md:justify-start gap-2">
                    🌿 Selamat {{ $greeting }}, Master {{ Auth::user()->name ?? 'Yesaya' }}!
                </h1>
                <p class="text-slate-600 mt-3 text-sm md:text-base leading-relaxed max-w-xl">
                    {!! $subText !!}<br>
                    <span class="italic font-medium">Karena... Pahlawan Himmel pasti akan melakukannya dengan cepat dan teliti~ ☕</span>
                </p>

                <!-- Tombol Action -->
                <div class="mt-6">
                    <a href="/pos" class="inline-block bg-[#A5B4FC] hover:bg-[#6366F1] text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1 relative group/btn">
                        Input Transaksi Baru
                        <span class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-[10px] py-1 px-3 rounded opacity-0 group-hover/btn:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                            Karena Himmel tidak pernah menunda tugas!
                        </span>
                    </a>
                </div>
            </div>

            <!-- Ornamen Dekoratif -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-40 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-10 right-20 w-32 h-32 bg-teal-100 opacity-50 rounded-full blur-xl"></div>
        </div>
    </div>
    @endif

    <!-- === ORIGINAL DASHBOARD CONTENT - OWNER & ADMIN (SAMA PERSIS) === -->
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-3xl font-black text-gray-800">Ringkasan Sistem ERP</h2>
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <!-- ROW 1: KARTU METRIK UTAMA -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- KOTAK NOTA -->
            <a href="/transaksi/riwayat" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 border-l-4 border-indigo-500 hover:bg-indigo-50 hover:border-indigo-600 transition-all cursor-pointer group">
                <div class="bg-indigo-100 p-4 rounded-xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></div>
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider group-hover:text-indigo-800 transition-colors">Total Nota POS Hari Ini</p>
                    <h3 class="text-3xl font-black text-gray-800 flex items-baseline gap-2">{{ $notaCount }} <span class="text-sm text-gray-400 font-semibold group-hover:text-indigo-600">Lihat Riwayat &rarr;</span></h3>
                </div>
            </a>

            <!-- HANYA TAMPIL JIKA OWNER -->
            @if($isOwner)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 border-l-4 border-green-500 relative overflow-hidden">
                    <div class="bg-green-100 p-4 rounded-xl text-green-600 z-10"><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                    <div class="z-10">
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Omset Kotor Hari Ini</p>
                        <h3 class="text-2xl font-black text-gray-800 mt-1">Rp {{ number_format($omsetHariIni, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 border-l-4 border-orange-500">
                    <div class="bg-orange-100 p-4 rounded-xl text-orange-600"><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg></div>
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Selisih Retur Hari Ini</p>
                        <h3 class="text-xl font-black text-gray-800 mt-1">Rp {{ number_format($returHariIni, 0, ',', '.') }}</h3>
                        <p class="text-[10px] text-gray-400 mt-1 font-bold">*(+) Plg Nombok, (-) Toko Rugi</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- ROW 2: CHART & AKTIVITAS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- CHART AREA -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-black text-gray-800 mb-4 uppercase tracking-wider text-sm border-b pb-2">Grafik Jumlah Transaksi (7 Hari Terakhir)</h4>
                <div class="w-full h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- ACTIVITY LOG / AUDIT TRAIL -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-0 flex flex-col h-full">
                <h4 class="font-black text-gray-800 p-5 uppercase tracking-wider text-sm border-b bg-gray-50 rounded-t-2xl">Log Aktivitas Sistem Hari Ini</h4>
                <div class="flex-1 overflow-y-auto p-5" style="max-height: 300px;">
                    @if(count($aktivitasHariIni) == 0)
                        <p class="text-center text-gray-400 text-sm font-bold mt-10">Belum ada aktivitas hari ini.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach($aktivitasHariIni as $log)
                                <li class="flex gap-3 text-sm">
                                    <div class="w-2 h-2 mt-1.5 rounded-full shrink-0 {{ in_array($log->tipe, ['MASUK', 'KOREKSI_PLUS']) ? 'bg-green-500' : (in_array($log->tipe, ['KELUAR', 'KOREKSI_MINUS']) ? 'bg-red-500' : 'bg-blue-500') }}"></div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $log->user->name }}</p>
                                        <p class="text-gray-600 text-xs">
                                            {{ str_replace('_', ' ', $log->tipe) }} <span class="font-bold text-gray-800">{{ abs($log->jumlah) }}</span> qty pada <span class="text-blue-600 font-semibold">{{ $log->produk->nama_produk }}</span>.
                                        </p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }} | {{ $log->keterangan }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="p-3 text-center border-t bg-gray-50 text-[10px] font-bold text-gray-400 rounded-b-2xl">Audit log ditarik dari Buku Riwayat Stok</div>
            </div>
        </div>

        <!-- ROW 3: TOP PERFORMERS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- TOP MARKETING -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <h4 class="font-black text-white bg-indigo-600 p-4 uppercase tracking-wider text-sm">🏆 Performa Marketing (Bulan Ini)</h4>
                <ul class="divide-y divide-gray-100">
                    @forelse($topMarketing as $index => $mkt)
                        <li class="p-4 flex justify-between items-center hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-black">{{ $index + 1 }}</div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $mkt->marketing->nama }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-black text-indigo-600">{{ $mkt->total_transaksi }}</p>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">Kali Jualan</p>
                            </div>
                        </li>
                    @empty
                        <li class="p-6 text-center text-gray-400 font-bold">Belum ada data marketing bulan ini.</li>
                    @endforelse
                </ul>
            </div>

            <!-- TOP PELANGGAN -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <h4 class="font-black text-white bg-pink-600 p-4 uppercase tracking-wider text-sm">🏅 Pelanggan Paling Aktif (Bulan Ini)</h4>
                <ul class="divide-y divide-gray-100">
                    @forelse($topPelanggan as $index => $plg)
                        <li class="p-4 flex justify-between items-center hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-700 flex items-center justify-center font-black">{{ $index + 1 }}</div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $plg->pelanggan->nama }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-black text-pink-600">{{ $plg->total_transaksi }}</p>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">Kali Pembelian</p>
                            </div>
                        </li>
                    @empty
                        <li class="p-6 text-center text-gray-400 font-bold">Belum ada data pelanggan tercatat.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- SCRIPT UNTUK ANIMASI CSS (FRIEREN HERO SECTION) -->
<!-- ============================================== -->
<style>
    @keyframes floating { 0% { transform: translateY(0px); } 50% { transform: translateY(-8px); } 100% { transform: translateY(0px); } }
    .frieren-float { animation: floating 4s ease-in-out infinite; }

    @keyframes wave { 0% { transform: rotate(0deg); } 25% { transform: rotate(2deg); } 75% { transform: rotate(-2deg); } 100% { transform: rotate(0deg); } }
    .frieren-wave { animation: wave 6s ease-in-out infinite; }

    .frieren-eye-closed { 
        opacity: 0; 
        transition: opacity 0.1s ease-in-out;
    }
</style>

<!-- SCRIPT UNTUK RENDER CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        const ctx = document.getElementById('salesChart');
        if(!ctx) return; // Mencegah error jika canvas tidak ada
        
        const labels = @json($chartLabels7Hari);
        const dataPoint = @json($chartData7Hari);

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Transaksi (Nota)',
                    data: dataPoint,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } } }
            }
        });

        // === CLICK TO BLINK FEATURE ===
        const chibi = document.getElementById('frieren-chibi');
        if (chibi) {
            const openImg = chibi.querySelector('.frieren-eye-open');
            const closedImg = chibi.querySelector('.frieren-eye-closed');

            chibi.addEventListener('click', () => {
                // Tampilkan mata tertutup
                closedImg.style.opacity = '1';
                
                // Kembalikan ke mata terbuka setelah 400ms
                setTimeout(() => {
                    closedImg.style.opacity = '0';
                }, 400);
            });
        }
    });
</script>
