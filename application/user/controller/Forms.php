<?php
namespace app\user\controller;


use think\Controller;
use think\Db;
use think\Exception;

class Forms extends Controller
{
    public function goWrite($id)
    {
        $form = Db::name('form_info') -> where('id',$id) -> find();
        $form['content'] = json_decode($form['content'], true);
        $this -> assign('form',$form);
//        dump($form);
        return $this -> fetch('index/course/write');
    }

    public function insert()
    {
        $table = input('tb_name');
        $data = input();
        unset($data['tb_name']);
        $data['id'] = session('user')['id'];
        Db::startTrans();
        try{
            $bool = Db::table($table) -> where('id',$data['id']) -> find();
            if(!empty($bool)){
                throw new Exception('已报名该课程，前往查看报名记录');
            }

            //生成订单
            $form_info = Db::name('form_info') -> where('tb_name',$table) -> find();
            $orderid = date('YmdHis').'878794';
            $order = Db::name('orderinfo') -> insertGetId(['order_id'=>$orderid,'form_id'=>$form_info['id'],'status'=>0]);
            $data['orderid'] = $order;
            //报名课程
            $num = Db::name($table) -> insert($data);

            if(!($order>0)){
                throw new Exception('添加订单失败'.$order);
            }
            Db::commit();
            return ['code' => 1,'error' => null,'orderid'=>$orderid,'money'=>$form_info['money']*100,'body'=>$form_info['fname']];
        }catch (Exception $e){
            Db::rollback();
            return ['code' => 0,'error' => $e -> getMessage()];
        }
    }
}