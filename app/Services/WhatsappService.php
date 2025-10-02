<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    protected string $baseUrl = 'https://api.fonnte.com/send';
    protected string $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /* Kirim pesan ke Whatsapp
     * @param string $target : Nomor tujuan (format: 62xxxxxxxxx)
     * @param string $message : Isi pesan whatsapp
     * @return array|null
     */

    public function send(string $target, string $message)
    {
        // dd($this->token);
        $response = Http::withHeaders([
            'authorization' => $this->token,
        ])
            ->asForm()
            ->post($this->baseUrl, [
                'target' => $target,
                'message' => $message,
            ]);

        // $response = Http::withToken($this->token)
        //     ->asForm()
        //     ->post($this->baseUrl, [
        //         'target' => $target,
        //         'message' => $message,
        //         // 'countryCode' => '62',
        //     ]);

        if ($response->successful()) {
            return $response->json();
        }

        // untuk debug error
        return [
            'status' => false,
            'error' => $response->body(),
        ];
    }
}
