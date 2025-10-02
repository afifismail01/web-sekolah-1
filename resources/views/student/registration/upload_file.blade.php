@extends('layouts.student')
@section('content')
    @php
        // Variabel yang bertugas membuat user dapat melakukan upload file apabila memenuhi kedua kondisi
        $canEdit = $isFirstTime || session('mode') == 'edit';
    @endphp
    <div class="mb-4">
        {{-- Menampilkan pesan notifikasi sesuai dengan kondisi yang dilakukan oleh user --}}
        @include('components.alerts')
    </div>
    <div class=" bg-white shadow-md rounded-xl p-4 max-w-4xl mx-auto mb-4 md:hidden">
        <h3 class="mb-4">Menu Halaman Pendaftaran</h3>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('student.personalData') }}"
                class="{{ request()->routeIs('student.personalData') ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-gray-200 text-black hover:bg-gray-400' }} p-2 rounded  transition-all duration-300 ease-in-out">Data
                Diri</a>
            <a href="{{ route('student.parentData') }}"
                class="{{ request()->routeIs('student.parentData') ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-gray-200 text-black hover:bg-gray-400' }} p-2 rounded  transition-all duration-300 ease-in-out">Data
                Orang Tua</a>
            <a href="{{ route('student.uploadFile') }}"
                class="{{ request()->routeIs('student.uploadFile') ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-gray-200 text-black hover:bg-gray-400' }} p-2 rounded transition-all duration-300 ease-in-out">Upload
                Berkas</a>
        </div>
    </div>
    <div class="bg-white shadow-md rounded-xl p-4 max-w-4xl mx-auto">
        <h2 class="text-2xl font-semibold mb-4">Upload Berkas</h2>
        @if ($activeStage?->stage_name !== App\Enums\StageNameEnum::REGISTRATION)
            <div class="bg-red-100 text-red-700 flex flex-col p-4 mb-4 rounded-md items-center text-center">
                <div class="text-4xl mb-2">
                    <i data-lucide="lock"></i>
                </div>
                <p class="font-semibold">Formulir Tidak Bisa Diakses</p>
                <p>Tahapan saat ini adalah : <span class="font-bold">{{ $activeStage->stage_name ?? '-' }}</span>,
                    pengisian formulir
                    pendaftaran tidak
                    diperbolehkan.</p>
            </div>
        @elseif($activeStage?->stage_name === App\Enums\StageNameEnum::REGISTRATION)
            @if (!$paidStatus)
                <div class="bg-yellow-100 text-yellow-700 p-4 mb-4 flex-col items-center rounded-md text-center">
                    <div class="text-4xl mb-2">
                        <i data-lucide="triangle-alert"></i>
                    </div>
                    <p class="font-semibold">Pembayaran Belum Diterima</p>
                    <p>Silahkan lakukan pembayaran terlebih dahulu agar dapat mengisi formulir pendaftaran</p>
                </div>
            @endif
        @endif
        <fieldset @disabled($disabledForm) class="{{ $disabledForm ? 'opacity-50 cursor-not-allowed' : '' }}">
            {{-- File wajib (required) --}}
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Berkas Wajib</h3>
            <div class="space-y-4 mb-8">
                @php
                    // Counter untuk mereset nomor mulai dari satu. Jika variable ini dihapus, maka penomoran akan berlanjut sesuai dengan urutan nomor uploadnya.
                    $requiredCounter = 1;
                @endphp
                <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-md text-sm">
                    <strong>Petunjuk Unggah Berkas Wajib: </strong>
                    <ul class="list-disc list-inside mt-2">
                        <li>Batas unggah <strong> semua file wajib</strong> (maksimal 3 file) </li>
                        <li>Dokumen yang<strong> wajib</strong> untuk diunggah: </li>
                        <ul class="list-disc ml-10 mt-1">
                            <li><strong>Foto Calon Siswa</strong></li>
                            <li><strong>Akta Kelahiran</strong></li>
                            <li><strong>Kartu Keluarga</strong></li>
                            <li>
                                <p><strong>Raport</strong>
                                    <italic>(upload salah satu)</italic>
                                </p>
                            </li>
                            <ul class="list-disc ml-4">
                                <li>Raport Kelas V semester 1,2 dan Raport kelas VI semester 1 ( Jika Mendaftar MTs)</li>
                                <li>Raport Kelas VIII semester 1,2 dan Raport kelas IX semester 1 (Jika Mendaftar MA)</li>
                            </ul>
                        </ul>
                        <li>Format file yang diperbolehkan: PDF/JPG/PNG</li>
                        <li>Ukuran maksimum tiap file: 2MB</li>
                        <li><strong>Penamaan file</strong> disarankan sesuai jenis dokumen, contoh: </li>
                        <ul class="ml-6 mt-1">
                            <li><code>nama_siswa_raport.pdf</code></li>
                            <li><code>nama_siswa_kk.png</code></li>
                            <li><code>nama_siswa_akta_kelahiran.png</code></li>
                        </ul>
                    </ul>
                </div>
                {{-- <p>Jumlah file required saat ini: {{ count($uploadedRequiredFiles) }}</p> --}}
                <div class="p-4 border rounded-xl bg-gray-50 mb-4">
                    @if ($uploadedRequiredFiles->count() >= 0)
                        <div class="overflow-x-auto mb-4">
                            <table class="table table-zebra w-full border text-sm sm:text-base">
                                <thead class="align-center text-center bg-gray-100">
                                    <tr>
                                        <th class="p-2">No</th>
                                        <th class="p-2">Nama Dokumen</th>
                                        <th class="p-2">Nama File</th>
                                        <th class="p-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($fileTypes as $index => $type)
                                        {{-- @foreach ($uploadedRequiredFiles as $index => $file) --}}
                                        @php
                                            $file = $uploadedFiles->firstWhere('file_type', $type->value);
                                            $uploadedForThisType = $uploadedFiles
                                                ->where('file_type', $type->value)
                                                ->count();
                                            $fileInputId = 'fileInput_' . $index; //id unik
                                            $fileNameDisplayId = 'fileName_' . $index; //id unik untuk nama file
                                        @endphp
                                        <tr class="border-t">
                                            <td class="p-2">{{ $requiredCounter++ }}</td>
                                            <td class="p-2">{{ $type->label() }}</td>
                                            <td class="p-2 break-words max-w-[200px] whitespace-normal">
                                                @if ($file)
                                                    {{ $file->file_name }}
                                                @else
                                                    <span class="italic text-gray-400">Belum diunggah</span>
                                                @endif
                                            </td>
                                            <td class="p-2">
                                                @if ($file)
                                                    <div class="flex flex-col sm:flex-row gap-2 justify-center mb-2">
                                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                                            target="_blank"
                                                            class="bg-blue-500 text-white hover:bg-blue-600 px-3 py-2 rounded-md w-full transition-all duration-300 ease-in-out sm:w-auto">Lihat
                                                            File</a>
                                                        @if ($canEdit)
                                                            <form action="{{ route('student.deleteFile', $file->id) }}"
                                                                method="POST" class="flex">
                                                                @csrf @method('DELETE')
                                                                <button type="submit"
                                                                    class="bg-red-500 text-white hover:bg-red-600 px-3 py-2 rounded-md w-full transition-all duration-300 ease-in-out sm:w-auto"
                                                                    onclick="return confirm('Yakin ingin menghapus file ini?')">Hapus</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @else
                                                    {{-- <span class="italic text-gray-400">Belum ada file</span> --}}
                                                @endif
                                                @if ($canEdit && $uploadedForThisType < 1)
                                                    <form action="{{ route('student.uploadFileStore') }}" method="POST"
                                                        class="flex flex-col sm:flex-row flex-wrap gap-2 items-center justify-center"
                                                        enctype="multipart/form-data">
                                                        @csrf

                                                        <label for="{{ $fileInputId }}"
                                                            class="w-full sm:w-auto text-center bg-gray-300 text-black border border-gray-400 px-3 py-2 rounded-md hover:bg-gray-400 hover:text-white cursor-pointer transition-all duration-300 ease-in-out">Pilih
                                                            File</label>
                                                        <input type="file" name="file" id="{{ $fileInputId }}"
                                                            class="ds-file-input ds-file-input-bordered hidden"
                                                            onchange="handleFileChange(this,'{{ $fileNameDisplayId }}')">
                                                        @error('file')
                                                            <p class="mt-2 text-sm text-red-600">{{ $message }}
                                                            </p>
                                                        @enderror
                                                        <p id="{{ $fileNameDisplayId }}"
                                                            class="file-name-display text-sm italic text-gray-700 opacity-0 translate-y-2 transition-all duration-300 ease-in-out">
                                                        </p>
                                                        <input type="hidden" name="file_type" value="{{ $type->value }}">
                                                        @error('file_type')
                                                            <p class="mt-2 text-sm text-red-600">{{ $message }}
                                                            </p>
                                                        @enderror
                                                        <input type="hidden" name="file_category"
                                                            value="{{ $categoryRequired->value }}">
                                                        @error('file_category')
                                                            <p class="mt-2 text-sm text-red-600">{{ $message }}
                                                            </p>
                                                        @enderror
                                                        <input type="hidden" name="file_name" id="fileNameInput">
                                                        @error('file_name')
                                                            <p class="mt-2 text-sm text-red-600">{{ $message }}
                                                            </p>
                                                        @enderror
                                                        <button type="submit"
                                                            class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 w-full sm:w-auto transition-all duration-300 ease-in-out">Upload</button>
                                                    </form>
                                                @elseif(!$canEdit)
                                                    <input type="file" class="hidden" disabled>
                                                @endif
                                            </td>
                                        </tr>
                                        {{-- @endforeach --}}
                                    @endforeach
                                </tbody>
                            </table>
                            @if (!$canEdit)
                                <p
                                    class="mt-4 p-4 bg-orange-200 border border-orange-300 text-orange-700 rounded-sm text-center text-sm italic">
                                    Anda perlu
                                    menekan tombol
                                    edit
                                    agar dapat melakukan
                                    perubahan pada file</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <hr>


            {{-- File Support (berdasarkan jalur pendaftaran ) --}}
            <h3 class="text-xl font-semibold text-gray-700 mt-8 mb-4">Berkas Pendukung</h3>
            {{-- <p>Jumlah file pendukung saat ini: {{ count($uploadedSupportingFiles) }}</p> --}}
            <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-md text-sm">
                <strong>Petunjuk Unggah Berkas Pendukung: </strong>
                <ul class="list-disc list-inside mt-2">
                    <li>File pendukung bersifat opsional</li>
                    <li>Format file yang diperbolehkan: PDF/JPG/PNG</li>
                    <li>Ukuran maksimum tiap file: 2MB</li>
                    <li>Dokumen pendukung yang dapat diunggah: </li>
                    <ul class=" list-inside pl-6">
                        <li><span><strong>Jalur Prestasi:</strong></span></li>
                        <ul class="list-disc list-inside pl-6">
                            <li>SK peringkat 1-3 kelas</li>
                            <li>SK hafalan Al-Qur’an >10 juz (siap diuji)</li>
                            <li>sertifikat juara 1-3 lomba MTQ/OSN/KSM tingkat kabupaten/provinsi/nasional</li>
                        </ul>
                        <li><span><strong>Jalur Yatim Dhuafa: </strong></span></li>
                        <ul class="list-disc list-inside pl-6">
                            <li>Kartu KIP/KPS/KKS/PKH/KIS/SKTM, atau dokumen sejenis lainnya</li>
                        </ul>
                    </ul>
                    <li><strong>Penamaan file</strong> disarankan sesuai jenis dokumen, contoh: </li>
                    <ul class="ml-6 mt-1">
                        <li><code>nama_siswa_surat_rekomendasi_sekolah_asal.pdf</code></li>
                        <li><code>nama_siswa_kartu_KIP.png</code></li>
                        <li><code>nama_siswa_sertifikat_lomba.png</code></li>
                    </ul>
                </ul>
            </div>
            <div class="space-y-4">
                @php
                    $supportCounter = 1;
                @endphp
                <div class="p-4 border rounded-xl bg-gray-50 mb-3">
                    @if ($uploadedSupportingFiles->count() >= 0)
                        <div class="overflow-x-auto mt-4">
                            <table class="table text-sm sm:text-base table-zebra w-full border">
                                <thead class="text-center bg-gray-100">
                                    <tr>
                                        <td class="p-2">No</td>
                                        <td class="p-2">Nama File</td>
                                        <td class="p-2">Action</td>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($uploadedSupportingFiles as $index => $file)
                                        <tr class="border-t">
                                            <td class="p-2">{{ $supportCounter++ }}</td>
                                            <td class="p-2">{{ $file->file_name }}</td>
                                            <td class="p-2">
                                                <div class="flex flex-col sm:flex-row gap-2 justify-center mb-2">
                                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                        class="bg-blue-500 text-white hover:bg-blue-600 px-3 py-2 rounded-md w-full transition-all duration-300 ease-in-out sm:auto">Lihat
                                                        File</a>
                                                    @if ($canEdit)
                                                        <form action="{{ route('student.deleteFile', $file->id) }}"
                                                            method="POST" class="flex">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                class="bg-red-500 text-white hover:bg-red-600 rounded-md px-3 py-2 w-full sm:auto transition-all duration-300 ease-in-out"
                                                                onclick="return confirm('Yakin ingin menghapus file ini?')">Hapus</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @if ($canEdit)
                        <form action="{{ route('student.uploadFileStore') }}" method="POST"
                            enctype="multipart/form-data" class="mb-2">
                            @csrf
                            <div class="flex flex-col gap-2">
                                <p
                                    class="file-name-display text-center
                                    text-sm italic text-gray-700 opacity-0 translate-y-2 transition-all duration-300
                                    ease-in-out">
                                </p>
                                <label for="fileInputSupport"
                                    class="block w-full text-center bg-gray-300 text-black px-3 py-2 border border-gray-400 rounded-md hover:bg-gray-400 hover:text-white cursor-pointer transition-all duration-300 ease-in-out">Pilih
                                    File</label>
                                <input type="file" name="file" id="fileInputSupport"
                                    class="ds-file-input ds-file-input-bordered hidden" onchange="handleFileChange(this)">
                                <input type="hidden" name="file_category" value="support">
                                <input type="hidden" name="file_type" value="Support_file">
                                <input type="hidden" name="file_name" id="fileNameInput">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full transition-all duration-300 ease-in-out">Upload</button>
                            </div>
                        </form>
                    @else
                        <input type="file" hidden disabled>
                        <p
                            class="mt-4 p-4 bg-orange-200 border border-orange-300 text-orange-700 rounded-sm text-center text-sm italic">
                            Anda perlu
                            menekan tombol
                            edit
                            agar dapat melakukan
                            perubahan pada file</p>
                    @endif

                </div>
            </div>
            {{-- Tombol simpan/edit --}}
            <div class="mt-8 text-end">
                @if ($isFirstTime || session('mode') == 'edit')
                    <form method="POST" action="{{ route('student.uploadFileStoreFinal') }}">
                        @csrf
                        <button type="submit"
                            class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-700 w-full">Simpan
                            Data</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('student.upload-file.edit-mode') }}">
                        @csrf
                        <button type="submit"
                            class="bg-orange-600 text-white font-bold px-6 py-2 rounded hover:bg-orange-500 w-full {{ $disabledForm ? 'bg-gray-400 text-gray-700 hover:bg-gray-400 cursor-not-allowed' : '' }}"
                            @disabled($disabledForm)>Edit</button>
                    </form>
                @endif
            </div>
        </fieldset>
    </div>
    <script>
        function handleFileChange(input, fileNameDisplayId) {
            const container = input.closest('form'); // cari elemen terdekat (form)
            const fileDisplay = container.querySelector('.file-name-display');
            const fileNameDisplay = document.getElementById(fileNameDisplayId);
            if (input.files.length > 0) {
                fileDisplay.textContent = `File dipilih: ${input.files[0].name}`;
                fileDisplay.classList.remove('opacity-0', 'translate-y-2');
                fileDisplay.classList.add('opacity-100', 'translate-y-0');
                fileDisplay.classList.remove('hidden');
                fileNameDisplay.textContent = input.files[0].name;
                fileNameDisplay.classList.remove('opacity-0', 'translate-y-2');
            } else {
                fileDisplay.textContent = '';
                fileDisplay.classList.remove('opacity-100', 'translate-y-0');
                fileDisplay.classList.add('opacity-0', 'translate-y-2');
                fileDisplay.classList.add('hidden');
                fileNameDisplay.textContent = '';
                fileNameDisplay.classList.add('opacity-0', 'translate-y-2');
            }
        }
    </script>
@endsection
