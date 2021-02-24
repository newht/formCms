<?php
namespace app\admin\controller;
use app\admin\model\Form_info;
use think\Controller;
use think\Db;
use think\facade\Config;

class Index extends Controller
{
    public function index()
    {
        $form_info = new Form_info();
        $data = $form_info->getAll();
        $this->assign("data", $data);
        return $this->fetch('index/index');
    }

    //查询表中的所有数据
    public function getData($tb_name)
    {
        $database =  Config::get('database.database');
        $th = Db::query("SELECT a.table_name '表名', b.column_name '字段名', b.column_comment '字段说明'
          FROM information_schema. TABLES a
          LEFT JOIN information_schema.columns b ON a.table_name = b.table_name
          WHERE a.table_schema = '" . $database . "'
          and a.table_name = '" . $tb_name . "'
          or a.table_name = 'orderinfo'
          ORDER BY a.table_name");
        $str = '';
        foreach ($th as $k => $v){
            if($v['表名'] == $tb_name &&  $v['字段说明'] != '订单id'){
                $str .= $tb_name."." . $v['字段名'] . ",";
            }else if($v['表名'] == 'orderinfo' && $v['字段说明'] == '订单状态'){
                $str .= "orderinfo." . $v['字段名'] . ",";
            }else{
                unset($th[$k]);
            }
        }
        $str = substr($str,0,-1);

        $sql = "select " . $str ." from ".$tb_name." left join orderinfo on " .$tb_name.".orderid = orderinfo.id";
        $data = Db::query($sql);
//        $data = Db::table($tb_name) -> select();
        $this->assign('th', $th);
        $this->assign('data', $data);
        $this->assign('jsonData', json_encode($data));
        return $this -> fetch("index/tables");
    }

    public function auditStates()
    {
        $tbname = input('tbname');
        $data = input('data');
        foreach($data as $v){
            Db::table($tbname) -> where("id",$v['id']) -> update(['auditstates' => $v['auditstates']]);
        }
        return 1;
    }

}
