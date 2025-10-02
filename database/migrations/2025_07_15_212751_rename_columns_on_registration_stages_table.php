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
        Schema::table('registration_stages', function (Blueprint $table) {
            $table->renameColumn('nama_tahapan', 'stage_name');
            $table->renameColumn('tanggal_mulai', 'start_date');
            $table->renameColumn('tanggal_selesai', 'end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_stages', function (Blueprint $table) {
            $table->renameColumn('stage_name', 'nama_tahapan');
            $table->renameColumn('start_date', 'tanggal_mulai');
            $table->renameColumn('end_date', 'tanggal_selesai');
        });
    }
};
