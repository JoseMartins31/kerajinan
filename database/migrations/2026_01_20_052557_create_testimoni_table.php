<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id('idTestimoni');
            $table->unsignedBigInteger('idUser');
            $table->unsignedBigInteger('idProduk');
            $table->float('rating');
            $table->text('komentar');
            $table->datetime('tanggal');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('idUser')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('idProduk')->references('idProduk')->on('produk')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimoni');
    }
};
