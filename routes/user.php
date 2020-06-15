<?php
Route::get('/', "HomeController@index");

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/category/{category:slug}','HomeController@category');
//duoc hieu la se lay category ben trong slug
// thay vi dien id thi se dung
Route::get('/product/{product:slug}','HomeController@product');

Route::post("/cart/add/{product}","HomeController@addToCart");

Route::get("/shopping-cart","HomeController@shoppingCart");
Route::get("/checkout","HomeController@checkout")->middleware("auth");
Route::post("/checkout","HomeController@placeOrder")->middleware("auth");
