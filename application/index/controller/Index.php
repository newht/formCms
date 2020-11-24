<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\facade\Config;

class Index extends Controller
{
    public function index()
    {
        $data = Db::table('form_info') -> select();
        $this -> assign("data",$data);
        return $this->fetch();
    }

    /**
     * 查看所有表单
     **/
    public function allTable()
    {
        $data = $this->getTables();
        $this->assign('data', $data);
        return $this->fetch("index/allTable");
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

    public function showTable($id)
    {
        $data = Db::table('form_info')
            ->where('id', 'eq', $id)
            ->find();
        $data['content'] = json_decode($data['content'], true);
        $this->assign('data', $data);
        return $this->fetch('index/test');
    }


    public function insertTable()
    {
        $tb_name = "form_tb_";
        $content = input('content');
        $database = Config::get('database.database');
        $bool = Db::query("select table_name from information_schema.tables where table_name like '" . $tb_name . "%' and table_schema = '" . $database . "'");
        $tb_name = $tb_name . count($bool);
        $this->createTable($tb_name, $content);
        $fname = input('fname');
        $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        $sql = "insert into form_info(fname,content,tb_name) values('" . $fname . "','" . $content . "','" . $tb_name . "')";
        $result = Db::execute($sql);
        return $result;
    }

    /**
     * 创建数据表
     */
    public function createTable($tabel_name, $content)
    {
        $sql = "create table " . $tabel_name . " ( id int auto_increment comment 'id' primary key  ,";
        foreach ($content as $k => $v) {
            $sql .= "fie_" . $k . " text comment '" . $v['comment'] . "' ,";
        }
        $sql = substr($sql, 0, -1) . ")";
        return Db::execute($sql);

    }

    /**
     * json_encode($array,JSON_UNESCAPED_UNICODE) -- 数组转json，并且编码格式转为utf-8
     **/
    public function digui($data, $parent_id = 0)
    {
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $v['下一级'] = $this->digui($data, $v['id']);
                $arr[] = $v;
            }
        }
        return $arr;
    }
}
