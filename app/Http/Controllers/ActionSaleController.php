<?php

namespace App\Http\Controllers;

use App\dis_drink;
use App\dis_freeze;
use App\dis_meat;
use App\dis_sweet;
use App\univerexport_action_sale;
use App\univerexport_drink;
use App\univerexport_freeze;
use App\univerexport_meats;
use App\univerexport_sweets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\action_sale;
use App\drink;
use App\Meat;
use App\Sweets;
use App\Freeze;
use App\dis_action_sale;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ActionSaleController extends Controller
{
    public $checkIdeaMaxi = [];
    public $checkIdeaMaxiDis = [];
    public $checkIdeaMaxiDisUni = [];
    public $maxiIdea = [];
    public $maxiIdeaDis = [];
    public $maxiIdeaDisUni = [];

    public function index()
    {
        ini_set('max_execution_time', '500');
        $expiresAt = Carbon::now()->endOfDay()->subHour()->addMinutes(30);
//        Cache::forget('maxiIdeaDisSale');
//        $cache = Cache::remember('maxiIdeaDisSale', 10, function () {
        $cache = Cache::rememberForever('maxiIdeaDisSale', function () {

            $maxiSales = action_sale::where('shop', 'maxi')->where('category', 'akcija')->whereNotNull('barcodes')->get();
            $ideaSales = action_sale::where('shop', 'idea')->where('category', 'akcija')->get();
            $disSales = dis_action_sale::orderBy('price', 'DESC')->get();
            $univerSales = univerexport_action_sale::where('deleted', 0)->orderBy('price', 'DESC')->get();


//            $maxi= [['barcodes' =>'2501011010845,8600995140020','price'=>200,'shop'=>'maxi'],['barcodes' =>'2501011010846,8600995140021','price'=>200,'shop'=>'maxi']];
//            $idea= [['barcodes' =>'2501011010845,2501011010844,2501011010843,8600995140020','price'=>400,'shop'=>'idea'],['barcodes' =>'2501011010855,2501011010844,2501011010843,8600995140020','price'=>400,'shop'=>'idea']];

            /**
             *  check if maxi sales articles barcodes exists in every idea category
             */
            /*foreach ($maxiSales as $max) {
                //foreach ($idea as $ide) {
                //$barcodesIdea = explode(',', $ide['barcodes']);
                $barcodesMaxi = explode(',', $max['barcodes']);
//                    foreach ($barcodesIdea as $barIde) {
                foreach ($barcodesMaxi as $barMax) {

                    $idea = action_sale::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray();

                    if (!empty($idea)) {

                        $this->checkIfMaxiBarcodeExistsInIdeaMarket($idea, $max);

                    } elseif (!empty(Sweets::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray())) {

                        $ideaSweets = Sweets::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiBarcodeExistsInIdeaMarket($ideaSweets, $max);

                    } elseif (!empty(Freeze::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray())) {

                        $ideaFreeze = Freeze::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiBarcodeExistsInIdeaMarket($ideaFreeze, $max);

                    } elseif (!empty(drink::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray())) {

                        $ideaDrink = drink::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiBarcodeExistsInIdeaMarket($ideaDrink, $max);

                    } elseif (!empty(Meat::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray())) {

                        $ideaMeat = Meat::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiBarcodeExistsInIdeaMarket($ideaMeat, $max);

                    }
                }
            }*/
            /**
             *  check if idea sales articles barcodes exists in every maxi category
             */
            /*foreach ($ideaSales as $ide) {
                $barcodesIdea = explode(',', $ide['barcodes']);
                foreach ($barcodesIdea as $barIde) {
                    $maxi = action_sale::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray();
                    if (!empty($maxi)) {

                        $this->checkIfIdeaBarcodeExistsInMaxiMarket($maxi, $ide);

                    } elseif (!empty(Sweets::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray())) {

                        $maxiSweets = Sweets::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfIdeaBarcodeExistsInMaxiMarket($maxiSweets, $ide);

                    } elseif (!empty(Freeze::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray())) {

                        $maxiFreeze = Freeze::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfIdeaBarcodeExistsInMaxiMarket($maxiFreeze, $ide);

                    } elseif (!empty(drink::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray())) {

                        $maxiDrink = drink::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfIdeaBarcodeExistsInMaxiMarket($maxiDrink, $ide);

                    } elseif (!empty(Meat::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray())) {

                        $maxiMeat = Meat::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfIdeaBarcodeExistsInMaxiMarket($maxiMeat, $ide);

                    }
                }
            }*/
            $this->maxiIdea = $this->compareDynamically();
            /**
             *  check if maxi/idea sales articles barcodes exists in every dis category
             */
            foreach ($this->maxiIdea as $maxide) {
                $barcodesMaxiIde = explode(',', $maxide['barcodes']);
                foreach ($barcodesMaxiIde as $barMaxIde) {
                    $dis = dis_action_sale::where('barcodes', 'LIKE', "%$barMaxIde%")->whereNotNull('barcodes')->get()->toArray();
                    if (!empty($dis)) {

                        $this->checkIfMaxiIdeaBarcodeExistsInDisMarket($dis, $maxide);

                    } elseif (!empty(dis_sweet::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                        $disSweets = dis_sweet::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiIdeaBarcodeExistsInDisMarket($disSweets, $maxide);

                    } elseif (!empty(dis_freeze::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                        $disFreeze = dis_freeze::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiIdeaBarcodeExistsInDisMarket($disFreeze, $maxide);

                    } elseif (!empty(dis_drink::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                        $disDrink = dis_drink::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiIdeaBarcodeExistsInDisMarket($disDrink, $maxide);

                    } elseif (!empty(dis_meat::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                        $disMeat = dis_meat::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiIdeaBarcodeExistsInDisMarket($disMeat, $maxide);

                    }
                }
            }
            //die;
            /**
             *  check if dis sales articles barcodes exists in every maxi/idea category
             */
            foreach ($disSales as $di) {
                if (!in_array($di['body'], $this->checkIdeaMaxiDis)) {
                    $barcodesDis = explode(',', $di['barcodes']);
                    foreach ($barcodesDis as $barDis) {
                        $idea = action_sale::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray();
                        $maxi = action_sale::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray();

                        if (!empty($idea) or !empty($maxi)) {

                            $this->checkIfDisBarcodeExistsInMaxiIdeaMarket($idea, $maxi, $di);

                        } elseif (!empty(Sweets::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(Sweets::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray())) {

                            $ideaSweets = Sweets::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray();
                            $maxiSweets = Sweets::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray();
                            $this->checkIfDisBarcodeExistsInMaxiIdeaMarket($ideaSweets, $maxiSweets, $di);

                        } elseif (!empty(Freeze::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(Freeze::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray())) {

                            $ideaFreeze = Freeze::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray();
                            $maxiFreeze = Freeze::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray();
                            $this->checkIfDisBarcodeExistsInMaxiIdeaMarket($ideaFreeze, $maxiFreeze, $di);

                        } elseif (!empty(drink::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(drink::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray())) {

                            $ideaDrink = drink::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray();
                            $maxiDrink = drink::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray();
                            $this->checkIfDisBarcodeExistsInMaxiIdeaMarket($ideaDrink, $maxiDrink, $di);

                        } elseif (!empty(Meat::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(Meat::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray())) {

                            $ideaMeat = Meat::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray();
                            $maxiMeat = Meat::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barDis%")->whereNotNull('barcodes')->get()->toArray();
                            $this->checkIfDisBarcodeExistsInMaxiIdeaMarket($ideaMeat, $maxiMeat, $di);

                        }
                    }
                }
            }

            /*foreach ($disSales as $di) {
                foreach ($this->maxiIdea as $maxide) {
                    $barcodesDis = explode(',', $di['barcodes']);
                    $barcodesMaxiIde = explode(',', $maxide['barcodes']);
                    foreach ($barcodesDis as $barDis) {
                        foreach ($barcodesMaxiIde as $barMaxIde) {
                            if ($barDis == $barMaxIde) {

//                                if (!$maxide['ideaCena']) {
//                                    $maxide['ideaCena'] = $maxide['formattedPrice'];
//                                }
//
//                                if (!$maxide['maxiCena']) {
//                                    $maxide['maxiCena'] = $maxide['formattedPrice'];
//                                }

                                $maxide[$di['shop'] . 'Cena'] = $di['formattedPrice'];
                                $maxide[$di['shop'] . 'OldPrice'] = $di['oldPrice'];
                                $maxide['disPriceCompare'] = $di['price'];
//                            $maxide['maxiPriceCompare'] = $maxide['maxiPriceCompare'];
//                            $maxide['ideaPriceCompare'] = $maxide['ideaPriceCompare'];
                                if (!in_array($maxide['barcodes'], array_column($this->maxiIdeaDis, 'barcodes'))) {
                                    array_push($this->maxiIdeaDis, $maxide);
                                }
                            }
                        }
                    }
                }
            }*/

//            return array_map("unserialize", array_unique(array_map("serialize", $this->maxiIdeaDis)));

//            if (!empty($maxiIdeaDis)) {
//                foreach ($univerSales as $uni) {
//                    foreach ($maxiIdeaDis as $maxidedis) {
//                        $barcodesUni = explode(',', $uni['barcodes']);
//                        $barcodesMaxiIdeDis = explode(',', $maxidedis['barcodes']);
//                        foreach ($barcodesUni as $barUni) {
//                            foreach ($barcodesMaxiIdeDis as $barMaxIdeDis) {
//                                if ($barUni == $barMaxIdeDis) {
//                                    //if ($uni['price'] >= $maxidedis['price']) {
//
//                                    $maxidedis[$uni['shop'] . 'Cena'] = str_replace('.', ',', $uni['formattedPrice']);
//                                    $maxidedis[$uni['shop'] . 'OldPrice'] = $uni['oldPrice'];
//                                    $maxidedis['supplementaryPriceUniver2'] = $uni['supplementaryPriceLabel2'];
//                                    //if (!in_array($maxidedis['barcodes'], array_column($maxiIdeaDisUni, 'barcodes'))) {
//                                    array_push($maxiIdeaDisUni, $maxidedis);
//                                    //}
////                                    } else {
////                                        $uni['univerCena'] = str_replace('.', ',', $uni['formattedPrice']);
////
////                                        if (!in_array($uni['barcodes'], array_column($maxiIdeaDisUni, 'barcodes'))) {
////                                            array_push($maxiIdeaDisUni, $uni);
////                                        }
////                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }

            foreach ($this->maxiIdeaDis as $maxidedis) {
                $barcodesMaxiIdeaDis = explode(',', $maxidedis['barcodes']);
                foreach ($barcodesMaxiIdeaDis as $barMaxIdeDis) {
                    $uni = univerexport_action_sale::where('barcodes', 'LIKE', "%$barMaxIdeDis%")->whereNotNull('barcodes')->get()->toArray();
                    if (!empty($uni)) {

                        $this->checkIfMaxiIdeaDisBarcodeExistsInUniMarket($uni, $maxidedis);

                    } elseif (!empty(univerexport_sweets::where('barcodes', 'LIKE', "%$barMaxIdeDis%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                        $uniSweets = univerexport_sweets::where('barcodes', 'LIKE', "%$barMaxIdeDis%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiIdeaDisBarcodeExistsInUniMarket($uniSweets, $maxidedis);

                    } elseif (!empty(univerexport_freeze::where('barcodes', 'LIKE', "%$barMaxIdeDis%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                        $uniFreeze = univerexport_freeze::where('barcodes', 'LIKE', "%$barMaxIdeDis%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiIdeaDisBarcodeExistsInUniMarket($uniFreeze, $maxidedis);

                    } elseif (!empty(univerexport_drink::where('barcodes', 'LIKE', "%$barMaxIdeDis%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                        $uniDrink = univerexport_drink::where('barcodes', 'LIKE', "%$barMaxIdeDis%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiIdeaDisBarcodeExistsInUniMarket($uniDrink, $maxidedis);

                    } elseif (!empty(univerexport_meats::where('barcodes', 'LIKE', "%$barMaxIdeDis%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                        $uniMeat = univerexport_meats::where('barcodes', 'LIKE', "%$barMaxIdeDis%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiIdeaDisBarcodeExistsInUniMarket($uniMeat, $maxidedis);

                    }
                }
            }

            foreach ($univerSales as $uni) {
                if (!in_array($uni['body'], $this->checkIdeaMaxiDisUni)) {
                    $barcodesUni = explode(',', $uni['barcodes']);
                    foreach ($barcodesUni as $barUni) {
                        $idea = action_sale::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();
                        $maxi = action_sale::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();
                        $dis = dis_action_sale::where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();

                        if (!empty($idea) or !empty($maxi) or !empty($dis)) {

                            $this->checkIfUniBarcodeExistsInMaxiIdeaDisMarket($idea, $maxi, $dis, $uni);

                        } elseif (!empty(Sweets::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(Sweets::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(dis_sweet::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                            $ideaSweets = Sweets::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();
                            $maxiSweets = Sweets::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();
                            $disSweets = dis_sweet::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                            $this->checkIfUniBarcodeExistsInMaxiIdeaDisMarket($ideaSweets, $maxiSweets, $disSweets, $uni);

                        } elseif (!empty(Freeze::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(Freeze::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(dis_freeze::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                            $ideaFreeze = Freeze::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();
                            $maxiFreeze = Freeze::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();
                            $disFreeze = dis_freeze::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                            $this->checkIfUniBarcodeExistsInMaxiIdeaDisMarket($ideaFreeze, $maxiFreeze, $disFreeze, $uni);

                        } elseif (!empty(drink::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(drink::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(dis_drink::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                            $ideaDrink = drink::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();
                            $maxiDrink = drink::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();
                            $disDrink = dis_drink::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                            $this->checkIfUniBarcodeExistsInMaxiIdeaDisMarket($ideaDrink, $maxiDrink, $disDrink, $uni);

                        } elseif (!empty(Meat::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(Meat::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray())
                            or !empty(dis_meat::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray())) {

                            $ideaMeat = Meat::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();
                            $maxiMeat = Meat::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barUni%")->whereNotNull('barcodes')->get()->toArray();
                            $disMeat = dis_meat::where('barcodes', 'LIKE', "%$barMaxIde%")->where('deleted', 0)->whereNotNull('barcodes')->get()->toArray();
                            $this->checkIfUniBarcodeExistsInMaxiIdeaDisMarket($ideaMeat, $maxiMeat, $disMeat, $uni);

                        }
                    }
                }
            }

            if (!empty($this->maxiIdeaDisUni)) return array_map("unserialize", array_unique(array_map("serialize", $this->maxiIdeaDisUni)));

            if (!empty($this->maxiIdeaDis)) return array_map("unserialize", array_unique(array_map("serialize", $this->maxiIdeaDis)));

            return array_map("unserialize", array_unique(array_map("serialize", $this->maxiIdea)));

        });

        return $cache;
    }

    protected function checkIfMaxiBarcodeExistsInIdeaMarket($category, $max)
    {
        if (!empty($category)) {
            foreach ($category as $cat) {
                if (!in_array($cat['body'], $this->checkIdeaMaxi)) {
                    array_push($this->checkIdeaMaxi, $cat['body']);
//                            var_dump('2',$max['barcodes']);
                    $cat['maxiCena'] = $max['formattedPrice'];
                    if (!isset($max['oldPrice'])) {
                        $cat['maxiOldPrice'] = null;
                        $cat['toDateMaxi'] = 'nedostupno';
                    } else {
                        $cat['maxiOldPrice'] = $max['oldPrice'];
                        $cat['toDateMaxi'] = $max['toDate'];
                    }
                    $cat['maxiBarcodes'] = $max['barcodes'];
//                            $ide['barcodes'] = $max['barcodes'].','.$ide['barcodes'];
                    $cat['supplementaryPriceMaxi'] = $max['supplementaryPriceLabel1'];
                    $cat['supplementaryPriceMaxi2'] = $max['supplementaryPriceLabel2'];
                    $cat['ideaCena'] = $cat['formattedPrice'];
                    $cat['supplementaryPriceIdea'] = $cat['supplementaryPriceLabel1'];
                    $cat['supplementaryPriceIdea2'] = $cat['supplementaryPriceLabel2'];
                    if (!isset($cat['oldPrice'])) {
                        $cat['ideaOldPrice'] = null;
                        $cat['toDateIdea'] = 'nedostupno';
                    } else {
                        $cat['ideaOldPrice'] = $cat['oldPrice'];
                        $cat['toDateIdea'] = $cat['toDate'];
                    }
                    if ($max['imageUrl'] != null) {
                        $cat['imageUrl'] = 'https://d3el976p2k4mvu.cloudfront.net' . $max['imageUrl'];
                    } else {
                        $cat['imageUrl'] = $cat['imageDefault'];
                    }
                    array_push($this->maxiIdea, $cat);
                }
            }
        }
    }

    protected function checkIfIdeaBarcodeExistsInMaxiMarket($category, $ide)
    {
        if (!empty($category)) {
            foreach ($category as $cat) {
                if (!in_array($ide['body'], $this->checkIdeaMaxi)) {
                    array_push($this->checkIdeaMaxi, $ide['body']);
                    $ide['maxiCena'] = $cat['formattedPrice'];
                    if (!isset($cat['oldPrice'])) {
                        $ide['maxiOldPrice'] = null;
                        $ide['toDateMaxi'] = 'nedostupno';
                    } else {
                        $ide['maxiOldPrice'] = $cat['oldPrice'];
                        $ide['toDateMaxi'] = $cat['toDate'];
                    }
                    $ide['maxiBarcodes'] = $cat['barcodes'];
//                            $ide['barcodes'] = $max['barcodes'].','.$ide['barcodes'];
                    $ide['supplementaryPriceMaxi'] = $cat['supplementaryPriceLabel1'];
                    $ide['supplementaryPriceMaxi2'] = $cat['supplementaryPriceLabel2'];
                    $ide['ideaCena'] = $ide['formattedPrice'];
                    $ide['supplementaryPriceIdea'] = $ide['supplementaryPriceLabel1'];
                    $ide['supplementaryPriceIdea2'] = $ide['supplementaryPriceLabel2'];
                    if (!isset($ide['oldPrice'])) {
                        $ide['ideaOldPrice'] = null;
                        $ide['toDateIdea'] = 'nedostupno';
                    } else {
                        $ide['ideaOldPrice'] = $ide['oldPrice'];
                        $ide['toDateIdea'] = $ide['toDate'];
                    }
                    if ($cat['imageUrl'] != null) {
                        $ide['imageUrl'] = 'https://d3el976p2k4mvu.cloudfront.net' . $cat['imageUrl'];
                    } else {
                        $ide['imageUrl'] = $ide['imageDefault'];
                    }
                    array_push($this->maxiIdea, $ide);
                }
            }
        }
    }

    public function checkIfMaxiIdeaBarcodeExistsInDisMarket($category, $maxide)
    {
        if (!empty($category)) {
            foreach ($category as $cat) {
                if (!in_array($cat['body'], $this->checkIdeaMaxiDis)) {
                    //var_dump($cat['body']);
                    array_push($this->checkIdeaMaxiDis, $cat['body']);
                    $maxide[$cat['shop'] . 'Cena'] = $cat['formattedPrice'];

                    if (!isset($cat['oldPrice'])) {
                        $maxide[$cat['shop'] . 'OldPrice'] = null;
                    } else {
                        $maxide[$cat['shop'] . 'OldPrice'] = $cat['oldPrice'];
                    }

                    $maxide['disPriceCompare'] = $cat['price'];
                    array_push($this->maxiIdeaDis, $maxide);
                }
            }
        }
    }

    public function checkIfDisBarcodeExistsInMaxiIdeaMarket($categoryIdea, $categoryMaxi, $dis)
    {
        if (!empty($categoryIdea) or !empty($categoryMaxi)) {
            foreach ($categoryIdea as $catIdea) {
                foreach ($categoryMaxi as $catMaxi) {
//                if (!in_array($dis['body'], $this->checkIdeaMaxiDis)) {
                    array_push($this->checkIdeaMaxiDis, $dis['body']);

                    if (isset($dis['oldPrice'])) {
                        $dis['disOldPrice'] = $dis['oldPrice'];
                    } else {
                        $dis['disOldPrice'] = null;
                    }

                    $dis['disCena'] = $dis['formattedPrice'];

                    if (isset($catIdea['formattedPrice'])) {
                        $dis['ideaCena'] = $catIdea['formattedPrice'];
                    } else {
                        $dis['ideaCena'] = null;
                    }

                    if (isset($catMaxi['formattedPrice'])) {
                        $dis['maxiCena'] = $catMaxi['formattedPrice'];
                    } else {
                        $dis['maxiCena'] = null;
                    }

                    if (!isset($catIdea['oldPrice'])) {
                        $dis[$catIdea['shop'] . 'OldPrice'] = null;
                        $dis['toDateIdea'] = 'nedostupno';
                    } else {
                        $dis[$catIdea['shop'] . 'OldPrice'] = $catIdea['oldPrice'];
                        $dis['toDateIdea'] = $catIdea['toDate'];
                    }

                    if (!isset($catMaxi['oldPrice'])) {
                        $dis[$catMaxi['shop'] . 'OldPrice'] = null;
                        $dis['toDateMaxi'] = 'nedostupno';
                    } else {
                        $dis[$catMaxi['shop'] . 'OldPrice'] = $catMaxi['oldPrice'];
                        $dis['toDateMaxi'] = $catMaxi['toDate'];
                    }

                    if (isset($catIdea['supplementaryPriceLabel1'])) {
                        $dis['supplementaryPriceMaxi'] = $catIdea['supplementaryPriceLabel1'];
                        $dis['supplementaryPriceMaxi2'] = $catIdea['supplementaryPriceLabel2'];
                    } else {
                        $dis['supplementaryPriceMaxi'] = null;
                        $dis['supplementaryPriceMaxi2'] = null;
                    }

                    if (isset($catMaxi['supplementaryPriceLabel1'])) {
                        $dis['supplementaryPriceIdea'] = $catMaxi['supplementaryPriceLabel1'];
                        $dis['supplementaryPriceIdea2'] = $catMaxi['supplementaryPriceLabel2'];
                    } else {
                        $dis['supplementaryPriceIdea'] = null;
                        $dis['supplementaryPriceIdea2'] = null;
                    }

                    if ($catMaxi['imageUrl'] != null) {
                        $dis['imageUrl'] = 'https://d3el976p2k4mvu.cloudfront.net' . $catMaxi['imageUrl'];
                    } else {
                        $dis['imageUrl'] = $catMaxi['imageDefault'];
                    }

                    array_push($this->maxiIdeaDis, $dis);
//                }
                }
            }
        }
    }

    public function checkIfMaxiIdeaDisBarcodeExistsInUniMarket($category, $maxidedis)
    {
        if (!empty($category)) {
            foreach ($category as $cat) {
                if (!in_array($cat['body'], $this->checkIdeaMaxiDisUni)) {
                    //var_dump($cat['body']);
                    array_push($this->checkIdeaMaxiDisUni, $cat['body']);
//                    $maxidedis[$cat['shop'] . 'Cena'] = str_replace('.', ',', $cat['formattedPrice']);
                    $maxidedis[$cat['shop'] . 'Cena'] = $cat['formattedPrice'];
                    if (!isset($cat['oldPrice'])) {
                        $maxidedis[$cat['shop'] . 'OldPrice'] = null;
                    } else {
                        $maxidedis[$cat['shop'] . 'OldPrice'] = $cat['oldPrice'];
                    }
                    $maxidedis['supplementaryPriceUniver2'] = $cat['supplementaryPriceLabel2'];
                    array_push($this->maxiIdeaDisUni, $maxidedis);
                }
            }
        }
    }

    public function checkIfUniBarcodeExistsInMaxiIdeaDisMarket($categoryIdea, $categoryMaxi, $categoryDis, $uni)
    {
        if (!empty($categoryIdea) or !empty($categoryMaxi) or !empty($categoryDis)) {
            foreach ($categoryIdea as $catIdea) {
                foreach ($categoryMaxi as $catMaxi) {
                    foreach ($categoryDis as $catDis) {
//                if (!in_array($dis['body'], $this->checkIdeaMaxiDis)) {
                        array_push($this->checkIdeaMaxiDisUni, $uni['body']);

                        if (isset($uni['oldPrice'])) {
                            $uni['univerexportOldPrice'] = $uni['oldPrice'];
                        } else {
                            $uni['univerexportOldPrice'] = null;
                        }

                        $uni['supplementaryPriceUniver2'] = $uni['supplementaryPrice2'];

                        $uni['univerexportCena'] = $uni['formattedPrice'];

                        if (isset($catDis['oldPrice'])) {
                            $uni['disOldPrice'] = $catDis['oldPrice'];
                        } else {
                            $uni['disOldPrice'] = null;
                        }

                        if(isset($catDis['formattedPrice'])){
                            $uni['disCena'] = $catDis['formattedPrice'];
                        }else{
                            $uni['disCena'] = null;
                        }

                        if (isset($catIdea['formattedPrice'])) {
                            $uni['ideaCena'] = $catIdea['formattedPrice'];
                        } else {
                            $uni['ideaCena'] = null;
                        }

                        if (isset($catMaxi['formattedPrice'])) {
                            $uni['maxiCena'] = $catMaxi['formattedPrice'];
                        } else {
                            $uni['maxiCena'] = null;
                        }

                        if (!isset($catIdea['oldPrice'])) {
                            $uni[$catIdea['shop'] . 'OldPrice'] = null;
                            $uni['toDateIdea'] = 'nedostupno';
                        } else {
                            $uni[$catIdea['shop'] . 'OldPrice'] = $catIdea['oldPrice'];
                            $uni['toDateIdea'] = $catIdea['toDate'];
                        }

                        if (!isset($catMaxi['oldPrice'])) {
                            $uni[$catMaxi['shop'] . 'OldPrice'] = null;
                            $uni['toDateMaxi'] = 'nedostupno';
                        } else {
                            $uni[$catMaxi['shop'] . 'OldPrice'] = $catMaxi['oldPrice'];
                            $uni['toDateMaxi'] = $catMaxi['toDate'];
                        }

                        if (isset($catIdea['supplementaryPriceLabel1'])) {
                            $uni['supplementaryPriceMaxi'] = $catIdea['supplementaryPriceLabel1'];
                            $uni['supplementaryPriceMaxi2'] = $catIdea['supplementaryPriceLabel2'];
                        } else {
                            $uni['supplementaryPriceMaxi'] = null;
                            $uni['supplementaryPriceMaxi2'] = null;
                        }

                        if (isset($catMaxi['supplementaryPriceLabel1'])) {
                            $uni['supplementaryPriceIdea'] = $catMaxi['supplementaryPriceLabel1'];
                            $uni['supplementaryPriceIdea2'] = $catMaxi['supplementaryPriceLabel2'];
                        } else {
                            $uni['supplementaryPriceIdea'] = null;
                            $uni['supplementaryPriceIdea2'] = null;
                        }

                        if ($catMaxi['imageUrl'] != null) {
                            $uni['imageUrl'] = 'https://d3el976p2k4mvu.cloudfront.net' . $catMaxi['imageUrl'];
                        } else {
                            $uni['imageUrl'] = $catMaxi['imageDefault'];
                        }

                        array_push($this->maxiIdeaDis, $uni);
//                }
                    }
                }
            }
        }
    }

    public function store(Request $request)
    {
        $imageUrl = null;
        $imageDefault = null;
        $storeRecords = [];
        $arrayLengt = sizeof($request->products[0]) - 1;

        if ($request->shop == 'idea') {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                $imageUrl = null;
                if (array_key_exists('images', $request->products[0][$i]) && !empty($request->products[0][$i]['images'])) {
                    $imageUrl = $request->products[0][$i]['images'][0]["image_n"];
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                array_push($storeRecords, ['code' => $request->products[0][$i]['code'], 'title' => $request->products[0][$i]['manufacturer'],
                    'body' => $request->products[0][$i]['name'], 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => implode(',', $request->products[0][$i]['barcodes']),
                    'formattedPrice' => $request->products[0][$i]['price']['formatted_price'], 'price' => $request->products[0][$i]['price']['amount'],
                    'supplementaryPriceLabel1' => $request->products[0][$i]['statistical_price'], 'supplementaryPriceLabel2' => null, 'shop' => $request->shop, 'category' => $request->category]);
            }
        } elseif ($request->category == 'akcija' && $request->shop == 'maxi') {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                $imageUrl = null;
                if (array_key_exists('images', $request->products[0][$i]) && !empty($request->products[0][$i]['images']) && array_key_exists('2', $request->products[0][$i]['images'])) {
                    $imageUrl = $request->products[0][$i]['images'][2]['url'];
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                preg_match_all('!\d+!', $request->products[0][$i]['potentialPromotions'][0]['description'], $matches);

                foreach ($matches as $match) {
                    $count = sizeof($match);

                    if ($count == 2) {
                        $formattedPrice = $match[1] . ',00 RSD';
                        $string = $match[1] . '00';
                        $price = (int)$string;
                    } elseif ($count == 3) {
                        $formattedPrice = $match[1] . ',' . $match[2] . ' RSD';
                        $string = $match[1] . $match[2];
                        $price = (int)$string;
                    }
                }

                //var_dump(implode(',', (array)$request->products[0][$i]['eanCodes'])); continue;

                array_push($storeRecords, ['code' => $request->products[0][$i]['code'], 'title' => $request->products[0][$i]['manufacturerName'],
                    'body' => $request->products[0][$i]['name'], 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => implode(',', (array)$request->products[0][$i]['eanCodes']),
                    'formattedPrice' => $formattedPrice, 'price' => $price,
                    'supplementaryPriceLabel1' => $request->products[0][$i]['price']['supplementaryPriceLabel1'],
                    'supplementaryPriceLabel2' => $request->products[0][$i]['price']['supplementaryPriceLabel2'], 'shop' => $request->shop, 'category' => $request->category]);
            }
        } else {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                $imageUrl = null;
                if (array_key_exists('images', $request->products[0][$i]) && !empty($request->products[0][$i]['images']) && array_key_exists('2', $request->products[0][$i]['images'])) {
                    $imageUrl = $request->products[0][$i]['images'][2]['url'];
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                if (array_key_exists('eanCodes', $request->products[0][$i]) && !empty($request->products[0][$i]['eanCodes'])) {
                    $barcode = implode(',', $request->products[0][$i]['eanCodes']);
                } else {
                    $barcode = null;
                }

                /*$pricesub = substr($request->products[0][$i]['price']['formattedValue'], 0, -4);

                $subs = substr($pricesub, -3);*/

                if ($request->products[0][$i]['price']['fractionValue'] == '00') {
                    $price = $request->products[0][$i]['price']['value'] . $request->products[0][$i]['price']['fractionValue'];
                } else {
                    $price = str_replace('.', '', $request->products[0][$i]['price']['value']);
                }

                array_push($storeRecords, ['code' => $request->products[0][$i]['code'], 'title' => $request->products[0][$i]['manufacturerName'],
                    'body' => $request->products[0][$i]['name'], 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => $barcode,
                    'formattedPrice' => $request->products[0][$i]['price']['formattedValue'], 'price' => $price,
                    'supplementaryPriceLabel1' => $request->products[0][$i]['price']['supplementaryPriceLabel1'],
                    'supplementaryPriceLabel2' => $request->products[0][$i]['price']['supplementaryPriceLabel2'], 'shop' => $request->shop, 'category' => $request->category]);
            }

//            array_push($storeRecords, ['code'=>'7176748', 'title' => 'Schauma', 'body' => 'Sampon za decu Schauma Mermaid 250ml', 'imageUrl' => '/medias/sys_master/h83/h36/8829669539870.png', 'imageDefault' => null, 'barcodes' => '3838905551948,3838905551955,2401000202056',
//                'formattedPrice' => '269,99 RSD', 'price' => '555.99', 'supplementaryPriceLabel1' => '1079.96 rsd/L', 'supplementaryPriceLabel2' => '250 ml', 'shop' => 'maxi']);
        }

        if ($request->category == 'akcija') {

            if ($request->shop == 'idea') {
                action_sale::where('shop', 'idea')->delete();
            } else {
                action_sale::where('shop', 'maxi')->delete();
            }

            foreach ($storeRecords as $record) {
                $article = action_sale::firstOrNew(array('code' => $record['code']));
                $article->code = $record['code'];
                $article->title = $record['title'];
                $article->body = $record['body'];
                $article->category = $record['category'];
                $article->imageUrl = $record['imageUrl'];
                $article->imageDefault = $record['imageDefault'];
                $article->barcodes = $record['barcodes'];
                $article->formattedPrice = $record['formattedPrice'];
                $article->price = $record['price'];
                $article->supplementaryPriceLabel1 = $record['supplementaryPriceLabel1'];
                $article->supplementaryPriceLabel2 = $record['supplementaryPriceLabel2'];
                $article->shop = $record['shop'];
                $article->save();
            }
        } elseif ($request->category == 'pice') {
            if ($request->shop == 'idea') {
                drink::where('shop', 'idea')->delete();
            } else {
                drink::where('shop', 'maxi')->delete();
            }

            foreach ($storeRecords as $record) {
                $article = drink::firstOrNew(array('code' => $record['code']));
                $article->code = $record['code'];
                $article->title = $record['title'];
                $article->body = $record['body'];
                $article->category = $record['category'];
                $article->imageUrl = $record['imageUrl'];
                $article->imageDefault = $record['imageDefault'];
                $article->barcodes = $record['barcodes'];
                $article->formattedPrice = $record['formattedPrice'];
                $article->price = $record['price'];
                $article->supplementaryPriceLabel1 = $record['supplementaryPriceLabel1'];
                $article->supplementaryPriceLabel2 = $record['supplementaryPriceLabel2'];
                $article->shop = $record['shop'];
                $article->save();
            }
        } elseif ($request->category == 'meso') {
            if ($request->shop == 'idea') {
                Meat::where('shop', 'idea')->delete();
            } else {
                Meat::where('shop', 'maxi')->delete();
            }

            foreach ($storeRecords as $record) {
                $article = Meat::firstOrNew(array('code' => $record['code']));
                $article->code = $record['code'];
                $article->title = $record['title'];
                $article->body = $record['body'];
                $article->category = $record['category'];
                $article->imageUrl = $record['imageUrl'];
                $article->imageDefault = $record['imageDefault'];
                $article->barcodes = $record['barcodes'];
                $article->formattedPrice = $record['formattedPrice'];
                $article->price = $record['price'];
                $article->supplementaryPriceLabel1 = $record['supplementaryPriceLabel1'];
                $article->supplementaryPriceLabel2 = $record['supplementaryPriceLabel2'];
                $article->shop = $record['shop'];
                $article->save();
            }
        } elseif ($request->category == 'slatkisi') {
            if ($request->shop == 'idea') {
                Sweets::where('shop', 'idea')->delete();
            } else {
                Sweets::where('shop', 'maxi')->delete();
            }

            foreach ($storeRecords as $record) {
                $article = Sweets::firstOrNew(array('code' => $record['code']));
                $article->code = $record['code'];
                $article->title = $record['title'];
                $article->body = $record['body'];
                $article->category = $record['category'];
                $article->imageUrl = $record['imageUrl'];
                $article->imageDefault = $record['imageDefault'];
                $article->barcodes = $record['barcodes'];
                $article->formattedPrice = $record['formattedPrice'];
                $article->price = $record['price'];
                $article->supplementaryPriceLabel1 = $record['supplementaryPriceLabel1'];
                $article->supplementaryPriceLabel2 = $record['supplementaryPriceLabel2'];
                $article->shop = $record['shop'];
                $article->save();
            }
        } elseif ($request->category == 'smrznuti') {
            if ($request->shop == 'idea') {
                Freeze::where('shop', 'idea')->delete();
            } else {
                Freeze::where('shop', 'maxi')->delete();
            }

            foreach ($storeRecords as $record) {
                $article = Freeze::firstOrNew(array('code' => $record['code']));
                $article->code = $record['code'];
                $article->title = $record['title'];
                $article->body = $record['body'];
                $article->category = $record['category'];
                $article->imageUrl = $record['imageUrl'];
                $article->imageDefault = $record['imageDefault'];
                $article->barcodes = $record['barcodes'];
                $article->formattedPrice = $record['formattedPrice'];
                $article->price = $record['price'];
                $article->supplementaryPriceLabel1 = $record['supplementaryPriceLabel1'];
                $article->supplementaryPriceLabel2 = $record['supplementaryPriceLabel2'];
                $article->shop = $record['shop'];
                $article->save();
            }
        }
    }

    public function getView()
    {
        return view('frontend.actionSaleArticles')->with('showStore', false);
    }

    public function getSeparatedMarket(Request $request)
    {

        $this->shop = $request->shop;

        if ($request->sort == 'rastuce') {
            $this->sort = 'ASC';
        } else {
            $this->sort = 'DESC';
        }

        $cache = Cache::rememberForever($this->shop . 'Action' . $this->sort, function () {

            if ($this->shop == 'maxi') {
                return action_sale::where('shop', 'maxi')->orderBy('price', $this->sort)->get();
            } elseif ($this->shop == 'idea') {
                return action_sale::where('shop', 'idea')->orderBy('price', $this->sort)->get();
            } elseif ($this->shop == 'dis') {
                return dis_action_sale::orderBy('price', $this->sort)->get();
            } elseif ($this->shop == 'univerexport') {
                return univerexport_action_sale::orderBy('price', $this->sort)->get();
            }
        });

        return $cache;
    }

    public function compareDynamically(Request $request = null)
    {
        ini_set('max_execution_time', 600);
        $cached = Cache::rememberForever('ActionDvI', function () {

            $maxiSales = action_sale::where('shop', 'maxi')->where('category', 'akcija')->whereNotNull('barcodes')->get();
            $ideaSales = action_sale::where('shop', 'idea')->where('category', 'akcija')->get();
//            $dis = dis_action_sale::orderBy('price', 'DESC')->get();
//            $univer = univerexport_action_sale::where('deleted', 0)->orderBy('price', 'DESC')->get();

            /**
             *  check if maxi sales articles barcodes exists in every idea category
             */
            foreach ($maxiSales as $max) {
                $barcodesMaxi = explode(',', $max['barcodes']);
                foreach ($barcodesMaxi as $barMax) {

                    $idea = action_sale::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray();

                    if (!empty($idea)) {

                        $this->checkIfMaxiBarcodeExistsInIdeaMarket($idea, $max);

                    } elseif (!empty(Sweets::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray())) {

                        $ideaSweets = Sweets::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiBarcodeExistsInIdeaMarket($ideaSweets, $max);

                    } elseif (!empty(Freeze::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray())) {

                        $ideaFreeze = Freeze::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiBarcodeExistsInIdeaMarket($ideaFreeze, $max);

                    } elseif (!empty(drink::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray())) {

                        $ideaDrink = drink::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiBarcodeExistsInIdeaMarket($ideaDrink, $max);

                    } elseif (!empty(Meat::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray())) {

                        $ideaMeat = Meat::where('shop', 'idea')->where('barcodes', 'LIKE', "%$barMax%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfMaxiBarcodeExistsInIdeaMarket($ideaMeat, $max);

                    }
                }
            }
            /**
             *  check if idea sales articles barcodes exists in every maxi category
             */
            foreach ($ideaSales as $ide) {
                $barcodesIdea = explode(',', $ide['barcodes']);
                foreach ($barcodesIdea as $barIde) {
                    $maxi = action_sale::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray();
                    if (!empty($maxi)) {

                        $this->checkIfIdeaBarcodeExistsInMaxiMarket($maxi, $ide);

                    } elseif (!empty(Sweets::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray())) {

                        $maxiSweets = Sweets::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfIdeaBarcodeExistsInMaxiMarket($maxiSweets, $ide);

                    } elseif (!empty(Freeze::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray())) {

                        $maxiFreeze = Freeze::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfIdeaBarcodeExistsInMaxiMarket($maxiFreeze, $ide);

                    } elseif (!empty(drink::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray())) {

                        $maxiDrink = drink::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfIdeaBarcodeExistsInMaxiMarket($maxiDrink, $ide);

                    } elseif (!empty(Meat::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray())) {

                        $maxiMeat = Meat::where('shop', 'maxi')->where('barcodes', 'LIKE', "%$barIde%")->whereNotNull('barcodes')->get()->toArray();
                        $this->checkIfIdeaBarcodeExistsInMaxiMarket($maxiMeat, $ide);

                    }
                }
            }

            return array_map("unserialize", array_unique(array_map("serialize", $this->maxiIdea)));

        });

        return $cached;
    }
}
