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

    public function getImage()
    {

        /*$html = "
        <div class='box'>
          Generated from PHP ✅
        </div>
        ";

                $css = "
        .box { 
          border: 4px solid #03B875; 
          padding: 20px; 
          font-family: 'Roboto'; 
        }";

        $google_fonts = "Roboto";

        $data = array('html' => $html,
            'css' => $css,
            'google_fonts' => $google_fonts);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://hcti.io/v1/image");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_POST, 1);
// Retrieve your user_id and api_key from https://htmlcsstoimage.com/dashboard
        curl_setopt($ch, CURLOPT_USERPWD, "2307eeb5-c99d-4b84-ba7d-a2a325ee5466" . ":" . "c58ef55f-71fc-4b46-b485-164243fd5974");

        $headers = array();
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $res = json_decode($result, true);
        echo $res['url'];
// https://hcti.io/v1/image/202dc04d-5efc-482e-8f92-bb51612c84cf*/

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
