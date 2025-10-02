@extends('layouts.student')

@section('content')
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-6 space-y-8 mb-6">
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Detail Tagihan</h2>
        </div>
        <div class="space-y-2">

            <div class="flex justify-between">
                <span>No Pendaftaran:</span>
                <span class="font-semibold">{{ $data['nopendaftaran'] ?? '-' }}</span>
            </div>

            <div class="flex justify-between">
                <span>Nama:</span>
                <span class="font-semibold">{{ auth()->user()->name }}</span>
            </div>

            <div class="flex justify-between">
                <span>No WA:</span>
                <span class="font-semibold">{{ auth()->user()->whatsapp }}</span>
            </div>

            <div class="flex justify-between">
                <span>Kode Kelas:</span>
                <span class="font-semibold">{{ auth()->user()->payment->kodekelas ?? '-' }}</span>
            </div>

            <div class="flex justify-between">
                <span>Kode Tahun Ajaran:</span>
                <span class="font-semibold">{{ auth()->user()->payment->kodeta ?? '-' }}</span>
            </div>

            <div class="flex justify-between">
                <span>Biaya Pendaftaran:</span>
                <span class="font-semibold text-green-600">
                    Rp{{ number_format(auth()->user()->payment->nominal ?? 0, 0, ',', '.') }}
                </span>
            </div>
            <div class="flex justify-between">
                <span>Status Pembayaran:</span>
                <span
                    class="font-semibold mb-4 {{ auth()->user()->payment->status === 'paid' ? 'text-green-600' : 'text-red-600' }}">
                    {{ auth()->user()->payment->status === 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                </span>
            </div>
        </div>

        @php
            \Carbon\Carbon::setLocale('id');

            $payment = auth()->user()->payment;
            $waktuAkhir = $payment && $payment->waktuakhir ? \Carbon\Carbon::parse($payment->waktuakhir) : null;
        @endphp

        @if ($waktuAkhir)
            <div class="flex justify-between">
                <span>Waktu Akhir Pembayaran:</span>
                <span class="font-semibold text-red-600">
                    {{ $waktuAkhir->translatedFormat('l, d F Y H:i') }}
                </span>
            </div>
        @endif
        <p class="mt-4 text-center text-sm text-gray-500">Silakan lakukan pembayaran sebelum waktu berakhir.</p>
    </div>

    {{-- Alur Pembayaran --}}
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-6 space-y-8 mb-6">
        <div class="space-y-6">
            <div class="text-center border-b pb-4">
                <h3 class="text-lg font-bold text-gray-800 uppercase">Alur Pembayaran</h3>
                <p class="text-sm text-gray-500">Ponpes Miftahunnajah - Pembayaran PSB - Kode 2131</p>
            </div>
            {{-- bagian 1 --}}
            <div class="space-y-4 border-b pb-4">
                <h4 class="font-semibold text-gray-700">I. MELALUI CHANNEL BSI</h4>
                <div>
                    <p class="font-medium">Melalui Mobile Banking BSI</p>
                    <ol class="list-decimal list-inside text-gray-600 space-y-1 ">
                        <li>Login akun Mobile Banking BSI</li>
                        <li>Pilih Menu <b>Pembayaran </b></li>
                        <li>Pilih <b>Akademik</b></li>
                        <li>Pilih atau ketik angka <b>2131 - Ponpes Miftahunnajah SPP</b></li>
                        <li>Masukan nomor pembayaran <b class="text-blue-600">
                                {{ $data['nopendaftaran'] ?? '-' }}
                            </b></li>
                        </li>
                        <li>Cek nominal dan nama siswa, masukkan PIN, lanjutkan transaksi</li>
                    </ol>
                </div>
                <div>
                    <p class="font-medium">Melalui ATM Bank Syariah Indonesia</p>
                    <ol class="list-decimal list-inside text-gray-600 space-y-1 ">
                        <li>Masukkan kartu ATM dan PIN </li>
                        <li>Pilih menu <b>Pembayaran/Pembelian </b></li>
                        <li>Pilih Menu <b>Akademik/ Institusi </b></li>
                        <li>Masukkan kode <b class="text-blue-600">2131 – Ponpes Miftahunnajah SPP </b> dan Nomor Pembayaran
                            Contoh :
                            <b class="text-blue-600">2131 {{ $data['nopendaftaran'] ?? '-' }}</font>
                            </b>
                        </li>
                        <li>Cek nominal dan nama siswa, masukkan PIN, lanjutkan transaksi
                        </li>
                    </ol>
                </div>
                <div>
                    <p class="font-medium">Melalui Internet Banking Bank Syariah Indonesia</p>
                    <ol class="list-decimal list-inside text-gray-600 space-y-1 ">
                        <li>Login ke <a href="https://bsinet.bankbsi.co.id/cms/index.php"
                                class="decoration-none text-blue-500 "
                                target="_blank">https://bsinet.bankbsi.co.id/cms/index.php</a>
                        </li>
                        <li>Pilih menu <b>Pembayaran </b></li>
                        <li>Pilih jenis pembayaran <b>Institusi </b></li>
                        <li>Cari Nama Sekolah - <b>Ponpes Miftahunnajah SPP</b></li>
                        <li>Masukan nomor pembayaran <b class="text-blue-600">
                                {{ $data['nopendaftaran'] ?? '-' }}
                            </b> </li>
                        <li>Cek nominal dan nama siswa, masukkan PIN, lanjutkan transaksi</li>
                    </ol>
                </div>
            </div>
            {{-- bagian 2 --}}
            <div class="space-y-4">
                <h4 class="font-semibold text-gray-700">II.MELALUI CHANNEL ATM BANK LAIN</h4>
                <div>
                    <p class="font-medium">Melalui Bank Mandiri</p>
                    <ol class="list-decimal list-inside text-gray-600 space-y-1 ">
                        <li>Masukkan kartu ATM dan PIN </li>
                        <li>Pilih menu <b>Transfer - Transfer ke Antar Bank</b></li>
                        <li>Masukkan <b class="text-blue-600">
                                900 2131 {{ $data['nopendaftaran'] ?? '-' }}
                            </b></li>
                        <li class="text-red-600">Nominal harus sesuai, jika berbeda sistem akan menolak
                        </li>
                    </ol>
                </div>
                <div>
                    <p class="font-medium">Melalui ATM Bersama</p>
                    <ol class="list-decimal list-inside text-gray-600 space-y-1 ">
                        <li>Masukkan kartu ATM dan PIN </li>
                        <li>Pilih menu <b>Transfer - Transfer ke Antar Bank</b></li>
                        <li>Masukkan <b class="text-blue-600">
                                451 900 2131 {{ $data['nopendaftaran'] ?? '-' }}
                            </b></li>
                        <li class="text-red-600">Nominal harus sesuai, jika berbeda sistem akan menolak
                        </li>
                    </ol>
                </div>
                <div>
                    <p class="font-medium">Melalui ATM Prima (BCA)</p>
                    <ol class="list-decimal list-inside text-gray-600 space-y-1 ">
                        <li>Pilih menu <b>Transfer ˃ Transfer ke Antar rekening Bank lain</b></li>
                        <li>Ketik kode <b>Bank Syariah Indonesia: 451</b></li>
                        <li>Masukkan <b class="text-blue-600">
                                900 2131 {{ $data['nopendaftaran'] ?? '-' }}
                            </b> </li>
                        <li class="text-red-600">Nominal harus sesuai, jika berbeda sistem akan menolak
                        </li>
                    </ol>
                </div>
                <div>
                    <p class="font-medium">Melalui Channel Bank selain ATM yaitu: Mobile Banking/Internet Banking</p>
                    <ol class="list-decimal list-inside text-gray-600 space-y-1 ">
                        <li>Pilih menu <b>Transfer Online ke Bank Lain</b></li>
                        <li>Pilih ke <b>Bank Syariah Indonesia / BSI ex BSM</b></li>
                        <li>Masukkan Nomor Rekening Tujuan<b class="text-blue-600">
                                900 2131 {{ $data['nopendaftaran'] ?? '-' }}
                            </b></li>
                        <li class="text-red-600">Nominal harus sesuai, jika berbeda sistem akan menolak</li>
                    </ol>
                </div>
            </div>
            {{-- keterangan --}}
            <div class="bg-gray-50 border rounded-lg p-4 text-sm text-gray-600">
                <p class="font-medium underline mb-2">Keterangan: </p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Tidak bisa menggunakan transfer SKN/Kliring, hanya Transfer Online Antar Bank</li>
                    <li>Transfer via OVO, Dana, atau Gopay <b>tidak disarankan</b> untuk menghindari gagal transaksi</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
