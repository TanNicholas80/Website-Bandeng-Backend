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
                    'status' => 400,
                    'errors' => $validator->errors(),
                ], 400);
            }
            $validator = $req->all();
            if($req->file('foto_produk')) {
                $imgProdukPath = $req->file('foto_produk')->store('produk-images');
                $validator['foto_produk'] = $imgProdukPath;
                $imgProdukPath = "storage/" . $imgProdukPath;
            }

            // mencari id mitra
            $mitra = Mitra::find($mitraId);
            if(!$mitra) {
                echo "ID Mitra Tidak Ditemukan";
            }
            // Create Product
            $product = new Product();
            $product->nmProduk = $req->input('nmProduk');
            $product->foto_produk = $imgProdukPath;
            $product->hrgProduk = $req->input('hrgProduk');
            $product->stok = $req->input('stok');
            $product->beratProduk = $req->input('beratProduk');
            $product->dskProduk = $req->input('dskProduk');
    
            $createProduct = $mitra->products()->save($product);
            if($createProduct) {
                return response()->json(['data' => "$createProduct"], 200);
            }
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

    public function getProductsForMitra($mitraId) {
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

    public function updateProduct(Request $req, $productId) {
        try {
            $validator = Validator::make($req->all(), [
                'foto_produk' => 'max:5024',
                'dskProduk' => 'max:255'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors(),
                ], 422);
            }
            $validator = $req->all();
            if($req->file('foto_produk')) {
                $imgProdukPath = $req->file('foto_produk')->store('produk-images');
                $validator['foto_produk'] = $imgProdukPath;
                $imgProdukPath = "storage/" . $imgProdukPath;
            }

            // mencari id product berdasarkan id yang dimiliki mitra
            $product = Product::find($productId);
            if(!$product) {
                echo "Produk Tidak Ditemukan";
            }
            $split = explode('/',$product->foto_produk,2);
            $filename = $split[1];
            Storage::delete($filename);

            $product->nmProduk = $req->nmProduk;
            if($req->file('foto_produk')) {
                $product->foto_produk = $imgProdukPath;
            }
            $product->hrgProduk = $req->hrgProduk;
            $product->stok = $req->stok;
            $product->beratProduk = $req->beratProduk;
            $product->dskProduk = $req->dskProduk;

            $update = $product->save();
            if($update) {
                return response()->json(['data' => "$product"], 200);
            }  
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function deleteProduct($productId) {
        // mencari id product berdasarkan id yang dimiliki mitra
        $product = Product::find($productId);
        if(!$product) {
            echo "Produk Tidak Ditemukan";
        }
        $split = explode('/',$product->foto_produk,2);
        $filename = $split[1];
        Storage::delete($filename);
        $delete = $product->delete();
        if(!$delete) {
            echo "Produk Gagal Dihapus";
        } else {
            echo "Produk Berhasil Dihapus";
        }
    }

    public function getProductHomepage(Request $req) {
        $mitraId = [$req->mitra1, $req->mitra2];
        $mitra = Mitra::query()->select('id', 'foto_mitra')->with(['products:id,mitra_id,nmProduk,foto_produk,hrgProduk'])->find($mitraId);
        return response()->json(['data' => $mitra], 200);
    }

    public function spesificProduct($productId) {
        $product = Product::select('id', 'mitra_id', 'nmProduk', 'hrgProduk', 'foto_produk', 'dskProduk')->find($productId);
        return response()->json(['data' => $product], 200);
    }
}
