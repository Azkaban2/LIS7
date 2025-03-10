<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // Description of the activity
            $table->unsignedBigInteger('user_id')->nullable(); // User performing the action
            $table->timestamps(); // Created at and updated at timestamps
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Foreign key linking to users table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
