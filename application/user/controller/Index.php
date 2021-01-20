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

    public function updatePwd()
    {
        return $this -> fetch('index/updatepwd');
    }

    public function setUpdatePwd()
    {
        $password = md5(input('oldpassword'));
        $newpassword = md5(input('newpassword'));
        $user = session('user');
        $data = Db::name('user')->where('id',$user['id'])->find();
        if($data['password'] == $password){
            $res = Db::name('user')->where('id',$user['id'])->update(['password'=>$newpassword]);
            if($res == 1){
                return json(['code'=>200,'error'=>null]);
            }
            return json(['code'=>201,'error'=>'数据错误，请联系站长']);
        }
        return json(['code'=>201,'error'=>'旧密码错误']);
    }
}