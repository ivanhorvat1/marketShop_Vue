<?php

namespace App\Http\Controllers;

use App\Meat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeatController extends Controller
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
        $maxi = Meat::where('shop', 'maxi')->where('category', 'meso')->whereNotNull('barcodes')->get();
        $idea = Meat::where('shop', 'idea')->where('category', 'meso')->whereNotNull('barcodes')->get();

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

    public function getView(){
        return view('frontend.meatsArticles')->with('showStore',false);
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
     * @param  \App\Meat  $meat
     * @return \Illuminate\Http\Response
     */
    public function show(Meat $meat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Meat  $meat
     * @return \Illuminate\Http\Response
     */
    public function edit(Meat $meat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Meat  $meat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meat $meat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Meat  $meat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meat $meat)
    {
        //
    }
}
