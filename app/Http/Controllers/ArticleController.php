<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function store(Request $req) {
        $validator = Validator::make($req->all(), [
            'jdlArticle' => 'required',
            'isiArticle' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'errors' => $validator->errors(),
            ], 422);
        }

        $input = $req->only(['jdlArticle', 'isiArticle']);
        $create = Article::create($input);

        if($create) {
            echo('Success Create Article');
        }
    }

    public function update(Request $req, $id) {
        try {
            // $input = $req->only(['jdlArticle', 'isiArticle']);
            // $article = Article::find($id);
            // $update = $article->update($input);

            $article = Article::find($id);
            $article->jdlArticle = $req->jdlArticle;
            $article->isiArticle = $req->isiArticle;
            $update = $article->update();

            if($update) {
                echo('Success Update Article');
                echo($article);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function destroy($id) {
        try {
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
