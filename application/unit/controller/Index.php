<?php
namespace app\unit\controller;
use app\admin\controller\Table;
use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        return $this -> fetch("index/index");
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
        session('unit',null);
        $login = new Login();
        return $login -> index();
    }

    public function null()
    {
        return $this -> fetch('index/null');
    }


}