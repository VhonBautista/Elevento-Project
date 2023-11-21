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
        Schema::create('event_people', function (Blueprint $table) {
            $table->id();
            
            // fields
            $table->unsignedBigInteger('event_id'); // FK
            $table->string('name');
            $table->string('title');
            $table->enum('role', ['mc', 'speaker', 'guest', 'technical', 'logistics', 'sponsor']);
            $table->timestamps();

            // references
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_people');
    }
};
