<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


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

Route::post('user/login', 'App\Http\Controllers\UserController@login');
Route::post('user/register', 'App\Http\Controllers\UserController@register');
Route::post('user/reset', 'App\Http\Controllers\UserController@reset');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'App\Http\Controllers\AdminController@login');
Route::post('register', 'App\Http\Controllers\AdminController@register');
Route::middleware('auth:admin')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('vendor/register', 'App\Http\Controllers\VendorController@register');

Route::get('/email/resend', 'App\Http\Controllers\VerificationController@resend')->name('verification.resend');

Route::get('/email/verify/{id}/{hash}', 'App\Http\Controllers\VerificationController@verify')->name('verification.verify');

Route::post('send-sms', 'App\Http\Controllers\SMSController@index');

Route::post('reset', 'App\Http\Controllers\UserController@reset');
Route::post('forgotpassword', 'App\Http\Controllers\ResetPasswordController@forgotPassword');
Route::post('token', 'App\Http\Controllers\UserController@token');



