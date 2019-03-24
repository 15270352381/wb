<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    /**
     * 登录页
     */
    public function create()
    {
    	return view('sessions.create');
    }
    /**
     * 登录验证及逻辑
     */
    public function store(Request $request)
    {
    	//注册数据验证
    	$credentials = $this->validate($request, [
    			'email' => 'required|email|max:255',
    			'password' => 'required|min:6'
    		]);
    	//attempt 方法实现登录认证
    	if (Auth::attempt($credentials, $request->has('remember'))) {

           session()->flash('success', '欢迎回来！');
           return redirect()->route('users.show', [Auth::user()]);
        } else {
           session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
           return redirect()->back();
        }
    }
    /**
     * 退出登录
     */
    public function destroy()
    {
    	//Auth门面的logout方法消除session认证信息
    	Auth::logout();
    	//提示信息
    	session()->flash('success', '您已成功退出！');
    	//跳转
    	return redirect('login');
    }
}
