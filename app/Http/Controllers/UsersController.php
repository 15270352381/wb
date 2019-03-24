<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;


class UsersController extends Controller
{
	/**
	 * 注册显示页
	 */ 
    public function create()
    {
    	return view('users.create');
    }
    /**
     * 用户信息
     */
    public function show(User $user)
    {
    	return view('users.show', compact('user'));
    }
    /**
     * 注册用户验证
     */
    public function store(Request $request)
    {
    	//注册信息验证
    	$this->validate($request, [
    			'name' => 'required',
    			'email' => 'required|unique:users|email|max:255',
    			'password' => 'required|min:6|confirmed'
    		]);
    	//获取用户输入的注册信息
    	$user = User::create([
    			'name' => $request->name,
    			'email' => $request->email,
    			'password' => bcrypt($request->password),
    		]);
    	// 注册后自动登录
    	Auth::login($user);
    	//添加注册提示信息
    	session()->flash('success', '欢迎！你将开始一段不一样的旅程~');
    	return redirect()->route('users.show', [$user]);
    }
}
