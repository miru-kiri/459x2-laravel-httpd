<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\SiteDetailPageController;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

Route::get('/storage/{any}', function ($path) {
    // Đường dẫn tới storage gốc (site chính)
    $basePath = '/var/www/vhosts/sub0000531330.hmk-temp.com/httpdocs/storage/app/public/';
    
    $fullPath = $basePath . $path;

    if (!file_exists($fullPath)) {
        abort(404, 'File not found');
    }

    // Lấy MIME type của file
    $mimeType = File::mimeType($fullPath);

    // Trả file về client
    return Response::file($fullPath, [
        'Content-Type' => $mimeType
    ]);
})->where('any', '.*'); // cho phép tất cả sub-path

Route::get('/', function (Request $request) {
    $request->merge(['genre_id' => 2]);

    $controller = app(SiteDetailPageController::class);
    return $controller->index($request);
})->name('site');
Route::get('/portal', 'SitePageController@portalIndex')->name('portal');
Route::get('/member/register', 'MyPageController@signupPortal')->name('signup.portal');
Route::get('/notice', 'SitePageController@notice')->name('site.notice');
Route::post('/like', 'SitePageController@siteLike')->name('site.like');
Route::post('/like/cast', 'SitePageController@siteCastLike')->name('site.like.cast');
Route::get('user/login', 'MyPageController@login');
Route::get('user/logout', 'MyPageController@logout');
Route::get('user/like-cast', 'MyPageController@top');
Route::get('user/reservation', 'MyPageController@reserveCoursePage');

Route::get('sites/portal', 'SitePageController@portalIndex')->name('site.portal');
Route::get('sites/member/register', 'MyPageController@signupPortal')->name('site.signup.portal');
Route::get('sites/user/login', 'MyPageController@login');
Route::get('sites/user/logout', 'MyPageController@logout');
Route::get('sites/user/like-cast', 'MyPageController@top');
Route::get('sites/user/reservation', 'MyPageController@reserveCoursePage');
Route::get('/underage', function () {
    return view('sitePages.underage');
});
Route::post('store-review', 'ReviewController@storeReview')->name('store.review');
Route::prefix('log')->group( function() {
    Route::post('/top', 'SitePageController@topLog')->name('site.log.top');
    Route::post('/cast', 'SitePageController@castLog')->name('site.log.cast');
    Route::post('/blog', 'SitePageController@blogLog')->name('site.log.blog');
    Route::post('/site', 'SitePageController@siteLog')->name('site.log.site');
});
Route::get('/search', 'SitePageController@search')->name('site.search');
Route::get('/search/detail', 'SitePageController@searchDetail')->name('site.search.detail');
Route::post('/search/detail', 'SitePageController@searchDetail')->name('site.search.detail');
Route::get('/guide', 'SitePageController@guide')->name('site.guide');
Route::get('/manual', 'SitePageController@manual')->name('site.manual');
Route::get('/dogo_kabuki', 'SitePageController@dogo_kabuki')->name('site.dogo_kabuki');
Route::get('/health_bill', 'SitePageController@health_bill')->name('site.health_bill');//krn 2025.5.21追加
Route::get('/all_movies', 'SitePageController@all_movies')->name('site.all_movies');//krn 2025.6.10追加
Route::get('/recruit_dogo_matuyama', 'SitePageController@recruit_dogo_matuyama')->name('site.recruit_dogo_matuyama');//krn 2025.6.17追加
Route::get('/recruit_top', 'SitePageController@recruit_top')->name('site.recruit_top');//krn 2025.6.17追加
Route::get('/recruit_kotohira', 'SitePageController@recruit_kotohira')->name('site.recruit_kotohira');//krn 2025.6.17追加
Route::get('/recruit_nihama', 'SitePageController@recruit_nihama')->name('site.recruit_nihama');//krn 2025.6.17追加
Route::get('/recruit_sikokutyuou', 'SitePageController@recruit_sikokutyuou')->name('site.recruit_sikokutyuou');//krn 2025.6.17追加
Route::get('/recruit_takamatu', 'SitePageController@recruit_takamatu')->name('site.recruit_takamatu');//krn 2025.6.17追加

