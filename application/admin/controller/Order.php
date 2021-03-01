<?php
/*
 * @Author: your name
 * @Date: 2021-01-04 16:06:12
 * @LastEditTime: 2021-01-06 17:28:24
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \project.com\application\admin\controller\Announce.php
 */
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\facade\View;

class Order extends Controller{

    /**
     * 订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function order()
    {
        $data = Db::name('orderinfo')->alias('order')
            ->join('form_info info','order.form_id = info.id')
            ->field('order.*, info.fname, info.money')
            ->paginate(10,false);
        $page = $data->render();
        $this->assign('page', $page);
        $this->assign('data', $data);
        return $this->fetch('/order/order');
    }

    /**
     * 订单删除
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del()
    {
        $orderId = (int)input('post.id');
        $res = Db::name('orderinfo')->delete($orderId);
        if (!$res) {
            return json(['code' => 0, 'msg' => '删除失败', 'url' => null]);
        }
        return json(['code' => 1, 'msg' => '删除成功', 'url' => null]);
    }

    public function test()
    {
        return "test";
    }
}


?>