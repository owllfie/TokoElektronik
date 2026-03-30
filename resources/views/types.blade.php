@extends('layouts.app')

@section('title', 'Toko Elektronik | Types')
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

    .modal input {
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
            <h1>Item Types</h1>
            <div class="toolbar">
                <form class="search" method="GET" action="{{ route('types') }}" id="searchForm">
                    <input type="text" name="q" placeholder="Search types..." value="{{ $search ?? '' }}" id="searchInput" autocomplete="off" />
                </form>
                <button class="add-btn" type="button" data-modal="add">Add Type</button>
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
            <table>
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($types as $type)
                        <tr>
                            <td>{{ $type->tipe }}</td>
                            <td>
                                <div class="actions">
                                    <button
                                        class="btn btn-update"
                                        type="button"
                                        data-modal="update"
                                        data-id="{{ $type->getKey() }}"
                                        data-tipe="{{ $type->tipe }}"
                                    >
                                        Update
                                    </button>
                                    <form method="POST" action="{{ route('types.destroy', $type->getKey()) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-delete" type="submit" onclick="return confirm('Delete this type?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No types found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $types->links() }}
        </div>
    </main>

    <div class="modal-backdrop" id="modalBackdrop">
        <div class="modal" role="dialog" aria-modal="true">
            <header>
                <h2 id="modalTitle">Add Type</h2>
                <button class="btn" type="button" id="modalClose">Close</button>
            </header>
            <form method="POST" id="modalForm">
                @csrf
                <input type="hidden" name="_method" id="modalMethod" value="POST" />
                <div>
                    <label for="modalTipe">Tipe</label>
                    <input id="modalTipe" name="tipe" type="text" required />
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
    const modalTipe = document.getElementById('modalTipe');
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');

    const openModal = (mode, data = {}) => {
        if (mode === 'add') {
            modalTitle.textContent = 'Add Type';
            modalForm.action = "{{ route('types.store') }}";
            modalMethod.value = 'POST';
            modalTipe.value = '';
        } else {
            modalTitle.textContent = 'Update Type';
            modalForm.action = "{{ url('/types') }}/" + data.id;
            modalMethod.value = 'PUT';
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
