<?php
namespace app\admin\controller;


use app\admin\model\Admin;
use think\Controller;

class login extends Controller
{
    public function login()
    {
        return $this -> fetch("login/login");
    }

    //登录接口
    public function in()
    {
        $admin = new Admin();
        $return = $admin -> login(input('name'),input('password'));
        return $return;
    }
}