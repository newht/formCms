<?php

namespace app\user\controller;


use app\user\model\User;
use think\Controller;

class Register extends Controller
{
    public function registerVerification()
    {
        $user = new User();
        $data = input();
        unset($data['password2']);
        $res = $user -> register($data);
        return $res;
    }
}