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
                'email' => 'required|email|unique:users',
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
            if($user) {
                return response()->json(['response' => 'Akun Mitra Sukses Terbuat'], 200);
            } 
        } catch(Exception $e) {
            return response()->json(['error' => $e], 500);
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
                $validator['foto_user'] = $req->file('foto_user');
                $uploadedFileUrl = cloudinary()->upload($req->file('foto_user')->getRealPath())->getSecurePath();
            } 
            $user = User::find($id);
            if($user->foto_user != null) {
                $imageUrl = $user->foto_user;
                $publicId = CloudinaryStorage::getPublicId($imageUrl);
                cloudinary()->destroy($publicId);
            }
    
            $user->name = $req->name;
            $user->alamatUser = $req->alamatUser;
            $user->no_user = $req->no_user;
            $user->email = $req->email;
            $user->foto_user = $uploadedFileUrl;
        
            $update = $user->update();
    
            if($update) {
                return response()->json(['response' => 'Profil User Sukses Terupdate'], 200);
            }
        } catch(Exception $e) {
            return response()->json(['error' => 'Profil User Gagal Terupdate'], 500);
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
        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['response' => 'Berhasil Login', 'token' => $success, 'id' => $user->id, 'email' => $user->email], 200);
    }

    public function getUser($id) {
        try {
            $user = User::find($id);

            if ($user) {
                return response()->json(['data' => $user], 200);
            } 
        } catch(Exception $e) {
            return response()->json(['error' => 'Mitra Tidak Ditemukan'], 500);
        }
    }

    public function userLogout(Request $req) {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['response' => "Anda Berhasil Logout"], 200);
    }
}
