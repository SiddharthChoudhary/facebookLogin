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

Auth::routes();

Route::get('/', function () {
    return view('loginpage.login');
});

Route::get('login/fb/callback',['as'=>'loginCallback','uses'=>'LoginController@loginCallback']);
Route::get('/',['as'=>'login','uses'=>'LoginController@login']);
Route::get('login/fb',['as'=>'loginFB','uses'=>'LoginController@loginFB']);
Route::get('logout',['as'=>'logout','uses'=>'LoginController@logout']);
Route::get('/home', 'HomeController@index')->name('home');
