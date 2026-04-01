@extends('layouts.app')

@section('title', 'Toko Elektronik | Report')
@section('brand_mark', 'E')
@section('brand_name', 'Electro')

@section('styles')
    :root {
        --ink: #1b1b1b;
        --muted: #6b6b6b;
        --accent: #0f6b5c;
        --accent-2: #f0b429;
        --danger: #b42318;
        --shadow: rgba(15, 34, 29, 0.12);
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        font-family: "Calibri", "Segoe UI", sans-serif;
        color: var(--ink);
        background:
            radial-gradient(circle at 15% 20%, rgba(240, 180, 41, 0.2), transparent 50%),
            radial-gradient(circle at 85% 10%, rgba(15, 107, 92, 0.18), transparent 55%),
            linear-gradient(120deg, #fef4e8 0%, #f6f1ff 40%, #f0f9f6 100%);
        min-height: 100vh;
    }

    .layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        min-height: 100vh;
        transition: grid-template-columns 0.2s ease;
    }

    aside {
        padding: 32px 20px;
        background: rgba(255, 255, 255, 0.6);
        border-right: 1px solid rgba(15, 107, 92, 0.12);
        display: flex;
        flex-direction: column;
        gap: 24px;
        backdrop-filter: blur(8px);
    }

    .layout.collapsed {
        grid-template-columns: 80px 1fr;
    }

    .layout.collapsed aside {
        padding: 24px 12px;
    }

    .toggle {
        border: none;
        background: rgba(15, 107, 92, 0.12);
        color: var(--ink);
        border-radius: 10px;
        padding: 8px 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        text-align: left;
    }

    .layout.collapsed .brand,
    .layout.collapsed .side-nav a,
    .layout.collapsed .side-nav button {
        justify-content: center;
        text-align: center;
    }

    .layout.collapsed .brand div,
    .layout.collapsed .side-nav span,
    .layout.collapsed small {
        display: none;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 14px;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .brand-mark {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: linear-gradient(135deg, #0f6b5c, #0d3d36);
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 20px;
        box-shadow: 0 12px 30px var(--shadow);
    }

    .side-nav {
        display: grid;
        gap: 12px;
        font-size: 15px;
    }

    .side-nav a,
    .side-nav button {
        text-decoration: none;
        color: var(--ink);
        padding: 10px 14px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(15, 107, 92, 0.18);
        font-family: inherit;
        cursor: pointer;
        text-align: left;
    }

    main {
        padding: 32px 6vw;
    }

    .panel {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 20px 40px var(--shadow);
        display: grid;
        gap: 16px;
    }

    .filters {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        background: rgba(15, 107, 92, 0.06);
        padding: 14px;
        border-radius: 14px;
    }

    .filters label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
    }

    .filters input,
    .filters select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.15);
        font-family: inherit;
        font-size: 14px;
    }

    .filters button {
        padding: 10px 14px;
        border-radius: 10px;
        border: none;
        background: var(--accent);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }

    .filter-actions {
        display: flex;
        align-items: end;
        gap: 10px;
    }

    .filter-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid rgba(15, 107, 92, 0.18);
        color: var(--ink);
        text-decoration: none;
        font-weight: 700;
        background: #fff;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
    }

    .export-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .export-actions a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid rgba(15, 107, 92, 0.18);
        color: var(--ink);
        text-decoration: none;
        font-weight: 700;
        background: #fff;
    }

    .summary-card {
        background: linear-gradient(180deg, rgba(15, 107, 92, 0.08), rgba(255, 255, 255, 0.96));
        border: 1px solid rgba(15, 107, 92, 0.12);
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 12px 24px rgba(15, 34, 29, 0.08);
    }

    .summary-card p {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .summary-card strong {
        display: block;
        margin-top: 8px;
        font-size: 24px;
        color: var(--ink);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
    }

    thead {
        background: rgba(15, 107, 92, 0.08);
    }

    th,
    td {
        padding: 12px 14px;
        text-align: left;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        font-size: 14px;
        vertical-align: top;
    }

    .empty-state {
        margin: 0;
        color: var(--muted);
    }

    .error-list {
        padding: 14px 16px;
        background: rgba(180, 35, 24, 0.08);
        border: 1px solid rgba(180, 35, 24, 0.18);
        color: var(--danger);
        border-radius: 14px;
    }

    .error-list p {
        margin: 0;
    }

    @media (max-width: 900px) {
        .layout { grid-template-columns: 1fr; }
        aside {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
        .side-nav {
            grid-auto-flow: column;
            grid-template-columns: unset;
        }
    }
@endsection

@section('content')
    <main>
        <div class="panel">
            <h1>Reports</h1>
            <p>Filter stock data by date range.</p>
            @if($errors->any())
                <div class="error-list">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form class="filters" method="GET" action="{{ route('report') }}">
                <div>
                    <label for="start_date">Start Date</label>
                    <input id="start_date" name="start_date" type="date" value="{{ $startDate }}" />
                </div>
                <div>
                    <label for="end_date">End Date</label>
                    <input id="end_date" name="end_date" type="date" value="{{ $endDate }}" />
                </div>
                <div class="filter-actions">
                    <button type="submit">Apply</button>
                    <a class="filter-reset" href="{{ route('report') }}">Reset</a>
                </div>
            </form>

            <div class="export-actions">
                <a href="{{ route('report.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" rel="noopener noreferrer">Print</a>
                <a href="{{ route('report.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}">PDF</a>
                <a href="{{ route('report.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}">Excel</a>
            </div>

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
                            <td>Rp {{ number_format($stock->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($stock->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <p class="empty-state">No stock data found for this date range.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @include('partials.pagination', ['paginator' => $stocks])
        </div>
    </main>
@endsection
