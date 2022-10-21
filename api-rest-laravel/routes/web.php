<?php
// Cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

// RUTAS DE USUARIO
Route::post('/api/register/', 'UserController@register');
Route::post('/api/login/', 'UserController@login');
//Route::post('/api/user/update/', 'UserController@update');
Route::post('/api/checkToken/', 'UserController@checkToken');

// RUTAS DE CATEGORIA
Route::resource('/api/category', 'CategoryController');
Route::post('/api/category/upload', 'CategoryController@upload');
Route::get('/api/category/id/{id}', 'CategoryController@getCategoryById');


// RUTAS DE SUB-CATEGORIA
Route::resource('/api/sub-category', 'SubCategoryController');
Route::post('/api/sub-category/upload', 'SubCategoryController@upload');
Route::get('/api/sub-category/category/{id}', 'SubCategoryController@getSubCategoryByCategory');


// RUTAS DE POST
Route::resource('/api/post', 'PostController');
Route::post('/api/post/upload', 'PostController@upload');
Route::get('/api/post/image/{filename}', 'PostController@getImage');
Route::get('api/post/sub-category/{id}' , 'PostController@getPostBySubCategory');
