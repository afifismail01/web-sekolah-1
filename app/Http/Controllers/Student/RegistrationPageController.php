<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\GenderEnum;
use App\Enums\AdmissionTrackEnum;
use App\Enums\EducationLevelEnum;
use App\Enums\StudentStatusEnum;
use App\Enums\FileTypeEnum;
use App\Enums\CategoryFileEnum;
use App\Enums\StageNameEnum;
use App\Enums\CitizenshipEnum;
use App\Enums\DisabilityEnum;
use App\Enums\EducationFundingEnum;
use App\Enums\FutureGoalEnum;
use App\Enums\HobbyEnum;
use App\Enums\SpecialNeedsEnum;
use App\Enums\LifeStatusEnum;
use App\Enums\HouseOwnershipEnum;
use App\Models\Student;
use App\Models\ParentData;
use App\Models\UploadedFile;
use App\Models\RegistrationStage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator; //Untuk menghandle bagian validasi dataOrangTuaStore
use Illuminate\Support\Facades\Storage; //Untuk menghandle bagian store upload berkas
use Carbon\Carbon;

class RegistrationPageController extends Controller
{
    public function personalData()
    {
        $student = auth()->user()->student;

        // Enums List
        $levelList = EducationLevelEnum::cases();
        $trackList = AdmissionTrackEnum::cases();
        $citizenshipList = CitizenshipEnum::cases();
        $disabilityList = DisabilityEnum::cases();
        $educationFundingList = EducationFundingEnum::cases();
        $futureGoalList = FutureGoalEnum::cases();
        $hobbyList = HobbyEnum::cases();
        $specialNeedList = SpecialNeedsEnum::cases();

        $paidStatus = auth()->user()->payment?->status === 'paid';
        $activeStage = RegistrationStage::where('is_active', true)->first();
        $disabledForm = !$paidStatus || $activeStage?->stage_name !== StageNameEnum::REGISTRATION;

        // Mengatur batasan input tanggal lahir

        $educationLevels = collect(EducationLevelEnum::cases())
            ->mapWithKeys(function ($case) {
                $ageLimit = match ($case->value) {
                    'MTs' => 15,
                    'MA' => 21,
                    'PAUD' => 6,
                    default => 19,
                };
                return [$case->value => $ageLimit];
            })
            ->toArray();
        $maxDate = Carbon::today()->toDateString();

        return view('student.registration.personal_data', compact('levelList', 'trackList', 'activeStage', 'paidStatus', 'disabledForm', 'student', 'educationLevels', 'maxDate', 'educationFundingList'));
    }

    public function parentData()
    {
        $user = auth()->user();
        $parent = $user->parent; //menggunakan relasi
        $paidStatus = auth()->user()->payment?->status === 'paid';
        $activeStage = RegistrationStage::where('is_active', true)->first();
        $disabledForm = !$paidStatus || $activeStage?->stage_name !== StageNameEnum::REGISTRATION;

        // $minDate = Carbon::now()->subYears(19)->startOfYear()->toDateString();
        // $maxDate = Carbon::today()->toDateString();

        return view('student.registration.parent_data', compact('paidStatus', 'activeStage', 'disabledForm', 'parent', 'user'));
    }

