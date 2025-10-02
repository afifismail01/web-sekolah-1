<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /* Daftar URI yang harus dikecualikan dari verifikasi CSRF

    @var array<int,string>
    */

    protected $except = ['siswa/administrasi/notification', 'api/siswa/midtrans/webhook', 'api/midtrans/callback', 'api/siswa/midtrans/*', 'api/midtrans/*'];
}
