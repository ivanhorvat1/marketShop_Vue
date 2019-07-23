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

//Admin Methods
Route::get('fetchAllUsers', 'AdminController@index');
Route::post('update_user', 'AdminController@updateUser');
Route::post('remove_user', 'AdminController@removeUser');
Route::get('getAllFreezeArticles', 'AdminController@getAllFreezeArticles');

// List articles
Route::get('articles', 'ArticleController@index');
Route::get('action_sale_fetch', 'ActionSaleController@index');
Route::get('action_action_fetch_separate', 'ActionSaleController@getSeparatedMarket');
Route::get('action_action_fetch_compare_dynamically', 'ActionSaleController@compareDynamically');
Route::get('action_drink_fetch', 'DrinkController@index');
Route::get('action_drink_fetch_separate', 'DrinkController@getSeparatedMarket');
Route::get('drinks_fetch_compare_dynamically', 'DrinkController@compareDynamically');
Route::get('action_meat_fetch', 'MeatController@index');
Route::get('action_meat_fetch_separate', 'MeatController@getSeparatedMarket');
Route::get('meats_fetch_compare_dynamically', 'MeatController@compareDynamically');
Route::get('action_sweet_fetch', 'SweetsController@index');
Route::get('action_sweet_fetch_separate', 'SweetsController@getSeparatedMarket');
Route::get('sweets_fetch_compare_dynamically', 'SweetsController@compareDynamically');
Route::get('action_freeze_fetch', 'FreezeController@index');
Route::get('action_freeze_fetch_separate', 'FreezeController@getSeparatedMarket');
Route::get('freeze_fetch_compare_dynamically', 'FreezeController@compareDynamically');
//Dis market
Route::get('compare_dis_market_drink', 'DisMarketController@getDisDrinks');
Route::get('compare_dis_market_meat', 'DisMarketController@getDisMeat');
Route::get('compare_dis_market_freeze', 'DisMarketController@getDisFreeze');
Route::get('compare_dis_market_sweet', 'DisMarketController@getDisSweet');
Route::get('dis_update_drinks', 'DisMarketController@updateExistingDrinks');
Route::get('dis_update_meat', 'DisMarketController@updateExistingMeat');
Route::get('dis_update_freeze', 'DisMarketController@updateExistingFreeze');
Route::get('dis_update_sweet', 'DisMarketController@updateExistingSweet');
//univerexport
Route::get('compare_univerexport_market_drink', 'UniverexportMarketController@getUniverexportMarketDrink');
Route::get('univer_update_drinks', 'UniverexportMarketController@updateExistingUniverDrinks');

// List single article
Route::get('article/{id}', 'ArticleController@show');

// Create new article
Route::post('article', 'ArticleController@store');
Route::post('action_sale_store', 'ActionSaleController@store');
Route::post('action_drink_store', 'ActionSaleController@store');
Route::post('storeDisArticles', 'DisMarketController@store');
Route::post('storeUniverexportArticles', 'UniverexportMarketController@store');

// Update article
Route::put('article', 'ArticleController@store');

// Delete article
Route::delete('article/{id}', 'ArticleController@destroy');


