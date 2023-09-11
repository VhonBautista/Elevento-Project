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
        Schema::create('venues', function (Blueprint $table) {
            $table->id();

            // defaults
            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            // fields
            $table->string('venue_name');
            $table->string('image')->nullable();
            $table->string('campus', 100); // FK
            $table->timestamps();

            // references
            $table->foreign('campus')->references('campus')->on('campuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
