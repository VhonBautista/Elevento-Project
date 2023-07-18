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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // fields
            $table->string('user_id', 20); // FK
            $table->string('profile_picture')->nullable();
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['attendee', 'organizer', 'admin', 'co_admin'])->default('attendee');
            $table->unsignedBigInteger('organization_id')->nullable(); // FK
            $table->rememberToken();
            $table->timestamps();
            
            // references
            $table->foreign('user_id')->references('user_id')->on('campus_entities');
            $table->foreign('organization_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
