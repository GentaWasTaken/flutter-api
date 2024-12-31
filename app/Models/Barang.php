<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'Nama_Produk',
        'Nama_Kategori',
        'Harga',
        'Stok',
        'Berat',
        'Deskripsi_Lengkap',
        'Foto_1',
        'Foto_2',
        'Foto_3',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'Nama_Kategori');
    }
}
