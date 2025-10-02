@extends('layouts.student')

@section('content')
    <div class="mb-4">
        @include ('components.alerts')
    </div>
    <div class=" bg-white shadow-md rounded-xl p-4 max-w-4xl mx-auto mb-4 md:hidden">
        <h3 class="mb-4">Menu Halaman Pendaftaran</h3>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('student.personalData') }}"
                class="{{ request()->routeIs('student.personalData') ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-gray-200 text-black hover:bg-gray-400' }}   p-2 rounded  transition-all duration-300 ease-in-out">Data
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
        <h2 class="text-2xl font-semibold mb-4">Data Diri Siswa</h2>
        @if ($activeStage?->stage_name !== App\Enums\StageNameEnum::REGISTRATION)
            <div class="bg-red-100 text-red-700 p-4 mb-4 flex flex-col rounded-md items-center text-center">
                <div class="text-4xl mb-2 text-center">
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
                <div class="bg-yellow-100 text-yellow-700 p-4 mb-4 flex flex-col items-center rounded-md text-center">
                    <div class="text-4xl mb-2">
                        <i data-lucide="triangle-alert"></i>
                    </div>
                    <p class="font-semibold">Pembayaran Belum Diterima</p>
                    <p>Silahkan lakukan pembayaran terlebih dahulu agar dapat mengisi formulir pendaftaran</p>
                </div>
            @endif
        @endif
        <fieldset @disabled($disabledForm) class="{{ $disabledForm ? 'opacity-50 cursor-not-allowed' : '' }}">
            <form action="{{ route('student.personalDataStore') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="Jenjang Pendaftaran" class="block text-sm font-medium text-gray-800 mt-1 mb-2">Pilih Jenjang
                        Pendaftaran</label>
                    <div class="flex gap-2">
                        @foreach ($educationLevels as $level => $ageLimit)
                            @php
                                $minDate = \Carbon\Carbon::now()->subYears($ageLimit)->startOfYear()->toDateString();
                            @endphp
                            <label>
                                <input type="radio" name="education_level" id="education_level_{{ $level }}"
                                    value="{{ $level }}" data-mindate="{{ $minDate }}"
                                    {{ old('education_level', $student->education_level->value ?? '') === $level ? 'checked' : '' }}
                                    class="hidden peer">
                                <div
                                    class="px-4 py-2 rounded-md border border-gray-300 text-gray-800 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                    {{ $level }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('education_level')
                        <p class="mb-4 mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Nama Lengkap" class="block text-sm font-medium text-gray-800"> Nama Lengkap</label>
                    <input type="text" name="name"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="e.g. Muhammad Fulan" value="{{ old('name', $student->name ?? '') }}">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="NISN" class="block text-sm font-medium text-gray-800"> NISN</label>
                    <input type="text" name="nisn"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="10 digits" value="{{ old('nisn', $student->nisn ?? '') }}">
                    @error('nisn')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Kewarganegaraan" class="block text-sm font-medium text-gray-800 mb-2">Pilih
                        Warganegara</label>
                    <div class="flex gap-2">
                        @foreach (\App\Enums\CitizenshipEnum::cases() as $citizen)
                            <label>
                                <input type="radio" name="citizenship" value="{{ $citizen->value }}"
                                    {{ old('citizenship', $student->citizenship->value ?? '') === $citizen->value ? 'checked' : '' }}
                                    class="hidden peer">
                                <div
                                    class="px-4 py-2 rounded-md border border-gray-300 text-gray-800 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                    {{ $citizen->value }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('citizenship')
                        <p class="mb-4 mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="NIK" class="block text-sm font-medium text-gray-800">NIK</label>
                    <input type="text" name="national_id_number"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="16 digits"
                        value="{{ old('national_id_number', $student->national_id_number ?? '') }}">
                    @error('national_id_number')
                        <p class="mt-0 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Tempat Lahir" class="block text-sm font-medium text-gray-800">Tempat
                        Lahir</label>
                    <input type="text" name="birth_place"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="e.g. Yogyakarta" value="{{ old('birth_place', $student->birth_place ?? '') }}">
                    @error('birth_place')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Tanggal Lahir" class="block text-sm font-medium text-gray-800">Tanggal Lahir</label>
                    <p class="text-xs text-gray-500 mt-1">* Format tanggal mengikuti pengaturan perangkat Anda (biasanya
                        Bulan-Hari-Tahun)</p>
                    <input type="date" name="birth_date" id="birth_date"
                        value="{{ old('birth_date', isset($student) && $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        min="{{ $minDate }}" max="{{ $maxDate }}">
                    @error('birth_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @if ($student?->registration_level === 'MTs')
                            <p class="text-xs text-red-500 italic">* Umur maksimal untuk MTs adalah 15 tahun.</p>
                        @elseif($student?->registration_level === 'MA')
                            <p class="text-xs text-red-500 italic">* Umur maksimal untuk MA adalah 21 tahun.</p>
                        @elseif($student?->registration_level === 'PAUD')
                            <p class="text-xs text-red-500 italic">* Umur maksimal untuk PAUD adalah 6 tahun.</p>
                        @endif
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Jenis Kelamin" class="block text-sm font-medium text-gray-800 mb-1">Jenis Kelamin</label>
                    <div class="flex gap-2">
                        @foreach (\App\Enums\GenderEnum::cases() as $jk)
                            <!-- jk is 'jenis kelamin'-->
                            <label>
                                <input type="radio" name="gender" value="{{ $jk->value }}"
                                    {{ old('gender', $student->gender->value ?? '') === $jk->value ? 'checked' : '' }}
                                    class="hidden peer">
                                <div
                                    class="px-4 py-2 rounded-md border border-blue-500 text-blue-600 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-blue-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                    {{ $jk->value }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('gender')
                        <p class="mb-4 mt-2 text-sm text-red-600 ">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Jumlah Saudara" class="block text-sm font-medium text-gray-800">Jumlah Saudara</label>
                    <input type="text" name="siblings_count"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="e.g. 3" value="{{ old('siblings_count', $student->siblings_count ?? '') }}">
                    @error('siblings_count')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Anak ke-" class="block text-sm font-medium text-gray-800">Anak Ke- </label>
                    <input type="text" name="child_number"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="e.g. 3" value="{{ old('child_number', $student->child_number ?? '') }}">
                    @error('child_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Agama" class="block text-sm font-medium text-gray-800">Agama</label>
                    <input type="text" name="religion"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('religion', $student->religion ?? '') }}">
                    @error('religion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Nomor Handphone" class="block text-sm font-medium text-gray-800">Nomor Handphone</label>
                    <input type="text" name="phone_number"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="e.g. 62xxxxxxxxxxxxx"
                        value="{{ old('phone_number', $student->phone_number ?? '') }}">
                    @error('phone_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Email" class="block text-sm font-medium text-gray-800">Email
                        (..@yahoo/..@gmail)</label>
                    <input type="text" name="email"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('email', $student->email ?? '') }}">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="future_goal" class="block text-sm font-medium text-gray-800 mb-1">Cita-cita</label>
                    <select name="future_goal" id="future_goal" onchange="toggleOther(this,'other_goal_input_wrapper')"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                        <option value="" disabled>-- Pilih cita-cita </option>

                        {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                        @if (!in_array(old('future_goal', $student->future_goal ?? ''), \App\Enums\FutureGoalEnum::values()))
                            <option value="{{ old('future_goal', $student->future_goal ?? '') }}" selected>
                                {{ old('future_goal', $student->future_goal ?? '') }}
                            </option>
                        @endif

                        @foreach (\App\Enums\FutureGoalEnum::cases() as $goal)
                            <option value="{{ $goal->value }}"
                                {{ old('future_goal', $student->future_goal ?? '') === $goal->value ? 'selected' : '' }}>
                                {{ $goal->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('future_goal')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    {{-- Field input manual muncul jika "lainnya" dipilih --}}
                    <div id="other_goal_input_wrapper"
                        class="mt-3 {{ old('future_goal', $student->future_goal ?? '') === 'Lainnya' ? '' : 'hidden' }}">
                        <label for="other_future_goal" class="block text-sm font-medium text-gray-800 mb-1">Tulis
                            cita-cita lainnya</label>
                        <input type="text" name="other_future_goal" id="other_future_goal"
                            value="{{ old('other_future_goal', $student->other_future_goal ?? '') }}"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring-indigo-500">
                        @error('other_future_goal')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="hobby" class="block text-sm font-medium text-gray-800 mb-1">Hobi</label>
                    <select name="hobby" id="hobby" onchange="toggleOther(this,'other_hobby_input_wrapper')"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                        <option value="" disabled>-- Pilih hobi </option>

                        {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                        @if (!in_array(old('hobby', $student->hobby ?? ''), \App\Enums\HobbyEnum::values()))
                            <option value="{{ old('hobby', $student->hobby ?? '') }}" selected>
                                {{ old('hobby', $student->hobby ?? '') }}
                            </option>
                        @endif

                        @foreach (\App\Enums\HobbyEnum::cases() as $hl)
                            <option value="{{ $hl->value }}"
                                {{ old('hobby', $student->hobby ?? '') === $hl->value ? 'selected' : '' }}>
                                {{ $hl->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('hobby')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    {{-- Field input manual muncul jika "lainnya" dipilih --}}
                    <div id="other_hobby_input_wrapper"
                        class="mt-3 {{ old('hobby', $student->hobby ?? '') === 'Lainnya' ? '' : 'hidden' }}">
                        <label for="other_hobby" class="block text-sm font-medium text-gray-800 mb-1">Tulis
                            hobi lainnya</label>
                        <input type="text" name="other_hobby" id="other_hobby"
                            value="{{ old('other_hobby', $student->other_hobby ?? '') }}"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring-indigo-500">
                        @error('other_hobby')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="Asal Sekolah" class="block text-sm font-medium text-gray-800">Asal Sekolah</label>
                    <input type="text" name="previous_school"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('previous_school', $student->previous_school ?? '') }}">
                    @error('previous_school')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="NPSN Sekolah Asal" class="block text-sm font-medium text-gray-800">NPSN Sekolah
                        Asal</label>
                    <input type="text" name="previous_school_npsn"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('previous_school_npsn', $student->previous_school_npsn ?? '') }}">
                    @error('previous_school_npsn')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Yang membiayai sekolah" class="block text-sm font-medium text-gray-800 mb-1">Yang
                        membiayai sekolah</label>
                    <div class="flex gap-2">
                        @foreach (\App\Enums\EducationFundingEnum::cases() as $ef)
                            <label>
                                <input type="radio" name="education_funding" value="{{ $ef->value }}"
                                    {{ old('education_funding', $student->education_funding->value ?? '') === $ef->value ? 'checked' : '' }}
                                    class="hidden peer">
                                <div
                                    class="px-4 py-2 rounded-md border border-blue-500 text-blue-600 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-blue-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                    {{ $ef->value }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('education_funding')
                        <p class="mb-4 mt-2 text-sm text-red-600 ">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Kebutuhan Khusus" class="block text-sm font-medium text-gray-800 mb-1">Kebutuhan
                        Khusus</label>
                    <select name="special_needs" id="special_needs"
                        onchange="toggleOther(this,'other_special_needs_input_wrapper')"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                        <option value="" disabled>-- Pilih cita-cita </option>

                        {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                        @if (!in_array(old('special_needs', $student->special_needs ?? ''), \App\Enums\SpecialNeedsEnum::values()))
                            <option value="{{ old('special_needs', $student->special_needs ?? '') }}" selected>
                                {{ old('special_needs', $student->special_needs ?? '') }}
                            </option>
                        @endif

                        @foreach (\App\Enums\SpecialNeedsEnum::cases() as $snl)
                            <option value="{{ $snl->value }}"
                                {{ old('special_needs', $student->snl ?? '') === $snl->value ? 'selected' : '' }}>
                                {{ $snl->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('special_needs')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    {{-- Field input manual muncul jika "lainnya" dipilih --}}
                    <div id="other_special_needs_input_wrapper"
                        class="mt-3 {{ old('special_needs', $student->special_needs ?? '') === 'Lainnya' ? '' : 'hidden' }}">
                        <label for="other_special_needs" class="block text-sm font-medium text-gray-800 mb-1">Tulis
                            kebutuhan disabilitas lainnya</label>
                        <input type="text" name="other_special_needs" id="other_special_needs"
                            value="{{ old('other_special_needs', $student->other_special_needs ?? '') }}"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring-indigo-500">
                        @error('other_special_needs')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="disability" class="block text-sm font-medium text-gray-800 mb-1">Kebutuhan
                        Disabilitas</label>
                    <select name="disability" id="disability"
                        onchange="toggleOther(this,'other_disability_input_wrapper')"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                        <option value="" disabled>-- Pilih cita-cita </option>

                        {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                        @if (!in_array(old('disability', $student->disability ?? ''), \App\Enums\DisabilityEnum::values()))
                            <option value="{{ old('disability', $student->disability ?? '') }}" selected>
                                {{ old('disability', $student->disability ?? '') }}
                            </option>
                        @endif

                        @foreach (\App\Enums\DisabilityEnum::cases() as $disability)
                            <option value="{{ $disability->value }}"
                                {{ old('disability', $student->disability ?? '') === $disability->value ? 'selected' : '' }}>
                                {{ $disability->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('disability')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    {{-- Field input manual muncul jika "lainnya" dipilih --}}
                    <div id="other_disability_input_wrapper"
                        class="mt-3 {{ old('disability', $student->disability ?? '') === 'Lainnya' ? '' : 'hidden' }}">
                        <label for="other_disability" class="block text-sm font-medium text-gray-800 mb-1">Tulis
                            kebutuhan disabilitas lainnya</label>
                        <input type="text" name="other_disability" id="other_disability"
                            value="{{ old('other_disability', $student->other_disability ?? '') }}"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring-indigo-500">
                        @error('other_disability')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="Nomor KIP" class="block text-sm font-medium text-gray-800">Nomor KIP (12 digit)</label>
                    <input type="text" name="kip_number"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="12 digits" value="{{ old('kip_number', $student->kip_number ?? '') }}">
                    @error('kip_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Tahun KIP" class="block text-sm font-medium text-gray-800">Tahun KIP</label>
                    <input type="text" name="kip_year"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="4 digits" value="{{ old('kip_year', $student->kip_year ?? '') }}">
                    @error('kip_year')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Nomor KK" class="block text-sm font-medium text-gray-800">Nomor Kartu Keluarga (16
                        digit)</label>
                    <input type="text" name="family_card_number"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="16 digits"
                        value="{{ old('family_card_number', $student->family_card_number ?? '') }}">
                    @error('family_card_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="Nama Kepala Keluarga" class="block text-sm font-medium text-gray-800">Nama Kepala
                        Keluarga</label>
                    <input type="text" name="family_head_name"
                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="e.g. Muhammad Fulan"
                        value="{{ old('family_head_name', $student->family_head_name ?? '') }}">
                    @error('family_head_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-800 mb-1">Jalur Pendaftaran</label>
                    <p class="text-sm text-gray-800 mb-2"><i>Silahkan pilih jalur pendaftaran yang ingin digunakan</i></p>
                    <div class="flex flex-wrap gap-2">
                        @foreach (\App\Enums\AdmissionTrackEnum::cases() as $track)
                            <label>
                                <input type="radio" name="admission_track" value="{{ $track->value }}"
                                    {{ old('admission_track', $student->admission_track->value ?? '') === $track->value ? 'checked' : '' }}
                                    class="hidden peer">
                                <div
                                    class="px-4 py-4 rounded-md border border-blue-500 text-blue-500 text-center peer-checked:bg-blue-500 peer-checked:text-white transition hover:bg-gray-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                    {{ $track->value }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('admission_track')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2 px-4 mt-2 rounded-md w-full cursor-pointer {{ $disabledForm ? 'bg-gray-400 text-gray-700 hover:bg-gray-400 cursor-not-allowed' : '' }}"
                        @disabled($disabledForm)>Simpan
                        Data</button>
                </div>

                <script>
                    // Setting tombol
                    document.querySelector('form').addEventListener('submit', function() {
                        document.getElementById('submitBtn').disabled = true;
                    });

                    function toggleOther(select, targetId) {
                        const wrapper = document.getElementById(targetId);
                        wrapper.classList.toggle('hidden', select.value !== 'Lainnya');
                    }

                    function toggleOtherRadio(radio) {
                        const radioTargetId = radio.dataset.otherTarget;
                        const inputWrapper = document.getElementById(radioTargetId);
                        if (!inputWrapper) return;
                        inputWrapper.classList.toggle('hidden', radio.value !== 'Lainnya');
                    }
                    document.addEventListener('DOMContentLoaded', () => {
                        const radios = document.querySelectorAll('input[type="radio"][data-other-target]');
                        radios.forEach((radio) => {
                            toggleOtherRadio(radio);

                            radio.addEventListener('change', () => {
                                toggleOtherRadio(radio)
                            });
                        });

                        const birthDateInput = document.getElementById('birth_date');
                        const educationLevelRadios = document.querySelectorAll('input[name="education_level"]');


                        function updateMinDate(minDate) {
                            if (birthDateInput && minDate) {
                                birthDateInput.min = minDate;
                            }
                        }

                        educationLevelRadios.forEach(radio => {
                            radio.addEventListener('change', function() {
                                updateMinDate(this.dataset.mindate);
                            });

                            if (radio.checked) {
                                updateMinDate(radio.dataset.mindate);
                            }
                        });

                        birthDateInput.max = "{{ $maxDate }}"; // tetap pakai variabel max

                    });
                </script>
            </form>
        </fieldset>
    </div>
@endsection
