<?php
namespace app\user\controller;


use think\Controller;
use think\Db;

class Info extends Controller
{
    public function setUserInfo()
    {
        $uid = session('user')['id'];
        $data = input();
        if(empty($data)){
            $filename = 'Scan1';
            $file = request()->file($filename);
            if(empty($file)){
                $filename = 'Scan2';
                $file =  request()->file($filename);
            }
            $info = $file->validate(['size'=>10485760,'ext'=>'jpg,png,gif'])->move( './uploads');
            if($info){
                $data = [$filename => '/uploads/'.$info-> getSaveName()];
                $num = Db::name("userinfo") -> where('uid',$uid) -> update($data);
                return ['code' => 1,'img' => $data[$filename]];
            }else{
                return ['code' => 0,'error' => $file->getError()];
            }
        }
        return Db::name("userinfo") -> where('uid',$uid) -> update($data);
    }

    public function setEmployerInfo()
    {
        $uid = session('user')['id'];
        $data = input();
        $data['uid'] = $uid;
        $user = Db::name("employerinfo")->where('uid',$uid)->find();
        if(empty($user)){
            $num = Db::name("employerinfo") -> insert($data);
        }else{
            $num = Db::name("employerinfo") -> update($data);
        }
        if($num){
            return ['code' => 1,'error' => null];
        }else{
            return ['code' => 0,'error' => '数据错误'];
        }
    }
}