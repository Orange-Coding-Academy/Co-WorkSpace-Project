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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending'); // pending, confirmed, cancelled, completed, expired
            $table->timestamp('payment_expires_at')->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable();

            $table->decimal('platform_commission_amount', 10, 3)->nullable();

            $table->decimal('workspace_net_amount', 10, 3)->nullable();
            $table->timestamps();
            $table->index(['room_id', 'start_date_time', 'end_date_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};