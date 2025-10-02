<?php

namespace App\Filament\Pages;

use App\Models\RegistrationStage;
use Filament\Pages\Page;

class DashboardAdmin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard Admin';
    protected static string $view = 'filament.pages.dashboard-admin';

    public $activeStep;

    public function mount()
    {
        $this->activeStep = RegistrationStage::where('is_active', true)->first();
    }
}
