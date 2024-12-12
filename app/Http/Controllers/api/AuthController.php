<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
class AuthController extends BaseController
{
    public function onRegister(Request $request)
    {
        $validate = $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'password'  => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        $validate['password'] = bcrypt($request->password);

        $user = User::create($validate);
        if($user) {
            $success['token'] = $user->createToken('GENTAHAX')->plainTextToken;
            $success['username'] = $user->name;
            $success['email'] = $user->email;
            return $this->sendSuccess($success, "Akun berhasil dibuat!", Response::HTTP_OK);
        }
        

        return $this->sendError(null, "Akun sudah ada!", Response::HTTP_OK);
    }

    public function onLogin(Request $request)
    {
        if(Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('GENTAHAX')->plainTextToken;
            $success['username'] = $user->name;
            $success['email'] = $user->email;
            return $this->sendSuccess($success, "Berhasil login, selamat datang {$user->name}!", Response::HTTP_OK);
        } else {
            return $this->sendError(null, "Maaf pengguna tidak dikenali!", Response::HTTP_OK);
        }
    }
}
