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
}