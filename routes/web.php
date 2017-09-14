<?php

use Illuminate\Support\Facades\Route;

Route::prefix('backend')->group(function(){
    Route::post('tempfile', 'FileController@upload');
    Route::delete('tempfile/{code}', 'FileController@destroyTempFile');
    Route::post('file-properties', 'FileController@fileProperties');
});

Route::get('/uploaded/{visitor_hash}/{code}', 'FileController@download')->name('file.download');

Route::get('/admin', 'AdminController@index')->name('admin.index');
Route::get('/admin/file/{file}', 'AdminController@editFileForm')->name('admin.file.edit.form');
Route::post('/admin/file/{file}', 'AdminController@editFile')->name('admin.file.edit');
Route::delete('/admin/file/{file}', 'AdminController@destroy')->name('admin.file.remove');

Route::get('admin/login', 'Auth\LoginController@showLoginForm');
Route::post('admin/login', 'Auth\LoginController@login');
Route::post('admin/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'AngularController@entryPoint')->name('index');

Route::get('/test', function () {
    \App\TemporaryStorage\TemporaryStorage::removeOldFiles();
});