<?php

namespace App\Http\Controllers;

use App\dis_freeze;
use App\dis_meat;
use App\dis_sweet;
use App\Freeze;
use App\Meat;
use App\Sweets;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\dis_drink;
use App\drink;
use App\dis_action_sale;
use Illuminate\Support\Facades\Cache;

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
        curl_setopt($login, CURLOPT_USERAGENT, isset($_SERVER['HTTP_USER_AGENT'])?:'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36');
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
        curl_setopt($ch, CURLOPT_USERAGENT, isset($_SERVER['HTTP_USER_AGENT'])?:'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36');
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
//        $cache = Cache::remember('dismarketdrink', 20, function () {
        ini_set('max_execution_time', 30000); //300 seconds = 5 minutes
        $this->login("http://online.dis.rs/inc/inc.nalog.prijava.php", "email=nemixbg%40gmail.com&lozinka=n3m4nj41982&radi=da");
        //$html = $this->grab_page("http://online.dis.rs/proizvodi.php?");
        //$html = iconv("Windows-1250", "UTF-8", $html);

        $drinkUrl = ['P1', 'O1'];

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
        /*});

        return $cache;*/
    }

    public function getDisDrinks()
    {

        $disArtikli = $this->disMarketDrink();

        $database = [];
        foreach ($disArtikli as $dis) {
//            $explo = explode(' ', $dis['name']);
            $explo = UniverexportMarketController::multiexplode(array(" ","."),$dis['name']);


            //$duzina = count($explo);

            //foreach ($explo as $ex){
            // if($duzina > 3){
            //->where('body', 'like', '%'.$explo[3].'%')
            $check = dis_drink::where('code', $dis['code'])->first();
            if(array_key_exists(2,$explo)) {
                if ($check == null) {
                    $databasee = ['dis' => $dis, 'drink' => drink::select('body', 'barcodes', 'supplementaryPriceLabel2', 'imageUrl')->where('category', 'pice')->whereNotNull('barcodes')->where('body', 'like', '%' . $explo[0] . '%')->where('body', 'like', '%' . $explo[1] . '%')->where('body', 'like', '%' . $explo[2] . '%')->get()];
                }else{
                    continue;
                }
            }

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
//        $cache = Cache::remember('dismarketmeat', 20, function () {
            ini_set('max_execution_time', 30000); //300 seconds = 5 minutes
            $this->login("http://online.dis.rs/inc/inc.nalog.prijava.php", "email=nemixbg%40gmail.com&lozinka=n3m4nj41982&radi=da");

            $drinkUrl = ['D1', 'B1'];
            foreach ($drinkUrl as $driUrl) {
                $url = 'http://online.dis.rs/proizvodi.php?&kat=' . $driUrl;
                $htmlKAT = $this->grab_page($url . '&brArtPoStr=96');

                $htmlKAT = iconv("Windows-1250", "UTF-8", $htmlKAT);
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
                        'category' => 'meso'
                        //'category' => $match[2][$i]
                    ];
                }

                // echo $i . '<br>';

                //}

                //$i++;

                //}
            }

            return $this->artikli;
        /*});

        return $cache;*/
        // echo "Ima ukupno : " . count($this->artikli) . " artikala u DIS marketu!";
        //var_dump($this->artikli);
    }

    public function getDisMeat()
    {
        $disArtikli = $this->disMarketMeat();

        $database = [];
        foreach ($disArtikli as $dis) {
            //$explo = explode(' ', $dis['name']);
            $explo = UniverexportMarketController::multiexplode(array(" ","."),$dis['name']);
            $duzina = count($explo);

            //foreach ($explo as $ex){
            // if($duzina > 3){
            //->where('body', 'like', '%'.$explo[3].'%')
            $check = dis_meat::where('code', $dis['code'])->first();
            if(array_key_exists(3,$explo)) {
                if ($check == null) {
                    $databasee = ['dis' => $dis, 'drink' => Meat::select('body', 'barcodes', 'supplementaryPriceLabel2', 'imageUrl')->whereNotNull('barcodes')->where('body', 'like', '%' . $explo[1] . '%')->where('body', 'like', '%' . $explo[2] . '%')->where('body', 'like', '%' . $explo[3] . '%')->get()];
                }else{
                    continue;
                }
            }
            // }
            //}
            //var_dump($databasee['drink']->isEmpty()); continue;
            if ($databasee['drink']->isNotEmpty()) {
                $database[] = $databasee;
            }

        }

        return $database;
    }

    public function disMarketFreeze()
    {
//        $cache = Cache::remember('dismarketfreeze', 20, function () {
            ini_set('max_execution_time', 30000); //300 seconds = 5 minutes
            $this->login("http://online.dis.rs/inc/inc.nalog.prijava.php", "email=nemixbg%40gmail.com&lozinka=n3m4nj41982&radi=da");

            $drinkUrl = ['E1'];
            foreach ($drinkUrl as $driUrl) {
                $url = 'http://online.dis.rs/proizvodi.php?&kat=' . $driUrl;
                $htmlKAT = $this->grab_page($url . '&brArtPoStr=96');

                $htmlKAT = iconv("Windows-1250", "UTF-8", $htmlKAT);
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
                        'category' => 'smrznuti'
                        //'category' => $match[2][$i]
                    ];
                }

                // echo $i . '<br>';

                //}

                //$i++;

                //}
            }

            return $this->artikli;
        /*});

        return $cache;*/
        // echo "Ima ukupno : " . count($this->artikli) . " artikala u DIS marketu!";
        //var_dump($this->artikli);
    }

    public function getDisFreeze()
    {

        $disArtikli = $this->disMarketFreeze();

        $database = [];
        foreach ($disArtikli as $dis) {
           //$explo = explode(' ', $dis['name']);
            $explo = UniverexportMarketController::multiexplode(array(" ","."),$dis['name']);
            $duzina = count($explo);

            //foreach ($explo as $ex){
            // if($duzina > 3){
            //->where('body', 'like', '%'.$explo[3].'%')
            $check = dis_freeze::where('code', $dis['code'])->first();
            if(array_key_exists(2,$explo)) {
                if ($check == null) {
                    $databasee = ['dis' => $dis, 'freeze' => Freeze::select('body', 'barcodes', 'supplementaryPriceLabel2', 'imageUrl')->whereNotNull('barcodes')->where('body', 'like', '%' . $explo[0] . '%')->where('body', 'like', '%' . $explo[1] . '%')->where('body', 'like', '%' . $explo[2] . '%')->get()];
                }else{
                    continue;
                }
            }
            // }
            //}
            //var_dump($databasee['drink']->isEmpty()); continue;
            if ($databasee['freeze']->isNotEmpty()) {
                $database[] = $databasee;
            }

        }

        return $database;
    }

    public function disMarketSweet()
    {
//        $cache = Cache::remember('dismarketsweets', 20, function () {
            ini_set('max_execution_time', 30000); //300 seconds = 5 minutes
            $this->login("http://online.dis.rs/inc/inc.nalog.prijava.php", "email=nemixbg%40gmail.com&lozinka=n3m4nj41982&radi=da");

            $drinkUrl = ['M1'];
            foreach ($drinkUrl as $driUrl) {
                $url = 'http://online.dis.rs/proizvodi.php?&kat=' . $driUrl;
                $htmlKAT = $this->grab_page($url . '&brArtPoStr=96');

                $htmlKAT = iconv("Windows-1250", "UTF-8", $htmlKAT);
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
                        'category' => 'slatkisi'
                        //'category' => $match[2][$i]
                    ];
                }

                // echo $i . '<br>';

                //}

                //$i++;

                //}
            }

            return $this->artikli;
        /*});

        return $cache;*/
        // echo "Ima ukupno : " . count($this->artikli) . " artikala u DIS marketu!";
        //var_dump($this->artikli);
    }

    public function getDisSweet()
    {

        $disArtikli = $this->disMarketSweet();

        $database = [];
        foreach ($disArtikli as $dis) {
//            $explo = explode(' ', $dis['name']);
            $explo = UniverexportMarketController::multiexplode(array(" ","."),$dis['name']);

            $duzina = count($explo);

            //foreach ($explo as $ex){
            // if($duzina > 3){
            //->where('body', 'like', '%'.$explo[3].'%')
            $check = dis_sweet::where('code', $dis['code'])->first();
            if(array_key_exists(2,$explo)) {
                if ($check == null) {
                    $databasee = ['dis' => $dis, 'sweet' => Sweets::select('body', 'barcodes', 'supplementaryPriceLabel2', 'imageUrl')->whereNotNull('barcodes')->where('body', 'like', '%' . $explo[0] . '%')->where('body', 'like', '%' . $explo[1] . '%')->where('body', 'like', '%' . $explo[2] . '%')->get()];
                }else{
                    continue;
                }
            }
            // }
            //}
            //var_dump($databasee['drink']->isEmpty()); continue;
            if ($databasee['sweet']->isNotEmpty()) {
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

    public function getViewFreeze()
    {
        return view('frontend.compareDisMarketFreeze')->with('showStore', false);
    }

    public function getViewSweet()
    {
        return view('frontend.compareDisMarketSweet')->with('showStore', false);
    }

    public function store(Request $request)
    {
        /*var_dump($request->code);
        die;*/
        dis_action_sale::truncate();
        $imageDefault = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";

        if ($request->salePrice != null) {
            $formattedPrice = $request->oldPrice . " RSD";
            $price = str_replace(',', '', $request->oldPrice);
        } else {
            $formattedPrice = $request->newPrice . " RSD";
            $price = str_replace(',', '', $request->newPrice);
        }

        if ($request->category == 'pice') {
            $article = dis_drink::firstOrNew(array('code' => $request->code));
        } elseif ($request->category == 'meso') {
            $article = dis_meat::firstOrNew(array('code' => $request->code));
        } elseif ($request->category == 'smrznuti') {
            $article = dis_freeze::firstOrNew(array('code' => $request->code));
        } elseif ($request->category == 'slatkisi') {
            $article = dis_sweet::firstOrNew(array('code' => $request->code));
        }

        $article->code = $request->code;
        $article->title = $request->name;
        $article->body = $request->name;
        $article->category = $request->category;
        $article->imageUrl = $request->imageUrl;
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
            $article->price = str_replace(',', '', $request->salePrice);
            $article->supplementaryPriceLabel1 = null;
            $article->supplementaryPriceLabel2 = null;
            $article->shop = $request->shop;
            $article->save();

        }
    }

    public function updateExistingDrinks()
    {
        $dis = $this->disMarketDrink();
        dis_action_sale::where('category', 'pice')->delete();
        $saved = true;
        $success = false;

        foreach ($dis as $disArtikli) {

            if ($disArtikli['salePrice'] != null) {
                $formattedPrice = $disArtikli['oldPrice'] . " RSD";
                $price = str_replace(',', '', $disArtikli['oldPrice']);
            } else {
                $formattedPrice = $disArtikli['newPrice'] . " RSD";
                $price = str_replace(',', '', $disArtikli['newPrice']);
            }

            if (!$price) {
                $price = null;
            }

            $article = dis_drink::where('code', $disArtikli['code'])->first();
            if(!$article) continue;

            if ($article) {
                $code = $article->code;
                $barcodes = $article->barcodes;
                $title = $article->title;
                $body = $article->body;
                $category = $article->category;
                $shop = $article->shop;
                $imageDefault = 'https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694';
                $article->formattedPrice = $formattedPrice;
                $article->price = $price;
                $saved = $article->save();

                if ($disArtikli['salePrice'] != null) {
                    $formattedPrice = $disArtikli['salePrice'] . " RSD";

                    $data = [
                        'code' => $code,
                        'title' => $title,
                        'body' => $body,
                        'barcodes' => $barcodes,
                        'category' => $category,
                        'shop' => $shop,
                        'imageDefault' => $imageDefault,
                        'formattedPrice' => $formattedPrice,
                        'price' => str_replace(',', '', $disArtikli['salePrice'])
                    ];

                    $saved = dis_action_sale::create($data);
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

        $data = [
            "success" => $success
        ];

        return response()->json($data);
    }

    public function updateExistingMeat()
    {
        $dis = $this->disMarketMeat();
        dis_action_sale::where('category', 'meso')->delete();
        $success = false;
        $saved = true;

        foreach ($dis as $disArtikli) {

            if ($disArtikli['salePrice'] != null) {
                $formattedPrice = $disArtikli['oldPrice'] . " RSD";
                $price = str_replace(',', '', $disArtikli['oldPrice']);
            } else {
                $formattedPrice = $disArtikli['newPrice'] . " RSD";
                $price = str_replace(',', '', $disArtikli['newPrice']);
            }

            if (!$price) {
                $price = null;
            }

            $article = dis_meat::where('code', $disArtikli['code'])->first();

            if ($article) {
                $code = $article->code;
                $barcodes = $article->barcodes;
                $title = $article->title;
                $body = $article->body;
                $category = $article->category;
                $shop = $article->shop;
                $imageDefault = 'https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694';
                $article->formattedPrice = $formattedPrice;
                $article->price = $price;
                $saved = $article->save();

                if ($disArtikli['salePrice'] != null) {
                    $formattedPrice = $disArtikli['salePrice'] . " RSD";

                    $data = [
                        'code' => $code,
                        'title' => $title,
                        'body' => $body,
                        'barcodes' => $barcodes,
                        'category' => $category,
                        'shop' => $shop,
                        'imageDefault' => $imageDefault,
                        'formattedPrice' => $formattedPrice,
                        'price' => str_replace(',', '', $disArtikli['salePrice'])
                    ];

                    $saved = dis_action_sale::create($data);
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

        $data = [
            "success" => $success
        ];

        return response()->json($data);
    }

    public function updateExistingFreeze()
    {
        $dis = $this->disMarketFreeze();

        $success = false;
        $saved = true;

        dis_action_sale::where('category', 'smrznuti')->delete();

        foreach ($dis as $disArtikli) {

            if ($disArtikli['salePrice'] != null) {
                $formattedPrice = $disArtikli['oldPrice'] . " RSD";
                $price = str_replace(',', '', $disArtikli['oldPrice']);
            } else {
                $formattedPrice = $disArtikli['newPrice'] . " RSD";
                $price = str_replace(',', '', $disArtikli['newPrice']);
            }

            if (!$price) {
                $price = null;
            }

            $article = dis_freeze::where('code', $disArtikli['code'])->first();

            if ($article) {
                $code = $article->code;
                $barcodes = $article->barcodes;
                $title = $article->title;
                $body = $article->body;
                $category = $article->category;
                $shop = $article->shop;
                $imageDefault = 'https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694';
                $article->formattedPrice = $formattedPrice;
                $article->price = $price;

                $saved = $article->save();
                if ($disArtikli['salePrice'] != null) {
                    $formattedPrice = $disArtikli['salePrice'] . " RSD";

                    $data = [
                        'code' => $code,
                        'title' => $title,
                        'body' => $body,
                        'barcodes' => $barcodes,
                        'category' => $category,
                        'shop' => $shop,
                        'imageDefault' => $imageDefault,
                        'formattedPrice' => $formattedPrice,
                        'price' => str_replace(',', '', $disArtikli['salePrice'])
                    ];

                    $saved = dis_action_sale::create($data);
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

        $data = [
            "success" => $success
        ];

        return response()->json($data);
    }

    public function updateExistingSweet()
    {
        $dis = $this->disMarketSweet();

        $success = false;
        $saved = true;

        dis_action_sale::where('category', 'slatkisi')->delete();

        foreach ($dis as $disArtikli) {

            if ($disArtikli['salePrice'] != null) {
                $formattedPrice = $disArtikli['oldPrice'] . " RSD";
                $price = str_replace(',', '', $disArtikli['oldPrice']);
            } else {
                $formattedPrice = $disArtikli['newPrice'] . " RSD";
                $price = str_replace(',', '', $disArtikli['newPrice']);
            }

            if (!$price) {
                $price = null;
            }

            $article = dis_sweet::where('code', $disArtikli['code'])->first();

            if ($article) {
                $code = $article->code;
                $barcodes = $article->barcodes;
                $title = $article->title;
                $body = $article->body;
                $category = $article->category;
                $shop = $article->shop;
                $imageDefault = 'https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694';
                $article->formattedPrice = $formattedPrice;
                $article->price = $price;

                $saved = $article->save();
                if ($disArtikli['salePrice'] != null) {
                    $formattedPrice = $disArtikli['salePrice'] . " RSD";

                    $data = [
                        'code' => $code,
                        'title' => $title,
                        'body' => $body,
                        'barcodes' => $barcodes,
                        'category' => $category,
                        'shop' => $shop,
                        'imageDefault' => $imageDefault,
                        'formattedPrice' => $formattedPrice,
                        'price' => str_replace(',', '', $disArtikli['salePrice'])
                    ];

                    $saved = dis_action_sale::create($data);
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

        $data = [
            "success" => $success
        ];

        return response()->json($data);
    }

}
