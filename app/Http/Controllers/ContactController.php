<?php

namespace App\Http\Controllers;

use App\Mail\ContactKategori;
use App\Mail\ThankingInputUser;
use App\Mail\UserMitraToAdmin;
use App\Models\Contact;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function kirimPesan(Request $req) {
        $validator = Validator::make($req->all(), [
            'nameCn' => 'required',
            'emailCn' => 'required|email',
            'pesanCn' => 'required|max:255',
            'kategoriCn' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }
        $contact = Contact::create([
            'nameCn' => $req->nameCn,
            'emailCn' => $req->emailCn,
            'pesanCn' => $req->pesanCn,
            'kategoriCn' => $req->kategoriCn,
        ]);
        // if($contact) {
        //     return response()->json(['response' => 'Sukses Mengirim Pesan'], 200);
        // } else {
        //     return response()->json(['error' => 'Gagal Mengirim Pesan'], 500);
        // }
        try {
            if($contact->kategoriCn === 'mitra') {
                Mail::to($contact->emailCn)->send(new ContactKategori($contact));
                $emailAdmin = 'dbandengkrobokan@gmail.com';
                Mail::to($emailAdmin)->send(new UserMitraToAdmin($contact));
            } elseif($contact->kategoriCn === 'umum') {
                Mail::to($contact->emailCn)->send(new ThankingInputUser($contact));
                $emailAdmin = 'dbandengkrobokan@gmail.com';
                Mail::to($emailAdmin)->send(new UserMitraToAdmin($contact));
            } else {
                $emailAdmin = 'dbandengkrobokan@gmail.com';
                Mail::to($emailAdmin)->send(new UserMitraToAdmin($contact));
            }
            echo 'sukses kirim pesan';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
