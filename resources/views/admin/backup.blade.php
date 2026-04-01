@extends('layouts.app')

@section('title', 'Toko Elektronik | Backup Database')

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
            radial-gradient(circle at top right, rgba(240, 180, 41, 0.18), transparent 30%),
            linear-gradient(135deg, #f5faf8 0%, #fff8ee 100%);
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

    .hero {
        display: grid;
        gap: 10px;
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

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 14px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        text-align: left;
    }

    thead {
        background: rgba(15, 107, 92, 0.08);
    }

    .muted {
        color: #666;
        margin: 0;
    }

    @media (max-width: 900px) {
        .layout { grid-template-columns: 1fr; }
        table { display: block; overflow-x: auto; }
    }
@endsection

@section('content')
    <main>
        <div class="panel">
            <div class="hero">
                <h1>Backup Database</h1>
                <p class="muted">Click the button to generate a fresh SQL backup file and download it immediately.</p>
            </div>

            <form method="POST" action="{{ route('admin.backup.run') }}">
                @csrf
                <button class="btn" type="submit">Backup Now</button>
            </form>

            <div>
                <h2>Saved Backup Files</h2>
                <table>
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Modified At</th>
                            <th>Size</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($backups as $backup)
                            <tr>
                                <td>{{ $backup['name'] }}</td>
                                <td>{{ $backup['modified_at'] }}</td>
                                <td>{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No backup file has been generated yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
