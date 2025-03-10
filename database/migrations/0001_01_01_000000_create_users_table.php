<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name')->nullable(); // Optional user name
            $table->string('email')->unique(); // Unique email address
            $table->string('licensed_number')->unique(); // Unique license number for medical users
            $table->string('usertype')->default('user'); // User type (e.g., 'admin' or 'medtech')
            $table->string('password'); // Hashed password
            $table->timestamps(); // Created and updated timestamps
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Primary key
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Foreign key to users
            $table->string('ip_address', 45)->nullable(); // IP address
            $table->text('user_agent')->nullable(); // User agent information
            $table->longText('payload'); // Session data
            $table->integer('last_activity')->index(); // Last activity timestamp
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions'); // Drop sessions table
        Schema::dropIfExists('users'); // Drop users table
    }
};
