<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('points', function (Blueprint $table) {
            // Mark a point row as already invoiced by a schedule to prevent double charging on overlapping ranges
            if (!Schema::hasColumn('points', 'stripe_invoice_schedule_id')) {
                $table->unsignedBigInteger('stripe_invoice_schedule_id')->nullable()->after('description')->index();
            }
            if (!Schema::hasColumn('points', 'stripe_invoiced_at')) {
                $table->timestamp('stripe_invoiced_at')->nullable()->after('stripe_invoice_schedule_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('points', function (Blueprint $table) {
            if (Schema::hasColumn('points', 'stripe_invoiced_at')) {
                $table->dropColumn('stripe_invoiced_at');
            }
            if (Schema::hasColumn('points', 'stripe_invoice_schedule_id')) {
                $table->dropColumn('stripe_invoice_schedule_id');
            }
        });
    }
};


