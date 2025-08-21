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
Route::prefix('app')->group( function() {
    Route::post('/login', 'AppController@login')->name('app.login');
    Route::post('/sendSms', 'AppController@sendSms')->name('app.sendSms');
    Route::post('/create', 'AppController@createCard')->name('app.create');
    Route::post('/info', 'AppController@fetchCardInfo')->name('app.info');
    Route::post('/use', 'AppController@usePoint')->name('app.use');
    Route::post('/transfer', 'AppController@cardTransfer')->name('app.transfer');
});

Route::prefix('portal')->group(function () {
    Route::post('like-site', 'PortalApiController@likeSite');
    Route::post('unlike-site', 'PortalApiController@unlikeSite');
    Route::post('like-cast', 'PortalApiController@likeCast');
    Route::post('unlike-cast', 'PortalApiController@unlikeCast');
    Route::get('check-liked-site', 'PortalApiController@checkLikedSite');
    Route::get('check-liked-casts', 'PortalApiController@checkLikedCasts');
    // Route::get('review-links', 'ReviewController@getReviewLinks');
    Route::get('access-token', 'PortalApiController@getAccessToken');
    Route::get('/user/me', 'PortalApiController@checkUserToken');
    
    Route::get('articles', 'PortalApiController@fetchArticles');
});