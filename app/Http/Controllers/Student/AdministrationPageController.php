<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\RegistrationStage;
use App\Enums\StageNameEnum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
// use Xendit\Configuration;
// use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Facades\Auth;
use App\Services\EdupayApiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AdministrationPageController extends Controller
{
    protected $edupayApiService;

    public function __construct(EdupayApiService $edupayApiService)
    {
        $this->edupayApiService = $edupayApiService;
    }
    public function index()
    {
        $user = auth()->user();

        $activeStage = RegistrationStage::where('is_active', true)->first();
        $disabledForm = $activeStage?->stage_name !== StageNameEnum::REGISTRATION;
        // Ambil status pembayaran dari relasi pembayaran()
        $status = optional($user->payment)->status;
        // $hasValidPayment = $user->payment && $user->payment->nopendaftaran;

        if ($status === 'pending' && request()->route()->getName() !== 'student.tagihan') {
            return redirect()->route('student.tagihan');
        }
        return view('student.administration_page', compact('status', 'activeStage', 'disabledForm'));
    }
    private function generateNoPendaftaran($kodeJalur)
    {
        $year = date('y');
        $count = DB::table('payments')->whereYear('created_at', date('Y'))->where('kodejalur', $kodeJalur)->count();
        $urutan = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        return $kodeJalur . $year . $urutan;
    }
    public function buatFormulir(Request $request)
    {
        $user = auth()->user();
        // Cek apakah sudah ada payment yang aktif dan belum expired
        $existingPayment = $user->payment;

        if ($existingPayment && $existingPayment->status === 'pending' && $existingPayment->waktuakhir > now()) {
            return redirect()->route('student.tagihan');
        }
        $request->validate([
            'kodejalur' => 'required|in:1,2,3,4,5',
            'kodekelas' => 'required|string',
        ]);

        $kodeJalur = $request->input('kodejalur'); //from button or select input
        $kodeKelas = $request->input('kodekelas');
        $noPendaftaran = $this->generateNoPendaftaran($kodeJalur);

        // Generate no_pendaftaran hanya kalau belum ada atau sebelumnya expired
        $noPendaftaran = $user->payment?->nopendaftaran;
        if (!$noPendaftaran || ($existingPayment && \Carbon\Carbon::parse($existingPayment->waktuakhir)->isPast())) {
            $noPendaftaran = $this->generateNoPendaftaran($kodeJalur);
        }

        //Hitung nominal
        $kodeJalur = (int) $request->input('kodejalur');

        $nominal = match ($kodeJalur) {
            1 => 250000, //Reguler
            2 => 250000, //Prestasi
            3 => 150000, //Yatim Dhuafa
            4 => 0, //Alumni
            5 => 50000, //PAUD
            default => 0,
        };

        $payment = Payment::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'nopendaftaran' => $noPendaftaran,
                'jeniskelamin' => $user->jeniskelamin ?? 'L',
                'kodejalur' => $kodeJalur,
                'kodekelas' => $kodeKelas,
                'kodeta' => '2026',
                'nominal' => $nominal,
                'status' => 'pending',
                'created_at' => now(),
                'update_at' => now()->addDays(1),
            ],
        );
        logger()->info('Formulir Create Request:', $request->all());
        // Kirim ke API
        $response = $this->edupayApiService->createFormulir([
            'nopendaftaran' => $noPendaftaran,
            'nama' => $user->name,
            'jeniskelamin' => $user->jeniskelamin ?? 'L',
            'nowa' => $user->whatsapp,
            'kodekelas' => $kodeKelas,
            'kodeta' => '2026',
            'nominal' => $nominal,
            'secretkey' => config('services.edupay.secretkey'),
            'waktuakhir' => now()->addDays(1)->format('Y-m-d H:i:s'),
        ]);
        return redirect()->route('student.tagihan');
    }

    public function checkPaymentStatus(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->payment) {
            return response()->json(
                [
                    'status' => null,
                    'message' => 'Belum ada pembayaran',
                ],
                200,
            );
        }
        return response()->json(
            [
                'status' => $user->payment->status,
            ],
            200,
        );
    }

    public function cekPembayaran()
    {
        $user = auth()->user();

        if (!$user || !$user->payment) {
            return response()->json(
                [
                    'status' => null,
                    'message' => 'Belum ada pembayaran',
                ],
                200,
            );
        }

        $payment = $user->payment;

        try {
            // Hit ke API EduPay
            /*$response = Http::asForm()->post('https://api-psb.miftahunnajah.my.id/cekformulir', [
                'idtagihan' => $payment->nopendaftaran, // sesuai mapping Anda
                'secretkey' => config('services.edupay.secretkey'),
            ]);

            if ($response->failed()) {
                return response()->json(
                    [
                        'status' => null,
                        'message' => 'Gagal menghubungi server EduPay',
                    ],
                    500,
                );
            }

            $result = $response->json();

            // Update payment ke DB
            $payment->update([
                'status' => $result['lunas'] ? 'paid' : 'pending',
                'paid_at' => $result['lunas'] ? $result['tglbayar'] : null,
                'waktuakhir' => $result['waktuakhir'] ?? $payment->waktuakhir,
            ]);

            return response()->json(
                [
                    'status' => $payment->status,
                    'paid_at' => $payment->paid_at,
                    'waktuakhir' => $payment->waktuakhir,
                    'message' => 'Status pembayaran berhasil diperbarui',
                ],
                200,
            );*/

            $response = Http::asForm()->post('https://api-psb.miftahunnajah.my.id/cekformulir', [
                'idtagihan' => $payment->nopendaftaran, // sesuai mapping Anda
                'secretkey' => config('services.edupay.secretkey'),
            ]);

            logger()->info('EduPay Request', [
                'idtagihan' => $payment->nopendaftaran,
                'secretkey' => config('services.edupay.secretkey'),
            ]);

            logger()->info('EduPay Response', $response->json());

            if ($response->failed()) {
                return response()->json(
                    [
                        'status' => null,
                        'message' => 'Gagal menghubungi server EduPay',
                    ],
                    500,
                );
            }

            $result = $response->json();

            // Default status
            $newStatus = 'pending';
            $paidAt = null;

            // Cek jika API balas "BILL NOT FOUND"
            if (isset($result['status']) && $result['status'] === 'BILL NOT FOUND') {
                $newStatus = 'pending';
            }

            // Cek apakah response valid
            if (!empty($result) && isset($result['lunas'])) {
                if ($result['lunas']) {
                    $newStatus = 'paid';
                    $paidAt = $result['tglbayar'] ?? now();
                } else {
                    $newStatus = 'pending'; // jika ada response tapi belum lunas
                }
            }

            // Update payment ke DB
            $payment->update([
                'status' => $newStatus,
                'paid_at' => $paidAt,
                'waktuakhir' => $result['waktuakhir'] ?? $payment->waktuakhir,
            ]);

            // arahkan user sesuai status
            if ($newStatus === 'pending' || $newStatus === null) {
                return redirect()->route('tagihan')->with('info', 'Status pembayaran masih pending, silakan lakukan pembayaran.');
            } elseif ($newStatus === 'paid') {
                return redirect()->route('administrationPage')->with('success', 'Pembayaran berhasil dikonfirmasi.');
            } else {
                return redirect()->route('tagihan')->with('error', 'Status pembayaran tidak valid atau gagal diproses.');
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => null,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function showTagihan()
    {
        \Log::info('== Masuk ke showTagihan ==');
        $user = auth()->user();
        $payment = $user->payment;

        if (!$payment || !$payment->nopendaftaran) {
            \Log::info('nomor pendaftaran tidak ditemukan');
            return view('student.tagihan', ['data' => []])->with('error', 'Tagihan belum tersedia.');
        }
        $idTagihan = $payment->nopendaftaran ?? null;

        if (!$idTagihan) {
            \Log::info('nomor pendaftaran tidak ditemukan');
            logger()->warning('ID tagihan tidak ditemukan. Redirect loop kemungkinan besar akan terjadi.');
            return view('student.tagihan')->with('error', 'ID tagihan tidak ditemukan.');
        }
        \Log::info('== ID pendaftaran berhasil ditemukan  ==');
        $response = Http::post('https://api-psb.miftahunnajah.my.id/cekformulir', [
            'idtagihan' => $idTagihan,
            'secretkey' => config('services.edupay.secretkey'),
        ]);
        \Log::info('== Sedang Menyambungkan ke API  ==');
        if ($response->successful()) {
            \Log::info('== API berhasil tersambung  ==');
            $data = $response->json();

            if (is_array($data) && array_key_exists('lunas', $data)) {
                if ($data['lunas'] === true && $payment->status !== 'paid') {
                    $payment->update([
                        'status' => 'paid',
                        'updated_at' => now(),
                    ]);
                }
            }

            // Fallback jika 'nopendaftaran' dari API tidak ada
            $data['nopendaftaran'] = $data['nopendaftaran'] ?? ($data['nopendaftaran'] ?? $user->payment->nopendaftaran);

            return view('student.tagihan', [
                'data' => $data,
            ]);
        }
        if (!$response->successful()) {
            \Log::error('API gagal: ', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
        \Log::info('== API gagal tersambung  ==');

        return view('student.administration_page', ['status' => $payment?->status ?? null, 'activeStage' => RegistrationStage::where('is_active', true)->first(), 'disabledForm' => true])->with('error', 'Gagal mengambil data tagihan.');
    }
}
