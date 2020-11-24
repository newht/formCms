<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2020/11/13
 * Time: 15:37
 */

namespace app\index\controller;


use think\Controller;
use think\Db;

class User extends Controller
{
    public function index()
    {

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