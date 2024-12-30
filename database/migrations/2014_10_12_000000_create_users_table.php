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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('no_telp')->nullable();;
            $table->string('email')->unique();
            $table->date('tanggal_Lahir')->nullable();;
            $table->string('alamat_Lengkap', 150)->nullable();;
            $table->string('provinsi', 35)->nullable();;
            $table->string('kota', 35)->nullable();;
            $table->string('rt_rw', 10)->nullable();;
            $table->string('Kel_desa')->nullable();;
            $table->string('kecamatan')->nullable();;
            $table->string('kode_Pos', 10)->nullable();;
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 10)->default('user');
            $table->string('image')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
