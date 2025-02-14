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
            // Menambahkan kolom shift 1, 2, dan 3
            $table->integer('shift1_target_qty')->nullable();
            $table->decimal('shift1_waktu', 5, 2)->nullable();
            $table->integer('shift2_target_qty')->nullable();
            $table->decimal('shift2_waktu', 5, 2)->nullable();
            $table->integer('shift3_target_qty')->nullable();
            $table->decimal('shift3_waktu', 5, 2)->nullable();
            
            // Menambahkan kolom cycle_time dan cavity
            $table->decimal('cycle_time', 8, 2)->nullable(); // Cycle time dengan 2 angka desimal
            $table->integer('cavity')->nullable(); // Cavity dengan tipe integer
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Menghapus kolom yang baru ditambahkan
            $table->dropColumn([
                'shift1_target_qty',
                'shift1_waktu',
                'shift2_target_qty',
                'shift2_waktu',
                'shift3_target_qty',
                'shift3_waktu',
                'cycle_time',
                'cavity',
            ]);
        });
    }
};
