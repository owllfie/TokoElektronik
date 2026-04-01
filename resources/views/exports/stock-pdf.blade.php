<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1b1b1b;
            margin: 24px;
            font-size: 12px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        .meta {
            margin: 0 0 16px;
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
    </style>
</head>
<body>
    @include('exports.stock-table', ['title' => $title, 'stocks' => $stocks, 'meta' => $meta])
</body>
</html>
