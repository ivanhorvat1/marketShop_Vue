<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\action_sale;
use App\drink;
use App\Meat;
use App\Sweets;
use App\Freeze;
use App\dis_action_sale;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ActionSaleController extends Controller
{
    public function index()
    {
        $expiresAt = Carbon::now()->endOfDay()->subHour()->addMinutes(30);

        $cache = Cache::remember('maxiIdeaDisSale', 10, function() {

            $maxi = action_sale::where('shop','maxi')->where('category','akcija')->get();
            $idea = action_sale::where('shop','idea')->where('category','akcija')->get();
            $dis = dis_action_sale::orderBy('price', 'DESC')->get();

            $maxiIdea = [];
            foreach ($maxi as $max) {
                foreach ($idea as $ide) {
                    if (explode(',', $ide['barcodes']) == explode(',', $max['barcodes'])) {
                        $max['price'] = str_replace('.', '', $max['price']);
                        if ($max['price'] >= $ide['price']) {
                            $ide['maxiCena'] = $max['formattedPrice'];
                            $ide['ideaCena'] = $ide['formattedPrice'];
                            $ide['imageUrl'] = $max['imageUrl'];
                            array_push($maxiIdea, $ide);
                        } else {
                            $max['ideaCena'] = $ide['formattedPrice'];
                            $max['maxiCena'] = $max['formattedPrice'];
                            array_push($maxiIdea, $max);
                        }
                    }
                }
            }

            $maxiIdeaDis = [];

            foreach ($dis as $di){
                foreach ($maxiIdea as $maxide){
                    if (explode(',', $di['barcodes']) == explode(',', $maxide['barcodes'])) {
                        if($di['price'] >= $maxide['price']){

                            if(!$maxide['ideaCena']){
                                $maxide['ideaCena'] = $maxide['formattedPrice'];
                            }

                            if(!$maxide['maxiCena']){
                                $maxide['maxiCena'] = $maxide['formattedPrice'];
                            }

                            $maxide[$di['shop'].'Cena'] = $di['formattedPrice'];
                            if(!in_array($maxide['barcodes'],array_column($maxiIdeaDis, 'barcodes'))){
                                array_push($maxiIdeaDis, $maxide);
                            }
                        }else{
                            if($maxide['ideaCena']){
                                $ideaCena = $maxide['ideaCena'];
                            }else{
                                $ideaCena = $maxide['formattedPrice'];
                            }
                            if($maxide['maxiCena']){
                                $maxiCena = $maxide['maxiCena'];
                            }else{
                                $maxiCena = $maxide['formattedPrice'];
                            }
                            $di['ideaCena'] = $ideaCena;
                            $di['disCena'] = $di['formattedPrice'];
                            $di['imageUrl'] = $maxide['imageUrl'];
                            $di['maxiCena'] = $maxiCena;
                            if(!in_array($di['barcodes'],array_column($maxiIdeaDis, 'barcodes'))) {
                                array_push($maxiIdeaDis, $di);
                            }
                        }
                    }
                }
            }

            if(empty($maxiIdeaDis)) return $maxiIdea;

            return $maxiIdeaDis;
        });

        return $cache;
    }

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

                if (array_key_exists('eanCodes', $request->products[0][$i]) && !empty($request->products[0][$i]['eanCodes'])) {
                    $barcode = implode(',', $request->products[0][$i]['eanCodes']);
                } else {
                    $barcode = null;
                }

                /*$pricesub = substr($request->products[0][$i]['price']['formattedValue'], 0, -4);

                $subs = substr($pricesub, -3);*/

                if($request->products[0][$i]['price']['fractionValue'] == '00'){
                    $price = $request->products[0][$i]['price']['value'].$request->products[0][$i]['price']['fractionValue'];
                }else{
                    $price = str_replace('.','',$request->products[0][$i]['price']['value']);
                }

                array_push($storeRecords, ['code'=>$request->products[0][$i]['code'], 'title' => $request->products[0][$i]['manufacturerName'],
                    'body' => $request->products[0][$i]['name'], 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => $barcode,
                    'formattedPrice' => $request->products[0][$i]['price']['formattedValue'], 'price' => $price,
                    'supplementaryPriceLabel1' => $request->products[0][$i]['price']['supplementaryPriceLabel1'],
                    'supplementaryPriceLabel2' => $request->products[0][$i]['price']['supplementaryPriceLabel2'], 'shop' => $request->shop, 'category' => $request->category]);
            }

//            array_push($storeRecords, ['code'=>'7176748', 'title' => 'Schauma', 'body' => 'Sampon za decu Schauma Mermaid 250ml', 'imageUrl' => '/medias/sys_master/h83/h36/8829669539870.png', 'imageDefault' => null, 'barcodes' => '3838905551948,3838905551955,2401000202056',
//                'formattedPrice' => '269,99 RSD', 'price' => '555.99', 'supplementaryPriceLabel1' => '1079.96 rsd/L', 'supplementaryPriceLabel2' => '250 ml', 'shop' => 'maxi']);
        }

        if($request->category == 'akcija'){

            if($request->shop == 'idea') {
                action_sale::where('shop', 'idea')->delete();
            }else{
                action_sale::where('shop', 'maxi')->delete();
            }

            foreach ($storeRecords as $record){
                $article = action_sale::firstOrNew(array('code'=>$record['code']));
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
        }elseif ($request->category == 'pice'){
            if($request->shop == 'idea') {
                drink::where('shop', 'idea')->delete();
            }else{
                drink::where('shop', 'maxi')->delete();
            }

            foreach ($storeRecords as $record){
                $article = drink::firstOrNew(array('code'=>$record['code']));
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
        }elseif ($request->category == 'meso'){
            if($request->shop == 'idea') {
                Meat::where('shop', 'idea')->delete();
            }else{
                Meat::where('shop', 'maxi')->delete();
            }

            foreach ($storeRecords as $record){
                $article = Meat::firstOrNew(array('code'=>$record['code']));
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
        }elseif ($request->category == 'slatkisi'){
            if($request->shop == 'idea') {
                Sweets::where('shop', 'idea')->delete();
            }else{
                Sweets::where('shop', 'maxi')->delete();
            }

            foreach ($storeRecords as $record){
                $article = Sweets::firstOrNew(array('code'=>$record['code']));
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
        }elseif ($request->category == 'smrznuti'){
            if($request->shop == 'idea') {
                Freeze::where('shop', 'idea')->delete();
            }else{
                Freeze::where('shop', 'maxi')->delete();
            }

            foreach ($storeRecords as $record){
                $article = Freeze::firstOrNew(array('code'=>$record['code']));
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
    }

    public function getView()
    {
        return view('frontend.actionSaleArticles')->with('showStore', false);
    }

    public function getSeparatedMarket(Request $request){

        $this->shop = $request->shop;

        if($request->sort == 'rastuce'){
            $this->sort = 'ASC';
        }else{
            $this->sort = 'DESC';
        }

        $cache = Cache::remember($this->shop.'Action'.$this->sort, 10, function () {

            if($this->shop == 'maxi'){
                return action_sale::where('shop', 'maxi')->orderBy('price', $this->sort)->get();
            }elseif ($this->shop == 'idea'){
                return action_sale::where('shop', 'idea')->orderBy('price', $this->sort)->get();
            }elseif ($this->shop == 'dis'){
                return dis_action_sale::orderBy('price', $this->sort)->get();
            }
        });

        return $cache;
    }
}
