<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stripe_invoice_schedules', function (Blueprint $table) {
            // Make range_from and range_to nullable since they're calculated dynamically for recurring schedules
            $table->timestamp('range_from')->nullable()->change();
            $table->timestamp('range_to')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('stripe_invoice_schedules', function (Blueprint $table) {
            $table->timestamp('range_from')->nullable(false)->change();
            $table->timestamp('range_to')->nullable(false)->change();
        });
    }
};


