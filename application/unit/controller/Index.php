<?php
namespace app\unit\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this -> fetch("index/index");
    }

    public function drop()
    {
        session('unit',null);
        $login = new Login();
        return $login -> login();
    }
}