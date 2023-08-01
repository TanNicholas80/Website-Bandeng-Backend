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
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            if($req->file('foto_article')) {
                $validator['foto_article'] = $req->file('foto_article')->store('article-images');
            }
    
            $input = $req->only(['jdlArticle', 'isiArticle', 'foto_article']);
            $create = Article::create($input);
    
            if($create) {
                echo('Success Create Article');
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
                'foto_article' => 'required|image|file|max:5024'
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
                $validator['foto_article'] = $req->file('foto_article')->store('article-images');
            }

            $article = Article::find($id);
            $article->jdlArticle = $req->jdlArticle;
            $article->isiArticle = $req->isiArticle;
            $article->foto_article = $req->foto_article;
            $update = $article->update();

            if($update) {
                echo('Success Update Article');
                echo($article);
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
                echo('Success Delete Article');
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
