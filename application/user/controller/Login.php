<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2020/11/24
 * Time: 16:24
 */

namespace app\user\controller;


use think\Controller;

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

    public function register()
    {
        return $this -> fetch("login/register");
    }
}