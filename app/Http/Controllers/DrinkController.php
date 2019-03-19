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

        $maxiIdea = Cache::remember('maxiIdeaDisDrink', 10, function () {
            $maxiIdea = [];
            // Get articles
            $maxi = drink::where('shop', 'maxi')->where('category', 'pice')->whereNotNull('barcodes')->get();
            $idea = drink::where('shop', 'idea')->where('category', 'pice')->whereNotNull('barcodes')->get();
            $dis = dis_drink::where('category', 'pice')->get();


            foreach ($maxi as $max) {
                foreach ($idea as $ide) {
                    $max['price'] = str_replace('.', '', $max['price']);
                    if (explode(',', $ide['barcodes']) == explode(',', $max['barcodes'])) {
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

        return $maxiIdea;
    }

    public function getView()
    {
        return view('frontend.drinksArticles')->with('showStore', false);
    }
}
