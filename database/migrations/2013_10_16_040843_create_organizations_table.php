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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            // fields
            $table->string('organization', 100);
            $table->enum('type', ['Student Organization', 'Faculty Association', 'Non Faculty Association', 'National Organization']);
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
        Schema::dropIfExists('organizations');
    }
};
