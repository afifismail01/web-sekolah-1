<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }
    public function sendResetPassword(Request $request)
    {
        $request->validate([
            'whatsapp' => 'required',
        ]);
        $user = User::where('whatsapp', $request->whatsapp)->first();
        if (!$user) {
            return back()->withErrors(['whatsapp' => 'Nomor telepon tidak ditemukan, silahkan lakukan registrasi ulang']);
        }

        // Buat password baru acak
        $newPassword = Str::random(8);

        // Update password user
        $user->password = Hash::make($newPassword);
        $user->save();

        // Kirim ke fonnte
        $message = "🔐 Reset Password\n\nEmail: {$user->email}\n Nomor Telepon: {$user->whatsapp}\nPassword Baru: {$newPassword}";
        $this->sendFonnteMessage($user->whatsapp, $message);
        return redirect()->route('login')->with('success', 'Password baru telah dikirim ke nomor telepon anda');
    }
    private function sendFonnteMessage($whatsapp, $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $whatsapp,
                'message' => $message,
            ],
            CURLOPT_HTTPHEADER => ['Authorization:' . env('FONNTE_TOKEN')],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
    }
}
