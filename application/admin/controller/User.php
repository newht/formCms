<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use think\Controller;
use think\Db;
use think\Exception;
use think\facade\Config;

class User extends Controller
{
    public function webUser()
    {
        $fie = '';
        $condition = '';
        $data = Db::table("user")->field('user.id,cardid,user.name,user.phone,unit.name as unit_name,balance,user.createtime');
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

    public function delete(){
        $tables = Db::name('form_info') -> select();
        $table = "";
        $order = "";
        foreach($tables as $k => $v){
            $table .= ",".$v["tb_name"].",t".($k+4);
            $order .= "left join ".$v["tb_name"]." on ".$v["tb_name"].".id=t1.id left join orderinfo t".($k+4)." on t".($k+4).".id=".$v["tb_name"].".orderid ";
        }
        $sql = "DELETE t1,t2,t3".$table." FROM user t1 LEFT JOIN userinfo t2 ON t1.id = t2.uid LEFT JOIN recommend t3 ON t2.uid = t3.user_id ".$order." WHERE t1.id = ".input("userid");
        try{
            $rst = Db::execute($sql);
            if ($rst < 1){
                throw new Exception("系统错误");
            }
        }catch (Exception $e){
            return ['code'=>201,"msg"=>"删除失败","error"=>$e->getMessage()];
        }
        return ['code'=>200,"msg"=>"删除成功"];
    }
}