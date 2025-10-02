@extends('layouts.student')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-4 mb-4">
            <h1 class="text-xl font-semibold mb-4">Halaman Administrasi</h1>
            @if ($activeStage?->stage_name !== App\Enums\StageNameEnum::REGISTRATION)
                <div class="bg-red-100 text-red-700 p-4 mb-4 flex flex-col rounded-md items-center text-center">
                    <div class="text-4xl mb-2">
                        <i data-lucide="lock"></i>
                    </div>
                    <p class="font-semibold">Halaman Pembayaran Tidak Bisa Diakses</p>
                    <p>Tahapan saat ini adalah : <span class="font-bold">{{ $activeStage->stage_name ?? '-' }}</span>,
                        pembayaran tidak
                        diperbolehkan.</p>
                </div>
            @elseif($activeStage?->stage_name === App\Enums\StageNameEnum::REGISTRATION)
                {{-- Status Pembayaran --}}
                @if ($status === 'pending')
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 inline-block">Pembayaran Masih Dalam Tahap
                        Pending
                    </div>
                @elseif($status === 'paid')
                    <div
                        class="flex flex-col bg-green-100 text-green-700 px-4 py-2 rounded mb-4 justify-center items-center">
                        <h2 class="text-xl font-bold mt-2 mb-2 text-center">Pembayaran Berhasil</h2>
                    </div>
                @elseif(is_null($status))
                    <div class="mb-4"><span
                            class="inline-block w-full text-center bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm font-medium">Belum
                            Melakukan Pembayaran</span>
                    </div>
                @endif
            @endif
            {{-- pengecekan session --}}
            @if (session('status'))
                <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif


        </div>
        <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">

            <fieldset @disabled($disabledForm) class="{{ $disabledForm ? 'opacity-50 cursor-not-allowed' : '' }}">

                {{-- Form untuk memulai pembayaran --}}
                @if ($status !== 'paid')
                    {{-- Biaya pendataran --}}
                    <div id="biaya-box"
                        class="border bg-white border-gray-200 rounded-lg p-4 flex justify-between items-center mb-6">
                        <span class="font-semibold text-gray-700">Biaya Pendaftaran</span>
                        <span id="biaya-text" class="font-semibold text-gray-600"> - </span>
                    </div>
                    <form method="POST" action="{{ route('student.formulir.buat') }}" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jenjang</label>
                                <select name="kodekelas"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2">
                                    <option>-- Pilih Jenjang --</option>
                                    <option value="PSB MA">MA</option>
                                    <option value="PSB MTs">MTs</option>
                                    <option value="PSB PAUD">PAUD</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jalur Pendaftaran</label>
                                <select name="kodejalur" id="kodejalur"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2">
                                    <option>-- Pilih Jalur --</option>
                                    <option value="1" data-nominal="250000">Reguler</option>
                                    <option value="2" data-nominal="250000">Prestasi</option>
                                    <option value="3" data-nominal="150000">Yatim Dhuafa</option>
                                    <option value="4" data-nominal="0">Alumni</option>
                                    <option value="5" data-nominal="50000">PAUD</option>
                                </select>
                            </div>
                        </div>
                        <p class="italic text-gray-700 text-sm"><small>Ket : Untuk Jenjang PAUD Silahkan Untuk Memilih
                                Jalur
                                Pendaftaran "PAUD"</small>
                        </p>
                        <div class="w-full">
                            <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition {{ $disabledForm ? 'bg-gray-400 text-gray-700 hover:bg-gray-400 cursor-not-allowed' : '' }}"
                                @disabled($disabledForm)>Bayar
                                Sekarang</button>
                        </div>
                    </form>
                @elseif ($status === 'paid')
                    <div class="flex flex-col text-black px-4 py-2 rounded mb-4 justify-center items-center">
                        <div class="space-y-2 justify-between text-sm w-full">
                            <div class="flex justify-between">
                                <span>No Pendaftaran: </span>
                                <span class="font-semibold">{{ auth()->user()->payment->nopendaftaran }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Nama: </span>
                                <span class="font-semibold">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>No Whatsapp: </span>
                                <span class="font-semibold">{{ auth()->user()->whatsapp }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Jumlah Pembayaran: </span>
                                <span
                                    class="font-semibold">{{ number_format(auth()->user()->payment->nominal ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tanggal Bayar: </span>
                                <span
                                    class="font-semibold">{{ \Carbon\Carbon::parse(auth()->user()->payment->updated_at)->translatedFormat('d F Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Status: </span>
                                <span class="font-semibold text-green-600 mb-2">LUNAS</span>
                            </div>
                            <hr>
                            <div class="flex flex-col text-center text-sm w-full">
                                <div class="flex text-sm italic text-gray-600 mt-2 mb-2 justify-center">
                                    <p>Terima kasih telah melakukan pembayaran. Simpan informasi ini sebagai bukti
                                        pembayaran
                                        Anda.</p>
                                </div>
                                <div class="flex text-sm font-semibold text-blue-700 justify-center">
                                    <p>Silakan melanjutkan pengisian data diri dan informasi
                                        lainnya
                                        pada menu berikutnya.
                                    </p>
                                </div>
                            </div>
                            @if (session('info'))
                                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative"
                                    role="alert">
                                    <span class="block sm:inline">{{ session('info') }}</span>
                                </div>
                            @endif
                            <form action="{{ route('cek.pembayaran.status') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition"></button>
                            </form>
                        </div>
                    </div>
                @endif
            </fieldset>
        </div>
        <script>
            const selectJalur = document.getElementById('kodejalur');
            const biayaText = document.getElementById('biaya-text');

            selectJalur.addEventListener('change', function() {
                const nominal = parseInt(this.selectedOption[0].dataset.nominal);
                biayaText.textContent = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(nominal);
            });


            setInterval(function() {
                fetch('{{ route('student.check.payment.status') }}').then(response => response.json()).then(data => {
                    if (data.status === 'paid') {
                        document.getElementById('status-text').innerText = 'LUNAS';
                        alert('Pembayaran berhasil !');
                        location.reload();
                    }
                });
            }, 10000); //interval 10 sec
        </script>
    </div>
@endsection
