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
        /*$collection->chunk(300, function ($subset) {
            var_dump($subset->toArray());
            $subset->each(function ($item) {
                var_dump($item);
            });
        }); die;*/

        $storeRecords = [];

        foreach ($request->products[0] as $product){
            if ($product['images'][0]["image_n"]) {
                $imageUrl = $product['images'][0]["image_n"];
            } else {
                $imageUrl = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
            }
            array_push($storeRecords,['title'=>$product['manufacturer'],'body'=>$product['name'],'imageUrl'=>$imageUrl,'barcodes'=>implode(',', $product['barcodes']),
                'formattedPrice'=>$product['price']['formatted_price'],'supplementaryPriceLabel1'=>$product['statistical_price'],'supplementaryPriceLabel2'=>null,'shop'=>$request->shop]);
        }

        $collection = collect($storeRecords);
        $chunks = $collection->chunk(300);
        $chunks->toArray();
//        var_dump($chunks->toArray()); die;

        foreach($chunks as $chunk)
        {
            Article::insert($chunk->toArray());
        }



        /*foreach($chunks as $producti)
        {
            foreach ($producti as $product){
                $article = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;
                $article->title = $product['manufacturer'];
                $article->body = $product['name'];
                if ($product['images'][0]["image_n"]) {
                    $article->imageUrl = $product['images'][0]["image_n"];
                } else {
                    $article->imageUrl = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }
                $article->barcodes = implode(',', $product['barcodes']);
                $article->formattedPrice = $product['price']['formatted_price'];
                $article->supplementaryPriceLabel1 = $product['statistical_price'];
                $article->supplementaryPriceLabel2 = null;
                $article->shop = $request->shop;

                $article->save();
            }
        }*/

       /*
        if($request->shop == 'idea') {
            foreach ($request->products[0] as $product) {
                $article = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;
                $article->title = $product['manufacturer'];
                $article->body = $product['name'];
                if ($product['images'][0]['image_n']) {
                    $article->imageUrl = $product['images'][0]["image_n"];
                } else {
                    $article->imageUrl = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }
                $article->barcodes = implode(',', $product['barcodes']);
                $article->formattedPrice = $product['price']['formatted_price'];
                $article->supplementaryPriceLabel1 = $product['statistical_price'];
                $article->supplementaryPriceLabel2 = null;
                $article->shop = $request->shop;

                $article->save();
            }
        }*/

        /*$article->title = $request->input('title');
        $article->body = $request->input('body');
        $article->imageUrl = $request->input('imageUrl');
        $article->formattedPrice = $request->input('formattedPrice');
        $article->supplementaryPriceLabel1 = $request->input('supplementaryPriceLabel1');
        $article->supplementaryPriceLabel2 = $request->input('supplementaryPriceLabel2');
        $article->shop = $request->input('shop');
        $article->barcodes = implode(',',$request->input('barcodes'));*/

        /*if($article->save()) {
            return new ArticleResource($article);
        }*/
        
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
