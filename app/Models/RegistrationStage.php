<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\StageNameEnum;

class RegistrationStage extends Model
{
    protected $table = 'registration_stages';
    protected $fillable = ['stage_name', 'start_date', 'end_date', 'is_active'];
    protected $casts = ['stage_name' => StageNameEnum::class, 'start_date' => 'date', 'end_date' => 'date', 'is_active' => 'boolean'];
}
