<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function createProduct(Request $req, $mitraId) {
        try {
            $validator = Validator::make($req->all(), [
                'nmProduk' => 'required',
                'foto_produk' => 'required|image|file|max:5024',
                'hrgProduk' => 'required',
                'stok' => 'required',
                'beratProduk' => 'required',
                'dskProduk' => 'required|max:255'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors(),
                ], 422);
            }

            if($req->file('foto_produk')) {
                $validator['foto_produk'] = $req->file('foto_produk')->store('produk-images');
            }

            // mencari id mitra
            $mitra = Mitra::find($mitraId);
            if(!$mitra) {
                echo "ID Mitra Tidak Ditemukan";
            }
            // Create Product
            $product = new Product();
            $product->nmProduk = $req->input('nmProduk');
            $product->foto_produk = $req->input('foto_produk');
            $product->hrgProduk = $req->input('hrgProduk');
            $product->stok = $req->input('stok');
            $product->beratProduk = $req->input('beratProduk');
            $product->dskProduk = $req->input('dskProduk');
    
            $mitra->products()->save($product);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getProductsByMitra($mitraId) {
        try {
            $mitra = Mitra::with('products')->find($mitraId);

            if (!$mitra) {
                echo "Mitra Tidak Ditemukan";
            } else {
                echo $mitra;
            }  
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function updateProduct(Request $req, $mitraId, $productId) {
        try {
            $validator = Validator::make($req->all(), [
                'foto_produk' => 'image|file|max:5024',
                'dskProduk' => 'max:255'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors(),
                ], 422);
            }

            if($req->file('foto_produk')) {
                if($req->oldImage) {
                    Storage::delete($req->oldImage);
                }
                $validator['foto_produk'] = $req->file('foto_produk')->store('produk-images');
            }

            // mencari id mitra
            $mitra = Mitra::find($mitraId);
            if(!$mitra) {
                echo "Mitra Tidak Ditemukan";
            }
            // mencari id product berdasarkan id yang dimiliki mitra
            $product = $mitra->products()->find($productId);
            if(!$product) {
                echo "Produk Tidak Ditemukan";
            }
            $product->nmProduk = $req->nmProduk;
            $product->foto_produk = $req->foto_produk;
            $product->hrgProduk = $req->hrgProduk;
            $product->stok = $req->stok;
            $product->beratProduk = $req->beratProduk;
            $product->dskProduk = $req->dskProduk;

            $update = $product->update();
            if(!$update) {
                echo "Produk Gagal Diupdate";
            } else {
                echo "Produk Berhasil Terupdate";
            }         
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function deleteProduct(Request $req, $mitraId, $productId) {
        if($req->foto_produk) {
            Storage::delete($req->foto_produk);
        }
        // mencari id mitra
        $mitra = Mitra::find($mitraId);
        if(!$mitra) {
            echo "Mitra Tidak Ditemukan";
        }
        // mencari id product berdasarkan id yang dimiliki mitra
        $product = $mitra->products()->find($productId);
        if(!$product) {
            echo "Produk Tidak Ditemukan";
        }

        $delete = $product->delete();
        if(!$delete) {
            echo "Produk Gagal Dihapus";
        } else {
            echo "Produk Berhasil Dihapus";
        }
    }
}
