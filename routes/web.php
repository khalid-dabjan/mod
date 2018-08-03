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
    return view('layouts.index');
});


Route::get('/verification/{token}','MailVerificationController@verification')->name('verification.mail');
Route::get('/passwordReset/{token}', 'ResetPasswordController@resetForm')->name('_password.reset');
Route::post('/passwordReset/{token}', 'ResetPasswordController@reset')->name('_password.reset');
