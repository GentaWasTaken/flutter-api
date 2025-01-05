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
        $validated = $request->validate([
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

        $barangData = $validated;
        $barangData['Foto_1'] = $this->uploadFile($request, 'Foto_1');
        $barangData['Foto_2'] = $this->uploadFile($request, 'Foto_2');
        $barangData['Foto_3'] = $this->uploadFile($request, 'Foto_3');

        $barang = Barang::create($barangData);

        return $this->sendSuccess($barang, 'Barang berhasil ditambahkan.', Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return $this->sendError('Barang tidak ditemukan.', [], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'Nama_Produk' => 'string|max:255',
            'Nama_Kategori' => 'exists:kategoris,id',
            'Harga' => 'numeric',
            'Stok' => 'integer',
            'Berat' => 'numeric',
            'Deskripsi_Lengkap' => 'string',
            'Foto_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Foto_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Foto_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $barangData = $validated;
        $barangData['Foto_1'] = $this->uploadFile($request, 'Foto_1', $barang->Foto_1);
        $barangData['Foto_2'] = $this->uploadFile($request, 'Foto_2', $barang->Foto_2);
        $barangData['Foto_3'] = $this->uploadFile($request, 'Foto_3', $barang->Foto_3);

        $barang->update($barangData);

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

        $this->deleteFile($barang->Foto_1);
        $this->deleteFile($barang->Foto_2);
        $this->deleteFile($barang->Foto_3);

        $barang->delete();

        return $this->sendSuccess([], 'Barang dan foto berhasil dihapus.', Response::HTTP_OK);
    }

    private function uploadFile(Request $request, $fieldName, $existingFilePath = null)
    {
        if ($request->hasFile($fieldName)) {
            if ($existingFilePath) {
                $this->deleteFile($existingFilePath);
            }
            return $request->file($fieldName)->store('public/images');
        }

        return $existingFilePath;
    }

    private function deleteFile($filePath)
    {
        if ($filePath && Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }
}
