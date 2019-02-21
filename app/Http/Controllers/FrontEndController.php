<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontEndController extends Controller
{
    public function index(){
        return view('welcome')->with('showStore',true);
    }

    public function login(){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://online.dis.rs/inc/inc.nalog.prijava.php?radi=da');
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "email=kja89560%40zwoho.com&lozinka=123456");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie-name');  //could be empty, but cause problems on some hosts
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');  //could be empty, but cause problems on some hosts
        $answer = curl_exec($ch);
        if (curl_error($ch)) {
            echo curl_error($ch);
        }
//another request preserving the session

        //curl_setopt($ch, CURLOPT_URL, 'http://online.dis.rs/proizvodi.php?brArtPoStr=96');
        curl_setopt($ch, CURLOPT_URL, 'http://online.dis.rs/proizvodi.php?brArtPoStr=96&kat=P1');
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        $answer = curl_exec($ch);

        echo $answer;

        if (curl_error($ch)) {
            echo curl_error($ch);
        }
    }
}
