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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            
            // defaults
            $table->enum('status', ['Pending', 'Active', 'Inactive'])->default('Pending');

            // fields
            $table->text('message')->nullable();
            $table->unsignedBigInteger('user_id'); // FK
            $table->unsignedBigInteger('organization_id'); // FK
            $table->timestamps();
            
            // references
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('organization_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
