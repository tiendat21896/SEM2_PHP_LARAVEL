<?php

Route::get("/","Webcontroller@dashboard");
//    Category
Route::get('/list-category', 'WebController@listCategory');
Route::get('/new-category', 'WebController@newCategory');
Route::post('/save-category', 'WebController@saveCategory');

Route::get('/list-brand', 'WebController@listBrand');
Route::get('/new-brand', 'WebController@newBrand');
Route::post('/save-brand', 'WebController@saveBrand');

//update -Delete Category
Route::get("/edit-category/{id}", "WebController@editCategory");
Route::put("/update-category/{id}", "WebController@updateCategory"); //cap nhat du lieu
Route::delete("/delete-category/{id}", "WebController@deleteCategory");


//update -delete Brand
Route::get("/edit-brand/{id}", "WebController@editBrand");
Route::put("/update-brand/{id}", "WebController@updateBrand"); //cap nhat du lieu
Route::delete("/delete-brand/{id}", "WebController@deleteBrand");


//Product
Route::get('/list-product', 'WebController@listProduct');
Route::get('/new-product', 'WebController@newProduct');
Route::post('/save-product', 'WebController@saveProduct');


//update -delete Product
Route::get("/edit-product/{id}", "WebController@editProduct");
Route::put("/update-product/{id}", "WebController@updateProduct"); //cap nhat du lieu
Route::delete("/delete-product/{id}", "WebController@deleteProduct");
