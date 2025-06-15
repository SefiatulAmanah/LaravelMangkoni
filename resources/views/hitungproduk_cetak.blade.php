<!DOCTYPE html>
<html>

<head>
    <title>Cetak Laporan Penjualan Produk</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 14px;
        margin: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    h2,
    h4 {
        text-align: center;
        margin: 0;
        padding: 5px 0;
    }
    </style>
</head>

<body>

    <h2>Laporan Penjualan Produk</h2>
    <h4>Produk: {{ $produk->nama_produk }} | Tahun: {{ $tahun }}</h4>

    <h4>Penjualan Per Minggu</h4>
    <table>
        <thead>
            <tr>
                <th>Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($perMinggu as $minggu => $jumlah)
            <tr>
                <td>{{ $jumlah }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Penjualan Per Bulan</h4>
    <table>
        <thead>
            <tr>
                <th>Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($perBulan as $bulan => $jumlah)
            <tr>
                <td>{{ $jumlah }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Total Jumlah Produk Terjual: {{ $total }}</h4>

    <script>
    window.print();
    </script>
</body>

</html>