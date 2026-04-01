<h1>{{ $title }}</h1>

@if (!empty($meta))
    <div class="meta">
        @foreach ($meta as $label => $value)
            <p><strong>{{ $label }}:</strong> {{ $value }}</p>
        @endforeach
    </div>
@endif

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>ID Barang</th>
            <th>Nama Barang</th>
            <th>Tipe</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($stocks as $stock)
            <tr>
                <td>{{ optional($stock->created_at)->format('d M Y H:i') ?? '-' }}</td>
                <td>{{ $stock->id_barang }}</td>
                <td>{{ $stock->nama_barang ?? '-' }}</td>
                <td>{{ strtoupper($stock->tipe) }}</td>
                <td>{{ number_format($stock->jumlah) }}</td>
                <td>{{ number_format($stock->harga_satuan, 0, ',', '.') }}</td>
                <td>{{ number_format($stock->total_harga, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
