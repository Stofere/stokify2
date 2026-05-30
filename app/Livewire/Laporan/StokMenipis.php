<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StokMenipis extends Component
{
    public function getQueryBuilder()
    {
        return Produk::with('kategori')
            ->where('status_aktif', true)
            ->where('lacak_stok', true)
            ->where(function ($query) {
                // HABIS: stok <= 0
                $query->where('stok_saat_ini', '<=', 0)
                    // MENIPIS: stok > 0 tapi di bawah batas minimum
                    ->orWhere(function ($q) {
                        $q->where('stok_saat_ini', '>', 0)
                          ->where(function ($inner) {
                              $inner->where(function ($sub) {
                                  $sub->whereIn(DB::raw('LOWER(satuan)'), ['pcs', 'biji', 'unit', 'buah'])
                                      ->where('stok_saat_ini', '<=', 20);
                              })->orWhere(function ($sub) {
                                  $sub->whereNotIn(DB::raw('LOWER(satuan)'), ['pcs', 'biji', 'unit', 'buah'])
                                      ->where('stok_saat_ini', '<=', 1);
                              });
                          });
                    });
            })
            // Order: HABIS first (stok <= 0), then MENIPIS, alphabetically by kode_barang
            ->orderByRaw('CASE WHEN stok_saat_ini <= 0 THEN 0 ELSE 1 END ASC')
            ->orderBy('kode_barang', 'ASC');
    }

    public function cetakPdf()
    {
        $produkList = $this->getQueryBuilder()->get();

        $pdf = Pdf::loadView('pdf.stok-menipis', [
            'produkList' => $produkList,
            'tanggal' => now()->translatedFormat('d F Y'),
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Laporan_Stok_Menipis_' . now()->format('Ymd') . '.pdf');
    }

    public function render()
    {
        return view('livewire.laporan.stok-menipis', [
            'produkList' => $this->getQueryBuilder()->get(),
        ]);
    }
}
