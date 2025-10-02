<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn(['father_life_status', 'mother_life_status', 'father_citizenship', 'mother_citizenship', 'guardian_citizenship', 'father_national_id_number', 'mother_national_id_number', 'guardian_national_id_number', 'father_birth_place', 'mother_birth_place', 'guardian_birth_place', 'father_birth_date', 'mother_birth_date', 'guardian_birth_date', 'father_last_education', 'mother_last_education', 'guardian_last_education', 'father_income', 'mother_income', 'guardian_income']);
            $table->renameColumn('father_main_job', 'father_job');
            $table->renameColumn('mother_main_job', 'mother_job');
            $table->renameColumn('guardian_main_job', 'guardian_job');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            // Optional: bisa diisi ulang jika suatu saat ingin restore field-nya lagi
        });
    }
};
