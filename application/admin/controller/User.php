<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2020/11/23
 * Time: 11:24
 */

namespace app\admin\controller;


use app\admin\model\Admin;
use think\Controller;
use think\Db;
use think\facade\Config;

class User extends Controller
{

    public function show()
    {
        $database = Config::get('database.database');
        $th = Db::query("SELECT a.table_name '表名', b.column_name '字段名', b.column_comment '字段说明' FROM information_schema. TABLES a LEFT JOIN information_schema.columns b ON a.table_name = b.table_name WHERE a.table_schema = '" . $database . "' and a.table_name = 'admin' ORDER BY a.table_name");
        $this->assign('th', $th);
        $admin = new Admin();
        $data = $admin->getAllData();
        $this->assign('data', $data);
        return $this->fetch("index/tables");
    }

    public function drop()
    {
        session('user_admin', '');
        $login = new Login();
        return $login->login();
    }
}