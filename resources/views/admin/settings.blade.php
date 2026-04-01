@extends('layouts.app')

@section('title', 'Toko Elektronik | Web Settings')

@section('styles')
    :root {
        --ink: #1b1b1b;
        --accent: #0f6b5c;
        --border: rgba(15, 107, 92, 0.18);
        --shadow: rgba(15, 34, 29, 0.12);
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        font-family: "Calibri", "Segoe UI", sans-serif;
        color: var(--ink);
        background:
            radial-gradient(circle at top left, rgba(240, 180, 41, 0.18), transparent 35%),
            linear-gradient(135deg, #fef7ea 0%, #f5fbf8 55%, #edf3ff 100%);
        min-height: 100vh;
    }

    .layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        min-height: 100vh;
    }

    aside {
        padding: 32px 20px;
        background: rgba(255, 255, 255, 0.7);
        border-right: 1px solid var(--border);
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

    .field-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    label {
        display: grid;
        gap: 8px;
        font-weight: 700;
        font-size: 14px;
    }

    input {
        width: 100%;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.12);
        font-family: inherit;
        font-size: 15px;
    }

    .actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn {
        border: none;
        border-radius: 12px;
        padding: 12px 16px;
        font-weight: 700;
        cursor: pointer;
        background: var(--accent);
        color: #fff;
    }

    .status {
        color: var(--accent);
        font-weight: 700;
        margin: 0;
    }

    .preview {
        display: grid;
        gap: 10px;
        padding: 18px;
        border-radius: 18px;
        border: 1px solid var(--border);
        background: rgba(15, 107, 92, 0.05);
    }

    .badge {
        width: 56px;
        height: 56px;
        display: grid;
        place-items: center;
        border-radius: 16px;
        background: linear-gradient(135deg, #0f6b5c, #0d3d36);
        color: #fff;
        font-size: 22px;
        font-weight: 700;
    }

    @media (max-width: 900px) {
        .layout { grid-template-columns: 1fr; }
        .field-grid { grid-template-columns: 1fr; }
    }
@endsection

@section('content')
    <main>
        <div class="panel">
            <div>
                <h1>Web Settings</h1>
                <p>Update the company identity shown across the application.</p>
            </div>

            @if (session('status'))
                <p class="status">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" class="panel">
                @csrf
                @method('PUT')
                <div class="field-grid">
                    <label>
                        Company Name
                        <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name'] ?? '') }}" required>
                    </label>
                    <label>
                        Company Mark
                        <input type="text" name="company_mark" value="{{ old('company_mark', $settings['company_mark'] ?? '') }}" maxlength="10" required>
                    </label>
                </div>
                <div class="actions">
                    <button class="btn" type="submit">Save Settings</button>
                </div>
            </form>

            <div class="preview">
                <div class="badge">{{ $settings['company_mark'] ?? 'E' }}</div>
                <strong>{{ $settings['company_name'] ?? 'Electro' }}</strong>
                <span>This preview matches the sidebar branding.</span>
            </div>
        </div>
    </main>
@endsection
