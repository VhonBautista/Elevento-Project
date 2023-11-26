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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            
            // fields
            $table->unsignedBigInteger('user_id'); // FK
            $table->unsignedBigInteger('event_id'); // FK
            $table->string('qr_code');
            $table->enum('status', ['registered', 'attended', 'absent'])->default('registered');
            $table->dateTime('time_in')->nullable();
            $table->timestamps();

            // references
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
