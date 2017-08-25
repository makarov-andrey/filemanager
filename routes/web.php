<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', 'FileController@uploadForm')->name('index');
Route::post('/load', 'FileController@upload')->name('file.upload');
Route::get('/uploaded/{file}', 'FileController@download')->name('file.download');

Route::get('/admin', 'AdminController@index')->name('admin.files');
Route::get('/admin/file/{file}', 'AdminController@editFileForm')->name('admin.file.edit.form');
Route::post('/admin/file/{file}', 'AdminController@editFile')->name('admin.file.edit');
Route::delete('/admin/file/{file}', 'AdminController@destroy')->name('admin.file.remove');

Route::get('admin/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('admin/login', 'Auth\LoginController@login');
Route::post('admin/logout', 'Auth\LoginController@logout')->name('logout');
