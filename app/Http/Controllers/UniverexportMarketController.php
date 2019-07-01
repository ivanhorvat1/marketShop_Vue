<?php

namespace App\Http\Controllers;

use App\drink;
use App\dis_drink;
use App\univerexport;
use App\univerexport_action_sale;
use App\univerexport_drink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UniverexportMarketController extends Controller
{
    public function getUniverexportMarketDrink()
    {
        //skip(1200)->
        $univerexportDrinks = univerexport::where('category', 'pice')->take(1200)->get();

        $database = [];
        foreach ($univerexportDrinks as $univerexport) {
            $explo = explode(' ', $univerexport['name']);

            $duzina = count($explo);

            //foreach ($explo as $ex){
            // if($duzina > 3){
            //->where('body', 'like', '%'.$explo[3].'%')
            $databasee = ['univerexport' => $univerexport, 'drink' => drink::select('body', 'barcodes', 'supplementaryPriceLabel2', 'imageUrl')->whereNotNull('barcodes')->where('body', 'like', '%' . $explo[0] . '%')->where('body', 'like', '%' . $explo[1] . '%')->get()];
            // }
            //}
            //var_dump($databasee['drink']->isEmpty()); continue;
            if ($databasee['drink']->isNotEmpty()) {
                $database[] = $databasee;
            }
        }

        return $database;
    }

    public function getViewDrink()
    {
        return view('frontend.compareUniverexportMarketDrink')->with('showStore', false);
    }

    public function store(Request $request)
    {
        /*var_dump($request->code);
        die;*/

        $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";

        if ($request->oldPrice != 0) {
            $formattedPrice = $request->newPrice . " RSD";
            $price = str_replace('.', '', $request->newPrice);
        } else {
            $formattedPrice = $request->newPrice . " RSD";
            $price = str_replace('.', '', $request->newPrice);
        }

        if ($request->category == 'pice') {
            $article = univerexport_drink::firstOrNew(array('code' => $request->code));
        }/* elseif ($request->category == 'meso') {
            $article = dis_meat::firstOrNew(array('code' => $request->code));
        } elseif ($request->category == 'smrznuti') {
            $article = dis_freeze::firstOrNew(array('code' => $request->code));
        } elseif ($request->category == 'slatkisi') {
            $article = dis_sweet::firstOrNew(array('code' => $request->code));
        }*/

        $article->code = $request->code;
        $article->title = $request->title;
        $article->body = $request->body;
        $article->category = $request->category;
        $article->imageUrl = $request->imageUrl;
        $article->imageDefault = $imageDefault;
        $article->barcodes = $request->barcodes;
        $article->formattedPrice = $formattedPrice;
        $article->price = $price;
        $article->supplementaryPriceLabel1 = $request->supplementaryPriceLabel1;
        $article->supplementaryPriceLabel2 = null;
        $article->shop = $request->shop;
        $article->save();

        if ($request->oldPrice != 0) {

            $formattedPrice = $request->newPrice . " RSD";

            $article = univerexport_action_sale::firstOrNew(array('code' => $request->code));
            $article->code = $request->code;
            $article->title = $request->title;
            $article->body = $request->body;
            $article->category = $request->category;
            $article->imageUrl = $request->imageUrl;
            $article->imageDefault = $imageDefault;
            $article->barcodes = $request->barcodes;
            $article->formattedPrice = $formattedPrice;
            $article->price = str_replace('.', '', $request->newPrice);
            $article->supplementaryPriceLabel1 = $request->supplementaryPriceLabel1;
            $article->supplementaryPriceLabel2 = null;
            $article->shop = $request->shop;
            $article->save();

        }
    }
}
