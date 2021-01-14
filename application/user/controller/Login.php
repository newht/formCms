<?php

namespace app\user\controller;

use app\user\model\User;
use think\Controller;
use think\Request;
use think\Db;

class Login extends Controller
{
    public function index()
    {
        $data = Db::table('announcements') -> where('is_delete', 0) ->select();
        $this -> assign('data',$data);
        return $this -> fetch("login/index");
    }

    public function announcepar($id){
        $data = Db::table('announcements') -> where('id',$id) ->find();
        $this -> assign('data',$data);
        return $this->fetch("login/announcepar");

    }

    public function login()
    {
        return $this -> fetch("login/login");
    }

    public function loginVerification(Request $request)
    {
        if($request -> isAjax()){
            $user = new User;
            $res = $user -> login(input('cardid'),input('password'));
            return $res;
        }else{
            return '请求错误';
        }
    }

    public function changePwd()
    {
        return $this -> fetch("login/changepwd");
    }

    public function setPassword()
    {

        $cardid = input('cardid');
        $problem = input('pwdproblem');
        $answer = input('pwdanswer');
        $password = md5(input('password'));
        $data = Db::name('user')->join('userinfo t2','user.id=t2.uid')->where('cardid',$cardid)->find();
        if($problem == $data['pwdproblem']){
            if($answer == $data['pwdanswer']){
                $res = Db::name('user')->where('cardid',$cardid)->update(['password'=>$password]);
                if($res==1){
                    return json(['code'=>200,'error'=>null]);
                }else{
                    return json(['code'=>201,'error'=>'数据错误，请联系站长']);
                }
            }
            return json(['code'=>201,'error'=>'密码找回答案错误']);
        }
        return json(['code'=>201,'error'=>'密码找回问题错误']);
    }
}