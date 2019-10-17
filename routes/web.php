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

//Scheduler

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

Route::get('/test', [
    'uses' => 'FrontEndController@getImage',
    'as' => 'test'
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

    Route::get('/univerexportFreeze', [
        'uses' => 'UniverexportMarketController@getViewFreeze',
        'as' => 'univerexportFreeze'
    ]);

    Route::get('/univerexportSweets', [
        'uses' => 'UniverexportMarketController@getViewSweets',
        'as' => 'univerexportSweets'
    ]);

    Route::get('/univerexportMeats', [
        'uses' => 'UniverexportMarketController@getViewMeats',
        'as' => 'univerexportMeats'
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

    Route::get('/getMaxiActionScheduler', [
        'uses' => 'MaxiScheduleController@getMaxiAction',
        'as' => 'getMaxiActionScheduler'
    ]);

    Route::get('/getMaxiDrinkScheduler', [
        'uses' => 'MaxiScheduleController@getMaxiDrink',
        'as' => 'getMaxiDrinkScheduler'
    ]);

    Route::get('/getMaxiMeatsScheduler', [
        'uses' => 'MaxiScheduleController@getMaxiMeats',
        'as' => 'getMaxiMeatsScheduler'
    ]);

    Route::get('/getMaxiSweetsScheduler', [
        'uses' => 'MaxiScheduleController@getMaxiSweets',
        'as' => 'getMaxiSweetsScheduler'
    ]);

    Route::get('/getMaxiFreezeScheduler', [
        'uses' => 'MaxiScheduleController@getMaxiFreeze',
        'as' => 'getMaxiFreezeScheduler'
    ]);

    Route::get('/deleteRecordsIdea', [
        'uses' => 'IdeaScheduleController@deleteRecords',
        'as' => 'deleteRecordsIdea'
    ]);

    Route::get('/getIdeaActionScheduler', [
        'uses' => 'IdeaScheduleController@getIdeaAction',
        'as' => 'getIdeaActionScheduler'
    ]);

    Route::get('/getIdeaDrinkScheduler/{categoryNumber}/{data}', [
        'uses' => 'IdeaScheduleController@getIdeaDrink',
        'as' => 'getIdeaDrinkScheduler'
    ]);

    Route::get('/getIdeaMeatScheduler/{categoryNumber}/{data}', [
        'uses' => 'IdeaScheduleController@getIdeaMeat',
        'as' => 'getIdeaMeatScheduler'
    ]);

    Route::get('/getIdeaMeat2Scheduler/{categoryNumber}/{data}', [
        'uses' => 'IdeaScheduleController@getIdeaMeat2',
        'as' => 'getIdeaMeat2Scheduler'
    ]);

    Route::get('/getIdeaSweetScheduler/{categoryNumber}/{data}', [
        'uses' => 'IdeaScheduleController@getIdeaSweet',
        'as' => 'getIdeaSweetScheduler'
    ]);

    Route::get('/getIdeaFreezeScheduler/{categoryNumber}/{data}', [
        'uses' => 'IdeaScheduleController@getIdeaFreeze',
        'as' => 'getIdeaFreezeScheduler'
    ]);

    Route::get('/getDisDrinksScheduler', [
        'uses' => 'DisMarketController@updateExistingDrinks',
        'as' => 'getDisDrinksScheduler'
    ]);

    Route::get('/getDisMeatScheduler', [
        'uses' => 'DisMarketController@updateExistingMeat',
        'as' => 'getDisMeatScheduler'
    ]);

    Route::get('/getDisFreezeScheduler', [
        'uses' => 'DisMarketController@updateExistingFreeze',
        'as' => 'getDisFreezeScheduler'
    ]);

    Route::get('/getDisSweetScheduler', [
        'uses' => 'DisMarketController@updateExistingSweet',
        'as' => 'getDisSweetScheduler'
    ]);

    Route::get('/univer', [
        'uses' => 'UniverexportMarketController@curlAllUniverexport',
        'as' => 'univer'
    ]);

    Route::get('/deleteCachedData', [
        'uses' => 'IdeaScheduleController@deleteCachedData',
        'as' => 'deleteCachedData'
    ]);

});