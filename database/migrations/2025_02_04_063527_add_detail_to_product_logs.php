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
        Schema::table('product_logs', function (Blueprint $table) {
            $table->text('detail')->nullable()->after('user');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('product_logs', function (Blueprint $table) {
            $table->dropColumn('detail');
        });
    }
};
