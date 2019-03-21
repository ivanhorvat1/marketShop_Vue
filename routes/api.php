<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// List articles
Route::get('articles', 'ArticleController@index');
Route::get('action_sale_fetch', 'ActionSaleController@index');
Route::get('action_drink_fetch', 'DrinkController@index');
Route::get('action_meat_fetch', 'MeatController@index');
Route::get('action_sweet_fetch', 'SweetsController@index');
Route::get('action_freeze_fetch', 'FreezeController@index');
Route::get('compare_dis_market_drink', 'DisMarketController@getDisDrinks');
Route::get('compare_dis_market_meat', 'DisMarketController@getDisMeat');
Route::get('dis_update_drinks', 'DisMarketController@updateExistingDrinks');

// List single article
Route::get('article/{id}', 'ArticleController@show');

// Create new article
Route::post('article', 'ArticleController@store');
Route::post('action_sale_store', 'ActionSaleController@store');
Route::post('action_drink_store', 'ActionSaleController@store');
Route::post('storeDisArticles', 'DisMarketController@store');

// Update article
Route::put('article', 'ArticleController@store');

// Delete article
Route::delete('article/{id}', 'ArticleController@destroy');


