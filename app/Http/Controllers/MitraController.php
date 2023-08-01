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
            return ["Error" => "Maaf, Email Anda Belum Terdaftar"];
        }
        if(!Hash::check($req->password, $mitra->password)) {
            return ["Error" => "Maaf, Password yang ada masukan salah"];
        }
        if(!$mitra->hasVerifiedEmail()) {
            return ["Error" => "Email Anda belum verified"];
        }
        return $mitra;
    }
    public function forgotPassword(Request $req) {
        try {
            $mitra = Mitra::where('email', $req->email)->first();
            if($mitra->email != null) {
                $input = $req->only(['password']);
                $mitra->password = Hash::make($input['password']);
                $mitra->save();
            }
            echo $mitra;
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
                $validator['foto_mitra'] = $req->file('foto_mitra')->store('mitra-images');
            }
    
            $mitra = Mitra::find($id);
            $mitra->namaLengkap = $req->namaLengkap;
            $mitra->tglLahir = $req->tglLahir;
            $mitra->jeniskel = $req->jeniskel;
            $mitra->foto_mitra = $req->foto_mitra;
            $mitra->no_tlp = $req->no_tlp;
    
            $update = $mitra->update();

            if($update) {
                echo('Success Update Article');
                echo($mitra);
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
