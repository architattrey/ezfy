<?php

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

Route::get('/storagess', function () {
    Artisan::call('storage:link');
    return "yooo";
});
Route::get('/', function () {
    // return url('/login');
    return redirect()->route('login');
});

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::post('login-dashboard','AdminController@login')->name('login-dashboard');
Route::get('dashboard','AdminController@index')->name('dashboard');
Route::get('category-actions','AdminController@categoryActions')->name('category-actions');
Route::get('sub-category-actions','AdminController@subCategoryActions')->name('sub-category-actions');
Route::get('products-actions','AdminController@getProdects')->name('products-actions');
Route::get('institutes-actions','AdminController@getInstitutes')->name('institutes-actions');
Route::get('manager-actions','AdminController@getManagers')->name('manager-actions');
Route::get('user-actions','AdminController@getUsers')->name('user-actions');
Route::get('add-coupens','AdminController@addCoupens')->name('add-coupens');
Route::get('all-transactions','AdminController@allTransactions')->name('all-transactions');
Route::get('/download-file',['as'=>'download-file', 'uses'=>'AdminController@export']);


#ajax routing 
#cateegory
Route::post('get-categories','AjaxController@getCategories');
Route::post('add-update','AjaxController@addOrUpdate');
Route::post('image-upload','AjaxController@imageUpload');
Route::post('delete-category','AjaxController@deleteCategory');

#subcategory
Route::get('get-subcategory','AjaxController@getSubcategory');
Route::post('add-update-subcategory','AjaxController@addUpdateSubcategory');
Route::post('delete-subcategory','AjaxController@deleteSubcategory');
Route::post('subcategory-image-upload','AjaxController@SubCatImageUpload');

#products
Route::get('get-products','AjaxController@getProducts');
Route::post('add-update-product','AjaxController@addUpdateProduct');
Route::post('delete-product','AjaxController@deleteProduct');

#institute
Route::get('get-institutes','AjaxController@getInstitutes');
Route::post('add-update-institute','AjaxController@addUpdateInstitute');
Route::post('delete-institute','AjaxController@deleteInstitute');

#manager
Route::post('get-managers','AjaxController@getManagers');
Route::post('add-update-manager','AjaxController@addUpdateManager');
Route::post('delete-manager','AjaxController@deleteManager');
Route::post('show-manager-credentials','AjaxController@showManagerCredentials');


#user
Route::post('get-users','AjaxController@getUsers');
Route::post('delete-user','AjaxController@deleteUser');
Route::post('show-transactions','AjaxController@showTransactions');

#coupens
Route::post('institute-wise-add-coupons','AjaxController@addInstituteWiseCoupens');

# transactions

Route::get('get-all-transactions','AjaxController@showAllTransactions');


