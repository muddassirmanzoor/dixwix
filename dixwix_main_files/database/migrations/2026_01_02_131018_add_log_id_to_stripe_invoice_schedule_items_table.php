<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stripe_invoice_schedule_items', function (Blueprint $table) {
            $table->unsignedBigInteger('log_id')->nullable()->after('schedule_id');
            $table->foreign('log_id')->references('id')->on('stripe_invoice_schedule_logs')->onDelete('set null');
            $table->index('log_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stripe_invoice_schedule_items', function (Blueprint $table) {
            $table->dropForeign(['log_id']);
            $table->dropIndex(['log_id']);
            $table->dropColumn('log_id');
        });
    }
};
