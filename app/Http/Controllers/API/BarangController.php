<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class BarangController extends BaseController
{
    public function index()
    {
        $barangs = Barang::with('kategori')->get();
        if ($barangs->isEmpty()) {
            return $this->sendError('Tidak ada barang yang ditemukan.', [], Response::HTTP_NOT_FOUND);
        }

        return $this->sendSuccess($barangs, 'Daftar barang ditemukan.', Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama_Produk' => 'required|string|max:255',
            'Nama_Kategori' => 'required|exists:kategoris,id',
            'Harga' => 'required|numeric',
            'Stok' => 'required|integer',
            'Berat' => 'required|numeric',
            'Deskripsi_Lengkap' => 'required|string',
            'Foto_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Foto_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Foto_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $foto1Path = $request->file('Foto_1') ? $request->file('Foto_1')->store('public/barang_images') : null;
        $foto2Path = $request->file('Foto_2') ? $request->file('Foto_2')->store('public/barang_images') : null;
        $foto3Path = $request->file('Foto_3') ? $request->file('Foto_3')->store('public/barang_images') : null;


        $barang = Barang::create([
            'Nama_Produk' => $request->Nama_Produk,
            'Nama_Kategori' => $request->Nama_Kategori,
            'Harga' => $request->Harga,
            'Stok' => $request->Stok,
            'Berat' => $request->Berat,
            'Deskripsi_Lengkap' => $request->Deskripsi_Lengkap,
            'Foto_1' => $foto1Path,
            'Foto_2' => $foto2Path,
            'Foto_3' => $foto3Path,
        ]);

        return $this->sendSuccess($barang, 'Barang berhasil ditambahkan.', Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return $this->sendError('Barang tidak ditemukan.', [], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'Nama_Produk' => 'required|string|max:255',
            'Nama_Kategori' => 'required|exists:kategoris,id',
            'Harga' => 'required|numeric',
            'Stok' => 'required|integer',
            'Berat' => 'required|numeric',
            'Deskripsi_Lengkap' => 'required|string',
            'Foto_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Foto_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Foto_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->file('Foto_1')) {
            Storage::delete($barang->Foto_1);
            $foto1Path = $request->file('Foto_1')->store('public/barang_images');
        } else {
            $foto1Path = $barang->Foto_1;
        }

        if ($request->file('Foto_2')) {
            Storage::delete($barang->Foto_2);
            $foto2Path = $request->file('Foto_2')->store('public/barang_images');
        } else {
            $foto2Path = $barang->Foto_2;
        }

        if ($request->file('Foto_3')) {
            Storage::delete($barang->Foto_3);
            $foto3Path = $request->file('Foto_3')->store('public/barang_images');
        } else {
            $foto3Path = $barang->Foto_3;
        }

        $barang->update([
            'Nama_Produk' => $request->Nama_Produk,
            'Nama_Kategori' => $request->Nama_Kategori,
            'Harga' => $request->Harga,
            'Stok' => $request->Stok,
            'Berat' => $request->Berat,
            'Deskripsi_Lengkap' => $request->Deskripsi_Lengkap,
            'Foto_1' => $foto1Path,
            'Foto_2' => $foto2Path,
            'Foto_3' => $foto3Path,
        ]);

        return $this->sendSuccess($barang, 'Barang berhasil diperbarui.', Response::HTTP_OK);
    }

    public function show($id)
    {
        $barang = Barang::with('kategori')->find($id);

        if (!$barang) {
            return $this->sendError('Barang tidak ditemukan.', [], Response::HTTP_NOT_FOUND);
        }

        return $this->sendSuccess($barang, 'Barang ditemukan.', Response::HTTP_OK);
    }


    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return $this->sendError('Barang tidak ditemukan.', [], Response::HTTP_NOT_FOUND);
        }

        if ($barang->Foto_1 && Storage::exists($barang->Foto_1)) {
            Storage::delete($barang->Foto_1);
        }

        if ($barang->Foto_2 && Storage::exists($barang->Foto_2)) {
            Storage::delete($barang->Foto_2);
        }

        if ($barang->Foto_3 && Storage::exists($barang->Foto_3)) {
            Storage::delete($barang->Foto_3);
        }

        $barang->delete();

        return $this->sendSuccess([], 'Barang dan foto berhasil dihapus.', Response::HTTP_OK);
    }
}
