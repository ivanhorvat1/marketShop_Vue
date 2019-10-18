<?php

namespace App\Http\Controllers;

use App\action_sale;
use App\drink;
use App\Freeze;
use App\Meat;
use App\Sweets;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class IdeaScheduleController extends Controller
{
    public $items = [];
    public $noChildren = [];

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

    public function deleteCachedData()
    {
        Artisan::call('cache:clear');
    }

    public function scheduleRun()
    {
        Artisan::call('schedule:run');
    }

    public function deleteRecords()
    {
        drink::where('shop', 'idea')->delete();
        Meat::where('shop', 'idea')->delete();
        Freeze::where('shop', 'idea')->delete();
        Sweets::where('shop', 'idea')->delete();
    }

    public function getIdeaAction()
    {
        $obj = $this->curlAction('https://www.idea.rs/online/v2/offers?per_page=5000&page=2&filter%5Bsort%5D=soldStatisticsDesc');
        $results = $obj->products;
        $arrayLengt = sizeof($results) - 1;
        $imageUrl = null;
        $imageDefault = null;
        $storeRecords = [];

        if($arrayLengt > 0) {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                $imageUrl = null;
                if (array_key_exists('images', $results[$i]) && !empty($results[$i]->images)) {
                    $imageUrl = $results[$i]->images[0]->image_n;
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                array_push($storeRecords, ['code' => $results[$i]->code, 'title' => $results[$i]->manufacturer,
                    'body' => $results[$i]->name, 'imageUrl' => $imageUrl, 'imageDefault' => $imageDefault, 'barcodes' => implode(',', $results[$i]->barcodes),
                    'formattedPrice' => $results[$i]->price->formatted_price, 'price' => $results[$i]->price->amount, 'oldPrice' => $results[$i]->offer->original_price->formatted_price,
                    'supplementaryPriceLabel1' => $results[$i]->statistical_price, 'supplementaryPriceLabel2' => null, 'shop' => 'idea', 'category' => 'akcija']);
            }

            action_sale::where('shop', 'idea')->delete();

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

    public function getIdeaDrink($categoryNumber, $data)
    {
        $obj = $this->curlAction('https://www.idea.rs/online/v2/categories/'.$categoryNumber);

        if (is_object($data)) {
            array_push($this->items ,(array)$data);
        }else{
            $this->items = [];
        }

        if($obj->has_children){
            foreach ($obj->children as $child){
                self::getIdeaDrink($child->id, $child);
            }
        }

        foreach ($this->items as $item) {
            if(!in_array($item['id'],$this->noChildren)){
                if (!$item['has_children']) {
                    array_push($this->noChildren, $item['id']);
                    $res = $this->curlAction('https://www.idea.rs/online/v2/categories/'.$item['id'].'/products?per_page=5000&page=1&filter%5Bsort%5D=offerSoldStatisticsDesc');
                    $results = $res->products;

                    $this->storeIdea('pice',$results);

                    /*$arrayLengt = sizeof($results) - 1;
                    //$storeRecords = [];
                    for ($i = 0; $i <= $arrayLengt; $i++) {
                        $imageUrl = null;
                        $imageDefault = null;
                        if (array_key_exists('images', $results[$i]) && !empty($results[$i]->images)) {
                            $imageUrl = $results[$i]->images[0]->image_n;
                        } else {
                            $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                        }

                        $article = drink::firstOrNew(array('code' => $results[$i]->code));
                        $article->code = $results[$i]->code;
                        $article->title = $results[$i]->manufacturer;
                        $article->body = $results[$i]->name;
                        $article->category = 'pice';
                        $article->imageUrl = $imageUrl;
                        $article->imageDefault = $imageDefault;
                        $article->barcodes = implode(',', $results[$i]->barcodes);
                        $article->formattedPrice = $results[$i]->price->formatted_price;
                        $article->price = $results[$i]->price->amount;
                        $article->supplementaryPriceLabel1 = $results[$i]->statistical_price;
                        $article->supplementaryPriceLabel2 = null;
                        $article->shop = 'idea';
                        $article->save();
                    }*/
                }
            }
        }
        echo 'successfully saved';
    }

    public function getIdeaMeat($categoryNumber, $data)
    {
        $obj = $this->curlAction('https://www.idea.rs/online/v2/categories/'.$categoryNumber);

        if (is_object($data)) {
            array_push($this->items ,(array)$data);
        }else{
            $this->items = [];
        }

        if($obj->has_children){
            foreach ($obj->children as $child){
                self::getIdeaMeat($child->id, $child);
            }
        }

        foreach ($this->items as $item) {
            if(!in_array($item['id'],$this->noChildren)){
                if (!$item['has_children']) {
                    array_push($this->noChildren, $item['id']);
                    $res = $this->curlAction('https://www.idea.rs/online/v2/categories/'.$item['id'].'/products?per_page=5000&page=1&filter%5Bsort%5D=offerSoldStatisticsDesc');
                    $results = $res->products;

                    $this->storeIdea('meso',$results);
                }
            }
        }
        echo 'successfully saved';
    }

    public function getIdeaMeat2($categoryNumber, $data)
    {
        $obj = $this->curlAction('https://www.idea.rs/online/v2/categories/'.$categoryNumber);

        if (is_object($data)) {
            array_push($this->items ,(array)$data);
        }else{
            $this->items = [];
        }

        if($obj->has_children){
            foreach ($obj->children as $child){
                self::getIdeaMeat2($child->id, $child);
            }
        }

        foreach ($this->items as $item) {
            if(!in_array($item['id'],$this->noChildren)){
                if (!$item['has_children']) {
                    array_push($this->noChildren, $item['id']);
                    $res = $this->curlAction('https://www.idea.rs/online/v2/categories/'.$item['id'].'/products?per_page=5000&page=1&filter%5Bsort%5D=offerSoldStatisticsDesc');
                    $results = $res->products;

                    $this->storeIdea('meso',$results);
                }
            }
        }
        echo 'successfully saved';
    }

    public function getIdeaSweet($categoryNumber, $data)
    {
        $obj = $this->curlAction('https://www.idea.rs/online/v2/categories/'.$categoryNumber);

        if (is_object($data)) {
            array_push($this->items ,(array)$data);
        }else{
            $this->items = [];
        }

        if($obj->has_children){
            foreach ($obj->children as $child){
                self::getIdeaSweet($child->id, $child);
            }
        }

        foreach ($this->items as $item) {
            if(!in_array($item['id'],$this->noChildren)){
                if (!$item['has_children']) {
                    array_push($this->noChildren, $item['id']);
                    $res = $this->curlAction('https://www.idea.rs/online/v2/categories/'.$item['id'].'/products?per_page=5000&page=1&filter%5Bsort%5D=offerSoldStatisticsDesc');
                    $results = $res->products;

                    $this->storeIdea('slatkisi',$results);
                }
            }
        }
        echo 'successfully saved';
    }

    public function getIdeaFreeze($categoryNumber, $data)
    {
        $obj = $this->curlAction('https://www.idea.rs/online/v2/categories/'.$categoryNumber);

        if (is_object($data)) {
            array_push($this->items ,(array)$data);
        }else{
            $this->items = [];
        }

        if($obj->has_children){
            foreach ($obj->children as $child){
                self::getIdeaFreeze($child->id, $child);
            }
        }

        foreach ($this->items as $item) {
            if(!in_array($item['id'],$this->noChildren)){
                if (!$item['has_children']) {
                    array_push($this->noChildren, $item['id']);
                    $res = $this->curlAction('https://www.idea.rs/online/v2/categories/'.$item['id'].'/products?per_page=5000&page=1&filter%5Bsort%5D=offerSoldStatisticsDesc');
                    $results = $res->products;

                    $this->storeIdea('smrznuti',$results);
                }
            }
        }
        echo 'successfully saved';
    }

    public function storeIdea($category, $results)
    {
        $arrayLengt = sizeof($results) - 1;
        if($arrayLengt > 0) {
            for ($i = 0; $i <= $arrayLengt; $i++) {
                $imageUrl = null;
                $imageDefault = null;
                if (array_key_exists('images', $results[$i]) && !empty($results[$i]->images)) {
                    $imageUrl = $results[$i]->images[0]->image_n;
                } else {
                    $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                }

                if ($category == 'smrznuti') {
                    $article = Freeze::firstOrNew(array('code' => $results[$i]->code));
                } elseif ($category == 'slatkisi') {
                    $article = Sweets::firstOrNew(array('code' => $results[$i]->code));
                } elseif ($category == 'meso') {
                    $article = Meat::firstOrNew(array('code' => $results[$i]->code));
                } elseif ($category == 'pice') {
                    $article = drink::firstOrNew(array('code' => $results[$i]->code));
                }
                $article->code = $results[$i]->code;
                $article->title = $results[$i]->manufacturer;
                $article->body = $results[$i]->name;
                $article->category = $category;
                $article->imageUrl = $imageUrl;
                $article->imageDefault = $imageDefault;
                $article->barcodes = implode(',', $results[$i]->barcodes);
                $article->formattedPrice = $results[$i]->price->formatted_price;
                $article->price = $results[$i]->price->amount;
                $article->supplementaryPriceLabel1 = $results[$i]->statistical_price;
                $article->supplementaryPriceLabel2 = null;
                $article->shop = 'idea';
                $article->save();
            }
        }
    }
}
