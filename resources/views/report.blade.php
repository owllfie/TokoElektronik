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
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
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
            <p>Placeholder report view with filters and a summary table.</p>
            <form class="filters">
                <div>
                    <label for="range">Date Range</label>
                    <input id="range" type="text" placeholder="Mar 1 - Mar 30" />
                </div>
                <div>
                    <label for="category">Category</label>
                    <select id="category">
                        <option>All</option>
                        <option>Audio</option>
                        <option>Wearables</option>
                        <option>Cameras</option>
                    </select>
                </div>
                <div>
                    <label for="channel">Channel</label>
                    <select id="channel">
                        <option>All</option>
                        <option>In-store</option>
                        <option>Online</option>
                    </select>
                </div>
                <div>
                    <label for="metric">Metric</label>
                    <select id="metric">
                        <option>Revenue</option>
                        <option>Units</option>
                        <option>Margin</option>
                    </select>
                </div>
                <div style="display:flex; align-items:end;">
                    <button type="button">Apply</button>
                </div>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Category</th>
                        <th>Units</th>
                        <th>Revenue</th>
                        <th>Margin</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Week 1</td>
                        <td>Audio</td>
                        <td>420</td>
                        <td>Rp 98,500,000</td>
                        <td>22%</td>
                    </tr>
                    <tr>
                        <td>Week 2</td>
                        <td>Wearables</td>
                        <td>310</td>
                        <td>Rp 76,200,000</td>
                        <td>18%</td>
                    </tr>
                    <tr>
                        <td>Week 3</td>
                        <td>Cameras</td>
                        <td>185</td>
                        <td>Rp 64,800,000</td>
                        <td>25%</td>
                    </tr>
                    <tr>
                        <td>Week 4</td>
                        <td>All</td>
                        <td>1,240</td>
                        <td>Rp 289,700,000</td>
                        <td>21%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
@endsection
