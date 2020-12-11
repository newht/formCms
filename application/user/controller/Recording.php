<?php

namespace app\user\controller;


use think\Controller;
use think\Db;
use think\facade\Config;

class Recording extends Controller
{
    public function goRecording()
    {
        $database = Config::get('database.database');
        $data = Db::table('form_info')->field('id,fname,subtitle,tb_name')->select();
        foreach ($data as $a => $v) {
            $temp = Db::table($v['tb_name'])->where('id', session('user')['id'])->find();
            if (!empty($temp)) {
                $count =  Db::table($v['tb_name']) -> count();
                $data[$a]['count'] = $count;
                $sql = "SELECT COLUMN_NAME,column_comment FROM INFORMATION_SCHEMA.Columns WHERE table_name='" . $v['tb_name'] . "' AND table_schema='" . $database . "'";
                $comment = Db::query($sql);
                foreach ($temp as $m => $n) {
                    foreach ($comment as $k) {
                        if ($k['COLUMN_NAME'] === $m && $m != 'id') {
                            $temp2[$k['column_comment']] = $n;
                        }
                    }
                }
                $data[$a]['formdatas'] = $temp2;
                $temp2= [];
            } else {
                unset($data[$a]);
            }
        }
        $this->assign('data', $data);
//        dump($data);
        return $this->fetch('index/recording/index');
    }
}