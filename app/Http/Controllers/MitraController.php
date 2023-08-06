<?php

namespace App\Http\Controllers;

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
                'status' => 401,
                'errors' => $validator->errors(),
            ], 422);
        }
        $mitra = Mitra::create([
            'namaMitra' => $req->namaMitra,
            'alamatMitra' => $req->alamatMitra,
            'no_tlp' => $req->no_tlp,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);
        if(!$mitra) {
            return response()->json(['error' => 'Akun Mitra Gagal Terbuat'], 500);
        } else {
            return response()->json(['response' => 'Akun Mitra Sukses Terbuat'], 200);
        }
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
            return response()->json(['error' => 'Maaf, Email Anda Belum Terdaftar'], 404);
        }
        if(!Hash::check($req->password, $mitra->password)) {
            return response()->json(['error' => 'Maaf, Password yang Anda masukkan salah'], 401);
        }
        if(!$mitra->hasVerifiedEmail()) {
            return response()->json(['error' => 'Email Anda Belum Terverifikasi'], 403);
        }
        return response()->json($mitra, 200);
    }
    public function forgotPassword(Request $req) {
        try {
            $mitra = Mitra::where('email', $req->email)->first();
            if($mitra->email != null) {
                $input = $req->only(['password']);
                $mitra->password = Hash::make($input['password']);
                $mitra->save();
            } else {
                return response()->json(['error' => 'Maaf, Email Tidak Ditemukan'], 404);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
            // return response($e)->json([
            //     'status' => 401,
            //     'msg' => $e->getMessage()
            // ], 422);
        }
    }
    public function editProfile(Request $req, $id) {
        try {
            $validator = Validator::make($req->all(), [
                'foto_mitra' => 'image|file|max:5024',
                'tglLahir' => 'date'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            if($req->file('foto_mitra')) {
                if($req->oldImage) {
                    Storage::delete($req->oldImage);
                }
                $imgMitraPath = $req->file('foto_mitra')->store('mitra-images');
                if($imgMitraPath) {
                    echo 'Penyimpanan Gambar Mitra sudah benar';
                }
            }
    
            $mitra = Mitra::find($id);
            $mitra->namaLengkap = $req->namaLengkap;
            $mitra->tglLahir = $req->tglLahir;
            $mitra->jeniskel = $req->jeniskel;
            $mitra->foto_mitra = $req->foto_mitra;
            $mitra->no_tlp = $req->no_tlp;
    
            $update = $mitra->update();

            if($update) {
                return response()->json(['response' => 'Profil Mitra Sukses Terupdate'], 200);
            } else {
                return response()->json(['error' => 'Profil Mitra Gagal Terupdate'], 403);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getMitra($id) {
        try {
            $mitra = Mitra::find($id);

            if (!$mitra) {
                echo "Mitra Tidak Ditemukan";
            } else {
                echo $mitra;
            } 
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
