<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stripe_invoice_schedules', function (Blueprint $table) {
            // Add recurring days column
            $table->integer('recurring_days')->nullable()->after('created_by');
            
            // Add next_run_at and last_run_at for recurring schedules
            $table->timestamp('next_run_at')->nullable()->after('run_at');
            $table->timestamp('last_run_at')->nullable()->after('next_run_at');
            
            // Keep range_from/range_to for now (will be calculated dynamically, but store for history)
            // Add is_active flag for recurring schedules
            $table->boolean('is_active')->default(true)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('stripe_invoice_schedules', function (Blueprint $table) {
            $table->dropColumn(['recurring_days', 'next_run_at', 'last_run_at', 'is_active']);
        });
    }
};


