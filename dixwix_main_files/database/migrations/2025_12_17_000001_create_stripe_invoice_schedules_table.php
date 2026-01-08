<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stripe_invoice_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamp('run_at');
            $table->timestamp('range_from');
            $table->timestamp('range_to');

            $table->string('status')->default('pending'); // pending|running|completed|failed|cancelled
            $table->string('stripe_behavior')->default('finalize_and_send');

            $table->json('result_summary')->nullable();
            $table->text('error')->nullable();

            $table->timestamps();

            $table->index(['status', 'run_at']);
            $table->index(['created_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_invoice_schedules');
    }
};



