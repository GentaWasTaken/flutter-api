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
        Schema::create('barangs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('Nama_Produk');
            $table->uuid('Nama_Kategori');
            $table->decimal('Harga', 15, 2);
            $table->integer('Stok');
            $table->decimal('Berat', 8, 2);
            $table->text('Deskripsi_Lengkap');
            $table->string('Foto_1')->nullable();
            $table->string('Foto_2')->nullable();
            $table->string('Foto_3')->nullable();
            $table->timestamps();

            $table->foreign('Nama_Kategori')->references('id')->on('kategoris')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
