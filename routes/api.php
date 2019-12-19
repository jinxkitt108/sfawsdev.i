<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Users Routes
Route::apiResources(['user' => 'API\UserController']);
Route::get('currentUser', 'API\UserController@current');
Route::get('email', 'API\UserController@uniqueEmail');
Route::get('experts', 'API\UserController@findExperts');
Route::get('searchUser', 'API\UserController@search');

//Follow and Commend Routes
Route::apiResources(['follow' => 'API\FollowController']);
Route::post('commend', 'API\FollowController@commend');
Route::post('agree', 'API\FollowController@agree');

//Profile Routes 
Route::apiResources(['profile' => 'API\ProfileController']);
Route::put('profile', 'API\ProfileController@profile');
Route::put('photo', 'API\ProfileController@updatePhoto');
Route::put('theme', 'API\ProfileController@addTheme');

//Store Routes
Route::apiResources(['store' => 'API\StoreController']);
Route::put('storephoto', 'API\StoreController@updatePhoto');
Route::put('storeCover', 'API\StoreController@updateCover');
Route::put('storeInfo', 'API\StoreController@storeInfo');
Route::get('allStores', 'API\StoreController@showAll');
Route::get('searchStore', 'API\StoreController@search');

//Product Routes
Route::apiResources(['product' => 'API\ProductController']);
Route::put('publicProducts', 'API\ProductController@togglePublic');
Route::get('allProducts', 'API\ProductController@showAll');
Route::get('latestProducts', 'API\ProductController@showLatest');
Route::get('vegetables', 'API\ProductController@getVegetables');
Route::get('fruits', 'API\ProductController@getFruits');

//POST ROUTES
Route::apiResources(['post' => 'API\PostController']);
Route::get('mypost', 'API\PostController@show');
Route::get('searchPost', 'API\PostController@search');
Route::get('userpost/{id}', 'API\PostController@getPost');

//Comment ROUTES
Route::apiResources(['comment' => 'API\CommentController']);


//Comment ROUTES
Route::apiResources(['chat' => 'API\ChatController']);

