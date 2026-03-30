<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Toko Elektronik | Stock</title>
    <style>
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

        .tag {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            background: rgba(240, 180, 41, 0.25);
            color: #5c3a00;
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
    </style>
</head>
<body>
    @php($user = session('user'))
    <div class="layout">
        <aside>
            <div class="brand">
                <div class="brand-mark">TE</div>
                <div>Toko Elektronik</div>
            </div>
            <nav class="side-nav">
                <button class="toggle" type="button" id="toggleSidebar">Menu</button>
                <a href="/home"><span>Dashboard</span></a>
                <a href="/report"><span>Report</span></a>
                <a href="/stock"><span>Stock</span></a>
                @if($user)
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="/login">Login</a>
                @endif
            </nav>
        </aside>

        <main>
            <div class="panel">
                <h1>Stock</h1>
                <p>Placeholder stock view. Sample inventory table below.</p>
                <table>
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>On Hand</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>EL-1001</td>
                            <td>Wireless Earbuds Pro</td>
                            <td>Audio</td>
                            <td>42</td>
                            <td><span class="tag">Healthy</span></td>
                        </tr>
                        <tr>
                            <td>EL-2033</td>
                            <td>Smartwatch Active</td>
                            <td>Wearables</td>
                            <td>9</td>
                            <td><span class="tag">Low</span></td>
                        </tr>
                        <tr>
                            <td>EL-3050</td>
                            <td>Portable Speaker</td>
                            <td>Audio</td>
                            <td>18</td>
                            <td><span class="tag">Reorder</span></td>
                        </tr>
                        <tr>
                            <td>EL-4108</td>
                            <td>4K Action Camera</td>
                            <td>Cameras</td>
                            <td>27</td>
                            <td><span class="tag">Healthy</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script>
        const layout = document.querySelector('.layout');
        const toggle = document.getElementById('toggleSidebar');
        toggle.addEventListener('click', () => {
            layout.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
