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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('cycle_time', 8, 2)->nullable();  // Sesuaikan tipe data
            $table->integer('cavity')->nullable();  // Sesuaikan tipe data
        });
    }
    
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['cycle_time', 'cavity']);
        });
    }
    
};
