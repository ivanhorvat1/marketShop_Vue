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

Route::get('/', [
    'uses' => 'FrontEndController@index',
    'as' => 'index'
]);

Route::get('/loginDis', [
    'uses' => 'DisMarketController@disMarket',
    'as' => 'loginDis'
]);

Route::get('/getDisArticles', [
    'uses' => 'DisMarketController@getDisArticles',
    'as' => 'getDisArticles'
]);

Route::get('/action',[
    'uses' => 'ActionSaleController@getView',
    'as' => 'freeze'
]);

Route::get('/freeze',[
    'uses' => 'FreezeController@getView',
    'as' => 'freeze'
]);
Route::get('/drinks',[
    'uses' => 'DrinkController@getView',
    'as' => 'drinks'
]);
Route::get('/sweets',[
    'uses' => 'SweetsController@getView',
    'as' => 'sweets'
]);
Route::get('/meats',[
    'uses' => 'MeatController@getView',
    'as' => 'meats'
]);
Route::get('/disDrink',[
    'uses' => 'DisMarketController@getView',
    'as' => 'disDrink'
]);

Route::get('/disMeat',[
    'uses' => 'DisMarketController@getViewMeat',
    'as' => 'disMeat'
]);

Route::get('/disFreeze',[
    'uses' => 'DisMarketController@getViewFreeze',
    'as' => 'disFreeze'
]);

Route::get('/disUpdateDrinks',[
    'uses' => 'DisMarketController@updateExistingDrinks',
    'as' => 'disUpdateDrinks'
]);
