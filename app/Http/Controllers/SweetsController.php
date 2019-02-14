<?php

namespace App\Http\Controllers;

use App\Sweets;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SweetsController extends Controller
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
        $maxi = Sweets::where('shop', 'maxi')->where('category', 'slatkisi')->whereNotNull('barcodes')->get();
        $idea = Sweets::where('shop', 'idea')->where('category', 'slatkisi')->whereNotNull('barcodes')->get();

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sweets  $sweets
     * @return \Illuminate\Http\Response
     */
    public function show(Sweets $sweets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sweets  $sweets
     * @return \Illuminate\Http\Response
     */
    public function edit(Sweets $sweets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sweets  $sweets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sweets $sweets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sweets  $sweets
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sweets $sweets)
    {
        //
    }
}
