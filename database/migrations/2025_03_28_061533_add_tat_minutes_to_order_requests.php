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
        Schema::table('order_requests', function (Blueprint $table) {
            $table->integer('tat_minutes')->nullable()->after('date_released'); 
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('order_requests', function (Blueprint $table) {
            if (Schema::hasColumn('order_requests', 'tat_minutes')) {
                $table->dropColumn('tat_minutes');
            }
        });
    }    
    
};
