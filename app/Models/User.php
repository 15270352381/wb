<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * 指明的数据表名
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     * 可分配的属性(可进行增删改查操作的字段)
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * 数组隐藏的属性
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * 获取用户头像
     */
    public function gravatar($size = '100')
    {
        // 获取用户email
        $hash = md5(strtolower((trim($this->attributes['email']))));
        // 返回拼接的用户头像信息
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }
}
