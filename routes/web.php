<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\RegistrationPageController;
use App\Http\Controllers\Student\AdministrationPageController;
use App\Http\Controllers\Student\AnnouncementPageController;
use App\Http\Controllers\Student\StudentLetterController;
use App\Http\Controllers\Admin\StudentExportController;
use App\Http\Controllers\Admin\StudentFileController;
use App\Http\Controllers\Admin\PaymentExportController;
use App\Http\Controllers\Admin\AnnouncementExportController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Log;
use Xendit\Xendit;
use App\Services\EdupayApiService;

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::middleware(['guest', 'preventBackHistory'])->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('forgot.password.form');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetPassword'])->name('forgot.password.send');

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin', 'preventBackHistory'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/students/export/excel', [StudentExportController::class, 'exportExcel'])->name('students.export.excel');
        Route::get('/students/export/pdf', [StudentExportController::class, 'exportPdf'])->name('students.export.pdf');
        Route::get('/students/{student}/files', [StudentFileController::class, 'show'])->name('students.files');
        Route::get('/payments/export/excel', [PaymentExportController::class, 'exportExcel'])->name('payments.export.excel');
        Route::get('/payments/export/pdf', [PaymentExportController::class, 'exportPdf'])->name('payments.export.pdf');
        Route::get('/announcement/export/excel', [AnnouncementExportController::class, 'exportExcel'])->name('announcements.export.excel');
        Route::get('/announcement/export/pdf', [AnnouncementExportController::class, 'exportPdf'])->name('announcements.export.pdf');
    });

Route::middleware(['auth', 'role:siswa', 'preventBackHistory'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/registration/personal-data', [RegistrationPageController::class, 'personalData'])->name('personalData');
        Route::post('/registration/personal-data', [RegistrationPageController::class, 'personalDataStore'])->name('personalDataStore');
        Route::get('/registration/parent-data', [RegistrationPageController::class, 'parentData'])->name('parentData');
        Route::post('/registration/parent-data', [RegistrationPageController::class, 'parentDataStore'])->name('parentDataStore');

        Route::get('/registration/upload-file', [RegistrationPageController::class, 'uploadFile'])->name('uploadFile');
        Route::post('/registration/upload-file', [RegistrationPageController::class, 'uploadFileStore'])->name('uploadFileStore');
        Route::delete('/registration/upload-file/{id}', [RegistrationPageController::class, 'deleteFile'])->name('deleteFile');
        Route::post('/registration/upload-file/edit-mode', function () {
            session(['mode' => 'edit']);
            return redirect()->route('student.uploadFile');
        })->name('upload-file.edit-mode');
        Route::post('/registration/upload-file/save-final', function () {
            session()->forget('mode');
            return back()->with('success', 'Perubahan berhasil disimpan');
        })->name('uploadFileStoreFinal');

        Route::get('/administration', [AdministrationPageController::class, 'index'])->name('administrationPage');
        Route::get('/tagihan', [AdministrationPageController::class, 'showTagihan'])->name('tagihan');
        Route::post('/createformulir', [AdministrationPageController::class, 'buatFormulir'])->name('formulir.buat');
        // Route::post('/administration/bayar', [AdministrationPageController::class, 'createTransaction'])->name('administration.pay');
        Route::get('/check-payment-status', [AdministrationPageController::class, 'checkPaymentStatus'])->name('check.payment.status');
        Route::get('/check-status-pembayaran', [AdministrationPageController::class, 'cekPembayaran'])->name('cek.pembayaran.status');

        Route::get('/announcement', [AnnouncementPageController::class, 'index'])->name('announcementPage');
        Route::get('/announcement/download-letter', [StudentLetterController::class, 'download'])->name('download.letter');
        Route::post('/announcement/download-letter', [StudentLetterController::class, 'download'])->name('download.letter.post');
    });

Route::fallback(function () {
    return redirect('/login')->with('error', 'Halaman tidak ditemukan, silahkan login terlebih dahulu');
});

Route::get('/test-email', function () {
    try {
        Mail::raw('Ini adalah email test SMTP dari Laravel ke Gmail.', function ($message) {
            $message->to('test-q4r0dgjiv@srv1.mail-tester.com')->subject('Test Email ke Gmail dari Laravel');
        });
        return 'Email berhasil dikirim ke Gmail!';
    } catch (\Exception $e) {
        return 'Gagal kirim email: ' . $e->getMessage();
    }
});

Route::get('/test-edupay', function (EdupayApiService $edupay) {
    $result = $edupay->testConnection();
    dd($result); // dump isi respon dari API
});

Route::get('/debug-edupay/{id}', function ($id) {
    $response = Http::post('url_edupay', [
        'idtagihan' => $id,
        'secretkey' => config('services.edupay.secretkey'),
    ]);
    if ($response->successful()) {
        $data = $response->json();
        \Log::info('Debug Edupay Response: ', $data);
        return $data;
    } else {
        return [
            'status' => $response->status(),
            'body' => $response->body(),
        ];
    }
});
