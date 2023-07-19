<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;
use Illuminate\Support\Facades\Hash;

class MitraController extends Controller
{
    function register(request $req) {
        $mitra = new Mitra();
        $mitra->namaMitra = $req->input('namaMitra');
        $mitra->alamatMitra = $req->input('alamatMitra');
        $mitra->tglLahir = $req->input('tglLahir');
        $mitra->jeniskel = $req->input('jeniskel');
        $mitra->no_tlp = $req->input('no_tlp');
        $mitra->foto_mitra = $req->input('foto_mitra');
        $mitra->email = $req->input('email');
        $mitra->password = Hash::make($req->input('password'));
        $mitra->save();
        return $mitra;
    }
}
