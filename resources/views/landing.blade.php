<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Toko Elektronik | Welcome</title>
    <style>
        :root {
            --ink: #1b1b1b;
            --muted: #6b6b6b;
            --accent: #0f6b5c;
            --accent-2: #f0b429;
            --shadow: rgba(15, 34, 29, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Calibri", "Segoe UI", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 20% 20%, rgba(240, 180, 41, 0.2), transparent 55%),
                radial-gradient(circle at 85% 15%, rgba(15, 107, 92, 0.18), transparent 55%),
                linear-gradient(135deg, #fff2e5 0%, #f6f1ff 40%, #f0f9f6 100%);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .shell {
            width: min(1000px, 100%);
            background: rgba(255, 255, 255, 0.86);
            border-radius: 28px;
            padding: 36px;
            box-shadow: 0 30px 60px var(--shadow);
            border: 1px solid rgba(15, 107, 92, 0.12);
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 32px;
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

        nav {
            display: flex;
            gap: 12px;
        }

        nav a {
            text-decoration: none;
            color: var(--ink);
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(15, 107, 92, 0.2);
            font-weight: 600;
        }

        .hero {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 28px;
            align-items: center;
        }

        .hero h1 {
            margin: 0 0 12px;
            font-size: clamp(2rem, 3.2vw, 3.2rem);
        }

        .hero p {
            margin: 0 0 18px;
            color: var(--muted);
            line-height: 1.6;
        }

        .cta-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .cta {
            padding: 12px 18px;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            box-shadow: 0 12px 24px rgba(15, 107, 92, 0.25);
        }

        .cta.secondary {
            background: transparent;
            color: var(--accent);
            border: 1px solid var(--accent);
            box-shadow: none;
        }

        .highlights {
            display: grid;
            gap: 12px;
        }

        .highlight {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 720px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <header>
            <div class="brand">
                <div class="brand-mark">TE</div>
                <div>
                    <div>Toko Elektronik</div>
                </div>
            </div>
        </header>

        <section class="hero">
            <div>
                <h1>Operate your electronics store with confidence.</h1>
                <p>
                    A single workspace for inventory, sales signals, and staff coordination.
                    Built for fast-moving retail teams.
                </p>
                <div class="cta-row">
                    <a class="cta" href="/login">Sign In</a>
                </div>
            </div>
            <div class="highlights">
                <div class="highlight">
                    <strong>Inventory Pulse</strong>
                    <p>Spot fast-moving SKUs before they go out of stock.</p>
                </div>
                <div class="highlight">
                    <strong>Daily Ops</strong>
                    <p>Keep restocks, promos, and staff tickets in sync.</p>
                </div>
                <div class="highlight">
                    <strong>Revenue Signals</strong>
                    <p>Track category performance without extra reports.</p>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
