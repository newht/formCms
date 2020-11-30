<?php
namespace app\user\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this -> fetch("index/index");
    }

    public function goInfo()
    {
        return $this -> fetch("index/userInfo");
    }

    public function goWorkInfo()
    {
        return $this -> fetch('index/workInfo');
    }

    public function drop()
    {
        $login = new Login();
        return $login -> index();
    }

    public function user()
    {
        return $this -> fetch('index/user');
    }

    public function null()
    {
        return $this -> fetch('index/null');
    }


}