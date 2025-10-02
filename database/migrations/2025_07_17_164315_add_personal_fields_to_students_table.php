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
            $table->string('nisn')->nullable()->after('name');
            $table->string('citizenship')->nullable()->after('nisn');
            $table->integer('siblings_count')->nullable()->after('gender');
            $table->integer('child_number')->nullable()->after('siblings_count');
            $table->string('religion')->nullable()->after('child_number');
            $table->string('future_goal')->nullable()->after('religion');
            $table->string('phone_number')->nullable()->after('future_goal');
            $table->string('email')->nullable()->after('phone_number');
            $table->string('hobby')->nullable()->after('email');
            $table->string('previous_school')->nullable()->after('hobby');
            $table->string('previous_school_npsn')->nullable()->after('previous_school');
            $table->string('education_funding')->nullable()->after('previous_school_npsn');
            $table->string('special_needs')->nullable()->after('education_funding');
            $table->string('disability')->nullable()->after('special_needs');
            $table->string('kip_number')->nullable()->after('disability');
            $table->string('kip_year')->nullable()->after('kip_number');
            $table->string('family_card_number')->nullable()->after('kip_year');
            $table->string('family_head_name')->nullable()->after('family_card_number');
            $table->dropColumn('address');
            $table->dropColumn('postal_code');
            $table->dropColumn('achievement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('nisn');
            $table->dropColumn('citizenship');
            $table->dropColumn('siblings_count');
            $table->dropColumn('child_number');
            $table->dropColumn('religion');
            $table->dropColumn('future_goal');
            $table->dropColumn('phone_number');
            $table->dropColumn('email');
            $table->dropColumn('hobby');
            $table->dropColumn('previous_school');
            $table->dropColumn('previous_school_npsn');
            $table->dropColumn('education_funding');
            $table->dropColumn('special_needs');
            $table->dropColumn('disability');
            $table->dropColumn('kip_number');
            $table->dropColumn('kip_year');
            $table->dropColumn('family_card_number');
            $table->dropColumn('family_head_name');
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('achievement')->nullable();
        });
    }
};
