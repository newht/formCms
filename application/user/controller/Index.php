<?php
namespace app\user\controller;
use app\admin\controller\Table;
use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        return $this -> fetch("index/index");
    }

    public function goInfo()
    {
        $data = Db::table('userinfo') -> where('uid',session('user')['id']) -> find();
        $this -> assign('data',$data);
        return $this -> fetch("index/info/user");
    }

    public function goWorkInfo()
    {
        $data = Db::table('employerinfo') -> where('uid',session('user')['id']) -> find();
        $this -> assign('data',$data);
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
        session('user',null);
        $login = new Login();
        return $login -> index();
    }

    public function null()
    {
        return $this -> fetch('index/null');
    }


}