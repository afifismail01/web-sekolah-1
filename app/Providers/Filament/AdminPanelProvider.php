<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use App\Filament\Pages\DashboardAdmin;
use App\Filament\Pages\EditStage;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Facades\Filament;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        Filament::serving(function () {
            // if (request()->is('admin*') && auth()->check() && auth()->user()->role !== 'admin') {
            //     Auth::logout();
            //     abort(403, 'Anda tidak memiliki akses ke halaman ini');
            // }

            logger('🟡 Masuk ke Filament::serving');

            $user = auth()->user();
            logger('👤 User dari Auth:', [$user]);
            logger('📦 All session:', [session()->all()]);

            // Jangan blok halaman login
            if (request()->routeIs('filament.admin.auth.login')) {
                logger('🔁 Halaman login, skip verifikasi role');
                return;
            }

            // Jika belum login, biarkan Filament handle redirect
            if (!$user) {
                logger('🔒 Belum login, biarkan Filament handle');
                return;
            }

            // Jika login tapi bukan admin
            if ($user->role !== 'admin') {
                logger('⛔ Role bukan admin. Role saat ini: ' . $user->role);
                Auth::logout();

                // Optional: hapus semua session
                Session::invalidate();
                Session::regenerateToken();
                abort(403, 'Anda tidak memiliki akses ke halaman ini');
            }

            logger('✅ Akses admin diperbolehkan');

            // Paksa cookie session agar disegarkan
            Cookie::queue(Cookie::make(config('session.cookie', 'laravel_session'), Session::getId(), config('session.lifetime', 120), config('session.path', '/'), config('session.domain', '.miftahunnajah.sch.id'), config('session.secure', false), config('session.http_only', true), false, config('session.same_site', 'lax')));
        });
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->homeUrl(fn() => route('filament.admin.pages.dashboard-admin'))
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([DashboardAdmin::class, EditStage::class])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([Widgets\AccountWidget::class, Widgets\FilamentInfoWidget::class])
            ->middleware([EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class, AuthenticateSession::class, ShareErrorsFromSession::class, VerifyCsrfToken::class, SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class]);
        // ->authMiddleware([Authenticate::class]);
    }
}
