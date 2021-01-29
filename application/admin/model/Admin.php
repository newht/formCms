<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2020/11/23
 * Time: 11:27
 */

namespace app\admin\model;


use think\Model;

class Admin extends Model
{
    public function getAllData()
    {
        return Admin::select() -> toArray();
    }

    public function login($name,$password)
    {
        $password = md5($password);
        $data = Admin::field('id,name,nick_name,password') -> where('name','eq',$name) -> find();
        if(empty($data)){
            return ['code' => 0, 'msg' => '账号不存在'];
        }
        if($data['password'] == $password){
            unset($data['password']);
            session('user_admin',$data);
            return ['code' => 1, 'href' => '/admin/index'];
        }
        return ['code' => 0, 'msg' => '密码错误'];
    }
}