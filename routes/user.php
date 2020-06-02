<?php
Route::get('/', "HomeController@index");

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/category/{category:slug}','HomeController@category');//duoc hieu la se lay category ben trong slug
// thay vi dien id thi se dung

Route::get('/product/{product:slug}','HomeController@product');
