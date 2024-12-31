<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class KategoriController extends BaseController
{
    public function index()
    {
        $categories = Kategori::all();

        if ($categories->isEmpty()) {
            return $this->sendError('Tidak ada kategori yang ditemukan.', [], Response::HTTP_NOT_FOUND);
        }

        return $this->sendSuccess($categories, 'Daftar kategori ditemukan.', Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kategoris|max:255',
        ]);

        $kategori = Kategori::create(['nama' => $request->nama]);

        return $this->sendSuccess($kategori, 'Kategori berhasil dibuat.', Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return $this->sendError('Kategori tidak ditemukan.', [], Response::HTTP_NOT_FOUND);
        }

        return $this->sendSuccess($kategori, 'Kategori ditemukan.', Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return $this->sendError('Kategori tidak ditemukan.', [], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'nama' => 'required|string|unique:kategoris,nama,' . $id . '|max:255', // Fix: Use 'kategoris' (plural table name)
        ]);

        $kategori->update(['nama' => $request->nama]);

        return $this->sendSuccess($kategori, 'Kategori berhasil diperbarui.', Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return $this->sendError('Kategori tidak ditemukan.', [], Response::HTTP_NOT_FOUND);
        }

        $kategori->delete();

        return $this->sendSuccess([], 'Kategori berhasil dihapus.', Response::HTTP_OK);
    }
}
