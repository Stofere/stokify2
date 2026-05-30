<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Menipis</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #2c3e50; font-size: 20px; }
        .header p { margin: 5px 0 0 0; color: #7f8c8d; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #bdc3c7; padding: 6px 8px; text-align: left; }
        th { background-color: #ecf0f1; color: #2c3e50; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .center { text-align: center; }
        .status-habis { color: #e74c3c; font-weight: bold; text-transform: uppercase; }
        .status-menipis { color: #e67e22; font-weight: bold; text-transform: uppercase; }
        .stok-habis { color: #e74c3c; font-weight: bold; }
        .stok-menipis { color: #e67e22; font-weight: bold; }
        .row-habis { background-color: #fdf2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STOK MENIPIS & HABIS</h1>
        <p>Tanggal Cetak: {{ $tanggal }} | Total Item: {{ $produkList->count() }} produk</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="center">No</th>
                <th width="12%">Kode / SKU</th>
                <th width="30%">Nama Barang</th>
                <th width="15%">Kategori</th>
                <th width="12%" class="center">Sisa Stok</th>
                <th width="12%" class="center">Batas Min.</th>
                <th width="14%" class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produkList as $index => $prod)
            <tr class="{{ $prod->status_stok === 'HABIS' ? 'row-habis' : '' }}">
                <td class="center">{{ $index + 1 }}.</td>
                <td style="font-family: monospace; font-size: 10px; text-transform: uppercase; font-weight: bold;">{{ strtoupper($prod->kode_barang) }}</td>
                <td><strong>{{ $prod->nama_produk }}</strong></td>
                <td>{{ $prod->kategori->nama_kategori ?? '-' }}</td>
                <td class="center {{ $prod->stok_saat_ini <= 0 ? 'stok-habis' : 'stok-menipis' }}">
                    {{ fmod($prod->stok_saat_ini, 1) == 0 ? (int)$prod->stok_saat_ini : $prod->stok_saat_ini }} {{ $prod->satuan }}
                </td>
                <td class="center">{{ $prod->stok_minimum }} {{ $prod->satuan }}</td>
                <td class="center">
                    @if($prod->status_stok === 'HABIS')
                        <strong class="status-habis">HABIS</strong>
                    @elseif($prod->status_stok === 'MENIPIS')
                        <strong class="status-menipis">MENIPIS</strong>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
