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
        Schema::table('stripe_invoice_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_schedule_id')->nullable()->after('created_by');
            $table->foreign('parent_schedule_id')->references('id')->on('stripe_invoice_schedules')->onDelete('cascade');
            $table->index('parent_schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stripe_invoice_schedules', function (Blueprint $table) {
            $table->dropForeign(['parent_schedule_id']);
            $table->dropIndex(['parent_schedule_id']);
            $table->dropColumn('parent_schedule_id');
        });
    }
};
