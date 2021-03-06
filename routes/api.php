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
Route::put('online', 'API\UserController@userOnline');
Route::post('currentUser', 'API\UserController@current');
Route::get('email', 'API\UserController@uniqueEmail');
Route::get('experts', 'API\UserController@findExperts');
Route::get('searchUser', 'API\UserController@search');

//Expert Routes
Route::apiResources(['expert' => 'API\ExpertController']);

//Live Session and Time Slot Routes
Route::apiResources(['session' => 'API\LiveSessionController']);
Route::post('time_slot', 'API\LiveSessionController@storeTimeSlot');
Route::get('time_slot', 'API\LiveSessionController@getTimeSlots');
Route::delete('time_slot/{id}', 'API\LiveSessionController@destroyTimeSlot');

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
Route::get('searchProduct', 'API\ProductController@search');

//Post ROUTES
Route::apiResources(['post' => 'API\PostController']);
Route::get('mypost', 'API\PostController@show');
Route::get('searchPost', 'API\PostController@search');
Route::get('userpost/{id}', 'API\PostController@getPost');
Route::get('viewpost/{id}', 'API\PostController@singlePost');
Route::get('searchPost', 'API\PostController@search');

//Comment ROUTES
Route::apiResources(['comment' => 'API\CommentController']);

//Discussion ROUTES
Route::apiResources(['discussion' => 'API\DiscussionController']);
Route::get('mydiscussions', 'API\DiscussionController@fetchMyDiscussions');
Route::post('reply', 'API\DiscussionController@postReply');
Route::get('reply/{id}', 'API\DiscussionController@fetchAllReply');
Route::delete('reply/{id}', 'API\DiscussionController@deleteReply');
Route::put('best_answer', 'API\DiscussionController@toggleBestAnswer');
Route::put('resolved', 'API\DiscussionController@toggleResolved');

//MESSAGE ROUTES
Route::apiResources(['message' => 'API\MessageController']);
Route::get('inbox', 'API\MessageController@fetchInbox');
Route::get('sent', 'API\MessageController@fetchSent');
Route::put('delete_one_inbox', 'API\MessageController@removeOneInbox');
Route::put('delete_inbox', 'API\MessageController@deleteInbox');
Route::put('delete_sent', 'API\MessageController@deleteSent');

//CHAT ROUTES
Route::apiResources(['chat' => 'API\ChatController']);
Route::get('conversation/{id}', 'API\ChatController@getConvo');
Route::get('all_conversation', 'API\ChatController@getAllConvo');
Route::get('all_chat_count', 'API\ChatController@getAllChatCount');

//NOTIFICATIONS ROUTES
Route::apiResources(['notification' => 'API\NotificationController']);
Route::put('mark_read_notification', 'API\NotificationController@markReadNotification');
