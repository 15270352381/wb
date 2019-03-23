<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


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
    	$this->validate($request, [
    			'name' => 'required',
    			'email' => 'required|unique:users|email|max:255',
    			'password' => 'required|min:6|confirmed'
    		]);
    	$user = User::create([
    			'name' => $request->name,
    			'email' => $request->email,
    			'password' => bcrypt($request->password),
    		]);
    	session()->flash('success', '欢迎！你将开始一段不一样的旅程~');
    	return redirect()->route('users.show', [$user]);
    }
}
