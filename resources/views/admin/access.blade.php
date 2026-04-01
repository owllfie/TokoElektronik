@extends('layouts.app')

@section('title', 'Toko Elektronik | Manage Access')

@section('styles')
    :root {
        --ink: #1b1b1b;
        --accent: #0f6b5c;
        --shadow: rgba(15, 34, 29, 0.12);
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        font-family: "Calibri", "Segoe UI", sans-serif;
        background:
            radial-gradient(circle at 10% 10%, rgba(15, 107, 92, 0.14), transparent 35%),
            linear-gradient(135deg, #f7fbff 0%, #fdf7eb 100%);
        min-height: 100vh;
    }

    .layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        min-height: 100vh;
    }

    aside {
        padding: 32px 20px;
        background: rgba(255, 255, 255, 0.72);
        border-right: 1px solid rgba(15, 107, 92, 0.15);
        backdrop-filter: blur(8px);
    }

    main {
        padding: 32px 6vw;
    }

    .panel {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 20px 40px var(--shadow);
        padding: 24px;
        display: grid;
        gap: 18px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 14px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        text-align: left;
        vertical-align: middle;
    }

    thead {
        background: rgba(15, 107, 92, 0.08);
    }

    .status {
        color: var(--accent);
        font-weight: 700;
        margin: 0;
    }

    .hint {
        color: #666;
        margin: 0;
    }

    .btn {
        border: none;
        border-radius: 12px;
        padding: 12px 16px;
        font-weight: 700;
        cursor: pointer;
        background: var(--accent);
        color: #fff;
        width: fit-content;
    }

    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--accent);
    }

    @media (max-width: 900px) {
        .layout { grid-template-columns: 1fr; }
        table { display: block; overflow-x: auto; }
    }
@endsection

@section('content')
    <main>
        <div class="panel">
            <div>
                <h1>Manage Access</h1>
                <p class="hint">Choose which role can open each page. Superadmin is always allowed and is not shown here.</p>
            </div>

            @if (session('status'))
                <p class="status">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('admin.access.update') }}">
                @csrf
                @method('PUT')
                <table>
                    <thead>
                        <tr>
                            <th>Page</th>
                            @foreach ($roles as $role)
                                <th>{{ $role->role }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $pageKey => $pageLabel)
                            <tr>
                                <td>{{ $pageLabel }}</td>
                                @foreach ($roles as $role)
                                    @php
                                        $checked = (bool) ($accessMap[$pageKey][$role->id_role] ?? false);
                                    @endphp
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="access[{{ $pageKey }}][{{ $role->id_role }}]"
                                            value="1"
                                            {{ $checked ? 'checked' : '' }}
                                            {{ (int) $role->id_role === 4 ? 'disabled' : '' }}
                                        >
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn" type="submit">Save Access</button>
            </form>
        </div>
    </main>
@endsection
