<?php

use Illuminate\Support\Facades\Route;

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

Route::post('items/update', 'ItemController@saveItemData')->name('items.save');
Route::get('items/all', 'ItemController@listAllItems')->name('items.list');
Route::get('items/{item}', 'ItemController@retrieveItemData')->name('items.info');



Route::get('itemtracker/{item}', 'ItemTrackerController@trackItem')->name('itemtracker.item');

