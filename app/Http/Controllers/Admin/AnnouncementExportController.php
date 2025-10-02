<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;
use Illuminate\Http\Request;

class AnnouncementExportController extends Controller
{
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header untuk table
        $sheet->fromArray(['No', 'Nama Lengkap', 'Status Seleksi'], null, 'A1');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        $announcements = Student::with('user')->get();
        $row = 2;
        foreach ($announcements as $index => $announcement) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $announcement->user?->name);
            $sheet->setCellValue("C{$row}", $announcement->status->value ?? '-');
            $row++;
        }
        // Membuat lebar kolom agar sesuai dengan lebar text
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'data_pengumuman' . now()->format('Y-m-d') . '.xlsx';
        $tempPath = storage_path($filename);
        $writer->save($tempPath);
        return response()->download($tempPath)->deleteFileAfterSend();
    }
    public static function exportPdf()
    {
        $announcements = Student::with('user')->get();
        $pdf = Pdf::loadView('pdf.announcements', compact('announcements'))->setPaper('legal', 'landscape');
        $filename = 'data_pengumuman' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
