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

        $cache = Cache::remember('maxiIdeaDisDrinks', 10, function () {
            $maxiIdea = [];
            $barcodesIdea = [];
            $barcodesMaxi = [];
            // Get articles
            $maxi = drink::where('shop', 'maxi')->where('category', 'pice')->whereNotNull('barcodes')->orderBy('price', 'DESC')->get();
            $idea = drink::where('shop', 'idea')->where('category', 'pice')->whereNotNull('barcodes')->orderBy('price', 'DESC')->get();
            $dis = dis_drink::orderBy('price', 'DESC')->get();
            $univer = univerexport_drink::orderBy('price', 'DESC')->get();


            foreach ($maxi as $max) {
                foreach ($idea as $ide) {
                    $barcodesIdea = explode(',', $ide['barcodes']);
                    $barcodesMaxi = explode(',', $max['barcodes']);
//                    if (explode(',', $ide['barcodes']) == explode(',', $max['barcodes'])) {
                    foreach ($barcodesIdea as $barIde) {
                        foreach ($barcodesMaxi as $barMax) {
                            if ($barIde == $barMax) {
                                $max['price'] = str_replace('.', '', $max['price']);
//                                if ($max['price'] >= $ide['price']) {
                                    $ide['maxiCena'] = $max['formattedPrice'];
                                    $ide['ideaCena'] = $ide['formattedPrice'];
                                    $ide['maxiPriceCompare'] = $max['price'];
                                    $ide['ideaPriceCompare'] = $ide['price'];
                                    if($max['imageUrl'] != null) {
                                        $ide['imageUrl'] = 'https://d3el976p2k4mvu.cloudfront.net'.$max['imageUrl'];
                                    }else{
                                        $ide['imageUrl'] = $ide['imageDefault'];
                                    }
                                    $ide['supplementaryPriceMaxi'] = $max['supplementaryPriceLabel1'];
                                    $ide['supplementaryPriceIdea'] = $ide['supplementaryPriceLabel1'];
                                    array_push($maxiIdea, $ide);
                                /*} else {
                                    $max['ideaCena'] = $ide['formattedPrice'];
                                    $max['maxiCena'] = $max['formattedPrice'];
                                    $max['ideaPriceCompare'] = $ide['price'];
                                    $max['maxiPriceCompare'] = $max['price'];
                                    $max['supplementaryPriceIdea'] = $ide['supplementaryPriceLabel1'];
                                    $max['supplementaryPriceMaxi'] = $max['supplementaryPriceLabel1'];
                                    array_push($maxiIdea, $max);
                                }*/
                            }
                        }
                    }
                }
            }

            $maxiIdeaDis = [];
            $barcodesMaxiIde = [];
            $barcodesDis = [];

            foreach ($dis as $di) {
                foreach ($maxiIdea as $maxide) {
                    $barcodesDis = explode(',', $di['barcodes']);
                    $barcodesMaxiIde = explode(',', $maxide['barcodes']);
                    foreach ($barcodesDis as $barDis) {
                        foreach ($barcodesMaxiIde as $barMaxIde) {
                            if ($barDis == $barMaxIde) {
                                //if ($di['price'] >= $maxide['price']) {

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
                                /*} else {
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
                                }*/
                            }
                        }
                    }
                }
            }



            $maxiIdeaDisUni = [];
            $barcodesMaxiIdeDis = [];
            $barcodesUni = [];
            if (!empty($maxiIdeaDis)) {
                foreach ($univer as $uni) {
                    foreach ($maxiIdeaDis as $maxidedis) {
                        $barcodesUni = explode(',', $uni['barcodes']);
                        $barcodesMaxiIdeDis = explode(',', $maxidedis['barcodes']);
                        foreach ($barcodesUni as $barUni) {
                            foreach ($barcodesMaxiIdeDis as $barMaxIdeDis) {
                                if ($barUni == $barMaxIdeDis) {
                                    //if ($uni['price'] >= $maxidedis['price']) {

                                        $maxidedis[$uni['shop'] . 'Cena'] = str_replace('.', ',', $uni['formattedPrice']);
                                        //if (!in_array($maxidedis['barcodes'], array_column($maxiIdeaDisUni, 'barcodes'))) {
                                            array_push($maxiIdeaDisUni, $maxidedis);
                                        //}
                                    /*} else {
                                        $uni['univerCena'] = str_replace('.', ',', $uni['formattedPrice']);

                                        if (!in_array($uni['barcodes'], array_column($maxiIdeaDisUni, 'barcodes'))) {
                                            array_push($maxiIdeaDisUni, $uni);
                                        }
                                    }*/
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($maxiIdeaDisUni)) return array_map("unserialize", array_unique(array_map("serialize", $maxiIdeaDisUni)));

            if (!empty($maxiIdeaDis)) return array_map("unserialize", array_unique(array_map("serialize", $maxiIdeaDis)));

            return array_map("unserialize", array_unique(array_map("serialize", $maxiIdea)));

        });

        return $cache;
    }

    public function getView()
    {
        return view('frontend.drinksArticles')->with('showStore', false);
    }

    public function getSeparatedMarket(Request $request)
    {

        $this->shop = $request->shop;

        if ($request->sort == 'rastuce') {
            $this->sort = 'ASC';
        } else {
            $this->sort = 'DESC';
        }

        $cache = Cache::remember($this->shop . 'Drinks' . $this->sort, 10, function () {

            if ($this->shop == 'maxi') {
                return drink::where('shop', 'maxi')->orderBy('price', $this->sort)->get();
            } elseif ($this->shop == 'idea') {
                return drink::where('shop', 'idea')->orderBy('price', $this->sort)->get();
            } elseif ($this->shop == 'dis') {
                return dis_drink::orderBy('price', $this->sort)->get();
            } elseif ($this->shop == 'univerexport') {
                return univerexport_drink::orderBy('price', $this->sort)->get();
            }
        });

        return $cache;
    }

    public function compareDynamically(Request $request)
    {

        $cached = Cache::remember('DrinkDvIs', 10, function () {

            $maxi = drink::where('shop', 'maxi')->where('category', 'pice')->whereNotNull('barcodes')->orderBy('price', 'DESC')->get();
            $idea = drink::where('shop', 'idea')->where('category', 'pice')->whereNotNull('barcodes')->orderBy('price', 'DESC')->get();

            $maxiIdea = [];

            foreach ($maxi as $max) {
                foreach ($idea as $ide) {
                    $barcodesIdea = explode(',', $ide['barcodes']);
                    $barcodesMaxi = explode(',', $max['barcodes']);
                    foreach ($barcodesIdea as $barIde) {
                        foreach ($barcodesMaxi as $barMax) {
                            if ($barIde == $barMax) {
                                $ide['maxiCena'] = $max['formattedPrice'];
                                $ide['ideaCena'] = $ide['formattedPrice'];
                                if($max['imageUrl'] != null) {
                                    $ide['imageUrl'] = 'https://d3el976p2k4mvu.cloudfront.net'.$max['imageUrl'];
                                }else{
                                    $ide['imageUrl'] = $ide['imageDefault'];
                                }
                                array_push($maxiIdea, $ide);
                            }
                        }
                    }
                }
            }

            return array_map("unserialize", array_unique(array_map("serialize", $maxiIdea)));

        });

        return $cached;
    }
}
