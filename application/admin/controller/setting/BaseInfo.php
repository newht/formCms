<?php

namespace app\admin\controller\setting;


use think\Controller;
use think\Db;

class BaseInfo extends Controller
{
    public function index()
    {
        return $this -> fetch('setting/baseinfo/baseinfo');
    }

    public function updateBaseInfo()
    {
        $name = input('name');
        $res = Db::name('admin') -> where('id',session('user_admin')['id']) -> update(['name'=>$name]);
        session('user_admin')['name'] = $name;
        return $this ->success('修改成功,返回','/admin/baseinfo');
    }

    public function password()
    {
        return $this -> fetch('setting/baseinfo/password');
    }

    public function updatePassword()
    {
        $password = input('password');
        $password2 = input('password2');
        if($password != $password2){
            return $this ->error('修改失败，两次密码输出不一致','/admin/baseinfo');
        }
        $res = Db::name('admin') -> where('id',session('user_admin')['id']) -> update(['password'=>md5($password)]);
        return $this ->success('修改成功,返回上一页','/admin/password');
    }
}
