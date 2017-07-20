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
    return view('index');
});
Route::get('/member/{id?}', 'MembersController@index')->name('member_index');
Route::post('/member/store','MembersController@store')->name('member_store');
Route::post('/member/{id}/update', 'MembersController@update');
Route::post('/member/{id}/destroy', 'MembersController@destroy');

Route::get('/test', function () {
	return "hello";
});
