<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::any('api/v1/login', array('as' => 'apilogin', 'uses' => 'ApiController@login'));
Route::any('api/v1/checkToken', array('uses' => 'ApiController@checkToken', 'as' => 'checkToken'));
