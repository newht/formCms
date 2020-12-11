<?php

namespace app\user\controller;


use app\user\model\User;
use think\Controller;
use think\Db;

class Register extends Controller
{
    public function registerVerification()
    {
        $user = new User();
        $data = input();
        unset($data['password2']);
        $res = $user -> register($data);
        Db::table('userinfo') -> insert(['id' => $res]);
        return ['code' => 1,'msg' => '注册成功','url' => '/user/login'];;
    }
}