<?php
namespace app\unit\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        return $this -> fetch("index/index");
    }

    public function drop()
    {
        session('unit',null);
        return redirect('/');
    }

    public function updatePwd()
    {
        return $this -> fetch('index/updatepwd');
    }

    public function setUpdatePwd()
    {
        $password = md5(input('oldpassword'));
        $newpassword = md5(input('newpassword'));
        $user = session('unit');
        $data = Db::name('unit')->where('id',$user['id'])->find();
        if($data['password'] == $password){
            $res = Db::name('unit')->where('id',$user['id'])->update(['password'=>$newpassword]);
            if($res == 1){
                return json(['code'=>200,'error'=>null]);
            }
            return json(['code'=>201,'error'=>'数据错误，请联系站长']);
        }
        return json(['code'=>201,'error'=>'旧密码错误']);
    }
}