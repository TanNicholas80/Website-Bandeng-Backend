<?php

namespace App\Http\Controllers;

use App\Mail\forgotPass;
use App\Mail\MitraVerification;
use Illuminate\Http\Request;
use App\Models\Mitra;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MitraController extends Controller
{
    // Fitur Register untuk Frontend
    public function register(Request $req) {
        $validator = Validator::make($req->all(), [
            'namaMitra' => 'required',
            'alamatMitra' => 'required',
            'no_tlp' => 'required',
            'email' => 'required|email|unique:mitras',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }
        $mitra = Mitra::create([
            'namaMitra' => $req->namaMitra,
            'alamatMitra' => $req->alamatMitra,
            'no_tlp' => $req->no_tlp,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);
        // if(!$mitra) {
        //     return response()->json(['error' => 'Akun Mitra Gagal Terbuat'], 500);
        // } else {
        //     return response()->json(['response' => 'Akun Mitra Sukses Terbuat'], 200);
        // }
        try {
            Mail::to($mitra)->send(new MitraVerification($mitra));
            echo 'suk';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    // Fitur Login untuk Frontend
    public function login(Request $req) {
        $mitra = Mitra::where('email', $req->email)->first();
        if(!$mitra) {
            return response()->json(['error' => 'Maaf, Email Anda Belum Terdaftar'], 400);
        }
        if(!Hash::check($req->password, $mitra->password)) {
            return response()->json(['error' => 'Maaf, Password yang Anda masukkan salah'], 400);
        }
        if(!$mitra->hasVerifiedEmail()) {
            return response()->json(['error' => 'Email Anda Belum Terverifikasi'], 400);
        }
        $success['token'] = $mitra->createToken('auth_token')->plainTextToken;
        return response()->json(['response' => 'Berhasil Login', 'token' => $success, 'id' => $mitra->id, 'email' => $mitra->email], 200);
    }
    public function forgotPassword(Request $req) {
        try {
            $validator = Validator::make($req->all(), [
                'email' => 'required|email',
                'password' => 'required|min:8',
                'confirm_password' => 'required|min:8|same:password'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $mitra = Mitra::where('email', $req->email)->first();
            if($mitra->email != null) {
                $input = $req->only(['password', 'confirm_password']);
                $mitra->password = Hash::make($input['password']);
                $mitra->save();
                return response()->json(['response' => 'Sukses Ganti Password'], 200);
            } else {
                return response()->json(['error' => 'Maaf, Email Tidak Ditemukan'], 204);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
            // return response($e)->json([
            //     'status' => 401,
            //     'msg' => $e->getMessage()
            // ], 422);
        }
    }

    public function reqPasswordBaru(Request $req) {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }

        $mitra = Mitra::where('email', $req->email)->first();
            if($mitra->email != null) {
                Mail::to($mitra)->send(new forgotPass($mitra));
                echo $mitra->email;
            } else {
                return response()->json(['error' => 'Maaf, Email Tidak Ditemukan'], 404);
            }
    }

    public function editProfile(Request $req, $id) {
        try {
            $validator = Validator::make($req->all(), [
                // 'foto_mitra' => 'image|file|max:5024',
                'tglLahir' => 'date'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors(),
                ], 400);
            }
            // $validator = $req->all();
            // if($req->file('foto_mitra')) {
            //     $imgMitraPath = $req->file('foto_mitra')->store('mitra-images');
            //     $validator['foto_mitra'] = $imgMitraPath;
            //     $imgMitraPath = "storage/" . $imgMitraPath;
            // }
    
            $mitra = Mitra::find($id);
            // if($mitra->foto_mitra != null) {
            //     $split = explode('/',$mitra->foto_mitra,2);
            //     $filename = $split[1];
            //     Storage::delete($filename);
            // }

            $mitra->namaLengkap = $req->namaLengkap;
            $mitra->alamatMitra = $req->alamatMitra;
            $mitra->tglLahir = $req->tglLahir;
            $mitra->jeniskel = $req->jeniskel;
            // $mitra->foto_mitra = $imgMitraPath;
            $mitra->no_tlp = $req->no_tlp;
    
            $update = $mitra->update();

            if($update) {
                return response()->json(['response' => 'Profil Mitra Sukses Terupdate', 'user' => $req->user()], 200);
            } else {
                return response()->json(['error' => 'Profil Mitra Gagal Terupdate'], 403);
            }
        } catch(Exception $e) {
            echo $e;
        }
    }

    public function getMitra($id) {
        try {
            $mitra = Mitra::find($id);

            if (!$mitra) {
                echo "Mitra Tidak Ditemukan";
            } else {
                return response()->json(['data' => $mitra], 200);
            } 
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function editFotoMitra(Request $req, $id) {
        try {
            $validator = Validator::make($req->all(), [
                'foto_mitra' => 'image|file|max:5024',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors(),
                ], 400);
            }
            $validator = $req->all();
            if($req->file('foto_mitra')) {
                $imgMitraPath = $req->file('foto_mitra')->store('mitra-images');
                $validator['foto_mitra'] = $imgMitraPath;
                $imgMitraPath = "storage/" . $imgMitraPath;
            }
            $mitra = Mitra::find($id);
            if($mitra->foto_mitra != null) {
                $split = explode('/',$mitra->foto_mitra,2);
                $filename = $split[1];
                Storage::delete($filename);
            }
    
            $mitra->foto_mitra = $imgMitraPath;
    
            $update = $mitra->update();
    
            if($update) {
                return response()->json(['response' => 'Foto Mitra Sukses Terupload', 'user' => $req->user()], 200);
            } else {
                return response()->json(['error' => 'Foto Mitra Gagal Terupload'], 403);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function show(Request $request)
    {
        // Menggunakan Sanctum, pengguna autentikasi bisa diakses melalui $request->user()
        try {
            $mitra = $request->user();

            if (!$mitra) {
                return response()->json(['message' => 'Mitra tidak ditemukan'], 404);
            }

            return response()->json(['mitra' => $mitra], 200);
        } catch(Exception $e) {
            return response()->json(['message' => $e], 500);
        }
    }
}
