<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function store(Request $req) {
        try {
            $validator = Validator::make($req->all(), [
                'jdlArticle' => 'required',
                'isiArticle' => 'required',
                'foto_article' => 'required|image|file|max:5024'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'error' => $validator->errors(),
                ], 422);
            }
            $validator = $req->all();
            if($req->file('foto_article')) {
                $imgArticlePath = $req->file('foto_article')->store('public/article-images');
                $validator['foto_article'] = $imgArticlePath;
            }
    
            $input = $req->only(['jdlArticle', 'isiArticle', 'foto_article']);
            $create = Article::create($input);
    
            if($create) {
                return response()->json(['response' => 'Article Berhasil Terbuat', 'path' => $imgArticlePath], 200);
            } else {
                return response()->json(['error' => 'Article Gagal Terbuat'], 500);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getArticle($id) {
        try {
            $article = Article::find($id);

            if (!$article) {
                echo "Article Tidak Ditemukan";
            } else {
                echo $article;
            } 
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function update(Request $req, $id) {
        try {
            $validator = Validator::make($req->all(), [
                'foto_article' => 'image|file|max:5024'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors(),
                ], 422);
            }

            if($req->file('foto_article')) {
                if($req->oldImage) {
                    Storage::delete($req->oldImage);
                }
                $imgArticlePath = $req->file('foto_article')->store('public/article-images');
                if($imgArticlePath) {
                    echo 'Update Gambar Article sudah benar';
                }
            }

            $article = Article::find($id);
            $article->jdlArticle = $req->jdlArticle;
            $article->isiArticle = $req->isiArticle;
            $article->foto_article = $req->foto_article;
            $update = $article->update();

            if($update) {
                return response()->json(['response' => 'Article Sukses Terupdate'], 200);
            } else {
                return response()->json(['error' => 'Article Gagal Terupdate'], 500);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function destroy(Request $req, $id) {
        try {
            if($req->foto_article) {
                Storage::delete($req->foto_article);
            }
            $article = Article::find($id);
            $delete = $article->delete();

            if($delete) {
                // Notification Delete
                return response()->json([
                    'response' => 'Article Sukses Terhapus',
                    'path' => $req->foto_article
                ], 200);
            } else {
                return response()->json(['error' => 'Article Gagal Terhapus'], 500);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
