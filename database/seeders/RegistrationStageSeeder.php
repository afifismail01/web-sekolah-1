<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RegistrationStage;
use App\Enums\StageNameEnum;

class RegistrationStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RegistrationStage::create([
            'stage_name' => StageNameEnum::REGISTRATION,
            'start_date' => now(),
            'end_date' => now()->addDays(10),
            'is_active' => true,
        ]);
    }
}
