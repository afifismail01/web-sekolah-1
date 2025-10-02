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
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('alamat', 'address');
            $table->renameColumn('tgl_lahir', 'birth_date');
            $table->renameColumn('tempat_lahir', 'birth_place');
            $table->renameColumn('jenis_kelamin', 'gender');
            $table->renameColumn('nik', 'national_id_number');
            $table->renameColumn('jalur_pendaftaran', 'registration_track');
            $table->renameColumn('jenjang_pendaftaran', 'education_level');
            $table->renameColumn('prestasi', 'achievement');
            $table->renameColumn('kode_pos', 'postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('address', 'alamat');
            $table->renameColumn('birth_date', 'tgl_lahir');
            $table->renameColumn('birth_place', 'tempat_lahir');
            $table->renameColumn('gender', 'jenis_kelamin');
            $table->renameColumn('national_id_number', 'nik');
            $table->renameColumn('registration_track', 'jalur_pendaftaran');
            $table->renameColumn('education_level', 'jenjang_pendaftaran');
            $table->renameColumn('achievement', 'prestasi');
            $table->renameColumn('postal_code', 'kode_pos');
        });
    }
};
