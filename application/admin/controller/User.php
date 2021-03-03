<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use think\Controller;
use think\Db;
use think\facade\Config;

class User extends Controller
{
    public function webUser()
    {
        $fie = '';
        $condition = '';
        $data = Db::table("user")->field('user.id,cardid,user.name,user.phone,unit.name as unit_name,user.createtime');
        if (!empty(input('fie')) && input('fie') != 'undefined') {
            $fie = input('fie');
            $condition = input('condition');
            $data = $data->where($fie, 'like', '%' . $condition . '%');
        }
        $data = $data->leftJoin('unit', 'user.unit_id = unit.id') ->order('user.id','desc') ->select();
        $this->assign('data', $data);

        $this -> assign('jsonData',json_encode($data));
        $this -> assign('fie',$fie);
        $this -> assign('condition',$condition);
        return $this->fetch("user/webuser");
    }


    public function show()
    {
        $database = Config::get('database.database');
        $th = Db::query("SELECT a.table_name '表名', b.column_name '字段名', b.column_comment '字段说明' FROM information_schema. TABLES a LEFT JOIN information_schema.columns b ON a.table_name = b.table_name WHERE a.table_schema = '" . $database . "' and a.table_name = 'admin' ORDER BY a.table_name");
        $this->assign('th', $th);
        $admin = new Admin();
        $data = $admin->getAllData();
        $this->assign('data', $data);
        return $this->fetch("user/webuser");
    }

    public function drop()
    {
        session('user_admin', null);
        $login = new Login();
        return $login->login();
    }
}