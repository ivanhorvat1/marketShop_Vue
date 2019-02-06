<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\drink;

class DrinkController extends Controller
{
    public function index()
    {
        $sameBarcode = [];
        // Get articles
        $maxi = drink::where('shop', 'maxi')->where('category', 'pice')->whereNotNull('barcodes')->get();
        $idea = drink::where('shop', 'idea')->where('category', 'pice')->whereNotNull('barcodes')->get();

//        $maxi = Cache::remember('articlesMaxi', 5, function(){
//            return Article::where('shop','maxi')->where('category','akcija')->get();
//        });
//
//        $idea = Cache::remember('articlesIdea', 5, function(){
//            return Article::where('shop','idea')->where('category','akcija')->get();
//        });

        foreach ($maxi as $max) {
            foreach ($idea as $ide) {
                if (explode(',', $ide['barcodes']) == explode(',', $max['barcodes'])) {
                    if (str_replace('.', '', $max['price']) >= $ide['price']) {
                        $ide['maxiCena'] = $max['formattedPrice'];
                        array_push($sameBarcode, $ide);
                    } else {
                        $max['ideaCena'] = $ide['formattedPrice'];
                        array_push($sameBarcode, $max);
                    }
                }
            }
        }

        return ['data' => json_encode($sameBarcode)];
    }
}
