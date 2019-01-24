<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Http\Resources\Article as ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get articles
        $articles = Article::orderBy('created_at','desc')->paginate(5);

        // Return collection of articles as a resource
        return ArticleResource::collection($articles);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $article = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;

        $article->title = $request->input('title');
        $article->body = $request->input('body');
        $article->imageUrl = $request->input('imageUrl');
        $article->formattedPrice = $request->input('formattedPrice');
        $article->supplementaryPriceLabel1 = $request->input('supplementaryPriceLabel1');
        $article->supplementaryPriceLabel2 = $request->input('supplementaryPriceLabel2');
        $article->barcodes = implode(',',$request->input('barcodes'));

        //var_dump($article); die;

        if($article->save()) {
            return new ArticleResource($article);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get article
        $article = Article::findOrFail($id);

        // Return single article as a resource
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get article
        $article = Article::findOrFail($id);

        if($article->delete()) {
            return new ArticleResource($article);
        }    
    }
}
