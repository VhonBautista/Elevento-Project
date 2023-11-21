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
        Schema::create('planning_logs', function (Blueprint $table) {
            $table->id();

            // fields
            $table->unsignedBigInteger('event_id'); // FK
            $table->unsignedBigInteger('user_id'); // FK
            $table->enum('status', ['created', 'updated', 'deleted', 'activated', 'rescheduled']);
            $table->text('description');
            $table->timestamps();

            // references
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planning_logs');
    }
};
