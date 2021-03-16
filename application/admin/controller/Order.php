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

class Order extends BaseController {

    /**
     * 订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function order()
    {
        $fie = input('get.fie') ?? ''; // 查询字段
        $condition = input('get.condition') ?? ''; // 查询条件
        $status = input('get.status') ?? '';  // 支付状态
        $table = Db::table('orderinfo')->alias('order')
            ->join('form_info info','order.form_id = info.id')
            ->field('order.*, info.fname, info.money, info.tb_name')
            ->order('order.create_time desc');
        if (!empty($fie)) {
            // 搜索
            $arr = $table->where($fie,'LIKE', '%'.$condition.'%')
                ->paginate(10,false, ['path' => request()->url(), 'query' => ['title' => 'search']]);
        }
        else if(!empty(input('get.status'))) {
            // 查询订单状态
            $arr = $table->where('order.status','=', input('get.status'))
                ->paginate(10,false, ['path' => request()->url(), 'query' => ['title' => 'status']]);
        }
        else {
            // 列表
            $arr = $table->paginate(10,false, ['path' => request()->url(), 'query' => ['title' => 'order']]);
        }
        $data = $arr->toArray()['data'];
        foreach ($data as $key => $val) {
            $tables = Db::table($val['tb_name'])->field('id,orderid')->where('orderid', $val['id'])->find();
            $info = Db::name('user')->where('id', $tables['id'])->field('name,phone,cardid')->find();
            $data[$key]['info'] = $info;
        }
        $paginate = $this->configPaginateInfo($arr);  // 分页配置
        $this->assign('fie', $fie);
        $this->assign('condition', $condition);
        $this->assign('status', $status);
        $this->assign('paginate', $paginate);
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
}


?>