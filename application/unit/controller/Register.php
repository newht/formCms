<?php

namespace app\unit\controller;
use app\user\model\User;
use think\Controller;
use think\Db;

class Register extends Controller
{
    //公司注册页面
    public function register()
    {
        return $this -> fetch("login/unit_register");
    }

    public function unitReg()
    {
        $data = input();
        $res = Db::name('unit') ->strict(false) -> insert($data);
        if($res > 0){
            return json(['code' => 1, 'msg' => '注册成功', 'url' => '/unit/login']);
        }
        return json(['code' => 0, 'msg' => '注册失败', 'url' => null]);
    }
}