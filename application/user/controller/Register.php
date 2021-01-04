<?php

namespace app\user\controller;


use app\user\model\User;
use think\Controller;
use think\Db;

class Register extends Controller
{
    public function register()
    {
        return $this -> fetch("login/register");
    }

    public function registerVerification()
    {
        $user = new User();
        $data = input();
        unset($data['password2']);
        $res = $user -> register($data);
        return $res;
    }

    public function unitRegister()
    {
        return $this -> fetch("login/unit_register");
    }

    public function unitReg()
    {
        $data = input();
        $res = Db::name('unit') ->strict(false) -> insert($data);
        if($res > 0){
            return json(['code' => 1, 'msg' => '注册成功', 'url' => '/user/unitlogin']);
        }
        return json(['code' => 0, 'msg' => '注册失败', 'url' => null]);
    }
}