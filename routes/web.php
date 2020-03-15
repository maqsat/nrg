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
use App\Models\News;

/*
************************ Auth Elements ***********************
 */
Auth::routes();

/*
************************ Pay Elements ***********************
 */
Route::get('/pay-types', 'PayController@payTypes')->middleware("auth");
Route::get('/pay-prepare', 'PayController@payPrepare')->middleware("auth");
Route::post('/pay-processing/{id}', 'PayController@payProcessing');
Route::get('/paypost', 'PayController@paypostSend')->name('paypost');// скоро нужно удалить
Route::get('/webhook/{id}', 'PayController@webhook')->name('webhook');// скоро нужно удалить

/*
************************ Test Elements ***********************
 */
Route::get('/tester', 'TestController@tester');

/*
************************ Website ***********************
 */
Route::get('/', 'PageController@welcome');
Route::get('/about', 'PageController@about');
Route::get('/products', 'PageController@products');
Route::get('/cert', 'PageController@cert');
Route::get('/faq', 'PageController@faq');
/*
************************ Profile ***********************
 */
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/invitations', 'HomeController@invitations')->name('invitations');
Route::get('/hierarchy', 'HomeController@hierarchy')->name('hierarchy');
Route::get('/tree/{id}', 'HomeController@tree')->name('tree');
Route::get('/team', 'HomeController@team')->name('team');
Route::get('/user_processing', 'HomeController@processing')->name('processing');//->middleware("activation");
Route::get('/programs' , 'HomeController@programs')->name('programs');
Route::get('/notifications', 'HomeController@notifications')->name('notifications');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::get('/faq-profile','FaqController@index');

Route::get('/rang-history', 'UserController@rangHistory')->middleware("activation");
/*
************************ Admin Control ***********************
 */
Route::get('/activation/{user_id}', 'UserController@activation')->middleware('admin');
Route::get('/deactivation/{user_id}', 'UserController@deactivation')->middleware('admin');
Route::get('/progress', 'AdminController@progress')->middleware("admin");
Route::get('/not_cash_bonuses', 'AdminController@notCashBonuses')->middleware("admin");
Route::get('/not_cash_bonuses/{not_cash_bonuses_id}/{status}', 'AdminController@notCashBonusesAnswer')->middleware("admin");
Route::get('offices_bonus', 'AdminController@offices_bonus')->middleware("admin");
Route::get('/sponsor_users', 'UserController@sponsor_users')->middleware("admin");
Route::get('/sponsor_positions', 'UserController@sponsor_positions')->middleware("admin");
Route::get('/user_offices', 'UserController@user_offices');
Route::get('user/{id}/transfer','UserController@transfer');
Route::get('user/{id}/program','UserController@program');
Route::post('user/{id}/program','UserController@programStore');


Route::resource('user', 'UserController')->middleware("admin");
Route::resource('package', 'PackageController')->middleware("admin");
Route::resource('office', 'OfficeController')->middleware("admin");
Route::resource('city', 'CityController')->middleware("admin");
Route::resource('country', 'CountryController')->middleware("admin");
/*
************************ Anything else ***********************
 */



Route::get('/bot_activation', 'AutoActivationController@bot_activation');
Route::get('/check_mentor', 'AutoActivationController@checkMentor');

Route::post('/updateProfile', 'HomeController@updateProfile')->name('updateProfile');
Route::post('/updateAvatar', 'HomeController@updateAvatar')->name('updateAvatar');
Route::get('/marketing', 'HomeController@marketing')->name('marketing');
Route::post('/transfer', 'ProcessingController@transfer')->name('transfer');
Route::get('/transfer/{status}/{processing_id}', 'ProcessingController@transferAnswer');
Route::get('/admin', 'AdminController@index')->name('admin');


Route::get('shopuser', 'UserController@getshopusers')->middleware("admin");
Route::get('clientswithoutphone', 'ClientController@getclientswithoutphone')->middleware("admin");
Route::resource('client', 'ClientController')->middleware("admin");
Route::get('/copy', 'UserController@copyUsers')->name('copy');
Route::get('/auto', 'UserController@autoActivate')->name('auto');
Route::post('/register-validate', 'UserController@registerValidate')->name('validate');
Route::resource('processing', 'ProcessingController');
Route::resource('store', 'ProductController')->middleware("admin");
Route::get('order', 'ProductController@orders');
Route::get('userorders', 'ProductController@userorders');
Route::get('basket_items/{basket_id}', 'ProductController@basket_items');
Route::resource('basket', 'BasketController')->middleware('auth');
Route::post('buycontact', 'BasketController@buycontact');
Route::get('/main-store', 'StoreController@store');

//Подробнее товара
Route::get('/product/{id}','StoreController@show');
Route::get('/story-store', 'StoreController@story');
Route::get('/activation-store', 'StoreController@activationStore');

/*Route::resource('page', 'PageController');*/



Route::get('/overview-money', 'ProcessingController@overview')->name('overview');

/*Новости*/
Route::resource('/news','NewsController')->middleware("admin");
Route::get('/faqgetadmin','FaqController@alladminfaq')->middleware("admin");
Route::get('/faqgetguest','FaqController@allguestfaq')->middleware("admin");


Route::get('/getnews',function(){
    $news=News::all();
   return  view('news.all_news',compact('news'));
});
Route::get('/getnews/{id}',function($id){
    $news=News::where('id',$id)->first();
    return  view('news.single',compact('news'));
});
Route::get('/contact',function(){
    return  view('page.contact');
});

/*Мобильный*/
Route::resource('/recommendations', 'RecommendationController');
Route::resource('/course', 'CourseController');
Route::resource('/{course_id}/lessons', 'MobileApp\LessonsController');

Route::get('userregister', function(){
    return view('auth.registeruser');
});

Route::post('/changedeliverystatus','ProductController@changedeliverystatus')->middleware("admin");
