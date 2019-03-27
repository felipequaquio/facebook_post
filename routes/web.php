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

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/facebook', 'FacebookController@getFacebookPages');
Route::post('/facebookPageMessagePost', 'FacebookController@postFacebookPage')->name('facebookPageMessagePost');
Route::post('/facebookPageImagePost', 'FacebookController@facebookImagePostPage')->name('facebookPageImagePost');

Auth::routes();

Route::get('/home', 'FacebookController@getFacebookPages');
Route::get('/login/facebook', 'Auth\LoginController@redirectToProvider');
Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback');

