<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function loginAdmin(Request $req) {
        $admin = Admin::where('email', $req->email)->first();
        if(!$admin) {
            return response()->json(['error' => 'Maaf, Email Anda Belum Terdaftar'], 400);
        }
        if(!Hash::check($req->password, $admin->password)) {
            return response()->json(['error' => 'Maaf, Password yang Anda masukkan salah'], 400);
        }
        $success['token'] = $admin->createToken('auth_token')->plainTextToken;
        return response()->json(['response' => 'Berhasil Login', 'token' => $success, 'id' => $admin->id, 'email' => $admin->email], 200);
    }

    public function adminLogout(Request $req) {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['msg' => "Anda Berhasil Logout"], 200);
    }
}
