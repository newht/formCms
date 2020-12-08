<?php
namespace app\user\controller;


use think\Controller;
use think\Db;

class Info extends Controller
{
    public function setUserInfo()
    {
        $uid = session('user')['id'];
        return Db::name("userinfo") -> where('uid',$uid) -> update(input());
    }
}