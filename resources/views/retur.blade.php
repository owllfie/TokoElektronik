@extends('layouts.app')

@section('title', 'Toko Elektronik | Retur')
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

    .toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
    }

    .tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .tab-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 14px;
        border-radius: 999px;
        border: 1px solid rgba(15, 107, 92, 0.18);
        color: var(--ink);
        text-decoration: none;
        font-weight: 700;
        background: #fff;
    }

    .tab-link.active {
        background: var(--accent);
        color: #fff;
        border-color: transparent;
    }

    .search {
        display: flex;
        gap: 8px;
        align-items: center;
        flex: 1 1 260px;
    }

    .search input,
    .modal input,
    .modal select,
    .modal textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.15);
        font-family: inherit;
    }

    .search input {
        width: min(360px, 100%);
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

    .btn-restore {
        background: rgba(240, 180, 41, 0.18);
        color: #6a4b00;
        border: 1px solid rgba(240, 180, 41, 0.28);
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

    .modal {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        width: min(520px, 100%);
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

    .modal textarea {
        min-height: 120px;
        resize: vertical;
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
    @php
        $formatHistoryState = function (?array $state) {
            if (empty($state)) {
                return '-';
            }

            return collect($state)
                ->only(['id_barang', 'nama_barang', 'keterangan', 'tanggal_retur'])
                ->map(function ($value, $key) {
                    $label = match ($key) {
                        'id_barang' => 'ID Barang',
                        'nama_barang' => 'Nama Barang',
                        'keterangan' => 'Keterangan',
                        'tanggal_retur' => 'Tanggal Retur',
                        default => ucfirst(str_replace('_', ' ', $key)),
                    };

                    return $label . ': ' . ($value === null || $value === '' ? '-' : $value);
                })
                ->implode(' | ');
        };
    @endphp
    <main>
        <div class="panel">
            <h1>Retur</h1>
            <div class="toolbar">
                <form class="search" method="GET" action="{{ route('retur') }}" id="searchForm">
                    <input type="hidden" name="tab" value="{{ $tab }}" />
                    <input type="text" name="q" placeholder="Search retur..." value="{{ $search ?? '' }}" id="searchInput" autocomplete="off" />
                </form>
                @if ($tab === 'now')
                    <button class="add-btn" type="button" data-modal="add">Add Retur</button>
                @endif
            </div>
            <div class="tabs">
                <a class="tab-link {{ $tab === 'now' ? 'active' : '' }}" href="{{ route('retur', ['tab' => 'now', 'q' => $search]) }}">Now</a>
                <a class="tab-link {{ $tab === 'trash' ? 'active' : '' }}" href="{{ route('retur', ['tab' => 'trash', 'q' => $search]) }}">Trash Bin</a>
                <a class="tab-link {{ $tab === 'history' ? 'active' : '' }}" href="{{ route('retur', ['tab' => 'history', 'q' => $search]) }}">Update History</a>
            </div>
            @if (session('status'))
                <p>{{ session('status') }}</p>
            @endif
            @if ($errors->any())
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if ($tab === 'history')
                <table>
                    <thead>
                        <tr>
                            <th>Record ID</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Changed At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr>
                                <td>#{{ $history->record_id }}</td>
                                <td>{{ $formatHistoryState($history->before_state) }}</td>
                                <td>{{ $formatHistoryState($history->after_state) }}</td>
                                <td>{{ optional($history->created_at)->format('d M Y H:i') ?? '-' }}</td>
                                <td>
                                    <div class="actions">
                                        <form method="POST" action="{{ route('retur.history.revert', $history->id) }}">
                                            @csrf
                                            <button class="btn btn-update" type="submit" onclick="return confirm('Revert this update?')">Revert</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No update history found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @include('partials.pagination', ['paginator' => $histories])
            @else
                <table>
                    <thead>
                        <tr>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Keterangan</th>
                            <th>Tanggal Retur</th>
                            @if ($tab === 'trash')
                                <th>Deleted At</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returs as $retur)
                            <tr>
                                <td>{{ $retur->id_barang }}</td>
                                <td>{{ $retur->item?->nama_barang ?? '-' }}</td>
                                <td>{{ $retur->keterangan }}</td>
                                <td>{{ optional($retur->tanggal_retur)->format('d M Y') ?? '-' }}</td>
                                @if ($tab === 'trash')
                                    <td>{{ optional($retur->deleted_at)->format('d M Y H:i') ?? '-' }}</td>
                                @endif
                                <td>
                                    <div class="actions">
                                        @if ($tab === 'now')
                                            <button
                                                class="btn btn-update"
                                                type="button"
                                                data-modal="update"
                                                data-id="{{ $retur->getKey() }}"
                                                data-id-barang="{{ $retur->id_barang }}"
                                                data-keterangan="{{ $retur->keterangan }}"
                                                data-tanggal-retur="{{ optional($retur->tanggal_retur)->format('Y-m-d') }}"
                                            >
                                                Update
                                            </button>
                                            <form method="POST" action="{{ route('retur.destroy', $retur->getKey()) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-delete" type="submit" onclick="return confirm('Move this retur to trash?')">Delete</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('retur.restore', $retur->getKey()) }}">
                                                @csrf
                                                <button class="btn btn-restore" type="submit">Restore</button>
                                            </form>
                                            <form method="POST" action="{{ route('retur.force-delete', $retur->getKey()) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-delete" type="submit" onclick="return confirm('Delete this retur permanently?')">Delete Permanently</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $tab === 'trash' ? 6 : 5 }}">No retur found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @include('partials.pagination', ['paginator' => $returs])
            @endif
        </div>
    </main>

    <div class="modal-backdrop" id="modalBackdrop">
        <div class="modal" role="dialog" aria-modal="true">
            <header>
                <h2 id="modalTitle">Add Retur</h2>
                <button class="btn" type="button" id="modalClose">Close</button>
            </header>
            <form method="POST" id="modalForm">
                @csrf
                <input type="hidden" name="_method" id="modalMethod" value="POST" />
                <div>
                    <label for="modalIdBarang">Barang</label>
                    <select id="modalIdBarang" name="id_barang" required>
                        <option value="" disabled selected>-- Select Barang --</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id_barang }}">{{ $item->id_barang }} - {{ $item->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="modalKeterangan">Keterangan</label>
                    <textarea id="modalKeterangan" name="keterangan" required></textarea>
                </div>
                <div>
                    <label for="modalTanggalRetur">Tanggal Retur</label>
                    <input id="modalTanggalRetur" name="tanggal_retur" type="date" required />
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
    const modalKeterangan = document.getElementById('modalKeterangan');
    const modalTanggalRetur = document.getElementById('modalTanggalRetur');
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const modalClose = document.getElementById('modalClose');

    const openModal = (mode, data = {}) => {
        if (!backdrop) {
            return;
        }

        if (mode === 'add') {
            modalTitle.textContent = 'Add Retur';
            modalForm.action = "{{ route('retur.store') }}";
            modalMethod.value = 'POST';
            modalIdBarang.value = '';
            modalKeterangan.value = '';
            modalTanggalRetur.value = '';
        } else {
            modalTitle.textContent = 'Update Retur';
            modalForm.action = "{{ url('/retur') }}/" + data.id;
            modalMethod.value = 'PUT';
            modalIdBarang.value = data.idBarang || '';
            modalKeterangan.value = data.keterangan || '';
            modalTanggalRetur.value = data.tanggalRetur || '';
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
                keterangan: btn.getAttribute('data-keterangan'),
                tanggalRetur: btn.getAttribute('data-tanggal-retur'),
            });
        });
    });

    if (modalClose) {
        modalClose.addEventListener('click', () => {
            backdrop.style.display = 'none';
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', (event) => {
            if (event.target === backdrop) {
                backdrop.style.display = 'none';
            }
        });
    }

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
