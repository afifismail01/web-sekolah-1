<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Berkas {{ $student->name }}</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        h2 {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        a {
            color: #3490dc;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <h2>Daftar Berkas {{ $student->name }}</h2>
    <table>
        <thead>
            <tr>
                <th>Nama File</th>
                <th>Lihat</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($files as $file)
                <tr>
                    @php
                        $fileNameWithExt = $file->file_name . '.' . pathinfo($file->file_path, PATHINFO_EXTENSION);
                    @endphp
                    <td>{{ $file->file_name }}</td>
                    <td><a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">Lihat</a></td>

                    {{-- agar nama file download sama dengan file yang telah diupload --}}
                    <td>
                        <a href="{{ asset('storage/' . $file->file_path) }}"
                            download="{{ $fileNameWithExt }}">Download</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Tidak ada berkas yang diunggah</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
