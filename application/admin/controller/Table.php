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
            'expiration' => empty(input('expiration')) ? null : input('expiration')
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
                throw new Exception('数据添加失败');
            }
            Db::commit();
            return ['code'=>1,'error'=>null,'tb_name'=>$tb_name];
        } catch (Exception $e) {
            Db::rollback();
            return ['code'=>0,'error'=>$e->getMessage()];
        }
    }

    public function createTable($tabel_name, $content)
    {
        $sql = "create table " . $tabel_name . " ( id int auto_increment comment 'id' primary key  ,";
        foreach ($content as $k => $v) {
            $sql .= "fie_" . $k . " text comment '" . $v['comment'] . "' ,";
        }
        $sql.="auditstates varchar(20) comment '审核状态' default '未审核')";
        $sql.="orderid int comment '订单id')";
//        $sql = substr($sql, 0, -1) . ")";
        return Db::execute($sql);
    }

    public function saveTable($id)
    {
        $data = Db::table('form_info')
            -> where('id', 'eq', $id)
            -> find();
        $data['content'] = json_decode($data['content'], true);
        $this->assign('data', $data);
        return $this->fetch('table/savetable');
    }

    public function updateTable()
    {
        $data = input();
        $data['content'] = json_encode($data['content'],JSON_UNESCAPED_UNICODE);
        Db::startTrans();
        try{
            $res = Db::name('form_info') -> update($data);
            if($res<1){
                throw new Exception('没有更新数据');
            }
            $tb_name = Db::name('form_info') -> where('id',$data['id']) ->value('tb_name');
            Db::query('DROP TABLE '.$tb_name);
            $this -> createTable($tb_name,input('content'));
            Db::commit();
        }catch (Exception $e){
            Db::rollback();
            return ['code'=> 201,'error' => $e -> getMessage()];
        }
        return ['code'=>200,'msg'=>'更新完成'];
    }

    //删除表单
    public function del()
    {
        Db::startTrans();
        try{
            $tb_name = Db::name('form_info') -> where('id',input('id')) -> value('tb_name');
            $sql = "drop table ".$tb_name;
            Db::execute($sql);
            Db::name('form_info') -> delete(input('id'));
            Db::commit();
            return ['code' => 1,'error'=> null];
        }catch (Exception $e){
            Db::rollback();
            return ['code'=>0,'error'=>$e->getMessage()];
        }
    }
}