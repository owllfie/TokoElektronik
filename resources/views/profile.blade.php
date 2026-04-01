@extends('layouts.app')

@section('title', 'Toko Elektronik | Profile')
@section('brand_mark', 'E')
@section('brand_name', 'Electro')

@section('styles')
    :root {
        --ink: #1b1b1b;
        --muted: #6b6b6b;
        --accent: #0f6b5c;
        --accent-2: #f0b429;
        --shadow: rgba(15, 34, 29, 0.12);
        --surface: rgba(255, 255, 255, 0.82);
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
        display: grid;
        align-content: start;
    }

    .panel {
        max-width: 720px;
        background: var(--surface);
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 20px 40px var(--shadow);
        display: grid;
        gap: 20px;
        border: 1px solid rgba(15, 107, 92, 0.12);
    }

    .intro h1,
    .intro p {
        margin: 0;
    }

    .intro p {
        color: var(--muted);
        margin-top: 8px;
    }

    .flash {
        padding: 12px 14px;
        border-radius: 12px;
        background: rgba(15, 107, 92, 0.12);
        border: 1px solid rgba(15, 107, 92, 0.18);
    }

    .errors {
        padding: 12px 14px;
        border-radius: 12px;
        background: rgba(180, 35, 24, 0.08);
        border: 1px solid rgba(180, 35, 24, 0.18);
    }

    .errors p {
        margin: 0;
    }

    form {
        display: grid;
        gap: 16px;
    }

    .field {
        display: grid;
        gap: 8px;
    }

    label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
    }

    input {
        width: 100%;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.15);
        font-family: inherit;
        font-size: 15px;
        background: #fff;
    }

    .hint {
        color: var(--muted);
        font-size: 13px;
    }

    .actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-primary {
        border: none;
        padding: 12px 16px;
        border-radius: 12px;
        background: var(--accent);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }

    .meta {
        display: grid;
        gap: 8px;
        padding-top: 4px;
        color: var(--muted);
        font-size: 14px;
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
        <section class="panel">
            <div class="intro">
                <h1>My Profile</h1>
                <p>Update your own account data here.</p>
            </div>

            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="errors">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" required />
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
                </div>

                <div class="field">
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password" />
                    <div class="hint">Leave blank if you do not want to change your password.</div>
                </div>

                <div class="actions">
                    <button class="btn-primary" type="submit">Save Changes</button>
                </div>
            </form>

            <div class="meta">
                <div>Role ID: {{ $user->role ?? '-' }}</div>
                <div>User ID: {{ $user->getKey() }}</div>
            </div>
        </section>
    </main>
@endsection
