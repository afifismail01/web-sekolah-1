<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\RegistrationStage;
use App\Enums\StudentStatusEnum;
use App\Enums\StageNameEnum;

class AnnouncementPageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;

        // validasi data student
        if (!$student) {
            return view('student.announcement_page_error')->with('error', 'Data siswa belum diisi');
        }
        // Ambil tahapan aktif
        $activeStage = RegistrationStage::where('is_active', true)->first();
        $stage = $activeStage?->stage_name?->value ?? 'belum_pengumuman';
        return view('student.announcement_page', [
            'name' => $student->name,
            'status' => $student->status?->value, //diterima, ditolak atau cadangan
            'stage' => $stage,
        ]);
    }
}
