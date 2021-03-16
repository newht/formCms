<?php
/**
 * Created by PhpStorm.
 * User: g
 * Date: 2021/3/11
 * Time: 16:11
 */

namespace app\admin\controller;


use think\App;
use think\Controller;
use think\Db;
use think\Exception;

class Deposit extends BaseController
{
    /**
     * 提现列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $auditStatus = input('get.audit_status') ?? '';
        if (!empty($auditStatus)) {
            // 搜索订单状态
            $arr = Db::name('deposit')->order('create_time desc')->where('audit_status',$auditStatus)->paginate(10,false);
        }
        else {
            $arr = Db::name('deposit')->order('audit_status desc')->paginate(10,false);
        }
        $data = $arr->toArray()['data'];
        foreach ($data as $key => $val) {
            $userInfo = Db::name('user')->field('name, phone, balance')->find($val['user_id']);
            $data[$key]['userInfo'] = $userInfo;
        }
        // 分页配置
        $paginate = $this->configPaginateInfo($arr);
        $this->assign('paginate', $paginate);
        $this->assign('data', $data);
        $this->assign('auditStatus', $auditStatus);
        return $this->fetch('/deposit/deposit');
    }

    /**
     * 删除单条
     * @return \think\response\Json
     * @throws Exception
     */
    public function del()
    {
        $id = (int)input("post.id");
        try {
            $res = Db::name('deposit')->delete($id);
            if ($res) {
                return json(['code' => 1, 'msg' => '删除成功']);
            }
        }catch (\Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * 更新提现审核状态
     * @return \think\response\Json
     * @throws Exception
     */
    public function updateDepositStatus()
    {
        $id = (int)input('get.id');
        $auditStatus = (int)input('get.audit_status');
        try {
            $res = Db::name('deposit')->where('id', $id)->update([
                'audit_status' => $auditStatus
            ]);
            if ($res === false) {
                return json(['code' => 0, 'msg' => '更新状态失败']);
            }
            return json(['code' => 1, 'msg' => '更新状态成功']);
        }catch (\Exception $ex) {
            throw new Exception($ex->getMessage());
        }

    }
}