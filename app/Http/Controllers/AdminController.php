<?php

namespace App\Http\Controllers;

use App\dis_freeze;
use App\Freeze;
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

    public function updateUser(Request $request)
    {
        $success = false;
        $saved = false;

        $article = User::firstOrNew(array('id'=>$request->id));
        $article->name = $request->name;
        $article->admin = $request->admin;
        $saved = $article->save();

        if ($saved) {
            return response()->json([
                "success" => "Successfully updated"
            ]);
        } else {
            return response()->json([
                "success" => "Error while updating"
            ]);
        }
    }

    public function removeUser(Request $request){
        $saved = User::where('id',$request->id)->delete();

        if ($saved) {
            return response()->json([
                "success" => "Successfully removed"
            ]);
        } else {
            return response()->json([
                "success" => "Error while removing"
            ]);
        }
    }

    public function getAllFreezeArticles()
    {
        $maxi = Freeze::where('category', 'smrznuti')->whereNotNull('barcodes')->get();
        $dis = dis_freeze::orderBy('price', 'DESC')->get();

        return $merged = $maxi->merge($dis);
    }
}
