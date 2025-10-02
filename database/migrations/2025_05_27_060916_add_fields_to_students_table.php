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
            $table->string('jenjang_pendaftaran')->nullable()->after('jalur_pendaftaran');
            $table->string('tempat_lahir')->nullable()->after('tgl_lahir');
            $table->string('jenis_kelamin')->nullable()->after('tempat_lahir');
            $table->string('nik')->nullable()->after('jenis_kelamin');
            $table->string('status')->nullable()->after('nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['jenjang_pendaftaran', 'tempat_lahir', 'jenis_kelamin', 'nik', 'status']);
        });
    }
};
