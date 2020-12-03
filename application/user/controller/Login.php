<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2020/11/24
 * Time: 16:24
 */

namespace app\user\controller;


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
            $user = new \app\model\User;
        }else{
            return '请求错误';
        }

    }

    public function register()
    {
        return $this -> fetch("login/register");
    }
}