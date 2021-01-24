<?php
namespace app\user\controller;


use think\Controller;
use think\Db;

class Forms extends Controller
{
    public function goWrite($id)
    {
        $form = Db::name('form_info') -> where('id',$id) -> find();
        $form['content'] = json_decode($form['content'], true);
        $this -> assign('form',$form);
//        dump($form);
        return $this -> fetch('index/course/write');
    }

    public function insert()
    {
        $table = input('tb_name');
        $data = input();
        unset($data['tb_name']);
        $data['id'] = session('user')['id'];
        if(empty(Db::table($table) -> where('id',$data['id']) -> find())){
            $num = Db::name($table) -> insert($data);
            return ['code' => 1,'error' => null];
        }
        return ['code' => 0,'error' => '已报名该课程'];
    }
}