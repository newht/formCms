<?php
namespace app\user\controller;
use app\admin\controller\Table;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this -> fetch("index/index");
    }

    public function goInfo()
    {
        return $this -> fetch("index/info/user");
    }

    public function goWorkInfo()
    {
        return $this -> fetch('index/info/work');
    }

    public function signUp()
    {
        $table = new Table();
        $data = $table -> getTables();
        $this -> assign('data',$data);
        return $this -> fetch('index/course/signup');
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