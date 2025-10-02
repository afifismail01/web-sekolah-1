@extends('layouts.student')
@section('content')
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
        <h2 class="text-2xl font-semibold mb-4">Data Orang Tua</h2>
        @if ($activeStage?->stage_name !== App\Enums\StageNameEnum::REGISTRATION)
            <div class="bg-red-100 text-red-700 p-4 mb-4 flex flex-col rounded-md items-center text-center">
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
                <div class="bg-yellow-100 text-yellow-700 p-4 mb-4 flex flex-col items-center  rounded-md text-center">
                    <div class="text-4xl mb-2"><i data-lucide="triangle-alert"></i></div>
                    <p class="font-semibold">Pembayaran Belum Diterima</p>
                    <p>Silahkan lakukan pembayaran terlebih dahulu agar dapat mengisi formulir pendaftaran</p>
                </div>
            @endif
        @endif
        <div class="mb-4">
            @include('components.alerts')
        </div>
        <fieldset @disabled($disabledForm) class="{{ $disabledForm ? 'opacity-50 cursor-not-allowed' : '' }}">
            <form action="{{ route('student.parentDataStore') }}" method="POST">
                @csrf
                <h3 class="font-bold mb-2">🟢 Ayah Kandung</h3>
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4">
                    <div>
                        <label for="Nama Ayah" class="block text-sm font-medium text-gray-800 mt-1 mb-2">Nama Ayah</label>
                        <input type="text" name="father_name"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. Muhammad Fulan" value="{{ old('father_name', $parent->father_name ?? '') }}">
                        @error('father_name')
                            <p class="mt-2 text-sm text-red-600"> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Status Ayah" class="block text-sm font-medium text-gray-800 mb-1">Status Ayah</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach (\App\Enums\LifeStatusEnum::cases() as $ls)
                                <!-- ls is 'life status'-->
                                <label>
                                    <input type="radio" name="father_life_status" value="{{ $ls->value }}"
                                        {{ old('father_life_status', $parent->father_life_status->value ?? '') === $ls->value ? 'checked' : '' }}
                                        class="hidden peer">
                                    <div
                                        class="px-4 py-2 rounded-md border border-blue-500 text-blue-600 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-blue-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                        {{ $ls->value }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('father_life_status')
                            <p class=" mt-2 text-sm text-red-600 ">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Kewarganegaraan" class="block text-sm font-medium text-gray-800 mb-2">Pilih
                            Warganegara</label>
                        <div class="flex gap-2">
                            @foreach (\App\Enums\CitizenshipEnum::cases() as $fc)
                                {{-- fc is father citizenship --}}
                                <label>
                                    <input type="radio" name="father_citizenship" value="{{ $fc->value }}"
                                        {{ old('father_citizenship', $parent->father_citizenship->value ?? '') === $fc->value ? 'checked' : '' }}
                                        class="hidden peer">
                                    <div
                                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-800 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                        {{ $fc->value }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('father_citizenship')
                            <p class=" mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800">NIK Ayah</label>
                        <input type="text" name="father_national_id_number"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="16 digits"
                            value="{{ old('father_national_id_number', $parent->father_national_id_number ?? '') }}">
                        @error('father_national_id_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Tempat Lahir" class="block text-sm font-medium text-gray-800">Tempat
                            Lahir</label>
                        <input type="text" name="father_birth_place"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. Yogyakarta"
                            value="{{ old('father_birth_place', $parent->father_birth_place ?? '') }}">
                        @error('father_birth_place')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Tanggal Lahir" class="block text-sm font-medium text-gray-800">Tanggal Lahir*</label>
                        <p class="text-xs text-gray-500 mt-1">* Format tanggal mengikuti pengaturan perangkat Anda (biasanya
                            Bulan-Hari-Tahun)</p>
                        <input type="date" name="father_birth_date"
                            value="{{ old('father_birth_date', isset($parent) && $parent->father_birth_date ? $parent->father_birth_date->format('Y-m-d') : '') }}"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('father_birth_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="father_last_education" class="block text-sm font-medium text-gray-800 mb-1">Pendidikan
                            Terakhir Ayah</label>
                        <select name="father_last_education" id="father_last_education"
                            onchange="toggleOther(this,'other_father_last_education_input_wrapper')"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                            <option value="" disabled>-- Pilih Pendidikan Terakhir </option>

                            {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                            @if (
                                !in_array(old('father_last_education', $parent->father_last_education ?? ''),
                                    \App\Enums\LastEducationEnum::values()))
                                <option value="{{ old('father_last_education', $parent->father_last_education ?? '') }}"
                                    selected>
                                    {{ old('father_last_education', $parent->father_last_education ?? '') }}
                                </option>
                            @endif

                            @foreach (\App\Enums\LastEducationEnum::cases() as $ls)
                                {{-- ls is last education --}}
                                <option value="{{ $ls->value }}"
                                    {{ old('father_last_education', $parent->father_last_education ?? '') === $ls->value ? 'selected' : '' }}>
                                    {{ $ls->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('father_last_education')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        {{-- Field input manual muncul jika "lainnya" dipilih --}}
                        <div id="other_father_last_education_input_wrapper"
                            class="mt-3 {{ old('father_last_education', $parent->father_last_education ?? '') === 'Lainnya' ? '' : 'hidden' }}">
                            <label for="other_father_last_education"
                                class="block text-sm font-medium text-gray-800 mb-1">Tulis
                                pendidikan terakhir lainnya</label>
                            <input type="text" name="other_father_last_education" id="other_father_last_education"
                                value="{{ old('other_father_last_education', $parent->other_father_last_education ?? '') }}"
                                class="border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring-indigo-500">
                            @error('other_father_last_education')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="father_main_job" class="block text-sm font-medium text-gray-800 mb-1">Pekerjaan utama
                            Ayah</label>
                        <select name="father_main_job" id="father_main_job"
                            onchange="toggleOther(this,'other_father_main_job_input_wrapper')"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                            <option value="" disabled>-- Pilih Pendidikan Terakhir </option>

                            {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                            @if (!in_array(old('father_main_job', $parent->father_main_job ?? ''), \App\Enums\MainJobEnum::values()))
                                <option value="{{ old('father_main_job', $parent->father_main_job ?? '') }}" selected>
                                    {{ old('father_main_job', $parent->father_main_job ?? '') }}
                                </option>
                            @endif

                            @foreach (\App\Enums\MainJobEnum::cases() as $mj)
                                {{-- mj is main job --}}
                                <option value="{{ $mj->value }}"
                                    {{ old('father_main_job', $parent->father_main_job ?? '') === $mj->value ? 'selected' : '' }}>
                                    {{ $mj->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('father_main_job')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        {{-- Field input manual muncul jika "lainnya" dipilih --}}
                        <div id="other_father_main_job_input_wrapper"
                            class="mt-3 {{ old('father_main_job', $parent->father_main_job ?? '') === 'Lainnya' ? '' : 'hidden' }}">
                            <label for="other_father_main_job" class="block text-sm font-medium text-gray-800 mb-1">Tulis
                                pekerjaan utama lainnya</label>
                            <input type="text" name="other_father_main_job" id="other_father_main_job"
                                value="{{ old('other_father_main_job', $parent->other_father_main_job ?? '') }}"
                                class="border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring-indigo-500">
                            @error('other_father_main_job')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="father_income" class="block text-sm font-medium text-gray-800 mb-1">Penghasilan
                            Ayah</label>
                        <select name="father_income" id="father_income"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                            <option value="" disabled>-- Pilih penghasilan ayah </option>

                            {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                            @if (!in_array(old('father_income', $parent->father_income ?? ''), \App\Enums\IncomeRangeEnum::values()))
                                <option value="{{ old('father_income', $parent->father_income ?? '') }}" selected>
                                    {{ old('father_income', $parent->father_income ?? '') }}
                                </option>
                            @endif

                            @foreach (\App\Enums\IncomeRangeEnum::cases() as $ir)
                                {{-- ir is income range --}}
                                <option value="{{ $ir->value }}"
                                    {{ old('father_income', $parent->father_income ?? '') === $ir->value ? 'selected' : '' }}>
                                    {{ $ir->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('father_income')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800">Nomor Telepon Ayah</label>
                        <input type="text" name="father_phone"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. 62xxxxx" value="{{ old('father_phone', $parent->father_phone ?? '') }}">
                        @error('father_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <h3 class="font-bold mb-2">🟢 Ibu Kandung</h3>
                    <div>
                        <label for="Nama Ibu" class="block text-sm font-medium text-gray-800 mt-1 mb-2">Nama Ibu</label>
                        <input type="text" name="mother_name"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. Fulanah" value="{{ old('mother_name', $parent->mother_name ?? '') }}">
                        @error('mother_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Status Ibu" class="block text-sm font-medium text-gray-800 mb-1">Status Ibu</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach (\App\Enums\LifeStatusEnum::cases() as $ls)
                                <!-- ls is 'life status'-->
                                <label>
                                    <input type="radio" name="mother_life_status" value="{{ $ls->value }}"
                                        {{ old('mother_life_status', $parent->mother_life_status->value ?? '') === $ls->value ? 'checked' : '' }}
                                        class="hidden peer">
                                    <div
                                        class="px-4 py-2 rounded-md border border-blue-500 text-blue-600 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-blue-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                        {{ $ls->value }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('mother_life_status')
                            <p class=" mt-2 text-sm text-red-600 ">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Kewarganegaraan" class="block text-sm font-medium text-gray-800 mb-2">Pilih
                            Warganegara</label>
                        <div class="flex gap-2">
                            @foreach (\App\Enums\CitizenshipEnum::cases() as $mc)
                                {{-- mc is mother citizenship --}}
                                <label>
                                    <input type="radio" name="mother_citizenship" value="{{ $mc->value }}"
                                        {{ old('mother_citizenship', $parent->mother_citizenship->value ?? '') === $mc->value ? 'checked' : '' }}
                                        class="hidden peer">
                                    <div
                                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-800 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                        {{ $mc->value }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('mother_citizenship')
                            <p class=" mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800">NIK Ibu</label>
                        <input type="text" name="mother_national_id_number"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="16 digits"
                            value="{{ old('mother_national_id_number', $parent->mother_national_id_number ?? '') }}">
                        @error('mother_national_id_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Tempat Lahir" class="block text-sm font-medium text-gray-800">Tempat
                            Lahir</label>
                        <input type="text" name="mother_birth_place"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. Yogyakarta"
                            value="{{ old('mother_birth_place', $parent->mother_birth_place ?? '') }}">
                        @error('mother_birth_place')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Tanggal Lahir" class="block text-sm font-medium text-gray-800">Tanggal Lahir*</label>
                        <p class="text-xs text-gray-500 mt-1">* Format tanggal mengikuti pengaturan perangkat Anda
                            (biasanya
                            Bulan-Hari-Tahun)</p>
                        <input type="date" name="mother_birth_date"
                            value="{{ old('mother_birth_date', isset($parent) && $parent->mother_birth_date ? $parent->mother_birth_date->format('Y-m-d') : '') }}"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('mother_birth_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="mother_last_education" class="block text-sm font-medium text-gray-800 mb-1">Pendidikan
                            Terakhir Ibu</label>
                        <select name="mother_last_education" id="mother_last_education"
                            onchange="toggleOther(this,'other_mother_last_education_input_wrapper')"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                            <option value="" disabled>-- Pilih Pendidikan Terakhir </option>

                            {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                            @if (
                                !in_array(old('mother_last_education', $parent->mother_last_education ?? ''),
                                    \App\Enums\LastEducationEnum::values()))
                                <option value="{{ old('mother_last_education', $parent->mother_last_education ?? '') }}"
                                    selected>
                                    {{ old('mother_last_education', $parent->mother_last_education ?? '') }}
                                </option>
                            @endif

                            @foreach (\App\Enums\LastEducationEnum::cases() as $ls)
                                {{-- ls is last education --}}
                                <option value="{{ $ls->value }}"
                                    {{ old('mother_last_education', $parent->mother_last_education ?? '') === $ls->value ? 'selected' : '' }}>
                                    {{ $ls->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('mother_last_education')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        {{-- Field input manual muncul jika "lainnya" dipilih --}}
                        <div id="other_mother_last_education_input_wrapper"
                            class="mt-3 {{ old('mother_last_education', $parent->mother_last_education ?? '') === 'Lainnya' ? '' : 'hidden' }}">
                            <label for="other_mother_last_education"
                                class="block text-sm font-medium text-gray-800 mb-1">Tulis
                                pendidikan terakhir lainnya</label>
                            <input type="text" name="other_mother_last_education" id="other_mother_last_education"
                                value="{{ old('other_mother_last_education', $parent->other_mother_last_education ?? '') }}"
                                class="border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring-indigo-500">
                            @error('other_mother_last_education')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="mother_main_job" class="block text-sm font-medium text-gray-800 mb-1">Pekerjaan utama
                            Ibu</label>
                        <select name="mother_main_job" id="mother_main_job"
                            onchange="toggleOther(this,'other_mother_input_wrapper')"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                            <option value="" disabled>-- Pilih Pendidikan Terakhir </option>

                            {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                            @if (!in_array(old('mother_main_job', $parent->mother_main_job ?? ''), \App\Enums\MainJobEnum::values()))
                                <option value="{{ old('mother_main_job', $parent->mother_main_job ?? '') }}" selected>
                                    {{ old('mother_main_job', $parent->mother_main_job ?? '') }}
                                </option>
                            @endif

                            @foreach (\App\Enums\MainJobEnum::cases() as $mj)
                                {{-- mj is main job --}}
                                <option value="{{ $mj->value }}"
                                    {{ old('mother_main_job', $parent->mother_main_job ?? '') === $mj->value ? 'selected' : '' }}>
                                    {{ $mj->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('mother_main_job')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        {{-- Field input manual muncul jika "lainnya" dipilih --}}
                        <div id="other_mother_input_wrapper"
                            class="mt-3 {{ old('mother_main_job', $parent->mother_main_job ?? '') === 'Lainnya' ? '' : 'hidden' }}">
                            <label for="other_mother_main_job" class="block text-sm font-medium text-gray-800 mb-1">Tulis
                                pekerjaan utama lainnya</label>
                            <input type="text" name="other_mother_main_job" id="other_mother_main_job"
                                value="{{ old('other_mother_main_job', $parent->other_mother_main_job ?? '') }}"
                                class="border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring-indigo-500">
                            @error('other_mother_main_job')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="mother_income" class="block text-sm font-medium text-gray-800 mb-1">Penghasilan
                            Ibu</label>
                        <select name="mother_income" id="mother_income"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                            <option value="" disabled>-- Pilih penghasilan Ibu </option>

                            {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                            @if (!in_array(old('mother_income', $parent->mother_income ?? ''), \App\Enums\IncomeRangeEnum::values()))
                                <option value="{{ old('mother_income', $parent->mother_income ?? '') }}" selected>
                                    {{ old('mother_income', $parent->mother_income ?? '') }}
                                </option>
                            @endif

                            @foreach (\App\Enums\IncomeRangeEnum::cases() as $ir)
                                {{-- ir is income range --}}
                                <option value="{{ $ir->value }}"
                                    {{ old('mother_income', $parent->mother_income ?? '') === $ir->value ? 'selected' : '' }}>
                                    {{ $ir->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('mother_income')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800">Nomor Telepon Ibu</label>
                        <input type="text" name="mother_phone"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. 62xxxxx" value="{{ old('mother_phone', $parent->mother_phone ?? '') }}">
                        @error('mother_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <h3 class="font-bold mb-2">🟢 Wali (Opsional)</h3>
                    <div>
                        <label for="Nama Wali" class="block text-sm font-medium text-gray-800">Nama Wali (tidak wajib
                            diisi)</label>
                        <input type="text" name="guardian_name"
                            class="border border-gray-300 rounde-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. Fulan/Fulanah"
                            value="{{ old('guardian_name', $parent->guardian_name ?? '') }}">
                        @error('guardian_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Kewarganegaraan" class="block text-sm font-medium text-gray-800 mb-2">Pilih
                            Warganegara</label>
                        <div class="flex gap-2">
                            @foreach (\App\Enums\CitizenshipEnum::cases() as $gc)
                                {{-- gc is guardian citizenship --}}
                                <label>
                                    <input type="radio" name="guardian_citizenship" value="{{ $gc->value }}"
                                        {{ old('guardian_citizenship', $parent->guardian_citizenship->value ?? '') === $gc->value ? 'checked' : '' }}
                                        class="hidden peer">
                                    <div
                                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-800 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                        {{ $gc->value }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('guardian_citizenship')
                            <p class=" mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800">NIK Wali</label>
                        <input type="text" name="guardian_national_id_number"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="16 digits"
                            value="{{ old('guardian_national_id_number', $parent->guardian_national_id_number ?? '') }}">
                        @error('guardian_national_id_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Tempat Lahir" class="block text-sm font-medium text-gray-800">Tempat
                            Lahir</label>
                        <input type="text" name="guardian_birth_place"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. Yogyakarta"
                            value="{{ old('guardian_birth_place', $parent->guardian_birth_place ?? '') }}">
                        @error('guardian_birth_place')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Tanggal Lahir" class="block text-sm font-medium text-gray-800">Tanggal Lahir*</label>
                        <p class="text-xs text-gray-500 mt-1">* Format tanggal mengikuti pengaturan perangkat Anda
                            (biasanya
                            Bulan-Hari-Tahun)</p>
                        <input type="date" name="guardian_birth_date"
                            value="{{ old('guardian_birth_date', isset($parent) && $parent->guardian_birth_date ? $parent->guardian_birth_date->format('Y-m-d') : '') }}"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('guardian_birth_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="guardian_last_education"
                            class="block text-sm font-medium text-gray-800 mb-1">Pendidikan
                            Terakhir Wali</label>
                        <select name="guardian_last_education" id="guardian_last_education"
                            onchange="toggleOther(this,'other_guardian_last_education_input_wrapper')"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                            <option value="" disabled>-- Pilih Pendidikan Terakhir </option>

                            {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                            @if (
                                !in_array(old('guardian_last_education', $parent->guardian_last_education ?? ''),
                                    \App\Enums\LastEducationEnum::values()))
                                <option
                                    value="{{ old('guardian_last_education', $parent->guardian_last_education ?? '') }}"
                                    selected>
                                    {{ old('guardian_last_education', $parent->guardian_last_education ?? '') }}
                                </option>
                            @endif

                            @foreach (\App\Enums\LastEducationEnum::cases() as $ls)
                                {{-- ls is last education --}}
                                <option value="{{ $ls->value }}"
                                    {{ old('guardian_last_education', $parent->guardian_last_education ?? '') === $ls->value ? 'selected' : '' }}>
                                    {{ $ls->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('guardian_last_education')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        {{-- Field input manual muncul jika "lainnya" dipilih --}}
                        <div id="other_guardian_last_education_input_wrapper"
                            class="mt-3 {{ old('guardian_last_education', $parent->guardian_last_education ?? '') === 'Lainnya' ? '' : 'hidden' }}">
                            <label for="other_guardian_last_education"
                                class="block text-sm font-medium text-gray-800 mb-1">Tulis
                                pendidikan terakhir lainnya</label>
                            <input type="text" name="other_guardian_last_education" id="other_guardian_last_education"
                                value="{{ old('other_guardian_last_education', $parent->other_guardian_last_education ?? '') }}"
                                class="border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring-indigo-500">
                            @error('other_guardian_last_education')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="guardian_main_job" class="block text-sm font-medium text-gray-800 mb-1">Pekerjaan
                            utama
                            Wali</label>
                        <select name="guardian_main_job" id="guardian_main_job"
                            onchange="toggleOther(this,'other_guardian_input_wrapper')"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                            <option value="" disabled>-- Pilih Pendidikan Terakhir </option>

                            {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                            @if (!in_array(old('guardian_main_job', $parent->guardian_main_job ?? ''), \App\Enums\MainJobEnum::values()))
                                <option value="{{ old('guardian_main_job', $parent->guardian_main_job ?? '') }}"
                                    selected>
                                    {{ old('guardian_main_job', $parent->guardian_main_job ?? '') }}
                                </option>
                            @endif

                            @foreach (\App\Enums\MainJobEnum::cases() as $mj)
                                {{-- mj is main job --}}
                                <option value="{{ $mj->value }}"
                                    {{ old('guardian_main_job', $parent->guardian_main_job ?? '') === $mj->value ? 'selected' : '' }}>
                                    {{ $mj->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('guardian_main_job')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        {{-- Field input manual muncul jika "lainnya" dipilih --}}
                        <div id="other_guardian_input_wrapper"
                            class="mt-3 {{ old('guardian_main_job', $parent->guardian_main_job ?? '') === 'Lainnya' ? '' : 'hidden' }}">
                            <label for="other_guardian_main_job"
                                class="block text-sm font-medium text-gray-800 mb-1">Tulis
                                pekerjaan utama lainnya</label>
                            <input type="text" name="other_guardian_main_job" id="other_guardian_main_job"
                                value="{{ old('other_guardian_main_job', $parent->other_guardian_main_job ?? '') }}"
                                class="border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring-indigo-500">
                            @error('other_guardian_main_job')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="guardian_income" class="block text-sm font-medium text-gray-800 mb-1">Penghasilan
                            Wali</label>
                        <select name="guardian_income" id="guardian_income"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 ">
                            <option value="" disabled>-- Pilih penghasilan Wali </option>

                            {{-- Tambahkan nilai custom jika tidak termasuk enum --}}
                            @if (!in_array(old('guardian_income', $parent->guardian_income ?? ''), \App\Enums\IncomeRangeEnum::values()))
                                <option value="{{ old('guardian_income', $parent->guardian_income ?? '') }}" selected>
                                    {{ old('guardian_income', $parent->guardian_income ?? '') }}
                                </option>
                            @endif

                            @foreach (\App\Enums\IncomeRangeEnum::cases() as $ir)
                                {{-- ir is income range --}}
                                <option value="{{ $ir->value }}"
                                    {{ old('guardian_income', $parent->guardian_income ?? '') === $ir->value ? 'selected' : '' }}>
                                    {{ $ir->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('guardian_income')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800">Nomor Telepon Wali</label>
                        <input type="text" name="guardian_phone"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. 62xxxxxxxxxx"
                            value="{{ old('guardian_phone', $parent->guardian_phone ?? '') }}">
                        @error('guardian_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <h3 class="font-bold ">🟢 Tempat tinggal domisili orangtua/wali</h3>
                    <div>
                        <label for="Kepemilikan rumah" class="block text-sm font-medium text-gray-800 mb-2">Pilih
                            Status kepemilikan rumah</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach (\App\Enums\HouseOwnershipEnum::cases() as $ho)
                                {{-- ho is house ownership --}}
                                <label>
                                    <input type="radio" name="house_ownership" value="{{ $ho->value }}"
                                        {{ old('house_ownership', $parent->house_ownership->value ?? '') === $ho->value ? 'checked' : '' }}
                                        class="hidden peer">
                                    <div
                                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-800 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-100 cursor-pointer {{ $disabledForm ? 'border-gray-400 text-gray-700 hover:bg-gray-100 cursor-not-allowed' : '' }}">
                                        {{ $ho->value }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('house_ownership')
                            <p class=" mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800">Alamat</label>
                        <input type="text" name="address"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('address', $parent->address ?? '') }}">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="province" class="block font-medium text-sm text-gray-700">Provinsi</label>
                        <select name="province" id="province"
                            data-selected="{{ old('province', $parent->province ?? '') }}"
                            class="form-select w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                            required></select>

                    </div>
                    <div>
                        <label for="regency" class="block font-medium text-sm text-gray-700">Kabupaten/Kota</label>
                        <select name="regency" id="regency"
                            data-selected="{{ old('regency', $parent->regency ?? '') }}"
                            class="form-select w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                            required></select>
                    </div>
                    <div>
                        <label for="district" class="block font-medium text-sm text-gray-700">Kecamatan</label>
                        <select name="district" id="district"
                            data-selected="{{ old('district', $parent->district ?? '') }}"
                            class="form-select w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                            required></select>
                    </div>
                    <div>
                        <label for="village" class="block font-medium text-sm text-gray-700">Desa/Kelurahan</label>
                        <select name="village" id="village"
                            data-selected="{{ old('village', $parent->village ?? '') }}"
                            class="form-select w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                            required></select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800">RW</label>
                        <input type="text" name="rw"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('rw', $parent->rw ?? '') }}">
                        @error('rw')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800">RT</label>
                        <input type="text" name="rt"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('rt', $parent->rt ?? '') }}">
                        @error('rt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800">Kode Pos</label>
                        <input type="text" name="postal_code"
                            class="border border-gray-300 rounded-md shadow-sm p-2 w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('postal_code', $parent->postal_code ?? '') }}">
                        @error('postal_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2 px-4 rounded-md w-full cursor-pointer {{ $disabledForm ? 'bg-gray-400 text-gray-700 hover:bg-gray-400 cursor-not-allowed' : '' }}"
                            @disabled($disabledForm)>
                            Simpan Data</button>
                    </div>

                </div>
                <script>
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

                        const provinceSelect = document.getElementById('province');
                        const regencySelect = document.getElementById('regency');
                        const districtSelect = document.getElementById('district');

                        const selectedProvince = provinceSelect.dataset.selected;
                        const selectedRegency = regencySelect.dataset.selected;
                        const selectedDistrict = districtSelect.dataset.selected;
                        const selectedVillage = document.getElementById('village').dataset.selected;

                        radios.forEach((radio) => {
                            toggleOtherRadio(radio);

                            radio.addEventListener('change', () => {
                                toggleOtherRadio(radio)
                            });
                        });

                        // 1. load provinsi
                        fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json').then(res => res.json()).then(
                            data => {
                                let options = '<option value="">-- Pilih Provinsi --</option>';
                                data.forEach(province => {
                                    const selected = province.name === selectedProvince ? 'selected' : '';
                                    options +=
                                        `<option value="${province.name}" ${selected}>${province.name}</option>`;
                                });
                                provinceSelect.innerHTML = options;

                                if (selectedProvince) {
                                    // Ambil ID provinsi terpilih
                                    const selectedProvinceData = data.find(p => p.name === selectedProvince);
                                    if (selectedProvinceData) {
                                        loadRegencies(selectedProvinceData.id);
                                    }
                                }
                            });

                        // 2. load kabupaten ketika provinsi dipilih
                        provinceSelect.addEventListener('change', function() {
                            loadRegenciesByName(this.value);
                        });

                        function loadRegenciesByName(provinceName) {
                            fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json').then(res => res.json())
                                .then(provinces => {
                                    const found = provinces.find(p => p.name === provinceName);
                                    if (found) {
                                        loadRegencies(found.id);
                                    }
                                });
                        }

                        function loadRegencies(provinceId) {
                            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`).then(res =>
                                res.json()).then(data => {
                                let options = '<option value="">-- Pilih Kabupaten --</option>';
                                data.forEach(regency => {
                                    const selected = regency.name === selectedRegency ? 'selected' : '';
                                    options +=
                                        `<option value="${regency.name}" ${selected}>${regency.name}</option>`;
                                });
                                regencySelect.innerHTML = options;

                                if (selectedRegency) {
                                    const selectedRegencyData = data.find(r => r.name === selectedRegency);
                                    if (selectedRegency) {
                                        loadDistricts(selectedRegencyData.id);
                                    }
                                }
                            });
                        }
                        // 3. load kecamatan ketika kabupaten dipilih
                        regencySelect.addEventListener('change', function() {
                            const regencyName = this.value;
                            fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json').then(res => res
                                .json()).then(provinces => {
                                const selectedProvinceData = provinces.find(p => p.name === provinceSelect
                                    .value);
                                if (selectedProvinceData) {
                                    fetch(
                                            `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${selectedProvinceData.id}.json`
                                        )
                                        .then(res => res.json()).then(regencies => {
                                            const regencyData = regencies.find(r => r.name === regencyName);
                                            if (regencyData) {
                                                loadDistricts(regencyData.id);
                                            }
                                        });
                                }
                            });
                        });

                        function loadDistricts(regencyId) {
                            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`).then(res =>
                                res.json()).then(data => {
                                let options = '<option value="">-- Pilih Kecamatan --</option>';
                                data.forEach(district => {
                                    const selected = district.name === selectedDistrict ? 'selected' : '';
                                    options +=
                                        `<option value="${district.name}" data-id="${district.id}" ${selected}>${district.name}</option>`;
                                });
                                districtSelect.innerHTML = options;

                                // Jika kecamatan sudah terpilih, maka akan load option village
                                if (selectedDistrict) {
                                    const selectedDistrictData = data.find(d => d.name === selectedDistrict);
                                    if (selectedDistrictData) {
                                        loadVillages(selectedDistrictData.id);
                                    }
                                }
                                districtSelect.addEventListener('change', function() {
                                    const selectedOption = this.options[this.selectedIndex];
                                    const districtId = selectedOption.getAttribute('data-id');
                                    loadVillages(districtId);
                                });
                            });
                        }

                        // 4. load village ketika kecamatan dipilih
                        function loadVillages(districtId) {
                            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`).then(res =>
                                res.json()).then(data => {
                                let options = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                                data.forEach(village => {
                                    const selected = village.name === selectedVillage ? 'selected' : '';
                                    options +=
                                        `<option value="${village.name}"${selected}>${village.name}</option>`;
                                });
                                document.getElementById('village').innerHTML = options;
                            });
                        }
                    });
                </script>
            </form>
        </fieldset>
    </div>
@endsection
