<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Calon Siswa</title>
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
    <h2>Data Calon Siswa - {{ date('Y-m-d') }}</h2>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Tanggal Lahir</th>
                <th>Tempat Lahir</th>
                <th>Jenis Kelamin</th>
                <th>NIK</th>
                <th>Status</th>
                <th>Jalur Pendaftaran</th>
                <th>Jenjang Pendaftaran</th>
                <th>Kode Pos</th>
                <th>Nama Ayah</th>
                <th>Nama Ibu</th>
                <th>Nama Wali</th>
                <th>Pekerjaan Ayah</th>
                <th>Pekerjaan Ibu</th>
                <th>Pekerjaan Wali</th>
                <th>Nomor Telepon Ayah</th>
                <th>Nomor Telepon Ibu</th>
                <th>Nomor Telepon Wali</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->birth_date->format('d-m-Y') }}</td>
                    <td>{{ $student->birth_place }}</td>
                    <td>{{ $student->gender->value ?? '-' }}</td>
                    <td>{{ $student->national_id_number }}</td>
                    <td>{{ $student->status->value ?? '-' }}</td>
                    <td>{{ $student->admission_track->value ?? '-' }}</td>
                    <td>{{ $student->education_level->value ?? '-' }}</td>
                    <td>{{ $student->postal_code }}</td>
                    <td>{{ $student->parents?->father_name }}</td>
                    <td>{{ $student->parents?->mother_name }}</td>
                    <td>{{ $student->parents?->guardian_name }}</td>
                    <td>{{ $student->parents?->father_job }}</td>
                    <td>{{ $student->parents?->mother_job }}</td>
                    <td>{{ $student->parents?->guardian_job }}</td>
                    <td>{{ $student->parents?->father_phone }}</td>
                    <td>{{ $student->parents?->mother_phone }}</td>
                    <td>{{ $student->parents?->guardian_phone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
