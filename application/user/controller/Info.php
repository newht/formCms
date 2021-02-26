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

        if(!empty(input('name'))){
            session('user')['name'] = input('name');
            Db::name("user") -> where('id',$uid) -> update($data);
            return ['code'=>1,'error' => null];
        }else if(!empty(input('cardid'))){
            $bool = Db::name('user') -> where('cardid',input('cardid')) -> find();
            if(empty($bool)){
                session('user')['cardid'] = input('cardid');
                Db::name("user") -> where('id',$uid) -> update($data);
                return ['code'=>1,'error' => null];
            }else{
                return ['code' => 0,'error' => '已存在的证件号'];
            }
        }
        Db::name("userinfo") -> where('uid',$uid) -> update($data);
        return ['code'=>1,'error' => null];
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