Route::prefix('detail')->group( function() {
    Route::get('/', 'SiteDetailPageController@index')->name('site.detail');
    Route::get('/area', 'SiteDetailPageController@area')->name('site.detail.area');
    Route::get('/top', 'SiteDetailPageController@top')->name('site.detail.top');
    Route::get('/work', 'SiteDetailPageController@work')->name('site.detail.work');
    Route::get('/cast', 'SiteDetailPageController@cast')->name('site.detail.cast');
    Route::get('/cast/detail', 'SiteDetailPageController@castDetail')->name('site.detail.cast.detail');
    Route::get('/price', 'SiteDetailPageController@price')->name('site.detail.price');
    Route::get('/shop', 'SiteDetailPageController@shop')->name('site.detail.shop');
    Route::get('/event', 'SiteDetailPageController@event')->name('site.detail.event');
    Route::get('/diary', 'SiteDetailPageController@diary')->name('site.detail.diary');
    Route::get('/recruit', 'SiteDetailPageController@recruit')->name('site.detail.recruit');
    Route::get('/blogManager', 'SiteDetailPageController@blogManager')->name('site.detail.blogManager');
    Route::get('/blog/detail', 'SiteDetailPageController@blogDetail')->name('site.detail.blog.detail');
});
Route::prefix('mypage')->group( function() {
    Route::get('/top', 'MyPageController@top')->name('mypage.top');
    Route::get('/favorite', 'MyPageController@favorite')->name('mypage.favorite');
    Route::post('/favorite/delete/{id}', 'MyPageController@favoriteDelete')->name('mypage.favorite.delete');
    Route::post('/favorite/delete/shop/{id}', 'MyPageController@favoriteShopDelete')->name('mypage.favorite.shop.delete');
    Route::get('/history', 'MyPageController@history')->name('mypage.history');
    Route::get('/point', 'MyPageController@point')->name('mypage.point');
    Route::get('/review', 'MyPageController@review')->name('mypage.review');
    Route::post('/review', 'MyPageController@reviewCreate')->name('mypage.review');
    Route::get('/setting', 'MyPageController@setting')->name('mypage.setting');
    Route::get('/setting/edit', 'MyPageController@settingEditPage')->name('mypage.setting.edit.page');
    Route::post('/setting/edit', 'MyPageController@settingEdit')->name('mypage.setting.edit');
    Route::post('/setting/edit/birthday', 'MyPageController@settingEditBirthDay')->name('mypage.setting.edit.birthday');
    Route::post('/phone/auth', 'MyPageController@phoneAuth')->name('mypage.phone.auth');
    Route::get('/phone/auth', 'MyPageController@phoneAuthPage')->name('mypage.phone.auth.page');
    Route::post('/phone/confirm', 'MyPageController@phoneConfirm')->name('mypage.phone.confirm');
    Route::post('/email/auth', 'MyPageController@emailAuth')->name('mypage.email.auth');
    Route::get('/email/auth', 'MyPageController@emailAuthPage')->name('mypage.email.auth.page');
    Route::post('/email/confirm', 'MyPageController@emailConfirm')->name('mypage.email.confirm');
    Route::post('/password/auth', 'MyPageController@passwordAuth')->name('mypage.password.auth');
    Route::get('/password/confirm', 'MyPageController@passwordConfirmPage')->name('mypage.password.confirm');
    Route::get('/password/end', 'MyPageController@passwordEndPage')->name('mypage.password.end');
    Route::post('/password/confirm', 'MyPageController@passwordConfirm')->name('mypage.password.confirm');
    Route::prefix('login')->group( function() {
        Route::get('/', 'MyPageController@login')->name('mypage.login');
        Route::post('/auth', 'MyPageController@loginAuth')->name('mypage.login.auth');
        Route::post('/checkToken', 'MyPageController@checkUserToken')->name('mypage.check.token');
    });
    Route::get('/logout', 'MyPageController@logout')->name('mypage.logout');
    Route::get('/signup', 'MyPageController@signup')->name('mypage.signup');
    Route::get('/password/forget', 'MyPageController@passwordForgetPage')->name('mypage.password.forget');
    Route::post('/password/forget', 'MyPageController@passwordForget')->name('mypage.password.forget');
    Route::get('/password/code', 'MyPageController@passwordForgetCodePage')->name('mypage.password.code');
    Route::post('/password/code', 'MyPageController@passwordForgetCode')->name('mypage.password.code');
    Route::get('/password/forget/confirm', 'MyPageController@passwordForgetConfirmPage')->name('mypage.password.confirm');
    Route::post('/password/forget/confirm', 'MyPageController@passwordForgetConfirm')->name('mypage.password.confirm');
    Route::get('/signupSms', 'MyPageController@signupSmsPage')->name('mypage.signup.sms.page');
    Route::get('/reserve/course', 'MyPageController@reserveCoursePage')->name('mypage.reserve.course');
    Route::get('/reserve/indicate', 'MyPageController@reserveCoursePage')->name('mypage.reserve.indicate');
    Route::get('/reserve/calender', 'MyPageController@reserveCalenderPage')->name('mypage.reserve.calender');
    Route::get('/reserve/confirm', 'MyPageController@reserveConfirmPage')->name('mypage.reserve.confirm');
    Route::post('/reserve/confirm', 'MyPageController@reserveConfirm')->name('mypage.reserve.confirm');
    Route::get('/reserve/free', 'MyPageController@reserveFreePage')->name('mypage.reserve.free');
    Route::post('/signupSms', 'MyPageController@signupSms')->name('mypage.signup.sms');
    Route::post('/signupSms/again', 'MyPageController@signupSmsAgain')->name('mypage.signup.sms.again');
    Route::post('/signupConfirm', 'MyPageController@signupConfirm')->name('mypage.signup.confirm');
    Route::get('/forget', 'MyPageController@forget')->name('mypage.forget');
    Route::get('/contact', 'MyPageController@contact')->name('mypage.contact');
    Route::post('/contact', 'MyPageController@contactRegistration')->name('mypage.contact.registration');
    Route::post('/filtering/cast', 'MyPageController@fetchCastFilteringData')->name('mypage.filtering.cast');
    Route::get('/withdrawal', 'MyPageController@withdrawalPage')->name('mypage.withdrawal');
    Route::post('/withdrawal', 'MyPageController@withdrawal')->name('mypage.withdrawal');
    Route::get('/withdrawal/confirm', 'MyPageController@withdrawalConfirmPage')->name('mypage.withdrawal.confirm');
    Route::get('/withdrawal/end', 'MyPageController@withdrawalEnd')->name('mypage.withdrawal.end');
    Route::get('/message', 'MyPageController@messagePage')->name('mypage.message');
    Route::get('/message/cast', 'MyPageController@messageCastPage')->name('mypage.message.cast');
    Route::post('/message/cast', 'MyPageController@messageCastCreate')->name('mypage.message.cast');
    Route::get('/message/site', 'MyPageController@messageSitePage')->name('mypage.message.site');
    Route::get('/message/site/detail', 'MyPageController@messageSiteDetailPage')->name('mypage.message.site.detail');
    Route::post('/message/site', 'MyPageController@messageSiteCreate')->name('mypage.message.site');
    Route::post('/message/site/replies', 'MyPageController@messageSiteReplies')->name('mypage.message.site.replies');
});