    public function uploadFile()
    {
        $paidStatus = auth()->user()->payment?->status === 'paid';
        $activeStage = RegistrationStage::where('is_active', true)->first();
        $disabledForm = !$paidStatus || $activeStage?->stage_name !== StageNameEnum::REGISTRATION;

        $student = auth()->user()->student;

        if (!$student || !$student->education_level || !$student->admission_track) {
            return view('student.registration.upload_file_error', compact('activeStage'));
        }

        $requiredFiles = [];
        $supportingFiles = [];

        // Perlu disesuaikan saat menambahkan field yang dibutuhkan agar sesuai dengan dapodik
        /*$requiredFiles = match ($student->education_level->value) {
            'MTs' => ['Foto Raport kelas V semester I dan II'],
            'MA' => ['Foto Raport kelas VIII semester I dan II'],
            default => [],
        };

        $supportingFiles = match ($student->admission_track->value) {
            'Jalur Kerjasama' => ['Surat Rekomendasi dari sekolah asal'],
            'Jalur Prestasi' => ['Sertifikat / Surat Keterangan dari sekolah asal'],
            'Jalur Beasiswa Yatim-Dhuafa' => ['Dokumen pendukung KIP/KIS/KKS/SKTM'],
            'Jalur Beasiswa Prestasi dan Yatim-Dhuafa' => ['Sertifikat / Surat Keterangan dari sekolah asal', 'Dokumen pendukung KIP/KIS/KKS/SKTM'],
            'Jalur Mandiri' => [],
            default => [],
        };*/
        $uploadedFiles = $student->uploadedFiles;
        $categoryFiles = CategoryFileEnum::cases();

        //Validasi untuk file bertipe required
        $uploadedRequiredFiles = $uploadedFiles->where('file_category', 'required');

        //Validasi untuk file bertipe support
        $uploadedSupportingFiles = $uploadedFiles->where('file_category', 'support');

        $fileTypes = FileTypeEnum::cases();
        $categoryRequired = CategoryFileEnum::REQUIRED;

        $isFirstTime = $uploadedFiles->isEmpty(); //bernilai true jika belum upload
        return view('student.registration.upload_file', compact('fileTypes', 'categoryRequired', 'categoryFiles', 'requiredFiles', 'supportingFiles', 'uploadedFiles', 'student', 'isFirstTime', 'uploadedRequiredFiles', 'uploadedSupportingFiles', 'activeStage', 'paidStatus', 'disabledForm'));
    }

