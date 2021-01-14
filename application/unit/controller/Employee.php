<?php
namespace app\unit\controller;

use think\Controller;
use think\Db;
use think\Exception;

class Employee extends Controller
{
    public function employeeList()
    {
        $unit = session("unit");
        $data = Db::name("user")->where("unit_id",$unit['id'])->select();
        $this->assign("data",$data);
        return $this -> fetch("index/employee/list");
    }

    public function showAdd()
    {
        return $this -> fetch('index/employee/adduser');
    }

    public function adduser()
    {
        $unit = session('unit');
        $cardid = input('cardid');
        $name = input('name');
        $phone = input('phone');
        $password = substr($cardid,-6);
        $pwdproblem = input('pwdproblem');
        $pwdanswer = input('pwdanswer');
        Db::startTrans();
        try{
            $is_null = Db::name('user')->where('cardid',$cardid)->find();
            if(!empty($is_null)){
                throw new Exception("该用户已存在");
            }
            $data = ['cardid'=>$cardid,'name'=>$name,'phone'=>$phone,'unit_id'=>$unit['id'],'password'=>$password];
            $id = Db::name('user')->insertGetId($data);
            Db::name('userinfo')->insert(['uid'=>$id,'pwdproblem'=>$pwdproblem,'pwdanswer'=>$pwdanswer]);
            Db::commit();
        }catch (Exception $e){
            Db::rollback();
            return ['code'=>0,'error'=>$e->getMessage()];
        }
        return ['code'=>1,'error'=>null];
    }

    public function userInfo($id)
    {
        $data = Db::name('user')->where("id",$id)->find();
        $data['birthday'] = substr($data['cardid'],6,4)."-".substr($data['cardid'],10,2)."-".substr($data['cardid'],12,2);
        $data['sex'] = substr($data['cardid'],-2,1)%2 == '1' ? '男' : '女';
        $this -> assign("data",$data);
        $this -> assign("id",$id);
        return $this -> fetch('index/employee/userinfo');
    }

    public function goSetUserInfo($id)
    {
        $data = Db::table('userinfo') -> where('uid',$id) -> find();
        $this -> assign('data',$data);
        $this -> assign("id",$id);
        return $this -> fetch("index/employee/info/setuserinfo");
    }

    public function setUserInfo()
    {
        $uid = input("id");
        $data = input();
        unset($data['id']);
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

    public function goSetWorkInfo($id)
    {
        $data = Db::table('employerinfo') -> where('uid',$id) -> find();
        $this -> assign('data',$data);
        $this -> assign("id",$id);
        return $this -> fetch("index/employee/info/setworkinfo");
    }

    public function setWordInfo()
    {
        $uid = input("uid");
        $data = input();
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

    public function goSetInvoiceInfo($id)
    {
        $unit = session("unit");
        $data = Db::table('invoice') -> where('id',$unit['id']) -> find();
        $this -> assign('data',$data);
        $this -> assign("id",$id);
        return $this -> fetch("index/employee/info/setinvoiceinfo");
    }

    public function setInvoiceInfo()
    {
        return input();
    }

}