@extends('layouts.app')

@section('title', 'Toko Elektronik | Stock')
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

    .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn {
        border: none;
        padding: 8px 12px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 700;
        font-size: 12px;
    }

    .btn-update {
        background: rgba(15, 107, 92, 0.12);
        color: var(--ink);
        border: 1px solid rgba(15, 107, 92, 0.2);
    }

    .btn-delete {
        background: rgba(180, 35, 24, 0.12);
        color: var(--danger);
        border: 1px solid rgba(180, 35, 24, 0.25);
    }

    .toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
    }

    .search {
        display: flex;
        gap: 8px;
        align-items: center;
        flex: 1 1 260px;
    }

    .search input {
        width: min(360px, 100%);
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.15);
        font-family: inherit;
    }

    .add-btn {
        padding: 10px 14px;
        border-radius: 10px;
        border: none;
        background: var(--accent);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }

    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.35);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        z-index: 50;
    }

    .section-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .section-header h2 {
        margin: 0;
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

    .modal {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        width: min(460px, 100%);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.2);
        display: grid;
        gap: 12px;
    }

    .modal header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
    }

    .modal input,
    .modal select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.15);
        font-family: inherit;
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
            <h1>Stock</h1>
            <div class="toolbar">
                <form class="search" method="GET" action="{{ route('stock') }}" id="searchForm">
                    <input type="text" name="q" placeholder="Search stock..." value="{{ $search ?? '' }}" id="searchInput" autocomplete="off" />
                </form>
                <button class="add-btn" type="button" data-modal="add">Add Stock</button>
            </div>
            @if(session('status'))
                <p>{{ session('status') }}</p>
            @endif
            @if($errors->any())
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="section-header">
                <h2>Stock In</h2>
                <div class="export-actions">
                    <a href="{{ route('stock.print', ['type' => 'in', 'q' => $search]) }}" target="_blank" rel="noopener noreferrer">Print</a>
                    <a href="{{ route('stock.pdf', ['type' => 'in', 'q' => $search]) }}">PDF</a>
                    <a href="{{ route('stock.excel', ['type' => 'in', 'q' => $search]) }}">Excel</a>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Harga</th>
                        <th>Tanggal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockIn as $stock)
                        <tr>
                            <td>{{ $stock->id_barang }}</td>
                            <td>{{ $stock->nama_barang ?? '-' }}</td>
                            <td>{{ $stock->jumlah }}</td>
                            <td>{{ $stock->harga_satuan }}</td>
                            <td>{{ $stock->total_harga }}</td>
                            <td>{{ optional($stock->created_at)->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                <div class="actions">
                                    <button
                                        class="btn btn-update"
                                        type="button"
                                        data-modal="update"
                                        data-id="{{ $stock->getKey() }}"
                                        data-id-barang="{{ $stock->id_barang }}"
                                        data-jumlah="{{ $stock->jumlah }}"
                                        data-harga-satuan="{{ $stock->harga_satuan }}"
                                        data-total-harga="{{ $stock->total_harga }}"
                                        data-tipe="{{ $stock->tipe }}"
                                    >
                                        Update
                                    </button>
                                    <form method="POST" action="{{ route('stock.destroy', $stock->getKey()) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-delete" type="submit" onclick="return confirm('Delete this stock?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No stock in found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @include('partials.pagination', ['paginator' => $stockIn])

            <div class="section-header">
                <h2>Stock Out</h2>
                <div class="export-actions">
                    <a href="{{ route('stock.print', ['type' => 'out', 'q' => $search]) }}" target="_blank" rel="noopener noreferrer">Print</a>
                    <a href="{{ route('stock.pdf', ['type' => 'out', 'q' => $search]) }}">PDF</a>
                    <a href="{{ route('stock.excel', ['type' => 'out', 'q' => $search]) }}">Excel</a>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Harga</th>
                        <th>Tanggal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockOut as $stock)
                        <tr>
                            <td>{{ $stock->id_barang }}</td>
                            <td>{{ $stock->nama_barang ?? '-' }}</td>
                            <td>{{ $stock->jumlah }}</td>
                            <td>{{ $stock->harga_satuan }}</td>
                            <td>{{ $stock->total_harga }}</td>
                            <td>{{ optional($stock->created_at)->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                <div class="actions">
                                    <button
                                        class="btn btn-update"
                                        type="button"
                                        data-modal="update"
                                        data-id="{{ $stock->getKey() }}"
                                        data-id-barang="{{ $stock->id_barang }}"
                                        data-jumlah="{{ $stock->jumlah }}"
                                        data-harga-satuan="{{ $stock->harga_satuan }}"
                                        data-total-harga="{{ $stock->total_harga }}"
                                        data-tipe="{{ $stock->tipe }}"
                                    >
                                        Update
                                    </button>
                                    <form method="POST" action="{{ route('stock.destroy', $stock->getKey()) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-delete" type="submit" onclick="return confirm('Delete this stock?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No stock out found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @include('partials.pagination', ['paginator' => $stockOut])
        </div>
    </main>

    <div class="modal-backdrop" id="modalBackdrop">
        <div class="modal" role="dialog" aria-modal="true">
            <header>
                <h2 id="modalTitle">Add Stock</h2>
                <button class="btn" type="button" id="modalClose">Close</button>
            </header>
            <form method="POST" id="modalForm">
                @csrf
                <input type="hidden" name="_method" id="modalMethod" value="POST" />
                <div>
                    <label for="modalIdBarang">ID Barang</label>
                    <input id="modalIdBarang" name="id_barang" type="text" list="barangList" required />
                    <datalist id="barangList">
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id_barang }}" label="{{ $barang->nama_barang }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label for="modalJumlah">Jumlah</label>
                    <input id="modalJumlah" name="jumlah" type="number" min="0" required />
                </div>
                <div>
                    <label for="modalHargaSatuan">Harga Satuan</label>
                    <input id="modalHargaSatuan" name="harga_satuan" type="number" min="0" required />
                </div>
                <div>
                    <label for="modalTotalHarga">Total Harga</label>
                    <input id="modalTotalHarga" name="total_harga" type="number" min="0" required />
                </div>
                <div>
                    <label for="modalTipe">Tipe</label>
                    <select id="modalTipe" name="tipe" required>
                        <option value="" disabled selected>-- Select Tipe --</option>
                        <option value="in">Stock In</option>
                        <option value="out">Stock Out</option>
                    </select>
                </div>
                <div class="actions">
                    <button class="btn btn-update" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const backdrop = document.getElementById('modalBackdrop');
    const modalForm = document.getElementById('modalForm');
    const modalTitle = document.getElementById('modalTitle');
    const modalMethod = document.getElementById('modalMethod');
    const modalIdBarang = document.getElementById('modalIdBarang');
    const modalJumlah = document.getElementById('modalJumlah');
    const modalHargaSatuan = document.getElementById('modalHargaSatuan');
    const modalTotalHarga = document.getElementById('modalTotalHarga');
    const modalTipe = document.getElementById('modalTipe');
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');

    const openModal = (mode, data = {}) => {
        if (mode === 'add') {
            modalTitle.textContent = 'Add Stock';
            modalForm.action = "{{ route('stock.store') }}";
            modalMethod.value = 'POST';
            modalIdBarang.value = '';
            modalJumlah.value = '';
            modalHargaSatuan.value = '';
            modalTotalHarga.value = '';
            modalTipe.value = '';
        } else {
            modalTitle.textContent = 'Update Stock';
            modalForm.action = "{{ url('/stock') }}/" + data.id;
            modalMethod.value = 'PUT';
            modalIdBarang.value = data.idBarang || '';
            modalJumlah.value = data.jumlah || '';
            modalHargaSatuan.value = data.hargaSatuan || '';
            modalTotalHarga.value = data.totalHarga || '';
            modalTipe.value = data.tipe || '';
        }
        backdrop.style.display = 'flex';
    };

    document.querySelectorAll('[data-modal]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const mode = btn.getAttribute('data-modal');
            if (mode === 'add') {
                openModal('add');
                return;
            }
            openModal('update', {
                id: btn.getAttribute('data-id'),
                idBarang: btn.getAttribute('data-id-barang'),
                jumlah: btn.getAttribute('data-jumlah'),
                hargaSatuan: btn.getAttribute('data-harga-satuan'),
                totalHarga: btn.getAttribute('data-total-harga'),
                tipe: btn.getAttribute('data-tipe'),
            });
        });
    });

    document.getElementById('modalClose').addEventListener('click', () => {
        backdrop.style.display = 'none';
    });

    backdrop.addEventListener('click', (event) => {
        if (event.target === backdrop) {
            backdrop.style.display = 'none';
        }
    });

    if (searchInput && searchForm) {
        let searchTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                searchForm.submit();
            }, 300);
        });
    }
</script>
@endsection
