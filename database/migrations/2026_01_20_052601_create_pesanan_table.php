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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('idPesanan');
            $table->unsignedBigInteger('idUser');
            $table->datetime('tanggal_pesanan');
            $table->string('alamat_pengiriman', 255);
            $table->string('metode_pembayaran', 30);
            $table->string('status_pesanan', 30);
            $table->float('total_harga');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('idUser')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
