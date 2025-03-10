<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            // Add `pdf_file_path` without referencing `pathologist_lic_no`
            $table->string('pdf_file_path')->nullable()->after('pdf_file'); // Adjust placement as needed
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn('pdf_file_path');
        });
    }
};
