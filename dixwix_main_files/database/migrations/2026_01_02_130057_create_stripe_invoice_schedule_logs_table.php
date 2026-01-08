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
        Schema::create('stripe_invoice_schedule_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id');
            $table->string('status')->default('running'); // running, completed, failed
            $table->datetime('run_at');
            $table->datetime('completed_at')->nullable();
            $table->integer('recurring_days')->nullable();
            $table->datetime('range_from')->nullable();
            $table->datetime('range_to')->nullable();
            $table->json('result_summary')->nullable(); // {processed_users, sent, skipped, failed}
            $table->text('error')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('schedule_id')->references('id')->on('stripe_invoice_schedules')->onDelete('cascade');
            $table->index('schedule_id');
            $table->index('run_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_invoice_schedule_logs');
    }
};
