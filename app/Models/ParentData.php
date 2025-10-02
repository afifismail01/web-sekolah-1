<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentData extends Model
{
    protected $table = 'parents';

    protected $casts = [
        'father_life_status' => \App\Enums\LifeStatusEnum::class,
        'father_citizenship' => \App\Enums\CitizenshipEnum::class,
        'father_income' => \App\Enums\IncomeRangeEnum::class,
        'father_birth_date' => 'date',

        'mother_life_status' => \App\Enums\LifeStatusEnum::class,
        'mother_citizenship' => \App\Enums\CitizenshipEnum::class,
        'mother_income' => \App\Enums\IncomeRangeEnum::class,
        'mother_birth_date' => 'date',

        'guardian_citizenship' => \App\Enums\CitizenshipEnum::class,
        'guardian_income' => \App\Enums\IncomeRangeEnum::class,
        'guardian_birth_date' => 'date',

        'house_ownership' => \App\Enums\HouseOwnershipEnum::class,
    ];

    protected $fillable = ['user_id', 'father_name', 'mother_name', 'guardian_name', 'father_main_job', 'mother_main_job', 'guardian_main_job', 'father_life_status', 'mother_life_status', 'father_citizenship', 'mother_citizenship', 'guardian_citizenship', 'father_national_id_number', 'mother_national_id_number', 'guardian_national_id_number', 'father_birth_place', 'mother_birth_place', 'guardian_birth_place', 'father_birth_date', 'mother_birth_date', 'guardian_birth_date', 'father_last_education', 'mother_last_education', 'guardian_last_education', 'father_income', 'mother_income', 'guardian_income', 'father_phone', 'mother_phone', 'guardian_phone', 'address', 'house_ownership', 'rt', 'rw', 'village', 'district', 'regency', 'province', 'postal_code'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
