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
        Schema::create('flows', function (Blueprint $table) {
            $table->id();

            // fields
            $table->unsignedBigInteger('segment_id'); // FK
            $table->enum('timeline', ['Morning', 'Noon', 'Afternoon', 'Evening', 'Midnight']);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->text('list');
            $table->timestamps();

            // references
            $table->foreign('segment_id')->references('id')->on('segments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flows');
    }
};
