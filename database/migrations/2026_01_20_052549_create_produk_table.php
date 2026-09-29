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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('idProduk');
            $table->datetime('tanggal_upload');
            $table->string('nama_produk', 255);
            $table->text('deskripsi');
            $table->float('Harga');
            $table->string('foto', 255);
            $table->string('status', 30);
            $table->unsignedBigInteger('idKategori')->nullable();
            $table->unsignedBigInteger('idUser')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('idKategori')->references('idKategori')->on('kategori')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('idUser')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
