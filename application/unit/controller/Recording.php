<?php

namespace app\unit\controller;


use think\Controller;
use think\Db;
use think\facade\Config;

class Recording extends Controller
{
    public function goRecording()
    {
        $unit_id = session('unit')['id'];
        $users = Db::name('user') -> where('unit_id',$unit_id) -> select();
        $tables = Db::name("form_info") -> select();
        foreach($tables as $k => $v){
            foreach($users as $m => $n){
                $data = Db::name($v['tb_name']) -> where('id',$n['id']) -> find();
                if(!empty($data)){
                    $temp = $n;
                    $temp['order_id'] = $data['orderid'];
                    $order = Db::name('orderinfo') -> where('order_id',$data['orderid']) -> find();
                    if(empty($order)){
                        $temp['order_status'] = "订单不存在，请联系站长";
                    }else{
                        $temp['order_status'] = $order['status'] == 2 ? '已缴费' : '前往缴费';
                    }
                    $tables[$k]['user'][] = $temp;
                }
            }
        }
        $this -> assign("tables",$tables);
        return $this->fetch('index/recording/completed');
    }

    //前往待缴费页面
    public function goCompleted()
    {
        $unit_id = session('unit')['id'];
        $users = Db::name('user') -> where('unit_id',$unit_id) -> select();
        $tables = Db::name("form_info") -> select();
        foreach($tables as $k => $v){
            foreach($users as $m => $n){
                $data = Db::name($v['tb_name']) -> where('id',$n['id']) -> find();
                if(!empty($data)){
                    $temp = $n;
                    $temp['order_id'] = $data['orderid'];
                    $order = Db::name('orderinfo') -> where('order_id',$data['orderid']) -> find();
                    if(empty($order)){
                        $temp['order_status'] = "订单不存在，请联系站长";
                    }else{
                        $temp['order_status'] = $order['status'] == 2 ? '已缴费' : '前往缴费';
                    }
                    $tables[$k]['user'][] = $temp;
                }
            }
        }
        $this -> assign("tables",$tables);
        return $this->fetch('index/recording/completed');
    }

    public function goUndone()
    {
        return $this->fetch('index/recording/undone');
    }
}