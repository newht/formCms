<?php

namespace app\unit\controller;

use app\user\model\User;
use think\Controller;
use think\Request;
use think\Db;

class Login extends Controller
{
    //公司登录界面
    public function login()
    {
        return $this -> fetch("login/unit_login");
    }

    public function unitLoignVerifty()
    {
        $unitcode = input('unitcode');
        $password = input('password');
        $data = Db::name('unit') -> where('unitcode',$unitcode) -> find();
        if( empty($data) ){
            return json(['code'=>0,'msg'=>'登录失败,账号不存在','errorname'=>'unitcode','url'=>null]);
        }
        if( $data['password'] !== $password ){
            return json(['code'=>0,'msg'=>'登录失败,密码错误','errorname'=>'password','url'=>null]);
        }
        session('unit',$data);
        return json(['code'=>1,'msg'=>'登录成功','errorname'=>null,'url'=>'/unit/index']);
    }
}