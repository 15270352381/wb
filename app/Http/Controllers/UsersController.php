<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;


class UsersController extends Controller
{
	/**
	 * 通过Auth提供的middleware方法按照数组依次执行
	 * except 表示对数组内方法以外的方法有效
	 */
	public function __construct()
	{
		$this->middleware('auth', [
				'except' => ['show', 'create', 'store', 'index']
			]);
		//只让没登录的用户访问注册页面
		$this->middleware('guest', [
				'only' => ['create']
			]);
	}
	/**
	 * 用户列表
	 */
	public function index()
	{
		//获取用户表所有用户信息
		$users = User::paginate(10);
		return view('users.index', compact('users'));
	}
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
    /**
     * 编辑登录显示
     */
    public function edit(User $user)
    {
    	return view('users.edit', compact('user'));
    }
    /**
     * 编辑用户信息逻辑
     */
    public function update(Request $request, User $user)
    {
    	/**
    	 * 编辑用户验证
    	 * nullable表示允许该列值为空
    	 */
    	$this->validate($request,[
    			'name' => 'required|max:50',
    			'password' => 'nullable|min:6|confirmed'
    		]);
    	/**
    	 * 调用授权策略
    	 * authorize方法用来接收授权
    	 */
    	$this->authorize('update', $user);
    	// 把输入的数据放到变量里
    	$data = [];
    	$data['name'] = $request->name;
    	if($request->password) {
    		$data['password'] = bcrypt($request->password);
    	}
    	//获取输入的数据并修改到数据表
    	$user->update($data);
    	session()->flash('success', '编辑成功！');
    	return redirect()->route('users.show', $user->id);
    }
    /**
     * 管理员删除用户
     */
    public function destroy(User $user)
    {
    	//只有已登录的管理员才能进行删除动作
    	$this->authorize('destroy', $user);
    	$user->delete();
    	session()->flash('success', '成功删除用户！');
    	return back();
    }
}
