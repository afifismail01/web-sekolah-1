<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }
        return view('auth.login'); //Show login form
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check by email and role
        $user = User::where('email', $credentials['email'])->where('role', 'siswa')->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('student.dashboard');
        }
        return back()
            ->withErrors([
                'email' => 'Email atau password salah',
            ])
            ->onlyInput('email');
    }
}
