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
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('gross_amount', 'nominal')->nullable();
            $table->renameColumn('order_id', 'nopendaftaran')->nullable();
            $table->string('waktuakhir')->nullable()->after('paid_at');
            $table->string('kodeta')->nullable()->after('waktuakhir');
            $table->string('kodekelas')->nullable()->after('kodeta');
            $table->string('kodejalur')->nullable()->after('kodekelas');
            $table->dropColumn(['invoice_url', 'invoice_id', 'total_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('nominal', 'gross_amount')->nullable();
            $table->dropColumn(['waktuakhir', 'kodeta', 'kodekelas', 'kodejalur']);
            $table->renameColumn('nopendaftaran', 'order_id')->nullable();
            $table->string('invoice_url')->nullable()->after('order_id');
            $table->string('invoice_id')->nullable()->after('invoice_url');
            $table->string('total_amount')->nullable();
        });
    }
};
