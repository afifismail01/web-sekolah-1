<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Hasil Seleksi</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
        }

        .kop {
            text-align: center;
            border-bottom: 2px solid #000000;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        .ttd {
            margin-top: 60px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="kop">
        {{-- kop pake foto aja --}}
        <h3>NAMA SEKOLAH</h3>
        <p>Jenjang Pendidikan</p>
        <p><i>Jl. Contoh Alamat No.123, Yogyakarta</i></p>
    </div>
    <p>Nomor: 123/MTs-MA/VI/2025</p>
    <p>Lampiran: - </p>
    <p>Perihal: Surat Hasil Seleksi</p>
    <br>
    <p>Yang bertanda tangan di bawah ini menyatakan bahwa: </p>
    <table>
        <tr>
            <td>Nama</td>
            <td>: {{ $student->name }}</td>
        </tr>
    </table>
    <p>Setelah memalui proses seleksi penerimaan siswa baru, maka dengan ini dinyatakan: </p>
    <h2 style="text-align:center; text-transform:uppercase;">
        {{ $status->value }}
    </h2>
    <p>Demikian surat ini dibuat untuk dapat digunakan sebagaimana mestinya. </p>
    <div class="ttd">
        <p>Yogyakarta, {{ now()->Format('d m Y') }}</p>
        <p>Kepala Madrasah</p>
        <br><br><br>
        <p><u>Ust. Ahmad Fulan</u><br>NIP. xxxxxxxxx</p>
    </div>
</body>

</html>
