<?php
namespace app\unit\controller;


use think\Controller;
use think\Db;

class Info extends Controller
{
    public function baseInfo()
    {
        $user = session('unit');
        $data = Db::table('unit')->field('phone,address') -> where('id',$user['id']) -> find();
        $this -> assign('data',$data);
        return $this -> fetch("index/info/baseinfo");
    }

    public function setBaseInfo()
    {
        $unit = session('unit');
        $id = $unit['id'];
        $data = input();
        $res = ['code'=>0];
        if(!empty($data)){
            $res['code'] = (Db::name("unit") -> where('id',$id) -> update($data)) > 0 ? ['code'=>1,'error'=>null] : ['code'=>0,'error'=>'数据错误'];
        }
        return json($res);
    }

    public function invoiceInfo()
    {
        $unit = session('unit');
        $data = Db::table('unit')
            -> leftJoin('invoice t2','unit.id = t2.id')
            -> where('unit.id',$unit['id'])
            -> find();
        $this -> assign('data',$data);
        return $this -> fetch('index/info/invoiceinfo');
    }

    public function setInvoiceInfo()
    {
        $unit = session('unit');
        $data = input();
        $res = ['code'=>0,'error'=>'数据错误'];
        foreach ($data as $k=>$v){
            $table = Db::query("SELECT column_name,table_name FROM information_schema.columns WHERE (table_name = 'unit' OR table_name = 'invoice') AND column_name='".$k."'");
            if(count($table) === 1){
                $res = Db::name($table[0]['table_name'])->where('id',$unit['id']) -> update($data) > 0 ? ['code'=>1,'error'=>null] : ['code'=>0,'error'=>'数据错误'];
            }
        }
        return $res;
    }
}