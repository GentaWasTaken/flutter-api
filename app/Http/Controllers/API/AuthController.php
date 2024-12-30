<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_telp' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
            'alamat_Lengkap' => 'nullable|string|max:150',
            'provinsi' => 'nullable|string|max:35',
            'kota' => 'nullable|string|max:35',
            'rt_rw' => 'nullable|string|max:10',
            'Kel_desa' => 'nullable|string|max:50',
            'kecamatan' => 'nullable|string|max:50',
            'kode_Pos' => 'nullable|string|max:10',
            'tanggal_Lahir' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.'
            ], Response::HTTP_NOT_FOUND);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->no_telp = $request->input('no_telp');
        $user->alamat_Lengkap = $request->input('alamat_Lengkap');
        $user->provinsi = $request->input('provinsi');
        $user->kota = $request->input('kota');
        $user->rt_rw = $request->input('rt_rw');
        $user->Kel_desa = $request->input('Kel_desa');
        $user->kecamatan = $request->input('kecamatan');
        $user->kode_Pos = $request->input('kode_Pos');
        $user->tanggal_Lahir = $request->input('tanggal_Lahir');


        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($request->hasFile('image')) {
            if ($user->image && Storage::exists('public/' . $user->image)) {
                Storage::delete('public/' . $user->image);
            }
            $user->image = $request->file('image')->store('user_images', 'public');
        }

        $user->save();

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
