<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        return User::all();
    }

    public function getView()
    {
        if(Auth::user()->admin == 0){
            return view('home')->with('showStore', false);
        }else{
            return view('adminhome')->with('showStore', false);
        }
    }
}
