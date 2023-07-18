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
        Schema::create('campus_entities', function (Blueprint $table) {
            $table->string('user_id', 20)->primary();

            // fields
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('middlename', 50);
            $table->enum('type', ['student', 'employee']);
            $table->enum('sex', ['male', 'female']);
            $table->unsignedBigInteger('campus_id')->nullable(); // FK
            $table->string('department_code', 20); // FK
            $table->timestamps();

            // references
            $table->foreign('campus_id')->references('id')->on('campuses');
            $table->foreign('department_code')->references('department_code')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campus_entities');
    }
};
