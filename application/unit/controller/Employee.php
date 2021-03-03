<?php
namespace app\unit\controller;

use app\admin\controller\Table;
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
        $password = md5(substr($cardid,-6));
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

    public function signUp()
    {
        $table = new Table();
        $data = $table -> getTables();
        $this -> assign('data',$data);
        $this -> assign('id',input('id'));
        return $this -> fetch('index/employee/course/signup');
    }

    public function goWrite($id)
    {
        $form = Db::name('form_info') -> where('id',$id) -> find();
        $form['content'] = json_decode($form['content'], true);
        $user = Db::name("user")
            -> alias('t1')
            -> join('userinfo t2','t1.id=t2.uid')
            -> where('t1.id',input("uid"))
            -> find();
        $this -> assign('form',$form);
        $this -> assign('user',$user);
        return $this -> fetch('index/employee/course/write');
    }

    public function insert()
    {
        $table = input('tb_name');
        $data = input();

        if(empty(Db::table($table) -> where('id',$data['id']) -> find())){
            $num = Db::name($table) ->strict(false) -> insert($data);
            return ['code' => 1,'error' => null];
        }
        return ['code' => 0,'error' => '已报名'];
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