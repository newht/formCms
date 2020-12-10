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
        $num = Db::name($table) -> insert($data);
        return [
            'code' => 0
        ];
    }
}