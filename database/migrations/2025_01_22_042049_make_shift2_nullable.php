<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('shift2')->nullable()->change();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('shift2')->nullable(false)->change();
        });
    }
};
