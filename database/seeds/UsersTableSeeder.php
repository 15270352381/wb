<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 数据填充
     * @return void
     */
    public function run()
    {
    	//添加数据的条数
        $user = factory(User::class)->times(50)->make();
        //插入到数据表
        User::insert($user->makeVisible(['password', 'remember_token'])->toArray());
        $user = User::find(1);
        $user->name = 'Aufree';
        $user->email = 'aufree@yousails.com';
        $user->password = bcrypt('password');
        //把第一个用户设置为管理员
        $user->is_admin = true;
        $user->activated = true;
        $user->save();
    }
}
