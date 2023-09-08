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
                // $imgArticlePath = $req->file('foto_article')->store('article-images');
                $validator['foto_article'] = $req->file('foto_article');
                $uploadedFileUrl = cloudinary()->upload($req->file('foto_article')->getRealPath())->getSecurePath();
                // $imgArticlePath = "storage/" . $imgArticlePath;
            }
    
            $article = Article::create([
                'jdlArticle' => $req->jdlArticle,
                'isiArticle' => $req->isiArticle,
                'foto_article' => $uploadedFileUrl
            ]);
            if($article) {
                return response()->json(['response' => $article], 200);
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

    public function getAllArticle() {
        $article = Article::all();
        return response()->json(['data' => $article]);
    }

    public function getAllArticleAdmin() {
        $article = Article::all();
        return response()->json(['data' => $article]);
    }

    public function update(Request $req, $id) {
        try {
            $validator = Validator::make($req->all(), [
                'foto_article' => 'max:5024'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors(),
                ], 422);
            }
            $validator = $req->all();
            if($req->file('foto_article')) {
                // $imgArticlePath = $req->file('foto_article')->store('article-images');
                // $validator['foto_article'] = $imgArticlePath;
                // $imgArticlePath = "storage/" . $imgArticlePath;
                $validator['foto_article'] = $req->file('foto_article');
                $uploadedFileUrl = cloudinary()->upload($req->file('foto_article')->getRealPath())->getSecurePath();
            }

            $article = Article::find($id);
            // $split = explode('/',$article->foto_article,2);
            // $filename = $split[1];
            // Storage::delete($filename);

            $article->jdlArticle = $req->jdlArticle;
            $article->isiArticle = $req->isiArticle;
            if($req->file('foto_article')) {
                $article->foto_article = $uploadedFileUrl;
            }
            $update = $article->update();
            if($update) {
                return response()->json(['response' => $article], 200);
            } else {
                return response()->json(['error' => 'Article Gagal Terupdate'], 500);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
            return response()->json(['error' => $e], 500);
        }
    }

    public function destroy($id) {
        try {
            // if($req->foto_article) {
            //     Storage::delete($req->foto_article);
            // }
            $article = Article::find($id);
            // $split = explode('/',$article->foto_article,2);
            // $filename = $split[1];
            // Storage::delete($filename);
            $delete = $article->delete();
            
            if($delete) {
                // Notification Delete
                return response()->json([
                    'response' => 'Article Sukses Terhapus',
                    'path' => $article->foto_article
                ], 200);
            } else {
                return response()->json(['error' => 'Article Gagal Terhapus'], 500);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getNewsArticle() {
        $article = Article::orderBy('created_at', 'desc')->limit(6)->get();
        return response()->json(['data' => $article]);
    }
}
