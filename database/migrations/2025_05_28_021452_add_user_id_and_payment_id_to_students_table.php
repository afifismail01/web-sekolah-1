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
            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('id');
            // $table->foreignId('payment_id')->nullable()->constrained()->onDelete('cascade')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['payment_id']);
            $table->dropColumn(['user_id', 'payment_id']);
        });
    }
};
