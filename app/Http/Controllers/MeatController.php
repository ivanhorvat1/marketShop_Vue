<?php

namespace App\Http\Controllers;

use App\dis_meat;
use App\Meat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class MeatController extends Controller
{
    public $shop;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expiresAt = Carbon::now()->endOfDay()->subHour()->addMinutes(30);

        $cache = Cache::remember('maxiIdeaDisMeats', 10, function () {
            $maxiIdea = [];
            // Get articles
            $maxi = Meat::where('shop', 'maxi')->where('category', 'meso')->whereNotNull('barcodes')->get();
            $idea = Meat::where('shop', 'idea')->where('category', 'meso')->whereNotNull('barcodes')->get();
            $dis = dis_meat::all();

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

            foreach ($dis as $di) {
                foreach ($maxiIdea as $maxide) {
                    if (explode(',', $di['barcodes']) == explode(',', $maxide['barcodes'])) {
                        if ($di['price'] >= $maxide['price']) {

                            if (!$maxide['ideaCena']) {
                                $maxide['ideaCena'] = $maxide['formattedPrice'];
                            }

                            if (!$maxide['maxiCena']) {
                                $maxide['maxiCena'] = $maxide['formattedPrice'];
                            }

                            $maxide[$di['shop'] . 'Cena'] = $di['formattedPrice'];
                            if (!in_array($maxide['barcodes'], array_column($maxiIdeaDis, 'barcodes'))) {
                                array_push($maxiIdeaDis, $maxide);
                            }
                        } else {
                            if ($maxide['ideaCena']) {
                                $ideaCena = $maxide['ideaCena'];
                            } else {
                                $ideaCena = $maxide['formattedPrice'];
                            }
                            if ($maxide['maxiCena']) {
                                $maxiCena = $maxide['maxiCena'];
                            } else {
                                $maxiCena = $maxide['formattedPrice'];
                            }
                            $di['ideaCena'] = $ideaCena;
                            $di['disCena'] = $di['formattedPrice'];
                            $di['imageUrl'] = $maxide['imageUrl'];
                            $di['maxiCena'] = $maxiCena;
                            if (!in_array($di['barcodes'], array_column($maxiIdeaDis, 'barcodes'))) {
                                array_push($maxiIdeaDis, $di);
                            }
                        }
                    }
                }
            }

            if (empty($maxiIdeaDis)) return $maxiIdea;

            return $maxiIdeaDis;
        });

        return $cache;
    }

    public function getView(){
        return view('frontend.meatsArticles')->with('showStore',false);
    }

    public function getSeparatedMarket(Request $request){

        $this->shop = $request->shop;

        if($request->sort == 'rastuce'){
            $this->sort = 'ASC';
        }else{
            $this->sort = 'DESC';
        }

        $cache = Cache::remember($this->shop.'Meats'.$this->sort, 10, function () {

            if($this->shop == 'maxi'){
                return Meat::where('shop', 'maxi')->orderBy('price', $this->sort)->get();
            }elseif ($this->shop == 'idea'){
                return Meat::where('shop', 'idea')->orderBy('price', $this->sort)->get();
            }elseif ($this->shop == 'dis'){
                return dis_meat::orderBy('price', $this->sort)->get();
            }
        });

        return $cache;
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
