<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SyncPayments extends Command
{
    protected $signature = 'app:sync-payments';
    protected $description = 'Command description';

    public function handle()
    {
        \Log::info('SyncPayment cron job dijalankan pada: ' . now());
        $pendingPayments = Payment::where('status', 'pending')->get();

        foreach ($pendingPayments as $payment) {
            $response = Http::post('https://api-psb.miftahunnajah.my.id/cekformulir', [
                'idtagihan' => $payment->nopendaftaran,
                'secretkey' => config('services.edupay.secret_key'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['lunas']) && $data['lunas'] === true) {
                    $payment->update([
                        'status' => 'paid',
                        'update_at' => now(),
                    ]);
                    $this->info("✅ Pembayaran {$payment->nopendaftaran} berhasil diupdate ke LUNAS");
                } else {
                    $this->line("ℹ️ Pembayaran {$payment->nopendaftaran} masih pending");
                }
            } else {
                $this->error("❌ Gagal mengambil data untuk {$payment->nopendaftaran}");
            }
        }
        return Command::SUCCESS;
    }
}
