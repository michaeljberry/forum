<?php

use App\Http\Controllers\RepliesController;

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
    return view('welcome');
});

Auth::routes();

/**
 * Threads
 */
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/threads', 'ThreadsController@index')->name('threads');
Route::get('/threads/create', 'ThreadsController@create')->name('create-thread');
Route::post('/threads', 'ThreadsController@store');

/**
 * Channels
 */
Route::get('/threads/{channel}', 'ThreadsController@index')->name('channel');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show')->name('thread');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');

/**
 * Replies
 */
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index')->name('replies');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::patch('replies/{reply}', 'RepliesController@update');
Route::delete('/replies/{reply}', 'RepliesController@destroy');

/**
 * Favorites
 */
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');

/**
 * Profile
 */
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');