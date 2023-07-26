<?php

namespace App\Http\Controllers;

use App\Mail\MitraVerification;
use Illuminate\Http\Request;
use App\Models\Mitra;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MitraController extends Controller
{
    // Fitur Register untuk Frontend
    public function register(Request $req) {
        $validator = Validator::make($req->all(), [
            'namaMitra' => 'required',
            'alamatMitra' => 'required',
            'tglLahir' => 'required',
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
            'tglLahir' => $req->tglLahir,
            'jeniskel' => $req->jeniskel,
            'no_tlp' => $req->no_tlp,
            'foto_mitra' => $req->foto_mitra,
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
    function login(Request $req) {
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
    function forgotPassword(Request $req) {
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
}