// Route::get('/test-img', function () {
//     $path = '/var/www/vhosts/sub0000531330.hmk-temp.com/httpdocs/storage/app/public/csm_movie/douga/28/free/28_free_2729.mp4';

//     if (!file_exists($path)) {
//         abort(404, 'File not found');
//     }

//     header('Content-Type: video/mp4');
//     header('Content-Length: ' . filesize($path));
//     readfile($path);
//     exit;
// });

//サイトページ

    /*******
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     *                                           エステ開発用 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     */
    //エステ開発用↓
    //エステトップページ
    Route::get('/', 'EstsController@index')->name('este.index'); 
    //エステトップ>エリアトップページ
    Route::get('/{url_area}', 'Ests_AreaController@index')->name('este_area.index'); 
    //エステトップ>エリア>ショップトップページ
    Route::get('/{url_area}/{url_siteid}', 'Ests_SiteController@index')->name('este_area.site.index');
    //エステトップ>エリア>ショップ>各コンテンツページ
    //コードが多岐になるならコントローラーを変更するかもしれないね。それか関数を変更するか・・・
    //area>site>contents
    Route::get('/{url_area}/{url_siteid}/{url_contents}', 'Ests_SiteController@contents')->name('este_area.site.contents');
    //area>site>contents>contentid
    Route::get('/{url_area}/{url_siteid}/{url_contents}/{url_contentid}', 'Ests_SiteController@contentId')->name('este_area.site.contentId');
    //post ajaxなど
    Route::post('/ests/post/ctl', 'EstsPostController@index')->name('ests.post.menu');





    //↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑エステ開発ここまで↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