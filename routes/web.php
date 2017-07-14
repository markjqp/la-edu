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

Route::get('/', function () {
    return view('welcome');

});

/*后台路由*/
//后台登陆
Route::match(['get','post'],'admin/login','Admin\LoginController@login')->name('login');

//后台登陆后才能访问的路由
Route::group(['middleware' => 'auth:admin','namespace' => 'Admin','prefix' => 'admin'],function(){
    //后台首页
    Route::get('index','AdminController@index');

    //退出
    Route::get('logout','AdminController@loginOut');

    //后台欢迎页面
    Route::get('welcome','AdminController@welcome');

    //*--管理员RBACstart--*//
    //管理员资源路由
    Route::resource('manage','ManageController');
    //管理员logo路由
    Route::post('manage/logo','ManageController@logo');
    //获取管理员列表数据
    Route::post('manage/getList','ManageController@getList');
    //批量删除管理员
    Route::post('manage/batchDel','ManageController@batchDel');

    //*--管理员end--*//

    //*--品牌start--*//
    //品牌资源路由
    Route::resource('brand','BrandController');
    //品牌logo路由
    Route::post('brand/logo','BrandController@logo');
    //获取品牌列表数据
    Route::post('brand/getList','BrandController@getList');
    //批量删除品牌
    Route::post('brand/batchDel','BrandController@batchDel');

    //*--品牌end--*//

});
