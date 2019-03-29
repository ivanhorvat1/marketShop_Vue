<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\dis_drink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\drink;
use Illuminate\Support\Facades\Cache;

class DrinkController extends Controller
{
    public function index()
    {

        $expiresAt = Carbon::now()->endOfDay()->subHour()->addMinutes(30);

        $cache = Cache::remember('maxiIdeaDisDrinksD', 10, function () {
            $maxiIdea = [];
            // Get articles
            $maxi = drink::where('shop', 'maxi')->where('category', 'pice')->whereNotNull('barcodes')->orderBy('price', 'DESC')->get();
            $idea = drink::where('shop', 'idea')->where('category', 'pice')->whereNotNull('barcodes')->orderBy('price', 'DESC')->get();
            $dis = dis_drink::orderBy('price', 'DESC')->get();


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

    public function getView()
    {
        return view('frontend.drinksArticles')->with('showStore', false);
    }

    public function getSeparatedMarket(Request $request){

        $this->shop = $request->shop;

        if($request->sort == 'rastuce'){
            $this->sort = 'ASC';
        }else{
            $this->sort = 'DESC';
        }

        $cache = Cache::remember($this->shop.'Drinks'.$this->sort, 10, function () {

            if($this->shop == 'maxi'){
                return drink::where('shop', 'maxi')->orderBy('price', $this->sort)->get();
            }elseif ($this->shop == 'idea'){
                return drink::where('shop', 'idea')->orderBy('price', $this->sort)->get();
            }elseif ($this->shop == 'dis'){
                return dis_drink::orderBy('price', $this->sort)->get();
            }
        });

        return $cache;
    }
}
