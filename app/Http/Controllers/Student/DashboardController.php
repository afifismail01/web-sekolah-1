<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegistrationStage;
use App\Enums\StageNameEnum;

class DashboardController extends Controller
{
    public function index()
    {
        $activeStage = RegistrationStage::where('is_active', true)->first();
        $disabledForm = $activeStage?->stage_name !== StageNameEnum::REGISTRATION;
        return view('student.dashboard', compact('activeStage', 'disabledForm'));
    }
}
