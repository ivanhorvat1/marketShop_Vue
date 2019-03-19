<?php

namespace App\Http\Controllers;

use App\Sweets;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SweetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expiresAt = Carbon::now()->endOfDay()->subHour()->addMinutes(30);

        $maxiIdea = Cache::remember('maxiIdeaDisSweets', 10, function () {
            $maxiIdea = [];
            // Get articles
            $maxi = Sweets::where('shop', 'maxi')->where('category', 'slatkisi')->whereNotNull('barcodes')->get();
            $idea = Sweets::where('shop', 'idea')->where('category', 'slatkisi')->whereNotNull('barcodes')->get();

            foreach ($maxi as $max) {
                foreach ($idea as $ide) {
                    if (explode(',', $ide['barcodes']) == explode(',', $max['barcodes'])) {
                        $max['price'] = str_replace('.', '', $max['price']);
                        if ($max['price'] >= $ide['price']) {
                            $ide['maxiCena'] = $max['formattedPrice'];
                            $ide['imageUrl'] = $max['imageUrl'];
                            array_push($maxiIdea, $ide);
                        } else {
                            $max['ideaCena'] = $ide['formattedPrice'];
                            array_push($maxiIdea, $max);
                        }
                    }
                }
            }

            return $maxiIdea;
        });

        return $maxiIdea;
    }

    public function getView(){
        return view('frontend.sweetsArticles')->with('showStore',false);
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
