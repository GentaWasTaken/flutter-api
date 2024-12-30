<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'user',
        ]);

        return response()->json([
            'token' => $user->createToken('MDPApp')->plainTextToken,
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MDPApp')->plainTextToken;

            return response()->json([
                'message' => 'Berhasil Login',
                'token' => $token,
                'name' => $user->name,
                'role' => $user->role,
            ], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_telp' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
            'alamat_lengkap' => 'nullable|string|max:150',
            'provinsi' => 'nullable|string|max:35',
            'kota' => 'nullable|string|max:35',
            'rt_rw' => 'nullable|string|max:10',
            'kel_desa' => 'nullable|string|max:50',
            'kecamatan' => 'nullable|string|max:50',
            'kode_pos' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($request->hasFile('image')) {
            if ($user->image && Storage::exists('public/' . $user->image)) {
                Storage::delete('public/' . $user->image);
            }

            $validated['image'] = $request->file('image')->store('user_images', 'public');
        }

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui.',
            'data' => $user
        ], Response::HTTP_OK);
    }



    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Berhasil Logout',
        ], 200);
    }
}
