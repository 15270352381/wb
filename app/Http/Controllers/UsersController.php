<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;


class UsersController extends Controller
{
	/**
	 * 通过Auth提供的middleware方法按照数组依次执行
	 * except 表示对数组内方法以外的方法有效
	 */
	public function __construct()
	{
		$this->middleware('auth', [
				'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
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
    	// 注册进行激活
    	$this->sendEmailConfirmationTo($user);
    	//添加需要激活提示信息
    	session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
    	return redirect('/');
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
    /**
     * 发送邮件
     */
    protected function sendEmailConfirmationTo($user)
    {
    	$view = 'emails.confirm';
    	$data = compact('user');
    	$to = $user->email;
    	$subject = "感谢注册 Sample 应用！请确认你的邮箱。";
    	Mail::send($view, $data, function($message) use ($to, $subject){
    		$message->to($to)->subject($subject);
    	});
    }
    /**
     * 用户激活成功
     * firstOrFail 表示查询第一个结果，没找到会报出异常
     */
    public function confirmEmail($token)
    {
    	$user = User::where('activation_token', $token)->firstOrFail();
    	//给激活后的用户给定状态
    	$user->activated = true;
    	$user->activation_token = Null;
    	$user->save();
    	echo $user;exit;
    	Auth::login($user);
    	session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}
