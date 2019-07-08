<?php

namespace App\Http\Controllers;

use App\univerexport_drink;
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

        /*$maxi = drink::where('shop', 'maxi')->where('category', 'pice')->whereNotNull('barcodes')->orderBy('price', 'DESC')->get();

        foreach ($maxi as $max) {

            $pricesub = substr($max['formattedPrice'], 0, -4);

            $subs = substr($pricesub, -3);

            if($subs == ',00'){
                $max['price'] = $max['price'].'00';
                var_dump($max);
            }


        }
        die;*/

        $cache = Cache::remember('maxiIdeaDisDrinks', 10, function () {
            $maxiIdea = [];
            // Get articles
            $maxi = drink::where('shop', 'maxi')->where('category', 'pice')->whereNotNull('barcodes')->orderBy('price', 'DESC')->get();
            $idea = drink::where('shop', 'idea')->where('category', 'pice')->whereNotNull('barcodes')->orderBy('price', 'DESC')->get();
            $dis = dis_drink::orderBy('price', 'DESC')->get();
            $univer = univerexport_drink::orderBy('price', 'DESC')->get();


            foreach ($maxi as $max) {
                foreach ($idea as $ide) {
                    if (explode(',', $ide['barcodes']) == explode(',', $max['barcodes'])) {
                        $max['price'] = str_replace('.', '', $max['price']);
                        if ($max['price'] >= $ide['price']) {
                            $ide['maxiCena'] = $max['formattedPrice'];
                            $ide['ideaCena'] = $ide['formattedPrice'];
                            $ide['maxiPriceCompare'] = $max['price'];
                            $ide['ideaPriceCompare'] = $ide['price'];
                            $ide['imageUrl'] = $max['imageUrl'];
                            $ide['supplementaryPriceMaxi'] = $max['supplementaryPriceLabel1'];
                            $ide['supplementaryPriceIdea'] = $ide['supplementaryPriceLabel1'];
                            array_push($maxiIdea, $ide);
                        } else {
                            $max['ideaCena'] = $ide['formattedPrice'];
                            $max['maxiCena'] = $max['formattedPrice'];
                            $max['ideaPriceCompare'] = $ide['price'];
                            $max['maxiPriceCompare'] = $max['price'];
                            $max['supplementaryPriceIdea'] = $ide['supplementaryPriceLabel1'];
                            $max['supplementaryPriceMaxi'] = $max['supplementaryPriceLabel1'];
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
                            $maxide['disPriceCompare'] = $di['price'];
//                            $maxide['maxiPriceCompare'] = $maxide['maxiPriceCompare'];
//                            $maxide['ideaPriceCompare'] = $maxide['ideaPriceCompare'];
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
                            $di['disPriceCompare'] = $di['price'];
//                            $maxide['maxiPriceCompare'] = $maxide['maxiPriceCompare'];
//                            $maxide['ideaPriceCompare'] = $maxide['ideaPriceCompare'];
                            $di['imageUrl'] = $maxide['imageUrl'];
                            $di['maxiCena'] = $maxiCena;
                            if (!in_array($di['barcodes'], array_column($maxiIdeaDis, 'barcodes'))) {
                                array_push($maxiIdeaDis, $di);
                            }
                        }
                    }
                }
            }

            $maxiIdeaDisUni = [];
            if(!empty($maxiIdeaDis)) {
                foreach ($univer as $uni) {
                    foreach ($maxiIdeaDis as $maxidedis) {
                        if (explode(',', $uni['barcodes']) == explode(',', $maxidedis['barcodes'])) {
                            if ($uni['price'] >= $maxidedis['price']) {

                                $maxidedis[$uni['shop'] . 'Cena'] = str_replace('.', ',', $uni['formattedPrice']);
                                if (!in_array($maxidedis['barcodes'], array_column($maxiIdeaDisUni, 'barcodes'))) {
                                    array_push($maxiIdeaDisUni, $maxidedis);
                                }
                            } else {
                                $uni['univerCena'] = str_replace('.', ',', $uni['formattedPrice']);

                                if (!in_array($uni['barcodes'], array_column($maxiIdeaDisUni, 'barcodes'))) {
                                    array_push($maxiIdeaDisUni, $uni);
                                }
                            }
                        }
                    }
                }
            }

            if(!empty($maxiIdeaDisUni)) return $maxiIdeaDisUni;

            if(!empty($maxiIdeaDis)) return $maxiIdeaDis;

            return $maxiIdea;

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
            }elseif ($this->shop == 'univerexport'){
                return univerexport_drink::orderBy('price', $this->sort)->get();
            }
        });

        return $cache;
    }
}
