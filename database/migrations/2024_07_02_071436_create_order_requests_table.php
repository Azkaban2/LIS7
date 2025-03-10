<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('patient_name');
            $table->string('patient_id')->nullable();
            $table->date('birthday')->nullable();  // Mark nullable if data may be missing
            $table->integer('age')->default(0);  // Add a default value to avoid insert failure
            $table->string('gender')->nullable();
            $table->date('date_performed')->nullable();
            $table->date('date_released')->nullable();
            $table->json('programs')->nullable();
            $table->json('order')->nullable();
            $table->string('sample_type')->nullable();
            $table->string('sample_container')->nullable();
            $table->date('collection_date')->nullable();
            $table->string('test_code')->nullable();
            $table->string('pathologist_full_name')->nullable();
            $table->string('pathologist_lic_no')->nullable();
            $table->string('physician_full_name')->nullable();
            $table->string('medtech_full_name');
            $table->string('medtech_lic_no');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_requests');
    }
};