    public function personalDataStore(Request $request)
    {
        $student = auth()->user()->student;
        $paidStatus = auth()->user()->payment?->status === 'paid';
        $activeStage = RegistrationStage::where('is_active', true)->first();
        $disabledForm = !$paidStatus || $activeStage?->stage_name !== StageNameEnum::REGISTRATION;

        $today = Carbon::today();
        $level = $request->input('education_level', 'MTs'); // default MTs

        // Tentukan batas usia berdasarkan jenjang
        if ($level === 'MA') {
            $minDate = $today->copy()->subYears(21)->startOfYear()->toDateString();
        } elseif ($level === 'PAUD') {
            $minDate = $today->copy()->subYears(6)->startOfYear()->toDateString();
        } else {
            // default MTs
            $minDate = $today->copy()->subYears(15)->startOfYear()->toDateString();
        }

        // Merge nilai akhir untuk menu yang memiliki opsi "Lainnya"
        $request->merge([
            'future_goal' => resolveOtherField('future_goal', $request),
            'hobby' => resolveOtherField('hobby', $request),
            'special_needs' => resolveOtherField('special_needs', $request),
            'disability' => resolveOtherField('disability', $request),
        ]);
        // Validasi data
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'nisn' => ['nullable', 'digits:10'],
                'national_id_number' => ['required', 'digits:16'],
                'birth_date' => ['required', 'date', 'after_or_equal:' . $minDate, 'before_or_equal:' . $today->toDateString()],
                'birth_place' => ['required', 'string', 'max:255'],
                'siblings_count' => ['required', 'numeric'],
                'child_number' => ['required', 'numeric'],
                'religion' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'regex:/^62[0-9]{9,13}$/'],
                'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com)$/i'],
                'previous_school' => ['nullable', 'string', 'max:255'],
                'previous_school_npsn' => ['nullable', 'regex:/^[A-Za-z0-9]{8}$/'],
                'kip_number' => ['nullable', 'digits:12'],
                'kip_year' => ['nullable', 'digits:4'],
                'family_card_number' => ['required', 'digits:16'],
                'family_head_name' => ['required', 'string', 'max:255'],
                'admission_track' => ['required', Rule::in(AdmissionTrackEnum::values())],
                'education_level' => ['required', Rule::in(EducationLevelEnum::values())],
                'gender' => ['required', Rule::in(GenderEnum::values())],
                'citizenship' => ['required', Rule::in(CitizenshipEnum::values())],
                'disability' => ['required', 'string', 'max:255'],
                'other_disability' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn() => $request->disability === 'Lainnya')],
                'education_funding' => ['required', Rule::in(EducationFundingEnum::values())],
                'future_goal' => ['required', 'string', 'max:255'],
                'other_future_goal' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn() => $request->future_goal === 'Lainnya')],
                'hobby' => ['required', 'string', 'max:255'],
                'other_hobby' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn() => $request->hobby === 'Lainnya')],
                'special_needs' => ['required', 'string', 'max:255'],
                'other_special_needs' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn() => $request->special_needs === 'Lainnya')],
                // 'address' => 'required|string|max:255',
                // 'postal_code' => 'required|numeric|digits:5',
            ],
            [
                // custom messages
                'name.required' => 'Kolom nama harus diisi',
                // 'nisn.required' => 'Kolom nisn harus diisi',
                'nisn.digits' => 'Pastikan jumlah digit dalam nisn berjumlah 10 digit',
                'siblings_count.required' => 'Kolom jumlah saudara harus diisi',
                'child_number.required' => 'Kolom anak ke- harus diisi',
                'religion.required' => 'Kolom agama harus diisi',
                'phone_number.required' => 'Kolom nomor telepon harus diisi',
                'phone_number.regex' => 'Format nomor diawali dengan 62',
                'email.required' => 'Kolom email harus diisi',
                'email.regex' => 'Email harus menggunakan @gmail.com atau @yahoo.com.',
                // 'previous_school.required' => 'Kolom sekolah asal harus diisi',
                // 'previous_school_npsn.required' => 'Kolom npsn sekolah asal harus diisi',
                'previous_school_npsn.regex' => 'Pastikan jumlah karakter dalam npsn sekolah asal berjumlah 8 karakter',
                'kip_number.digits' => 'Pastikan jumlah digit dalam nomor KIP berjumlah 12 digit',
                'kip_year.digits' => 'Pastikan jumlah digit dalam tahun KIP berjumlah 4 digit',
                'birth_date.required' => 'Kolom tanggal lahir harus diisi',
                'birth_date.after_or_equal' => 'Tanggal hanya bisa diinput mulai dari 2010 hingga hari ini',
                'birth_date.before_or_equal' => 'Tanggal yang bisa diinput hanya dari 2010 hingga hari ini',
                'birth_place.required' => 'Kolom tempat lahir harus diisi',
                'gender.required' => 'Kolom jenis kelamin harus diisi',
                'national_id_number.digits' => 'Pastikan angka berjumlah 16',
                'admission_track.required' => 'Kolom jalur pendaftaran harus diisi',
                'education_level.required' => 'Kolom jenjang pendaftaran harus diisi',
                'citizenship.required' => 'Kolom kewarganegaraan harus diisi',
                'disability.required' => 'Kolom kebutuhan disabilitas harus diisi',
                'education_funding.required' => 'Kolom Yang membiayai sekolah harus diisi',
                'future_goal.required' => 'Kolom cita-cita harus diisi',
                'hobby.required' => 'Kolom hobi harus diisi',
                'special_needs.required' => 'Kolom kebutuhan khusus harus diisi',
                // 'postal_code.required' => 'Kode pos harus diisi',
                // 'address.required' => 'Alamat harus diisi',
            ],
        );
        if (!$paidStatus || $activeStage?->stage_name !== StageNameEnum::REGISTRATION) {
            return redirect()->back()->with('error', 'Saat ini Anda tidak dapat mengisi data. Periksa status pembayaran dan tahapan.');
        }

        // Simpan ke session
        Session::put('student.registration.personal_data', $validated);
        // Simpan ke database
        $updateData = $validated;
        $updateData['payment_id'] = auth()->user()->payment?->id;
        Student::updateOrCreate(['user_id' => auth()->id()], $updateData);

        return redirect()->back()->with('success', 'Data diri berhasil disimpan!');
    }

    public function parentDataStore(Request $request)
    {
        $request->merge([
            'father_last_education' => resolveOtherField('father_last_education', $request),
            'father_main_job' => resolveOtherField('father_main_job', $request),
            'mother_last_education' => resolveOtherField('mother_last_education', $request),
            'mother_main_job' => resolveOtherField('mother_main_job', $request),
            'guardian_main_job' => resolveOtherField('guardian_main_job', $request),
            'guardian_last_education' => resolveOtherField('guardian_last_education', $request),
        ]);
        $rules = [
            'father_name' => ['required', 'string', 'max:255'],
            'father_life_status' => ['required', Rule::in(LifeStatusEnum::values())],
            'father_citizenship' => ['required', Rule::in(CitizenshipEnum::values())],
            'father_national_id_number' => ['required', 'digits:16'],
            'father_phone' => ['required', 'string', 'regex:/^62[0-9]{9,13}$/'], //memastikan bahwa nilai yang diinputkan memiliki rentang 10-14 digit
            'father_birth_place' => ['required', 'string', 'max:255'],
            'father_birth_date' => ['required', 'date'],
            'father_income' => ['required', 'string', 'max:255'],
            'father_main_job' => ['required', 'string', 'max:255'],
            'other_father_main_job' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn() => $request->father_main_job === 'Lainnya')],
            'father_last_education' => ['required', 'string', 'max:255'],
            'other_father_last_education' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn() => $request->father_last_education === 'Lainnya')],

            'mother_name' => ['required', 'string', 'max:255'],
            'mother_life_status' => ['required', Rule::in(LifeStatusEnum::values())],
            'mother_citizenship' => ['required', Rule::in(CitizenshipEnum::values())],
            'mother_national_id_number' => ['required', 'digits:16'],
            'mother_birth_place' => ['required', 'string', 'max:255'],
            'mother_birth_date' => ['required', 'date'],
            'mother_phone' => ['required', 'string', 'regex:/^62[0-9]{9,13}$/'],
            'mother_last_education' => ['required', 'string', 'max:255'],
            'other_mother_last_education' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn() => $request->mother_last_education === 'Lainnya')],
            'mother_main_job' => ['required', 'string', 'max:255'],
            'other_mother_main_job' => ['nullable', Rule::requiredIf(fn() => $request->mother_main_job === 'Lainnya')],
            'mother_income' => ['required', 'string', 'max:255'],

            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_citizenship' => ['nullable', 'string', 'max:255'],
            'guardian_national_id_number' => ['nullable', 'digits:16'],
            'guardian_birth_place' => ['nullable', 'string', 'max:255'],
            'guardian_birth_date' => ['nullable', 'date'],
            'guardian_main_job' => ['nullable', 'string', 'max:255'],
            'other_guardian_main_job' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn() => $request->guardian_main_job === 'Lainnya')],
            'guardian_last_education' => ['nullable', 'string', 'max:255'],
            'other_guardian_last_education' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn() => $request->guardian_last_education === 'Lainnya')],
            'guardian_income' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'regex:/^62[0-9]{9,13}$/'],

            'house_ownership' => ['required', Rule::in(HouseOwnershipEnum::values())],
            'address' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'string', 'max:255'],
            'rw' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'regency' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'village' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'digits:5'],
        ];

        $validator = Validator::make($request->all(), $rules);
        /*Kondisi 1: Jika kolom guardian_name diisi, maka kolom guardian_job dan kolom guardian_phone wajib diisi
         sedangkan kolom lainnya tidak wajib diisi*/
        if ($request->filled('guardian_name')) {
            $validator->sometimes(['guardian_main_job', 'guardian_phone'], 'required', function () {
                return true;
            });
        }

        /*Kondisi 2: Jika kolom father_name diisi, maka semua data selain kolom guardian_name, kolom pekerjaan wali dan kolom guardian_phone
         wajib diisi*/
        if ($request->filled('father_name')) {
            $validator->sometimes(['mother_name', 'father_main_job', 'mother_main_job', 'father_phone', 'mother_phone'], 'required', function () {
                return true;
            });
        }

        /*Kondisi 3: Jika semua kolom field tidak terisi, maka memunculkan pesan
         untuk mengisi field terlebih dahulu*/
        if (!$request->filled('guardian_name') && !$request->filled('father_name')) {
            $validator->after(function ($validator) {
                $validator->errors()->add('guardian_name', 'isi setidaknya data wali atau data orang tua (ayah & ibu)');
            });
        }
        // dd($request->all());
        $validated = $validator->validate([
            //Custom messages
            'father_name.required' => 'Nama Ayah harap diisi',
            'father_status_life.required' => 'Status Ayah harap diisi',
            'father_citizenship.required' => 'Kewarganegaraan harap diisi',
            'father_national_id_number.required' => 'NIK harap diisi',
            'father_national_id_number.digits' => 'Pastikan jumlah digits berjumlah 16',
            'father_main_job.required' => 'Pekerjaan Ayah harap diisi',
            'father_phone.required' => 'Nomor Telepon Ayah harap diisi',
            'father_phone.regex' => 'Format nomor telepon diawali 62',
            'father_birth_place.required' => 'Tempat lahir ayah harap diisi',
            'father_birth_date.required' => 'Tanggal lahir harap diisi',
            'father_income.required' => 'Harap pilih penghasilan ayah',

            'mother_name.required' => 'Nama Ibu harap diisi',
            'mother_status_life.required' => 'Status Ibu harap diisi',
            'mother_citizenship.required' => 'Kewarganegaraan harap diisi',
            'mother_national_id_number.required' => 'NIK harap diisi',
            'mother_national_id_number.digits' => 'Pastikan jumlah digits berjumlah 16',
            'mother_main_job.required' => 'Pekerjaan Ibu harap diisi',
            'mother_phone.required' => 'Nomor Telepon Ibu harap diisi',
            'mother_phone.regex' => 'Format nomor telepon diawali 62',
            'mother_birth_place.required' => 'Tempat lahir ibu harap diisi',
            'mother_birth_date.required' => 'Tanggal lahir harap diisi',
            'mother_income.required' => 'Harap pilih penghasilan ibu',

            'guardian_phone.regex' => 'Format nomor telepon diawali 62',
            'guardian_national_id_number.digits' => 'Pastikan jumlah digits berjumlah 16',

            'house_ownership.required' => 'Kolom status kepemilikan rumah harap diisi',
            'address.required' => 'Kolom alamat harap diisi',
            'rt' => 'Kolom RT harap diisi',
            'rw' => 'Kolom RW harap diisi',
        ]);

        //Simpan di dalam session
        Session::put('student.registration.parent_data', $validated);
        //Simpan di dalam database
        ParentData::updateOrCreate(['user_id' => auth()->id()], array_merge($validated));

        return redirect()->back()->with('success', 'Data Orang Tua berhasil disimpan!');
    }

    public function uploadFileStore(Request $request)
    {
        // Multiple upload
        /*$validated = $request->validate(
            [
                'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_category' => ['required', Rule::in(CategoryFileEnum::values())],
            ],
            [
                'file.required' => 'file perlu diisi',
                'file.mimes' => 'format file tidak didukung',
                'file.max' => 'ukuran file lebih dari 2mb',
                'file.type' => 'bagian tipe belum terisi',
            ],
        );

        $student = auth()->user()->student;
        if ($student) {
            return redirect()->back()->with('error', 'Harap isi terlebih dahulu halaman data diri siswa');
        }

        $files = $request->file('file');
        $fileCategory = $request->file_category;

        // Multiple file : file[] = [key => file] berdasarkan file_type
        if (is_array($files)) {
            foreach ($files as $file_type => $file) {
                if (!$file) {
                    continue;
                }
                if (in_array($file_type, FileTypeEnum::values())) {
                    continue;
                }

                // validasi format
                $file->validate([
                    'file' => 'mimes:pdf,jpg,jpeg,png|max:2048',
                ]);

                //
                $limitedTypes = [FileTypeEnum::RAPORT->value, FileTypeEnum::BIRTH_CERTIFICATE->value, FileTypeEnum::FAMILY_CARD->value];
                if (in_array($request->file_type, $limitedTypes)) {
                    $existingCount = UploadedFile::where('student_id', $student->id)->where('file_type', $request->file_type)->count();
                    if ($existingCount >= 1) {
                        continue;
                    }
                }
                $originalName = $file->getClientOriginalName();
                //menyimpan file ke storage
                $path = $request->file('file')->store("upload/berkas/{$student->id}", 'public');
                //upload file
                UploadedFile::create([
                    'user_id' => auth()->id(),
                    'student_id' => $student->id,
                    'file_name' => $originalName,
                    'file_path' => $path,
                    'file_type' => $request->file_type,
                    'file_category' => $request->file_category,
                ]);
            }
            return back()->with('success', 'File berhasil diunggah');
        }*/

        // jika single upload
        $validated = $request->validate(
            [
                'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_category' => ['required', Rule::in(CategoryFileEnum::values())],
            ],
            [
                'file.required' => 'file perlu diisi',
                'file.mimes' => 'format file tidak didukung',
                'file.max' => 'ukuran file lebih dari 2mb',
                'file.type' => 'bagian tipe belum terisi',
            ],
        );

        //mengecek data siswa
        $student = auth()->user()->student;
        if (!$student) {
            return redirect()->back()->with('error', 'Harap isi terlebih dahulu halaman data diri siswa');
        }

        //Memberikan batasan upload file bertipe required. Untuk mengantisipasi kemungkinan user yang mencoba melakukan tambah data menggunakan aplikasi pihak ketiga atau DevTools.
        if ($request->file_category === 'required') {
            $requiredFilesCount = UploadedFile::where('student_id', $student->id)->where('file_type', 'required')->count();
            if ($requiredFilesCount >= 3) {
                return back()->with('error', 'Maksimal upload 3 file');
            }
        }

        // Memberi batasan pada setiap tipe file
        $limitedTypes = [FileTypeEnum::RAPORT->value, FileTypeEnum::BIRTH_CERTIFICATE->value, FileTypeEnum::FAMILY_CARD->value];
        if (in_array($request->file_type, $limitedTypes)) {
            $existingCount = UploadedFile::where('student_id', $student->id)->where('file_type', $request->file_type)->count();
            if ($existingCount >= 1) {
                return back()->with('error', 'File' . ucfirst($request->file_type) . ' sudah diunggah. Hapus terlebih dahulu untuk mengganti.');
            }
        }

        if ($request->file_category !== 'support') {
            $request->validate([
                'file_type' => ['required', Rule::in(FileTypeEnum::values())],
            ]);
        }

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        //menyimpan file ke storage
        $path = $request->file('file')->store("upload/berkas/{$student->id}", 'public');

        //upload file
        UploadedFile::create([
            'user_id' => auth()->id(),
            'student_id' => $student->id,
            'file_name' => $originalName,
            'file_path' => $path,
            'file_type' => $request->file_type,
            'file_category' => $request->file_category,
        ]);
        return back()->with('success', 'File berhasil diunggah');
    }
    public function deleteFile($id)
    {
        $file = UploadedFile::findOrFail($id);

        //mengecek apakah file ada
        if (!$file) {
            return back()->with('error', 'file not found');
        }

        //jika menemukan file
        if ($file->file_path && \Storage::disk('public')->exists($file->file_path)) {
            //hapus file dari storage
            \Storage::disk('public')->delete($file->file_path);
        }

        //hapus dari database
        $file->delete();

        return redirect()->back()->with('success', 'data berhasil dihapus');
    }
}
