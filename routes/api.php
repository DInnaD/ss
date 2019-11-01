<?php


use Symfony\Component\HttpFoundation\Response;

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

/***REGISTRATION***/
Route::post('register', 'Auth\RegisterController@register');
/***END-REGISTRATION***/

/***USER-LOGIN***/
Route::post('login', 'Auth\LoginController@login');
Route::post('auth', 'SocialAuth\AuthController@socialLogin');//fb default
Route::post('auth_google', 'SocialAuth\AuthController@socialLoginGoogle');
/***END-USER-LOGIN***/



Route::post('refresh', 'AuthController@refresh');
Route::post('me', 'AuthController@me');
Route::post('password/reset','AuthController@reset');	
//Route::group(['middleware' => ['jwt.auth']], function () {
//Route::middleware('auth:api')->group(function () {
Route::group(['middleware' => ['jwt.auth']], function () {

    /***END-USER-LOGOUT***/
    Route::post('logout', 'Auth\LoginController@logout');
    /***END-USER-LGOUT***/

    /*** USER ***/
    Route::get('user/history', 'UserController@history');
    Route::get('user/profile', 'UserController@profile');
    Route::delete('user/history/{product}', 'UserController@deleteProduct');
    Route::delete('user/history/', 'UserController@deleteProductAll');

    Route::apiResource('user', 'UserController')->only([ 'index', 'show', 'update']);
	/*** END - USER ***/

	/*** PROFILE ***/

//second func
    Route::post('user/update/pass', 'UserController@updatePassword');
//end second func
    Route::apiResource('profile', 'ProfileController')->except(['show', 'store']);
	/*** END - PROFILE ***/

	/*** CATEGORY ***/
    //Route::apiResource('category', 'CategoryController');
	/*** END - CATEGORY ***/

    /*** PRODUCT ***/    
    Route::get('products/filter', 'ProductController@filter');//products/?filter

    Route::apiResource('product', 'ProductController');
    /*** END - PRODUCT ***/

	/*** COMMENT ***/
    //Route::apiResource('comment', 'CommentController');
    /*** END - COMMENT ***/

    /*** AUTH ***/
	//Route::get('user/me', 'AuthController@me');
	//Route::get('user/delete', 'AuthController@refresh');
	/*** END - AUTH ***/
});




/***ADMIN-LOGIN***/
//Route::post('admin/login', 'AdminController@login');
/***END-ADMIN-LOGIN***/

//Route::get('user/verify/{token}', 'Auth\RegisterController@verifyUser');

//postman register
//   let jsonData = pm.response.json();
// pm.environment.set("token", jsonData.data.api_token);  
