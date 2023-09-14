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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // defaults
            $table->enum('status', ['Pending', 'Active', 'Inactive'])->default('Pending');
            $table->integer('register')->default(0);
            $table->integer('hearts')->default(0);

            // fields
            $table->string('title');
            $table->text('description');
            $table->string('cover_photo')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedBigInteger('creator_id'); // FK
            $table->string('campus', 100); // FK
            $table->unsignedBigInteger('venue_id')->nullable(); // FK
            $table->string('event_type', 100); // FK
            $table->timestamps();
            
            // references
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('campus')->references('campus')->on('campuses');
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->foreign('event_type')->references('event_type')->on('event_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
