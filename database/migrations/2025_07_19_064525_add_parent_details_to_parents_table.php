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
            $table->string('father_life_status')->nullable();
            $table->string('mother_life_status')->nullable();
            $table->string('father_citizenship')->nullable();
            $table->string('mother_citizenship')->nullable();
            $table->string('guardian_citizenship')->nullable();
            $table->string('father_national_id_number')->nullable();
            $table->string('mother_national_id_number')->nullable();
            $table->string('guardian_national_id_number')->nullable();
            $table->string('father_birth_place')->nullable();
            $table->string('mother_birth_place')->nullable();
            $table->string('guardian_birth_place')->nullable();
            $table->date('father_birth_date')->nullable();
            $table->date('mother_birth_date')->nullable();
            $table->date('guardian_birth_date')->nullable();
            $table->string('father_last_education')->nullable();
            $table->string('mother_last_education')->nullable();
            $table->string('guardian_last_education')->nullable();
            $table->renameColumn('father_job', 'father_main_job');
            $table->renameColumn('mother_job', 'mother_main_job');
            $table->renameColumn('guardian_job', 'guardian_main_job');
            $table->string('father_income')->nullable();
            $table->string('mother_income')->nullable();
            $table->string('guardian_income')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn(['father_life_status', 'mother_life_status', 'father_citizenship', 'mother_citizenship', 'guardian_citizenship', 'father_national_id_number', 'mother_national_id_number', 'guardian_national_id_number', 'father_birth_place', 'mother_birth_place', 'guardian_birth_place', 'father_birth_date', 'mother_birth_date', 'guardian_birth_date', 'father_last_education', 'mother_last_education', 'guardian_last_education', 'father_income', 'mother_income', 'guardian_income']);
            $table->renameColumn('father_main_job', 'father_job');
            $table->renameColumn('mother_main_job', 'mother_job');
            $table->renameColumn('guardian_main_job', 'guardian_job');
        });
    }
};
