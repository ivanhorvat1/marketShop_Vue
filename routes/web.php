<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome')->with('showStore',true);
//});

Auth::routes();

Route::get('/', [
    'uses' => 'FrontEndController@index',
    'as' => 'index'
]);

Route::get('/action', [
    'uses' => 'ActionSaleController@getView',
    'as' => 'freeze'
]);

Route::get('/freeze', [
    'uses' => 'FreezeController@getView',
    'as' => 'freeze'
]);
Route::get('/drinks', [
    'uses' => 'DrinkController@getView',
    'as' => 'drinks'
]);
Route::get('/sweets', [
    'uses' => 'SweetsController@getView',
    'as' => 'sweets'
]);

Route::get('/meats', [
    'uses' => 'MeatController@getView',
    'as' => 'meats'
]);

Route::group(['middleware' => ['web', 'auth']], function () {

    Route::get('/loginDis', [
        'uses' => 'DisMarketController@disMarket',
        'as' => 'loginDis'
    ]);

    Route::get('/getDisArticles', [
        'uses' => 'DisMarketController@getDisArticles',
        'as' => 'getDisArticles'
    ]);

    Route::get('/disDrink', [
        'uses' => 'DisMarketController@getView',
        'as' => 'disDrink'
    ]);

    Route::get('/disMeat', [
        'uses' => 'DisMarketController@getViewMeat',
        'as' => 'disMeat'
    ]);

    Route::get('/disFreeze', [
        'uses' => 'DisMarketController@getViewFreeze',
        'as' => 'disFreeze'
    ]);

    Route::get('/disSweet', [
        'uses' => 'DisMarketController@getViewSweet',
        'as' => 'disSweet'
    ]);

    Route::get('/disUpdateDrinks', [
        'uses' => 'DisMarketController@updateExistingDrinks',
        'as' => 'disUpdateDrinks'
    ]);

    Route::get('/univerexportDrinks', [
        'uses' => 'UniverexportMarketController@getViewDrink',
        'as' => 'univerexportDrinks'
    ]);

//Route::get('/home', function (){
//    if(Auth::user()->admin == 0){
//        return view('home')->with('showStore', false);
//    }else{
//        $users['user'] = \App\User::all();
//        return view('adminhome', $users)->with('showStore', false);
//    }
//});

    Route::get('/home', [
        'uses' => 'AdminController@getView',
        'as' => 'home'
    ]);

});