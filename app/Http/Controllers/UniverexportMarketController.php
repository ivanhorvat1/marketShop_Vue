<?php

namespace App\Http\Controllers;

use App\drink;
use App\dis_drink;
use App\univerexport;
use App\univerexport_action_sale;
use App\univerexport_drink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class UniverexportMarketController extends Controller
{
    public function getUniverexportMarketDrink()
    {
        //skip(1200)->
        $univerexportDrinks = univerexport::where('category', 'pice')->take(1200)->get();

        $database = [];
        foreach ($univerexportDrinks as $univerexport) {
            $explo = explode(' ', $univerexport['name']);

            $duzina = count($explo);

            //foreach ($explo as $ex){
            // if($duzina > 3){
            //->where('body', 'like', '%'.$explo[3].'%')
            $databasee = ['univerexport' => $univerexport, 'drink' => drink::select('body', 'barcodes', 'supplementaryPriceLabel2', 'imageUrl')->whereNotNull('barcodes')->where('body', 'like', '%' . $explo[0] . '%')->where('body', 'like', '%' . $explo[1] . '%')->get()];
            // }
            //}
            //var_dump($databasee['drink']->isEmpty()); continue;
            if ($databasee['drink']->isNotEmpty()) {
                $database[] = $databasee;
            }
        }

        return $database;
    }

    public function getViewDrink()
    {
        return view('frontend.compareUniverexportMarketDrink')->with('showStore', false);
    }

    public function store(Request $request)
    {
        /*var_dump($request->code);
        die;*/

        $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";

        if ($request->oldPrice != 0) {
            $formattedPrice = $request->newPrice . " RSD";
            $price = str_replace('.', '', $request->newPrice);
        } else {
            $formattedPrice = $request->newPrice . " RSD";
            $price = str_replace('.', '', $request->newPrice);
        }

        if ($request->category == 'pice') {
            $article = univerexport_drink::firstOrNew(array('code' => $request->code));
        }/* elseif ($request->category == 'meso') {
            $article = dis_meat::firstOrNew(array('code' => $request->code));
        } elseif ($request->category == 'smrznuti') {
            $article = dis_freeze::firstOrNew(array('code' => $request->code));
        } elseif ($request->category == 'slatkisi') {
            $article = dis_sweet::firstOrNew(array('code' => $request->code));
        }*/

        $article->code = $request->code;
        $article->title = $request->title;
        $article->body = $request->body;
        $article->category = $request->category;
        $article->imageUrl = $request->imageUrl;
        $article->imageDefault = $imageDefault;
        $article->barcodes = $request->barcodes;
        $article->formattedPrice = $formattedPrice;
        $article->price = $price;
        $article->supplementaryPriceLabel1 = $request->supplementaryPriceLabel1;
        $article->supplementaryPriceLabel2 = null;
        $article->shop = $request->shop;
        $article->save();

        if ($request->oldPrice != 0) {

            $formattedPrice = $request->newPrice . " RSD";

            $article = univerexport_action_sale::firstOrNew(array('code' => $request->code));
            $article->code = $request->code;
            $article->title = $request->title;
            $article->body = $request->body;
            $article->category = $request->category;
            $article->imageUrl = $request->imageUrl;
            $article->imageDefault = $imageDefault;
            $article->barcodes = $request->barcodes;
            $article->formattedPrice = $formattedPrice;
            $article->price = str_replace('.', '', $request->newPrice);
            $article->supplementaryPriceLabel1 = $request->supplementaryPriceLabel1;
            $article->supplementaryPriceLabel2 = null;
            $article->shop = $request->shop;
            $article->save();

        }
    }

    public function updateExistingUniverDrinks()
    {
        $categories = $this->curlAllUniverexport();
        univerexport_action_sale::where('category', 'pice')->delete();
        $saved = false;
        $success = false;

        foreach ($categories as $key => $value) {
            for ($i = 0; $i < count($value['id']); $i++) {

                if (strpos($value['salePrice'][$i], ".") == false) {
                    $price = $value['salePrice'][$i].'00';
                    $formattedPrice = $value['salePrice'][$i] . ".00 RSD";
                }else{
                    $price = str_replace('.', '', $value['salePrice'][$i]);
                    $formattedPrice = $value['salePrice'][$i] . " RSD";
                }


                if (!$price) {
                    $price = null;
                }

                if ($key = 'Pica i napici') {
                    $article = univerexport_drink::where('code', (int)$value['id'][$i])->first();
                }else{
                    continue;
                }

                var_dump(count($article->id)); continue;

                if ($article) {
                    $code = $value['id'][$i];
                    //$barcodes = $article->barcodes;
                    $title = $value['manufacturer'][$i];
                    $body = $value['name'][$i];
                    $category = $article->category;
                    $shop = $article->shop;
                    $imageDefault = 'https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694';
                    $article->formattedPrice = $formattedPrice;
                    $article->price = $price;
                    $saved = $article->save();

                    if ($value['oldPrice'][$i] != 0) {

                        if (strpos($value['salePrice'][$i], ".") == false) {
                            $price = $value['salePrice'][$i].'00';
                            $formattedPrice = $value['salePrice'][$i] . ".00 RSD";
                        }else{
                            $price = str_replace('.', '', $value['salePrice'][$i]);
                            $formattedPrice = $value['salePrice'][$i] . " RSD";
                        }


                        $data = [
                            'code' => $code,
                            'title' => $title,
                            'body' => $body,
                            //'barcodes' => $barcodes,
                            'category' => $category,
                            'shop' => $shop,
                            'imageDefault' => $imageDefault,
                            'formattedPrice' => $formattedPrice,
                            'price' => $price
                        ];

                        $saved = univerexport_action_sale::create($data);
                    }
                }


                if ($saved) {
                    $success = true;
                } else {
                    return response()->json([
                        "success" => false
                    ]);
                }
            }
        }

        $data = [
            "success" => $success
        ];

        return response()->json($data);

    }

    function grab_page($site)
    {

        try {
            $ch = curl_init();

            // Check if initialization had gone wrong*
            if ($ch === false) {
                throw new Exception('failed to initialize');
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40);
            curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
            curl_setopt($ch, CURLOPT_URL, $site);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $content = curl_exec($ch);

            // Check the return value of curl_exec(), too
            if ($content === false) {
                throw new Exception(curl_error($ch), curl_errno($ch));
            }

            /* Process $content here */
            return curl_exec($ch);
            ob_end_clean();

            // Close curl handle
            curl_close($ch);
            unset($ch);
        } catch (Exception $e) {

            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);

        }
    }

    function multi($urlArray)
    {
        $requests = array();
//Initiate a multiple cURL handle
        $mh = curl_multi_init();

//Loop through each URL.
        foreach ($urlArray as $k => $url) {
            $requests[$k] = array();
            $requests[$k]['url'] = $url;
            //Create a normal cURL handle for this particular request.
            $requests[$k]['curl_handle'] = curl_init($url);
            //Configure the options for this request.
            curl_setopt($requests[$k]['curl_handle'], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($requests[$k]['curl_handle'], CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($requests[$k]['curl_handle'], CURLOPT_TIMEOUT, 10);
            curl_setopt($requests[$k]['curl_handle'], CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($requests[$k]['curl_handle'], CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($requests[$k]['curl_handle'], CURLOPT_SSL_VERIFYPEER, false);
            //Add our normal / single cURL handle to the cURL multi handle.
            curl_multi_add_handle($mh, $requests[$k]['curl_handle']);
        }

//Execute our requests using curl_multi_exec.
        $stillRunning = false;
        do {
            curl_multi_exec($mh, $stillRunning);
        } while ($stillRunning);

//Loop through the requests that we executed.
        foreach ($requests as $k => $request) {
            //Remove the handle from the multi handle.
            curl_multi_remove_handle($mh, $request['curl_handle']);
            //Get the response content and the HTTP status code.
            $requests[$k] = curl_multi_getcontent($request['curl_handle']);
            //Close the handle.
            //curl_close($requests[$k]['curl_handle']);
        }
//Close the multi handle.
        curl_multi_close($mh);
        return $requests;
    }

    function curlAllUniverexport()
    {
        $cache = Cache::remember('curlUniverexport', 20, function () {
            ini_set('max_execution_time', 30000); //300 seconds = 5 minutes
            ini_set('memory_limit', '-1');

            $host = 'https://elakolije.univerexport.rs/';
            $html = $this->grab_page($host);

            //$html = iconv("Windows-1250", "UTF-8", $html);

            preg_match_all("!<a href=\'(.*?)'\s*class=\'kat_1_a\'\s*title=\'(.*?)\((\d*)\)!",
                $html,
                $match);
            $category = [];
            $category['url'] = $match[1];
            $category['name'] = array_map(function ($name) {
                return trim($name);
            }, $match[2]);
            $category['count'] = $match[3];

            //var_dump($category['url']);die;
            $data = array_map(function ($url) use ($host) {
                return $host . $url;
            }, $category['url']);

            foreach ($category['url'] as $url) {
                $url1 = $host . $url;
                $regexUrl = str_replace('.', '\.', $url);
                $regexUrl = str_replace('?', '\?', $regexUrl);
                //var_dump($regexUrl);die;
                $htmlKAT = $this->grab_page($url1);
                preg_match_all("!$regexUrl(\d*)!",
                    $htmlKAT, $all_match);
                array_shift($all_match[0]);
                $category['subUrl'][] = $all_match[0];


            }
            $i = 0;
            $count = 0;

            foreach ($category['subUrl'] as $subUrl) {
                $productsContent = [];
                $urlArray = array_map(function ($url) use ($host) {
                    return $host . $url;
                }, $subUrl);

                /** @var Content of all subCategory $productsSubCategoriesContent */
                $productsSubCategoriesContent = $this->multi($urlArray);

                foreach ($productsSubCategoriesContent as $productSubCategoryContent) {
                    preg_match_all("!<a href=\'(.*?)(\d*)&naz=(.*?)\'\s*>!",
                        $productSubCategoryContent, $all_match);
                    //echo $category['name'][$i] . '<br>';

                    //var_dump($all_match); die;

                    $count += count($all_match[3]);

                    $productsUrl = array_map(
                        function ($url, $id, $name) {
                            return $url . $id . '&naz=' . $name;
                        },
                        $all_match[1],
                        $all_match[2],
                        $all_match[3]
                    );
                    $productsUrl = array_map(function ($url) use ($host) {
                        return $host . $url;
                    }, $productsUrl);


                    $products[$category['name'][$i]]['url'][] = $productsUrl;
                    $products[$category['name'][$i]]['id'][] = $all_match[2];
                    $products[$category['name'][$i]]['name'][] = $all_match[3];
                    $products[$category['name'][$i]]['imgUrl'][] = array_map(
                        function ($id) use ($host) {
                            return $host . 'slike_pro/pro_v_' . $id . '.jpg';
                        },
                        $all_match[2]
                    );

                    $numberOfPage = round(count($productsUrl) / 2, 0, PHP_ROUND_HALF_DOWN);
                    $urlArrayFirstHalf = [];
                    $urlArraySecondHalf = [];
                    for ($j = 0; $j < $numberOfPage; $j++) {
                        $urlArrayFirstHalf[] = $productsUrl[$j];
                    }

                    $oldPrice = [];
                    $salePrice = [];
                    $saleMeasure = [];
                    $referencePrice = [];
                    $referenceMeasure = [];
                    $manufacturer = [];

                    /** @var * $productsUrl content of all urls product of a category */
                    $productsUrl = array_chunk($productsUrl, 25);
                    foreach ($productsUrl as $chank) {
                        $productsContent = $this->multi($chank);

                        foreach ($productsContent as $id => $product) {
//                    var_dump($product); die;
                            /** Finding old price */
                            preg_match("!<div class='singl-stara-cena'\s*>.*?\s*.*?>(\d*).*?\.(\d*)!",
                                $product, $match_oldPrice);

                            if (array_filter($match_oldPrice)) {
                                $oldPrice[] = $match_oldPrice[1] . '.' . $match_oldPrice[2];
                            } else {
                                $oldPrice[] = '';
                            }

                            /** Find sale price */
                            preg_match("!<div class='singl-akcijska-cena'\s*>.*?\s*.*?>(\d*).*?\.(\d*).*\/>(.*?)<!",
                                $product, $match_salePrice);
                            if (array_filter($match_salePrice)) {
                                $salePrice[] = $match_salePrice[1] . '.' . $match_salePrice[2];
                                $saleMeasure[] = $match_salePrice[3];
                            } else {
                                //$salePrice[] = '';
                                //$saleMeasure[] = '';
                            }

                            /** Find reference price */
                            preg_match("!<div class='singl-referentna-cena'\s*>.*?\s*<div.*?>(.*)\s*<span\s*.*?\/>(.*?)<\/span><\/div>!",
                                $product, $match_referencePrice);
                            if (array_filter($match_referencePrice)) {
                                $referencePrice[] = trim($match_referencePrice[1], " ");
                                $referenceMeasure[] = trim($match_referencePrice[2], " ");
                            } else {
                                $referencePrice[] = '';
                                $referenceMeasure[] = '';
                            }

                            /** Find manufacturer */
                            preg_match("!Dobavlja.*?:\s*\d*(.*?)<br\/>!",
                                $product, $match_manufacturer);
                            if (array_filter($match_manufacturer)) {
                                $manufacturer[] = trim($match_manufacturer[1], " \t\n\r\0\x0B");
                            } else {
                                $manufacturer[] = '';
                            }
                        }
                    }

                    $products[$category['name'][$i]]['oldPrice'][] = $oldPrice;
                    $products[$category['name'][$i]]['salePrice'][] = $salePrice;
                    $products[$category['name'][$i]]['saleMeasure'][] = $saleMeasure;
                    $products[$category['name'][$i]]['referencePrice'][] = $referencePrice;
                    $products[$category['name'][$i]]['referenceMeasure'][] = $referenceMeasure;
                    $products[$category['name'][$i]]['manufacturer'][] = $manufacturer;

                }

                $products[$category['name'][$i]]['id'] = array_merge(...$products[$category['name'][$i]]['id']);
                $products[$category['name'][$i]]['name'] = array_map(function ($name) {
                    return str_replace('-', ' ', $name);
                },
                    array_merge(...$products[$category['name'][$i]]['name']));
                $products[$category['name'][$i]]['oldPrice'] = array_merge(...$products[$category['name'][$i]]['oldPrice']);
                $products[$category['name'][$i]]['salePrice'] = array_merge(...$products[$category['name'][$i]]['salePrice']);
                $products[$category['name'][$i]]['saleMeasure'] = array_merge(...$products[$category['name'][$i]]['saleMeasure']);
                $products[$category['name'][$i]]['referencePrice'] = array_merge(...$products[$category['name'][$i]]['referencePrice']);
                $products[$category['name'][$i]]['referenceMeasure'] = array_merge(...$products[$category['name'][$i]]['referenceMeasure']);
                $products[$category['name'][$i]]['manufacturer'] = array_merge(...$products[$category['name'][$i]]['manufacturer']);
                $products[$category['name'][$i]]['imgUrl'] = array_merge(...$products[$category['name'][$i]]['imgUrl']);
                $products[$category['name'][$i]]['url'] = array_merge(...$products[$category['name'][$i]]['url']);

                $i++;

            }

            return $products;
        });

        return $cache;

    }


}
