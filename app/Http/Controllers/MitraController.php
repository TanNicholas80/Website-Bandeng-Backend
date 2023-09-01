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
use Illuminate\Support\Str;

class MitraController extends Controller
{
    // Fitur Register untuk Frontend
    public function register(Request $req) {
        $validator = Validator::make($req->all(), [
            'namaMitra' => 'required',
            'alamatMitra' => 'required',
            'no_tlp' => 'required',
            'email' => 'required|email|unique:mitras',
            'password' => 'min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }
        $password = Str::random(12);
        $mitra = Mitra::create([
            'namaMitra' => $req->namaMitra,
            'alamatMitra' => $req->alamatMitra,
            'no_tlp' => $req->no_tlp,
            'email' => $req->email,
            'password' => Hash::make($password),
        ]);
        // if(!$mitra) {
        //     return response()->json(['error' => 'Akun Mitra Gagal Terbuat'], 500);
        // } else {
        //     return response()->json(['response' => 'Akun Mitra Sukses Terbuat'], 200);
        // }
        try {
            Mail::to($mitra)->send(new MitraVerification($mitra, $password));
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
                'password' => 'required|min:8',
                'confirm_password' => 'required|min:8|same:password'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $mitra = Mitra::where('resetPassToken', $req->resetPassToken)->first();
            if($mitra->resetPassToken != null) {
                $input = $req->only(['password', 'confirm_password']);
                $mitra->password = Hash::make($input['password']);
                $mitra->save();
                $mitra->resetPassToken = null;
                $mitra->update();
                return response()->json(['response' => 'Sukses Ganti Password'], 200);
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
        $reqPassToken = Str::random(12);
        $mitra = Mitra::where('email', $req->email)->first();
            if($mitra->email != null) {
                $mitra->resetPassToken = $reqPassToken;
                $update = $mitra->update();
                if($update) {
                    Mail::to($mitra)->send(new forgotPass($mitra, $reqPassToken));
                    echo $mitra->email;
                } else {
                    return response()->json(['error' => 'Request Reset Password Gagal Terkirim'], 400);
                }
            } else {
                return response()->json(['error' => 'Maaf, Email Tidak Ditemukan'], 400);
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
    
    public function getAllMitra() {
        $mitra = Mitra::select('id', 'namaMitra', 'foto_mitra')->get();
        return response()->json(['data' => $mitra]);
    }

    public function getAllMitraAdmin() {
        $mitra = Mitra::all();
        return response()->json(['data' => $mitra]);
    }

    public function getTest($id) {
        $mitra = Mitra::select('namaMitra', 'foto_mitra', 'alamatMitra', 'no_tlp')->where('id', $id)->get();
        return response()->json(['data' => $mitra]);
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

    public function mitraLogout(Request $req) {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['msg' => "Anda Berhasil Logout"], 200);
    }

    public function createMitra(Request $req) {
        $validator = Validator::make($req->all(), [
            'namaMitra' => 'required',
            'alamatMitra' => 'required',
            'no_tlp' => 'required',
            'email' => 'required|email|unique:mitras',
            'password' => 'min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }
        $password = Str::random(12);
        $mitra = Mitra::create([
            'namaMitra' => $req->namaMitra,
            'alamatMitra' => $req->alamatMitra,
            'no_tlp' => $req->no_tlp,
            'email' => $req->email,
            'password' => Hash::make($password),
        ]);
        try {
            echo $mitra;
            Mail::to($mitra)->send(new MitraVerification($mitra, $password));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function editMitra(Request $req, $id) {
        $validator = Validator::make($req->all(), [
            'email' => 'email',
            'password' => 'min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }
        $mitra = Mitra::find($id);
        $mitra->namaMitra = $req->namaMitra; 
        $mitra->alamatMitra = $req->alamatMitra; 
        $mitra->no_tlp = $req->no_tlp; 
        $mitra->email = $req->email; 
        $mitra->password = Hash::make($req->password);
        
        $update = $mitra->update();
        if($update) {
            return response()->json(['response' => $mitra], 200);
        } else {
            return response()->json(['error' => 'Mitra Gagal Terupdate'], 500);
        }
    }

    public function deleteMitra($id) {
        $mitra = Mitra::find($id);
        $delete = $mitra->delete();
        if($delete) {
            return response()->json(['response' => 'Mitra Sukses Terhapus'], 200);
        } else {
            return response()->json(['error' => 'Mitra Gagal Terhapus'], 500);
        }
    }
}
