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
        Schema::create('collection_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('company_id')->nullable()->constrained('users');
            $table->foreignId('vehicle_id')->nullable()->constrained();
            $table->date('date');
            $table->string('type'); // organico, inorganico, reciclable, peligroso
            $table->decimal('weight', 8, 2);
            $table->integer('points');
            $table->string('status')->default('pending'); // pending, accepted, completed, rejected
            $table->boolean('notified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_requests');
    }
};
