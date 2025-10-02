<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class StudentLetterController extends Controller
{
    public function download(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student) {
            abort(404, 'Data Siswa Tidak Ditemukan');
        }
        $status = $student->status;

        try {
            // Log::info('Ini adalah log dari download surat keterangan dan berarti sistem sampai ke tahap ini..');

            $pdf = Pdf::loadView('pdf.selection_result_letter', compact('student', 'status'))->setPaper('A4', 'Potrait');
            return $pdf->download("Surat_Hasil_Seleksi_{$student->name}.pdf");
        } catch (\Throwable $e) {
            // Log::info('ini adalah log jika sistem tidak dapat mencapai ke tahap ini');
            return response()->json(['error' => 'Error generating PDF', 'message' => $e->getMessage()]);
        }
    }
}
