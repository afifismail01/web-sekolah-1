<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ParentData;

class Student extends Model
{
    protected $casts = [
        'admission_track' => \App\Enums\AdmissionTrackEnum::class,
        'gender' => \App\Enums\GenderEnum::class,
        'education_level' => \App\Enums\EducationLevelEnum::class,
        'birth_date' => 'date',
        'status' => \App\Enums\StudentStatusEnum::class,
        'citizenship' => \App\Enums\CitizenshipEnum::class,
        // 'disability' => \App\Enums\DisabilityEnum::class,
        'education_funding' => \App\Enums\EducationFundingEnum::class,
        // 'future_goal' => \App\Enums\FutureGoalEnum::class,
        // 'hobby' => \App\Enums\HobbyEnum::class,
        // 'special_needs' => \App\Enums\SpecialNeedsEnum::class,
    ];

    protected $fillable = ['name', 'birth_date', 'birth_place', 'gender', 'national_id_number', 'admission_track', 'education_level', 'status', 'nisn', 'siblings_count', 'child_number', 'religion', 'phone_number', 'email', 'previous_school', 'previous_school_npsn', 'kip_number', 'kip_year', 'family_card_number', 'family_head_name', 'citizenship', 'disability', 'education_funding', 'future_goal', 'hobby', 'special_needs', 'user_id', 'payment_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function UploadedFiles()
    {
        return $this->hasMany(UploadedFile::class);
    }
    public function parents()
    {
        return $this->hasOne(ParentData::class, 'user_id', 'user_id');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    protected static function booted()
    {
        static::deleting(function ($student) {
            $student->parents()->delete(); // akan menghapus parent yang punya user_id yang sama
        });
        static::deleting(function ($student) {
            $student->uploadedFiles()->delete(); //akan menghapus file berkas yang berelasi dengan user_id
        });
    }
}
