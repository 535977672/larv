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
Route::get('/', 'IndexController@index');
Route::prefix('index')->group(function () {
    Route::get('index', 'IndexController@index');
    Route::get('main', 'IndexController@main');
    Route::match(['get', 'post'], 'search', 'IndexController@search');
    Route::get('see', 'IndexController@see');
    Route::get('me', 'IndexController@me');
    Route::get('detail/{id}', 'IndexController@detail');
});


Route::prefix('member')->group(function () {
    Route::post('login', 'MemberController@login');
    Route::post('register', 'MemberController@register');
});


Auth::routes();
Route::get('/home', 'IndexController@index');
