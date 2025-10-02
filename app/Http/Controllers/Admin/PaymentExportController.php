<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentExportController extends Controller
{
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //header untuk table
        $sheet->fromArray([['No', 'Nama Lengkap', 'ID Transaksi', 'Status', 'Dibayar Pada']], null, 'A1');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $payments = Payment::with('user')->get();
        $row = 2;
        foreach ($payments as $index => $payment) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $payment->user?->name);
            $sheet->setCellValue("C{$row}", $payment->order_id);
            $sheet->setCellValue("D{$row}", $payment->status);
            $sheet->setCellValue("E{$row}", $payment->paid_at);

            $row++;
        }
        //membuat lebar kolom sesuai dengan lebar text
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'data_administrasi_' . now()->format('Y-m-d') . '.xlsx';
        $tempPath = storage_path($filename);
        $writer->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend();
    }
    public function exportPdf()
    {
        $payments = Payment::with('user')->get();
        $pdf = Pdf::loadView('pdf.payments', compact('payments'))->setPaper('legal', 'landscape');

        $filename = 'data_administrasi_' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
