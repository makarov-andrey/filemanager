<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'AngularController@entryPoint');

Route::group(['prefix' => 'backend'], function(){
    Route::post('/load', 'FileController@upload');

});

Route::get('/uploaded/{visitor_hash}/{code}', 'FileController@download')->name('file.download');

Route::get('/admin', 'AdminController@index')->name('admin.index');
Route::get('/admin/file/{file}', 'AdminController@editFileForm')->name('admin.file.edit.form');
Route::post('/admin/file/{file}', 'AdminController@editFile')->name('admin.file.edit');
Route::delete('/admin/file/{file}', 'AdminController@destroy')->name('admin.file.remove');

Route::post('admin/login', 'Auth\LoginController@login');
Route::post('admin/logout', 'Auth\LoginController@logout')->name('logout');