<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentFileController extends Controller
{
    public function show(Student $student)
    {
        $files = $student->uploadedFiles;
        return view('admin.students.files', compact('student', 'files'));
    }
}
