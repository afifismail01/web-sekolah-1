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
            $table->renameColumn('nama_ayah', 'father_name');
            $table->renameColumn('nama_ibu', 'mother_name');
            $table->renameColumn('nama_wali', 'guardian_name');
            $table->renameColumn('pekerjaan_ayah', 'father_job');
            $table->renameColumn('pekerjaan_ibu', 'mother_job');
            $table->renameColumn('pekerjaan_wali', 'guardian_job');
            $table->renameColumn('telp_ayah', 'father_phone');
            $table->renameColumn('telp_ibu', 'mother_phone');
            $table->renameColumn('telp_wali', 'guardian_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->renameColumn('father_name', 'nama_ayah');
            $table->renameColumn('mother_name', 'nama_ibu');
            $table->renameColumn('guardian_name', 'nama_wali');
            $table->renameColumn('father_job', 'pekerjaan_ayah');
            $table->renameColumn('mother_job', 'pekerjaan_ibu');
            $table->renameColumn('guardian_job', 'pekerjaan_wali');
            $table->renameColumn('father_phone', 'telp_ayah');
            $table->renameColumn('mother_phone', 'telp_ibu');
            $table->renameColumn('guardian_phone', 'telp_wali');
        });
    }
};
