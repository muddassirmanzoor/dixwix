<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stripe_invoice_schedule_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('user_id');

            $table->decimal('subtotal_amount', 10, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_invoice_id')->nullable();
            $table->string('status')->default('pending'); // pending|processing|completed|failed|skipped
            $table->text('error')->nullable();

            $table->timestamps();

            $table->unique(['schedule_id', 'user_id']);
            $table->index(['status']);

            // FKs (no cascade delete on users)
            $table->foreign('schedule_id')->references('id')->on('stripe_invoice_schedules')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_invoice_schedule_items');
    }
};


