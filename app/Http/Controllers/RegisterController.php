<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\WhatsappService;

class RegisterController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request, WhatsappService $whatsapp)
    {
        \Log::info('Registrasi dimulai');
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com)$/i'],
                'whatsapp' => ['required', 'string', 'unique:users,whatsapp', 'regex:/^62[0-9]{9,13}$/'],
            ],
            [
                'whatsapp.regex' => 'Nomor WhatsApp harus diawali dengan 62 dan terdiri dari 10-15 digit total.',
                'email.regex' => 'Email harus menggunakan domain gmail.com atau yahoo.com.',
            ],
        );

        // Generate a random password
        $password = Str::random(8);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'whatsapp' => $validated['whatsapp'],
            'password' => Hash::make($password),
        ]);

        // Send the password to the user via WhatsApp
        $message = "Halo {$user->name}, berikut adalah email dan password untuk login ke sistem pendaftaran,, email anda:{$user->email}, password anda: {$password}";
        $response = $this->whatsappService->send($user->whatsapp, $message);

        //debug response
        logger($response);
        return redirect()->route('login')->with('success', 'Pendaftaran Berhasil!, Password dikirim via WhatsApp');
    }
}
