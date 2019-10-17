<?php

namespace App\Http\Controllers;

use App\action_sale;
use App\drink;
use App\Freeze;
use App\Meat;
use App\Sweets;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaxiScheduleController extends Controller
{
    public function curlAction($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }

    public function getMaxiAction()
    {
        /*$ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://www.maxi.rs/search/promotions/loadMore?pageNumber=0&pageSize=5000&sort=promotionType');
        $result = curl_exec($ch);
        curl_close($ch);*/

        $obj = $this->curlAction('https://www.maxi.rs/search/promotions/loadMore?pageNumber=0&pageSize=5000&sort=promotionType');
        $results = $obj->results;
        $arrayLengt = sizeof($obj->results) - 1;
        $imageUrl = null;
        $imageDefault = null;
        $storeRecords = [];
        dd($results[0]);
        if($arrayLengt > 0) {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                $imageUrl = null;
                if (array_key_exists('images', $results[$i]) && !empty($results[$i]->images) && array_key_exists('2', $results[$i]->images)) {
                    $imageUrl = $results[$i]->images[2]->url;
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                preg_match_all('!\d+!', $results[$i]->potentialPromotions[0]->description, $matches);

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

                if (array_key_exists('eanCodes', $results[$i]) && !empty($results[$i]->eanCodes)) {
                    $barcode = implode(',', $results[$i]->eanCodes);
                } else {
                    $barcode = null;
                }
                //var_dump(empty($results[$i]->price->intValue)); continue;
                if (!empty($results[$i]->price)){
                    $oldPrice = $results[$i]->price->intValue . ',' . $results[$i]->price->fractionValue . ' ' . $results[$i]->price->currencySymbol;
                    $supplementaryPriceLabel1 = $results[$i]->price->supplementaryPriceLabel1;
                    $supplementaryPriceLabel2 = $results[$i]->price->supplementaryPriceLabel2;
                }else{
                    $oldPrice = '--';
                    $supplementaryPriceLabel1 = '--';
                    $supplementaryPriceLabel2 = '--';
                }

                /*$pricesub = substr($request->products[0][$i]['price']['formattedValue'], 0, -4);

                $subs = substr($pricesub, -3);*/

                array_push($storeRecords, ['code' => $results[$i]->code, 'title' => $results[$i]->manufacturerName,
                    'body' => $results[$i]->name, 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => $barcode,
                    'formattedPrice' => $formattedPrice, 'price' => $price, 'oldPrice' => $oldPrice,
                    'supplementaryPriceLabel1' => $supplementaryPriceLabel1,
                    'supplementaryPriceLabel2' => $supplementaryPriceLabel2, 'shop' => 'maxi', 'category' => 'akcija']);
            }

            action_sale::where('shop', 'maxi')->delete();

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
                $article->oldPrice = $record['oldPrice'];
                $article->supplementaryPriceLabel1 = $record['supplementaryPriceLabel1'];
                $article->supplementaryPriceLabel2 = $record['supplementaryPriceLabel2'];
                $article->shop = $record['shop'];
                $saved = $article->save();

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

        public function getMaxiDrink()
    {
        $obj = $this->curlAction('https://www.maxi.rs/online/Pice%2C-kafa-i-caj/c/01/getSearchPageData?pageSize=5000&pageNumber=0&sort=promotion');
        $results = $obj->results;
        $arrayLengt = sizeof($obj->results) - 1;
        $imageUrl = null;
        $imageDefault = null;
        $storeRecords = [];
        if($arrayLengt > 0) {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                $imageUrl = null;
                if (array_key_exists('images', $results[$i]) && !empty($results[$i]->images) && array_key_exists('2', $results[$i]->images)) {
                    $imageUrl = $results[$i]->images[2]->url;
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                if (array_key_exists('eanCodes', $results[$i]) && !empty($results[$i]->eanCodes)) {
                    $barcode = implode(',', $results[$i]->eanCodes);
                } else {
                    $barcode = null;
                }

                /*$pricesub = substr($request->products[0][$i]['price']['formattedValue'], 0, -4);

                $subs = substr($pricesub, -3);*/

                if ($results[$i]->price->fractionValue == '00') {
                    $price = $results[$i]->price->value . $results[$i]->price->fractionValue;
                } else {
                    $price = str_replace('.', '', $results[$i]->price->value);
                }

                array_push($storeRecords, ['code' => $results[$i]->code, 'title' => $results[$i]->manufacturerName,
                    'body' => $results[$i]->name, 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => $barcode,
                    'formattedPrice' => $results[$i]->price->formattedValue, 'price' => $price,
                    'supplementaryPriceLabel1' => $results[$i]->price->supplementaryPriceLabel1,
                    'supplementaryPriceLabel2' => $results[$i]->price->supplementaryPriceLabel2, 'shop' => 'maxi', 'category' => 'pice']);
            }

            drink::where('shop', 'maxi')->delete();

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
                $saved = $article->save();

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

    public function getMaxiMeats()
    {
        $obj = $this->curlAction('https://www.maxi.rs/online/Meso%2C-mesne-i-riblje-prera%C4%91evine/c/02/getSearchPageData?pageSize=5000&pageNumber=0&sort=promotion');
        $results = $obj->results;
        $arrayLengt = sizeof($obj->results) - 1;
        $imageUrl = null;
        $imageDefault = null;
        $storeRecords = [];
        if($arrayLengt > 0) {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                $imageUrl = null;
                if (array_key_exists('images', $results[$i]) && !empty($results[$i]->images) && array_key_exists('2', $results[$i]->images)) {
                    $imageUrl = $results[$i]->images[2]->url;
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                if (array_key_exists('eanCodes', $results[$i]) && !empty($results[$i]->eanCodes)) {
                    $barcode = implode(',', $results[$i]->eanCodes);
                } else {
                    $barcode = null;
                }

                /*$pricesub = substr($request->products[0][$i]['price']['formattedValue'], 0, -4);

                $subs = substr($pricesub, -3);*/

                if ($results[$i]->price->fractionValue == '00') {
                    $price = $results[$i]->price->value . $results[$i]->price->fractionValue;
                } else {
                    $price = str_replace('.', '', $results[$i]->price->value);
                }

                array_push($storeRecords, ['code' => $results[$i]->code, 'title' => $results[$i]->manufacturerName,
                    'body' => $results[$i]->name, 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => $barcode,
                    'formattedPrice' => $results[$i]->price->formattedValue, 'price' => $price,
                    'supplementaryPriceLabel1' => $results[$i]->price->supplementaryPriceLabel1,
                    'supplementaryPriceLabel2' => $results[$i]->price->supplementaryPriceLabel2, 'shop' => 'maxi', 'category' => 'meso']);
            }

            Meat::where('shop', 'maxi')->delete();

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
                $saved = $article->save();

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

    public function getMaxiSweets()
    {
        $obj = $this->curlAction('https://www.maxi.rs/online/Cokolade%2C-keks%2C-slane-i-slatke-grickalice/c/09/getSearchPageData?q=%3Apopularity&sort=promotion&pageSize=5000&pageNumber=0');
        $results = $obj->results;
        $arrayLengt = sizeof($obj->results) - 1;
        $imageUrl = null;
        $imageDefault = null;
        $storeRecords = [];
        if($arrayLengt > 0) {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                $imageUrl = null;
                if (array_key_exists('images', $results[$i]) && !empty($results[$i]->images) && array_key_exists('2', $results[$i]->images)) {
                    $imageUrl = $results[$i]->images[2]->url;
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                if (array_key_exists('eanCodes', $results[$i]) && !empty($results[$i]->eanCodes)) {
                    $barcode = implode(',', $results[$i]->eanCodes);
                } else {
                    $barcode = null;
                }

                /*$pricesub = substr($request->products[0][$i]['price']['formattedValue'], 0, -4);

                $subs = substr($pricesub, -3);*/

                if ($results[$i]->price->fractionValue == '00') {
                    $price = $results[$i]->price->value . $results[$i]->price->fractionValue;
                } else {
                    $price = str_replace('.', '', $results[$i]->price->value);
                }

                array_push($storeRecords, ['code' => $results[$i]->code, 'title' => $results[$i]->manufacturerName,
                    'body' => $results[$i]->name, 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => $barcode,
                    'formattedPrice' => $results[$i]->price->formattedValue, 'price' => $price,
                    'supplementaryPriceLabel1' => $results[$i]->price->supplementaryPriceLabel1,
                    'supplementaryPriceLabel2' => $results[$i]->price->supplementaryPriceLabel2, 'shop' => 'maxi', 'category' => 'slatkisi']);
            }

            Sweets::where('shop', 'maxi')->delete();

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
                $saved = $article->save();

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

    public function getMaxiFreeze()
    {
        $obj = $this->curlAction('https://www.maxi.rs/online/Smrznuti-proizvodi/c/10/getSearchPageData?pageSize=5000&pageNumber=0&sort=promotion');
        $results = $obj->results;
        $arrayLengt = sizeof($obj->results) - 1;
        $imageUrl = null;
        $imageDefault = null;
        $storeRecords = [];
        if($arrayLengt > 0) {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                $imageUrl = null;
                if (array_key_exists('images', $results[$i]) && !empty($results[$i]->images) && array_key_exists('2', $results[$i]->images)) {
                    $imageUrl = $results[$i]->images[2]->url;
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                if (array_key_exists('eanCodes', $results[$i]) && !empty($results[$i]->eanCodes)) {
                    $barcode = implode(',', $results[$i]->eanCodes);
                } else {
                    $barcode = null;
                }

                /*$pricesub = substr($request->products[0][$i]['price']['formattedValue'], 0, -4);

                $subs = substr($pricesub, -3);*/

                if ($results[$i]->price->fractionValue == '00') {
                    $price = $results[$i]->price->value . $results[$i]->price->fractionValue;
                } else {
                    $price = str_replace('.', '', $results[$i]->price->value);
                }

                array_push($storeRecords, ['code' => $results[$i]->code, 'title' => $results[$i]->manufacturerName,
                    'body' => $results[$i]->name, 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => $barcode,
                    'formattedPrice' => $results[$i]->price->formattedValue, 'price' => $price,
                    'supplementaryPriceLabel1' => $results[$i]->price->supplementaryPriceLabel1,
                    'supplementaryPriceLabel2' => $results[$i]->price->supplementaryPriceLabel2, 'shop' => 'maxi', 'category' => 'smrznuti']);
            }

            Freeze::where('shop', 'maxi')->delete();

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
                $saved = $article->save();

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
}
