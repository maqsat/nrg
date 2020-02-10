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

Route::group(['middleware' => ['jwt.auth','api-header']], function () {

    // all routes to protected resources are registered here
    Route::get('users/list', function(){
        $users = App\User::all();

        $response = ['success'=>true, 'data'=>$users];
        return response()->json($response, 201);
    });
});
Route::group(['middleware' => 'api-header'], function () {

    // The registration and login requests doesn't come with tokens
    // as users at that point have not been authenticated yet
    // Therefore the jwtMiddleware will be exclusive of them
    Route::post('user/login', 'UserController@login');
    Route::post('user/register', 'UserController@register');
    Route::post('updateprofile', 'UserController@updateprofile');
    Route::get('recommendations', 'MobileApp\RecommendationController@index');
    Route::get('courses', 'MobileApp\CourseController@index');
    Route::get('takencourse', 'MobileApp\CourseController@takenByUserCourses');
    Route::get('course', 'MobileApp\CourseController@show');
    Route::post('course', 'MobileApp\CourseController@store');
    Route::delete('course', 'MobileApp\CourseController@destroy');
    Route::get('/bookmarks', 'MobileApp\BookmarkController@index');
    Route::post('/bookmarks', 'MobileApp\BookmarkController@store');
    Route::delete('/bookmarks', 'MobileApp\BookmarkController@destroy');
    Route::post('/comment', 'MobileApp\CommentController@store');
    Route::post('/like', 'MobileApp\LikeController@store');
    Route::get('/like', 'MobileApp\LikeController@index');
    Route::post('/reply', 'MobileApp\CommentController@reply');
    Route::get('/notification', 'MobileApp\NotificationController@index');
    Route::post('/lessonview', 'UserController@userview');
    Route::get('/activity', 'UserController@getActivity');
    Route::get('/videolike', 'MobileApp\LikeController@allvideolikes');
    Route::get('/completedcourses', 'UserController@completedcourses');

    Route::get('/recommendationComment', 'MobileApp\CommentController@getallComments');
});