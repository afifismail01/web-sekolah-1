<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pengumuman</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
            white-space: normal;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h2>Data Pengumuman - {{ date('Y-m-d') }}</h2>
    <table class="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($announcements as $index => $announcement)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $announcement->user?->name }}</td>
                    <td>{{ $announcement->status->value ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
