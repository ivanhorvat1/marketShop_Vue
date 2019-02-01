<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Http\Resources\Article as ArticleResource;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sameBarcode = [];
        // Get articles
        $maxi = Article::where('shop','maxi')->where('category','akcija')->get();
        $idea = Article::where('shop','idea')->where('category','akcija')->get();

//        $maxi = Cache::remember('articlesMaxi', 5, function(){
//            return Article::where('shop','maxi')->where('category','akcija')->get();
//        });
//
//        $idea = Cache::remember('articlesIdea', 5, function(){
//            return Article::where('shop','idea')->where('category','akcija')->get();
//        });

        foreach ($maxi as $max){
            foreach ($idea as $ide){
                if(explode(',' ,$ide['barcodes']) == explode(',' ,$max['barcodes'])){
                    if(str_replace('.','',$max['price']) >= $ide['price']){
                        $ide['maxiCena'] = $max['formattedPrice'];
                        array_push($sameBarcode, $ide);
                    }else{
                        $max['ideaCena'] = $ide['formattedPrice'];
                        array_push($sameBarcode, $max);
                    }
                }
            }
        }

        return ['data'=> json_encode($sameBarcode)];
        // Return collection of articles as a resource
        //return ArticleResource::collection($sameBarcode);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imageUrl = null;
        $imageDefault = null;
        $storeRecords = [];
        $arrayLengt = sizeof($request->products[0]) - 1;
        if($request->shop == 'idea') {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                if (array_key_exists('images', $request->products[0][$i]) && !empty($request->products[0][$i]['images'])) {
                    $imageUrl = $request->products[0][$i]['images'][0]["image_n"];
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                array_push($storeRecords, ['code'=>$request->products[0][$i]['code'], 'title' => $request->products[0][$i]['manufacturer'],
                    'body' => $request->products[0][$i]['name'], 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => implode(',', $request->products[0][$i]['barcodes']),
                    'formattedPrice' => $request->products[0][$i]['price']['formatted_price'], 'price' => $request->products[0][$i]['price']['amount'],
                    'supplementaryPriceLabel1' => $request->products[0][$i]['statistical_price'], 'supplementaryPriceLabel2' => null, 'shop' => $request->shop, 'category' => $request->category]);
            }
        }else{
            for ($i = 0; $i <= $arrayLengt; $i++) {
                if (array_key_exists('images', $request->products[0][$i]) && !empty($request->products[0][$i]['images'])) {
                    $imageUrl = $request->products[0][$i]['images'][2]['url'];
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                array_push($storeRecords, ['code'=>$request->products[0][$i]['code'], 'title' => $request->products[0][$i]['manufacturerName'],
                    'body' => $request->products[0][$i]['name'], 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => implode(',', $request->products[0][$i]['eanCodes']),
                    'formattedPrice' => $request->products[0][$i]['price']['formattedValue'], 'price' => $request->products[0][$i]['price']['value'],
                    'supplementaryPriceLabel1' => $request->products[0][$i]['price']['supplementaryPriceLabel1'],
                    'supplementaryPriceLabel2' => $request->products[0][$i]['price']['supplementaryPriceLabel2'], 'shop' => $request->shop, 'category' => $request->category]);
            }
//            array_push($storeRecords, ['code'=>'7176748', 'title' => 'Schauma', 'body' => 'Sampon za decu Schauma Mermaid 250ml', 'imageUrl' => '/medias/sys_master/h83/h36/8829669539870.png', 'imageDefault' => null, 'barcodes' => '3838905551948,3838905551955,2401000202056',
//                'formattedPrice' => '269,99 RSD', 'price' => '555.99', 'supplementaryPriceLabel1' => '1079.96 rsd/L', 'supplementaryPriceLabel2' => '250 ml', 'shop' => 'maxi']);
        }

        foreach ($storeRecords as $record){
            $article = Article::firstOrNew(array('code'=>$record['code']));
            $article->code = $record['code'];
            $article->title = $record['title'];
            $article->body = $record['body'];
            $article->category = $record['category'];
            $article->imageUrl = $record['imageUrl'];
            $article->imageDefault = $record['imageDefault'];
            $article->barcodes = $record['barcodes'];
            $article->formattedPrice = $record['formattedPrice'];
            $article->price = $record['price'];
            $article->supplementaryPriceLabel1 = $record['supplementaryPriceLabel1'];
            $article->supplementaryPriceLabel2 = $record['supplementaryPriceLabel2'];
            $article->shop = $record['shop'];
            $article->save();
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
