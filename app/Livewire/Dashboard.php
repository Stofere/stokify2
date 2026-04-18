<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiPenjualan;
use App\Models\TransaksiRetur;
use App\Models\RiwayatStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $hariIni = today();
        $isOwner = Auth::user()->peran === 'OWNER';

        // 1. DATA KEUANGAN (Hanya untuk Owner)
        $omsetHariIni = 0;
        $returHariIni = 0;
        if ($isOwner) {
            $omsetHariIni = TransaksiPenjualan::whereDate('tanggal_transaksi', $hariIni)
                ->where('status_penjualan', '!=', 'DIBATALKAN')
                ->sum('total_harga');
            $returHariIni = TransaksiRetur::whereDate('tanggal_retur', $hariIni)->sum('total_biaya_retur');
        }

        $notaCount = TransaksiPenjualan::whereDate('tanggal_transaksi', $hariIni)->count();

        // 2. DATA CHART (7 Hari Terakhir & Bulan Ini)
        $chartLabels7Hari = [];
        $chartData7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels7Hari[] = $date->format('d M');
            $chartData7Hari[] = TransaksiPenjualan::whereDate('tanggal_transaksi', $date)->count();
        }

        // 3. TOP PERFORMER MARKETING (Bulan Ini)
        $topMarketing = TransaksiPenjualan::with('marketing')
            ->whereNotNull('id_marketing')
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->select('id_marketing', DB::raw('count(*) as total_transaksi'))
            ->groupBy('id_marketing')
            ->orderByDesc('total_transaksi')
            ->limit(5)->get();

        // 4. TOP PELANGGAN AKTIF (Bulan Ini)
        $topPelanggan = TransaksiPenjualan::with('pelanggan')
            ->whereNotNull('id_pelanggan')
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->select('id_pelanggan', DB::raw('count(*) as total_transaksi'))
            ->groupBy('id_pelanggan')
            ->orderByDesc('total_transaksi')
            ->limit(5)->get();

        // 5. AUDIT LOG (Aktivitas User Hari Ini)
        $aktivitasHariIni = RiwayatStok::with(['user', 'produk', 'transaksiPenjualan'])
            ->whereDate('created_at', $hariIni)
            ->orderBy('id_riwayat', 'desc')
            ->limit(8)
            ->get();

        return view('livewire.dashboard', [
            'isOwner' => $isOwner,
            'omsetHariIni' => $omsetHariIni,
            'returHariIni' => $returHariIni,
            'notaCount' => $notaCount,
            'chartLabels7Hari' => $chartLabels7Hari,
            'chartData7Hari' => $chartData7Hari,
            'topMarketing' => $topMarketing,
            'topPelanggan' => $topPelanggan,
            'aktivitasHariIni' => $aktivitasHariIni,
        ]);
    }
}