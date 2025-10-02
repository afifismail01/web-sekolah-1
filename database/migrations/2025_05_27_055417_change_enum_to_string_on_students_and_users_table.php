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
            // mengubah enum ke string
            $table->string('jalur_pendaftaran')->change();
        });
        Schema::table('users', function (Blueprint $table) {
            // mengubah enum ke string
            $table->string('role')->default('siswa')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->enum('jalur_pendaftaran', ['M', 'D', 'P', 'K', 'PD'])->change();
        });
        Schema::table('users', function (Blueprint $table) {
            $table
                ->enum('role', ['admin', 'siswa'])
                ->default('siswa')
                ->change();
        });
    }
};
