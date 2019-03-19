<?php

namespace App\Http\Controllers;

use App\Freeze;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class FreezeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $expiresAt = Carbon::now()->endOfDay()->subHour()->addMinutes(30);

        $maxiIdea = Cache::remember('maxiIdeaDisFreeze', 10, function () {
            $maxiIdea = [];

            $maxi = Freeze::where('shop', 'maxi')->where('category', 'smrznuti')->whereNotNull('barcodes')->get();
            $idea = Freeze::where('shop', 'idea')->where('category', 'smrznuti')->whereNotNull('barcodes')->get();

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

    public function getView()
    {
        return view('frontend.freezeArticles')->with('showStore', false);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Freeze $freeze
     * @return \Illuminate\Http\Response
     */
    public function show(Freeze $freeze)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Freeze $freeze
     * @return \Illuminate\Http\Response
     */
    public function edit(Freeze $freeze)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Freeze $freeze
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Freeze $freeze)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Freeze $freeze
     * @return \Illuminate\Http\Response
     */
    public function destroy(Freeze $freeze)
    {
        //
    }
}
