<?php

namespace App\Http\Controllers;

use App\dis_meat;
use App\Meat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\dis_drink;
use App\drink;
use App\dis_action_sale;

class DisMarketController extends Controller
{
    public $artikli = [];

    public function login($url, $data)
    {
        $fp = fopen("cookie.txt", "w");
        fclose($fp);
        $login = curl_init();
        curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
        curl_setopt($login, CURLOPT_TIMEOUT, 40000);
        curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($login, CURLOPT_URL, $url);
        curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($login, CURLOPT_POST, TRUE);
        curl_setopt($login, CURLOPT_POSTFIELDS, $data);
        ob_start();
        return curl_exec($login);
        ob_end_clean();
        curl_close($login);
        unset($login);
    }

    public function grab_page($site)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
        curl_setopt($ch, CURLOPT_URL, $site);
        ob_start();
        return curl_exec($ch);
        ob_end_clean();
        curl_close($ch);
        unset($ch);
    }

    public function disMarketDrink()
    {
        ini_set('max_execution_time', 30000); //300 seconds = 5 minutes
        $this->login("http://online.dis.rs/inc/inc.nalog.prijava.php", "email=nemixbg%40gmail.com&lozinka=n3m4nj41982&radi=da");
        //$html = $this->grab_page("http://online.dis.rs/proizvodi.php?");
        //$html = iconv("Windows-1250", "UTF-8", $html);

        $drinkUrl = ['P1','O1'];

        /*preg_match_all("!<a href=\'#\' onclick=\"proslediNaStranicu\('(.*?)'\);return .*?;\s*\"\s*>(.*?)<\/a>!",
            $html,
            $match);*/

        /*$match[1] = array_slice($match[1], 8, -12);
        $match[2] = array_slice($match[2], 8, -12);

        $duzinaKategorije = strlen($match[1][0]);

        //$artikli = [];
        $i = 0;
        foreach ($match[1] as $url) {
            if (strlen($url) == $duzinaKategorije) {*/

        //echo $match[2][$i] . '<br>';
        //$url = 'http://online.dis.rs/' . $url;
        //http://online.dis.rs/proizvodi.php?brArtPoStr=350&kat=P1
        foreach ($drinkUrl as $driUrl) {
//        $url = 'http://online.dis.rs/proizvodi.php?&kat=P1';
            $url = 'http://online.dis.rs/proizvodi.php?&kat=' . $driUrl;
            $htmlKAT = $this->grab_page($url . '&brArtPoStr=96');

            $htmlKAT = iconv("Windows-1250", "UTF-8", $htmlKAT);
            //<a href=\'#\' onclick="proslediNaStranicu\('.*?limit=(\d*)&.*?'\);return .*?;\s*"\s*>››<\/a>
            preg_match_all("!<a href=\'#\' onclick=\"proslediNaStranicu\('.*?limit=(\d*)&.*?'\);return .*?;\s*\"\s*>(.*?)<\/a>!",
                $htmlKAT,
                $limit);

            if (array_filter($limit)) {
                $limit = max($limit[1]) + 96;
            } else {
                $limit = 0;
            }

            $urlFinal = $url . '&sortArt=sortNazart&brArtPoStr=' . $limit;

            $htmlContent = $this->grab_page($urlFinal);

            $htmlContent = iconv("Windows-1250", "UTF-8", $htmlContent);

            $artikal_start = '<div id="artikal">';
            $artikliHTML = array_slice(explode($artikal_start, $htmlContent), 1);

            foreach ($artikliHTML as &$artikal) {
                // Matching artical name
                preg_match_all('!<div id="artikal-naziv">\s*(.*?)\s*<\/div>\s*!',
                    $artikal,
                    $artikal_nazivi);
                // Matching artical cod
                preg_match_all('!<div id="artikal-slika-okvir">\s*.*?data-target="#slikaModal(.*?)".*?\s*<\/div>\s*!',
                    $artikal,
                    $artikal_kodovi);

                // Matching artical newPrice
                preg_match_all("!<span class='tekst-artikal-cena'>(\d*,\d{2}).*?<!",
                    $artikal,
                    $artikal_cena_nova);

                if (!empty($artikal_cena_nova[1])) {
                    $artikal_cena_nova = $artikal_cena_nova[1][0];
                } else {
                    $artikal_cena_nova = null;
                }

                // Matching artical oldPrice
                preg_match_all("!<span class='tekst-artikal-cena-stara'>(\d*,\d{2}).*?<!",
                    $artikal,
                    $artikal_cena_stara);

                if (!empty($artikal_cena_stara[1])) {
                    $artikal_cena_stara = $artikal_cena_stara[1][0];
                } else {
                    $artikal_cena_stara = null;
                }

                // Matching artical salePrice
                preg_match_all("!<span class='tekst-artikal-cena-akcija'>(\d*,\d{2}).*?<!",
                    $artikal,
                    $artikal_cena_akcija);

                if (!empty($artikal_cena_akcija[1])) {
                    $artikal_cena_akcija = $artikal_cena_akcija[1][0];
                } else {
                    $artikal_cena_akcija = null;
                }

                $this->artikli[] = [
                    'name' => $artikal_nazivi[1][0],
                    'code' => $artikal_kodovi[1][0],
                    'newPrice' => $artikal_cena_nova,
                    'oldPrice' => $artikal_cena_stara,
                    'salePrice' => $artikal_cena_akcija,
                    'category' => 'pice'
                    //'category' => $match[2][$i]
                ];
            }

            // echo $i . '<br>';

            //}

            //$i++;

            //}
        }

        return $this->artikli;
        // echo "Ima ukupno : " . count($this->artikli) . " artikala u DIS marketu!";
        //var_dump($this->artikli);
    }

    public function getDisDrinks()
    {

        $disArtikli = $this->disMarketDrink();

        $database = [];
        foreach ($disArtikli as $dis) {
            $explo = explode(' ', $dis['name']);

            $duzina = count($explo);

            //foreach ($explo as $ex){
            // if($duzina > 3){
            //->where('body', 'like', '%'.$explo[3].'%')
            $databasee = ['dis' => $dis, 'drink' => drink::select('body', 'barcodes', 'supplementaryPriceLabel2')->where('category', 'pice')->whereNotNull('barcodes')->where('body', 'like', '%' . $explo[0] . '%')->where('body', 'like', '%' . $explo[1] . '%')->get()];
            // }
            //}
            //var_dump($databasee['drink']->isEmpty()); continue;
            if ($databasee['drink']->isNotEmpty()) {
                $database[] = $databasee;
            }

        }

        return $database;
    }

    public function disMarketMeat()
    {
        ini_set('max_execution_time', 30000); //300 seconds = 5 minutes
        $this->login("http://online.dis.rs/inc/inc.nalog.prijava.php", "email=nemixbg%40gmail.com&lozinka=n3m4nj41982&radi=da");
        //$html = $this->grab_page("http://online.dis.rs/proizvodi.php?");
        //$html = iconv("Windows-1250", "UTF-8", $html);

        $drinkUrl = ['D1','B1'];

        /*preg_match_all("!<a href=\'#\' onclick=\"proslediNaStranicu\('(.*?)'\);return .*?;\s*\"\s*>(.*?)<\/a>!",
            $html,
            $match);*/

        /*$match[1] = array_slice($match[1], 8, -12);
        $match[2] = array_slice($match[2], 8, -12);

        $duzinaKategorije = strlen($match[1][0]);

        //$artikli = [];
        $i = 0;
        foreach ($match[1] as $url) {
            if (strlen($url) == $duzinaKategorije) {*/

        //echo $match[2][$i] . '<br>';
        //$url = 'http://online.dis.rs/' . $url;
        //http://online.dis.rs/proizvodi.php?brArtPoStr=350&kat=P1
        foreach ($drinkUrl as $driUrl) {
//        $url = 'http://online.dis.rs/proizvodi.php?&kat=P1';
            $url = 'http://online.dis.rs/proizvodi.php?&kat=' . $driUrl;
            $htmlKAT = $this->grab_page($url . '&brArtPoStr=96');

            $htmlKAT = iconv("Windows-1250", "UTF-8", $htmlKAT);
            //<a href=\'#\' onclick="proslediNaStranicu\('.*?limit=(\d*)&.*?'\);return .*?;\s*"\s*>››<\/a>
            preg_match_all("!<a href=\'#\' onclick=\"proslediNaStranicu\('.*?limit=(\d*)&.*?'\);return .*?;\s*\"\s*>(.*?)<\/a>!",
                $htmlKAT,
                $limit);

            if (array_filter($limit)) {
                $limit = max($limit[1]) + 96;
            } else {
                $limit = 0;
            }

            $urlFinal = $url . '&sortArt=sortNazart&brArtPoStr=' . $limit;

            $htmlContent = $this->grab_page($urlFinal);

            $htmlContent = iconv("Windows-1250", "UTF-8", $htmlContent);

            $artikal_start = '<div id="artikal">';
            $artikliHTML = array_slice(explode($artikal_start, $htmlContent), 1);

            foreach ($artikliHTML as &$artikal) {
                // Matching artical name
                preg_match_all('!<div id="artikal-naziv">\s*(.*?)\s*<\/div>\s*!',
                    $artikal,
                    $artikal_nazivi);
                // Matching artical cod
                preg_match_all('!<div id="artikal-slika-okvir">\s*.*?data-target="#slikaModal(.*?)".*?\s*<\/div>\s*!',
                    $artikal,
                    $artikal_kodovi);

                // Matching artical newPrice
                preg_match_all("!<span class='tekst-artikal-cena'>(\d*,\d{2}).*?<!",
                    $artikal,
                    $artikal_cena_nova);

                if (!empty($artikal_cena_nova[1])) {
                    $artikal_cena_nova = $artikal_cena_nova[1][0];
                } else {
                    $artikal_cena_nova = null;
                }

                // Matching artical oldPrice
                preg_match_all("!<span class='tekst-artikal-cena-stara'>(\d*,\d{2}).*?<!",
                    $artikal,
                    $artikal_cena_stara);

                if (!empty($artikal_cena_stara[1])) {
                    $artikal_cena_stara = $artikal_cena_stara[1][0];
                } else {
                    $artikal_cena_stara = null;
                }

                // Matching artical salePrice
                preg_match_all("!<span class='tekst-artikal-cena-akcija'>(\d*,\d{2}).*?<!",
                    $artikal,
                    $artikal_cena_akcija);

                if (!empty($artikal_cena_akcija[1])) {
                    $artikal_cena_akcija = $artikal_cena_akcija[1][0];
                } else {
                    $artikal_cena_akcija = null;
                }

                $this->artikli[] = [
                    'name' => $artikal_nazivi[1][0],
                    'code' => $artikal_kodovi[1][0],
                    'newPrice' => $artikal_cena_nova,
                    'oldPrice' => $artikal_cena_stara,
                    'salePrice' => $artikal_cena_akcija,
                    'category' => 'pice'
                    //'category' => $match[2][$i]
                ];
            }

            // echo $i . '<br>';

            //}

            //$i++;

            //}
        }

        return $this->artikli;
        // echo "Ima ukupno : " . count($this->artikli) . " artikala u DIS marketu!";
        //var_dump($this->artikli);
    }

    public function getDisMeat()
    {

        $disArtikli = $this->disMarketMeat();

        $database = [];
        foreach ($disArtikli as $dis) {
            $explo = explode(' ', $dis['name']);

            $duzina = count($explo);

            //foreach ($explo as $ex){
            // if($duzina > 3){
            //->where('body', 'like', '%'.$explo[3].'%')
            $databasee = ['dis' => $dis, 'drink' => Meat::select('body', 'barcodes', 'supplementaryPriceLabel2')->whereNotNull('barcodes')->where('body', 'like', '%' . $explo[0] . '%')->where('body', 'like', '%' . $explo[1] . '%')->get()];
            // }
            //}
            //var_dump($databasee['drink']->isEmpty()); continue;
            if ($databasee['drink']->isNotEmpty()) {
                $database[] = $databasee;
            }

        }

        return $database;
    }

    public function getView()
    {
        return view('frontend.compareDisMarket')->with('showStore', false);
    }

    public function getViewMeat()
    {
        return view('frontend.compareDisMarketMeat')->with('showStore', false);
    }

    public function store(Request $request)
    {
        /*var_dump($request->code);
        die;*/

        $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";

        if ($request->salePrice != null) {
            $formattedPrice = $request->oldPrice . " RSD";
            $price = str_replace(',','',$request->oldPrice);
        } else {
            $formattedPrice = $request->newPrice . " RSD";
            $price = str_replace(',','',$request->newPrice);
        }

        if($request->category == 'pice'){
            $article = dis_drink::firstOrNew(array('code' => $request->code));
        }elseif ($request->category == 'meso'){
            $article = dis_meat::firstOrNew(array('code' => $request->code));
        }

        $article->code = $request->code;
        $article->title = $request->name;
        $article->body = $request->name;
        $article->category = $request->category;
        $article->imageUrl = null;
        $article->imageDefault = $imageDefault;
        $article->barcodes = $request->barcodes;
        $article->formattedPrice = $formattedPrice;
        $article->price = $price;
        $article->supplementaryPriceLabel1 = null;
        $article->supplementaryPriceLabel2 = null;
        $article->shop = $request->shop;
        $article->save();

        if ($request->salePrice != null) {

            $formattedPrice = $request->salePrice . " RSD";

            $article = dis_action_sale::firstOrNew(array('code' => $request->code));
            $article->code = $request->code;
            $article->title = $request->name;
            $article->body = $request->name;
            $article->category = $request->category;
            $article->imageUrl = null;
            $article->imageDefault = $imageDefault;
            $article->barcodes = $request->barcodes;
            $article->formattedPrice = $formattedPrice;
            $article->price = str_replace(',','',$request->salePrice);
            $article->supplementaryPriceLabel1 = null;
            $article->supplementaryPriceLabel2 = null;
            $article->shop = $request->shop;
            $article->save();

        }
    }

    public function updateExistingDrinks()
    {
        $dis = $this->disMarketDrink();

        /*var_dump($dis);
        die;*/

        $success = false;

        foreach ($dis as $disArtikli) {

            if ($disArtikli['salePrice'] != null) {
                $formattedPrice = $disArtikli['oldPrice'] . " RSD";
                $price = str_replace(',', '', $disArtikli['oldPrice']);
            } else {
                $formattedPrice = $disArtikli['newPrice'] . " RSD";
                $price = str_replace(',', '', $disArtikli['newPrice']);
            }

            if(!$price){
                $price = null;
            }

            //var_dump($price); continue;
            $article = dis_drink::where('code',  $disArtikli['code'])->first();
            if($article) {
                $article->formattedPrice = $formattedPrice;
                $article->price = $price;
                $saved = $article->save();
            }

            if ($disArtikli['salePrice'] != null) {

                $formattedPrice = $disArtikli['salePrice'] . " RSD";

                $article = dis_action_sale::where('code',  $disArtikli['code'])->first();
                if($article) {
                    $article->formattedPrice = $formattedPrice;
                    $article->price = str_replace(',', '', $disArtikli['salePrice']);
                    $saved = $article->save();
                }

            }

            if($saved){
                $success = true;
            }else{
                return response()->json([
                    "success"=>false
                ]);
            }
        }

        $data = [
            "success"=>$success
        ];

        return response()->json($data);
    }

}
