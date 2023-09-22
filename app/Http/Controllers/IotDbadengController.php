<?php

namespace App\Http\Controllers;

use App\Models\Iotdbandeng;
use App\Models\Mitra;
use Exception;
use Illuminate\Http\Request;

class IotDbadengController extends Controller
{
    public function kirimDB(Request $req) {
        try {
            // Create Product
            $iot = Iotdbandeng::create([
                'panjang' => $req->panjang,
                'berat' => $req->berat,
                'harga' => $req->harga,
            ]);

            if($iot) {
                return response()->json(['data' => $iot, 'response' => 'Data Berhasil Dikirim'], 200);
            }
        }catch(Exception $e) {
            return response()->json(['data' => $iot, 'reponse' => 'Data Gagal Terkirim'] , 500);
        }
    }
}
