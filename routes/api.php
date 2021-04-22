<?php

use Illuminate\Http\Request;

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

#user application
Route::get('/send-otp/{phone_number}','UserApiController@sendOtp');
Route::post('/user-login','UserApiController@UserLogin');
Route::post('/update-firebase-token','UserApiController@updateFireBaseToken');
Route::post('/image-upload','UserApiController@imageUpload');
Route::post('/profile-update','UserApiController@appUserProfileUpdate');
Route::get('/get-all-institutes','UserApiController@getAllInstitutes');
Route::get('/get-all-cats','UserApiController@getAllCategories');
Route::post('/get-all-subcats','UserApiController@getAllSubCategories');
Route::post('/get-all-delivers','UserApiController@oldTransactions');
Route::post('/submit-transaction','UserApiController@submitTransaction');
Route::post('/check-order-status','UserApiController@checkOrderStatus');
Route::post('/get-all-orders','UserApiController@getAllOrders');
Route::post('/get-previous-orders','UserApiController@getPreviousOrder');
Route::post('/remaining-washes','UserApiController@remainingWashes');
Route::post('/total-washes','UserApiController@washesSum');


#maager application
Route::post('/manager-login','ManagerApiController@ManagerLogin');
Route::post('/manager-update-token','ManagerApiController@updateFireBaseToken');
Route::post('/change-password','ManagerApiController@changePassword');
Route::post('/get-all-orders','ManagerApiController@getAllOrders');
Route::post('/get-order-details','ManagerApiController@getOrderDetails');
Route::post('/update-order-status','ManagerApiController@updateOrderStatus');
Route::post('/user-details','ManagerApiController@getUserDetails');






