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

Route::group(['prefix' => 'amicus'], function () {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login')->name('login');


    Route::apiResource('comments', 'CommentsController')->except(['index']);
    Route::apiResource('subject_comments', 'SubjectCommentsController')->only(['show']);
});
