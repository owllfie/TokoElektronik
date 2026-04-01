<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            color: #1b1b1b;
            margin: 24px;
        }

        h1 {
            margin: 0 0 8px;
        }

        .meta {
            margin-bottom: 18px;
            color: #555;
        }

        .meta p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d9d9d9;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #eef6f4;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">Print</button>
    @include('exports.stock-table', ['title' => $title, 'stocks' => $stocks, 'meta' => $meta])
</body>
</html>
