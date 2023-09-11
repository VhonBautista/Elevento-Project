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
        Schema::create('departments', function (Blueprint $table) {
            $table->string('department_code', 20)->primary();

            // fields
            $table->string('department', 100);
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
        Schema::dropIfExists('departments');
    }
};
