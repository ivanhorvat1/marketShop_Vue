<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FrontEndController extends Controller
{
    public function index()
    {
        return view('welcome')->with('showStore', true);
    }

    public function checkAuth()
    {
        return json_encode(Auth::check());
    }

    function file_get_contents_curl($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function getImage()
    {
        $categories=['food,steak,bbq','juice,beverage,food','food,dessert,fruit','vegetables,market-stall,food','food,dessert,sweet','beverage,drink,smoothie'];

        foreach ($categories as $category){
            $data = $this->file_get_contents_curl(
                'https://source.unsplash.com/1600x900/?'.$category);

            preg_match_all('<a(.*?)href="([^"]+)"(.*?)>', $data, $matches);

            $data = $this->file_get_contents_curl(
                $matches[2][0]);

            $fp = 'images/homePage/'.$category.'.png';

            file_put_contents( $fp, $data );
            echo "File downloaded!";
        }

    }

    /*public function login1(){

        include('simple_html_dom.php');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://online.dis.rs/inc/inc.nalog.prijava.php?radi=da');
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "email=kja89560%40zwoho.com&lozinka=123456");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie-name');  //could be empty, but cause problems on some hosts
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');  //could be empty, but cause problems on some hosts
        $file_contents = curl_exec($ch);
        if (curl_error($ch)) {
            echo curl_error($ch);
        }
//another request preserving the session

        //curl_setopt($ch, CURLOPT_URL, 'http://online.dis.rs/proizvodi.php?brArtPoStr=96');
        curl_setopt($ch, CURLOPT_URL, 'http://online.dis.rs/proizvodi.php?brArtPoStr=350&kat=P1');
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        $file_contents = curl_exec($ch);

        echo $file_contents;

//        $html = str_get_html($file_contents);
//        if (!empty($html)) {
//            $elem = $html->find('div#artikal', 0);
//            echo $elem;
//        }

        if (curl_error($ch)) {
            echo curl_error($ch);
        }
    }*/

}
