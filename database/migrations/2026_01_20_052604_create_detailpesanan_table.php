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
        Schema::create('detailpesanan', function (Blueprint $table) {
            $table->id('idDetail');
            $table->unsignedBigInteger('idPesanan');
            $table->unsignedBigInteger('idProduk');
            $table->integer('jumlah');
            $table->float('sub_total');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('idPesanan')->references('idPesanan')->on('pesanan')
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
        Schema::dropIfExists('detailpesanan');
    }
};
