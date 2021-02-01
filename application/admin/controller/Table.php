<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Exception;
use think\facade\Config;

class Table extends Controller
{
    public function allTable()
    {
        $data = $this->getTables();
        $this->assign('data', $data);
        return $this->fetch("table/allTable");
    }

    public function getTables()
    {
        $data = Db::table("form_info")
            ->field("id,fname,tb_name,subtitle,status,expiration")
            ->select();
        foreach ($data as $k => $v) {
            $data[$k]['count'] = Db::table($v['tb_name'])->count();
        }
        return $data;
    }

    public function setTableStatus()
    {
        $data = [
            'status' => empty(input('status')) ? 0 : 1,
            'expiration' => input('expiration')
        ];
        $res = Db::name('form_info')->where('id',input('id'))->update($data);
        if($res > 0){
            $return = json(['code' => 200,'msg'=> '保存成功']);
        }else{
            $return = json(['code' => 201,'msg'=> '没有做出修改']);
        }
        return $return;
    }

    public function goCreate()
    {
        return $this->fetch("table/create");
    }

    public function insertTable()
    {
        $tb_name = "form_tb_";
        $content = input('content');
        $database = Config::get('database.database');
        $bool = Db::query("select table_name from information_schema.tables where table_name like '" . $tb_name . "%' and table_schema = '" . $database . "'");
        $tb_name = $tb_name . count($bool);
        Db::startTrans();
        try {
            $bool = $this->createTable($tb_name, $content);
            if ($bool) {
                throw new Exception('表单创建失败');
            }
            $fname = input('fname');
            $subtitle = input('subtitle');
            $content = json_encode($content, JSON_UNESCAPED_UNICODE);
            $result = Db::table('form_info')->insert(['fname' => $fname, 'subtitle' => $subtitle, 'content' => $content, 'tb_name' => $tb_name,]);
            if ($result === 0) {
                throw new Exception('数据添加失');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $result = $e->getMessage();
        }
        return $result;
    }

    public function createTable($tabel_name, $content)
    {
        $sql = "create table " . $tabel_name . " ( id int auto_increment comment 'id' primary key  ,";
        foreach ($content as $k => $v) {
            $sql .= "fie_" . $k . " text comment '" . $v['comment'] . "' ,";
        }
        $sql.="auditstates varchar(20) comment '审核状态' default '未审核')";
//        $sql = substr($sql, 0, -1) . ")";
        return Db::execute($sql);

    }

}