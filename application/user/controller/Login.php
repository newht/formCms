<?php

namespace app\user\controller;

use app\user\model\User;
use think\Controller;
use think\Request;

class Login extends Controller
{
    public function index()
    {
        return $this -> fetch("login/index");
    }

    public function login()
    {
        return $this -> fetch("login/login");
    }

    public function loginVerification(Request $request)
    {
        if($request -> isAjax()){
            $user = new User;
            $res = $user -> login(input('cardid'),input('password'));
            return $res;
        }else{
            return '请求错误';
        }
    }

    public function register()
    {
        return $this -> fetch("login/register");
    }
}