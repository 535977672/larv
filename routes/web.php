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

Route::prefix('test')->group(function () {
    Route::get('index', 'TestController@index');
    Route::match(['get', 'post'], 'http', 'TestController@http');
});

Route::prefix('comm')->group(function () {
    //Route::post('notification', 'CommController@notification');
    Route::match(['get', 'post'], 'notification', 'CommController@notification');
});

Route::get('/cd34/3miy/qoc4m/0jmzs', 'CheckController@machine');
Route::get('/csij/dso3/1dksl/dcns', 'CheckController@ses');
Route::get('/', 'IndexController@index');
Route::prefix('index')->group(function () {
    Route::get('/', 'IndexController@index');
    Route::get('index', 'IndexController@index');
    Route::get('main', 'IndexController@main');
    Route::match(['get'], 'hot', 'IndexController@hot');
    Route::get('see', 'IndexController@see');
    Route::get('me', 'IndexController@me');
    Route::get('detail/{id}', 'IndexController@detail');
});
Route::prefix('order')->group(function () {
    Route::post('add', 'OrderController@addOrder');
    Route::get('pay/{id}', 'OrderController@payOrder');
    Route::post('paycheck', 'OrderController@payCheck');
    Route::get('request/{type}/{id}/{num}/{price}/{guestuid}', 'OrderController@orderRequest')->where(['type' => '[12]', 'num' => '([1-9])|(10)', 'price' => '[0-9]+', 'guestuid' => '[0-9]{10}']);
});
Route::prefix('goods')->group(function () {
    Route::get('/', 'GoodsController@goods');
    Route::get('detail/{id}', 'GoodsController@goodsDetail');
    Route::get('comment/{id}', 'GoodsController@goodsComment');
    Route::get('search', 'GoodsController@search');
});


Route::prefix('member')->group(function () {
    Route::get('index', 'MemberController@index');
});


Auth::routes();
//vendor\laravel\framework\src\Illuminate\Routing\Router.php
//public function auth()
//    {
//        // Authentication Routes...
//        $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
//        $this->post('login', 'Auth\LoginController@login');
//        $this->post('logout', 'Auth\LoginController@logout')->name('logout');
//
//        // Registration Routes...
//        $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//        $this->post('register', 'Auth\RegisterController@register');
//
//        // Password Reset Routes...
//        $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//        $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//        $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//        $this->post('password/reset', 'Auth\ResetPasswordController@reset');
//    }
Route::get('/home', 'IndexController@index');






Route::prefix('admin')->group(function () {
    Route::get('login', 'Admin\LoginController@showLoginForm');
    Route::post('login', 'Admin\LoginController@login');
    Route::post('logout', 'Admin\LoginController@logout');
    
    Route::get('/', 'Admin\IndexController@index');
    Route::get('main', 'Admin\IndexController@main');
    Route::prefix('index')->group(function () {
        Route::get('/{index?}', 'Admin\IndexController@index');
    });
    Route::prefix('g')->group(function () {
        Route::get('c', 'Admin\GoodsController@check');
        Route::get('cd/{id}', 'Admin\GoodsController@checkDetail');
        Route::post('s/{id?}', 'Admin\GoodsController@save');
        Route::post('cd/', 'Admin\GoodsController@checkDel');
    });
    Route::prefix('order')->group(function () {
        Route::match(['get', 'post'], 'orderlist', 'Admin\OrderController@orderList');
        Route::post('orderdel', 'Admin\OrderController@orderDel');
        Route::get('detail/{id}', 'Admin\OrderController@detail');
        Route::get('ordergoodslist', 'Admin\OrderController@orderGoodsList');
        Route::get('paylist', 'Admin\OrderController@payList');
    });
});