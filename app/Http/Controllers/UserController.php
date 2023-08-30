<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function registerUser(Request $req) {
        try {
            $validator = Validator::make($req->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:mitras',
                'password' => 'required|min:8',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors(),
                ], 400);
            }
            $user = User::create([
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make($req->password),
            ]);
            if(!$user) {
                return response()->json(['error' => 'Akun Mitra Gagal Terbuat'], 500);
            } else {
                return response()->json(['response' => 'Akun Mitra Sukses Terbuat'], 200);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function editUser(Request $req, $id) {
        try {
            $validator = Validator::make($req->all(), [
                'foto_user' => 'max:5024'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors(),
                ], 400);
            }
            $validator = $req->all();
            if($req->file('foto_user')) {
                $imgUserPath = $req->file('foto_user')->store('article-user');
                $validator['foto_user'] = $imgUserPath;
                $imgUserPath = "storage/" . $imgUserPath;
            } 
            $user = User::find($id);
            if($user->foto_user != null) {
                $split = explode('/',$user->foto_user,2);
                $filename = $split[1];
                Storage::delete($filename);
            }
    
            $user->name = $req->name;
            $user->alamatUser = $req->alamatUser;
            $user->no_user = $req->no_user;
            $user->email = $req->email;
            $user->foto_user = $imgUserPath;
        
            $update = $user->update();
    
            if($update) {
                return response()->json(['response' => 'Profil User Sukses Terupdate'], 200);
            } else {
                return response()->json(['error' => 'Profil User Gagal Terupdate'], 403);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function loginUser(Request $req) {
        $user = User::where('email', $req->email)->first();
        if(!$user) {
            return response()->json(['error' => 'Maaf, Email Anda Belum Terdaftar'], 400);
        }
        if(!Hash::check($req->password, $user->password)) {
            return response()->json(['error' => 'Maaf, Password yang Anda masukkan salah'], 400);
        }
        return response()->json(['response' => 'Berhasil Login', 'id' => $user->id, 'email' => $user->email], 200);
    }
}
