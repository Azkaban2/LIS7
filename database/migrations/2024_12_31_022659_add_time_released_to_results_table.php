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
        Schema::table('results', function (Blueprint $table) {
            // Add the `time_released` column if it doesn't exist
            if (!Schema::hasColumn('results', 'time_released')) {
                $table->time('time_released')->nullable()->after('date_released');
            } else {
                // Modify the column only if it already exists
                $table->time('time_released')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn('time_released');
        });
    }
};