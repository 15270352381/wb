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
//首页
Route::get('/', 'StaticPagesController@home')->name('home');
//帮助页
Route::get('/help', 'StaticPagesController@help')->name('help');
//关于页
Route::get('/about', 'StaticPagesController@about')->name('about');
//注册页
Route::get('signup', 'UsersController@create')->name('signup');
//对用户数据进行增删改查
Route::resource('users', 'UsersController');
//登录
Route::get('login', 'SessionsController@create')->name('login');
//登录逻辑
Route::post('login', 'SessionsController@store')->name('login');
//退出登录
Route::delete('logout', 'SessionsController@destroy')->name('logout');
//编辑正在登录的用户信息
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
//用户激活
Route::get('/signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
//重置密码
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//发送密码重置邮件
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//激活用户
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//修改成功
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

