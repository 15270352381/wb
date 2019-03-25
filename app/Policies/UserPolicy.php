<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * 给用户添加修改权限
     * $currentUser 表示当前登录用户
     * $user 表示你用修改的用户
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }
    /**
     * 删除动作
     */
    public function destroy(User $currentUser, User $user)
    {
        //必须是管理员,不能删除自己
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
