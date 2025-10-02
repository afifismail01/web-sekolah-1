<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Contoh: Jalankan command sync tiap 5 menit
        $schedule->command('app:sync-payments')->hourlyAt(15);
    }

    // protected function commands(): void
    // {
    //     $this->load(__DIR__ . '/Commands');
    // }
}
