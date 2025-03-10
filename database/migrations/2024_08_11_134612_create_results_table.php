<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('order_request_id')->constrained('order_requests')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->json('results'); // JSON field for test results
            $table->string('pdf_file')->default('No PDF'); // Default value for pdf_file
            $table->date('date_released')->nullable(); // Date the result was released
            $table->time('time_released')->default(DB::raw('CURRENT_TIME'))->change(); // Time the result was released
            $table->timestamps(); // Created and updated timestamps
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
