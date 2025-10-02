<?php

namespace App\Http\Controllers;

use App\Models\RegistrationStage;
use Illuminate\Http\Request;

class RegistrationStageController extends Controller
{
    public function index()
    {
        $tahapan = RegistrationStage::lastest()->first();
        return view('admin.dashboard', compact);
    }
}
