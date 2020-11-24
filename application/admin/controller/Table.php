<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2020/11/24
 * Time: 14:46
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;

class Table extends Controller
{
    public function createTable()
    {

    }

    /**
     * 查看所有表单
     **/
    public function allTable()
    {
        $data = $this->getTables();
        $this->assign('data', $data);
        return $this->fetch("table/allTable");
    }

    public function getTables()
    {
        $data = Db::table("form_info")
            ->field("id,fname,tb_name")
            ->select();
        foreach ($data as $k => $v) {
            $data[$k]['count'] = Db::table($v['tb_name'])->count();
        }
        return $data;
    }
}