<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2020/12/3
 * Time: 11:34
 */

namespace app\user\model;


use think\Model;

class User extends Model
{
    public function login($cardid,$password)
    {
        $data = User::where('cardid','eq',$cardid) -> find();
        if(empty($data)){
            return ['code' => 0, 'errorname' => 'cardid', 'msg' => '用户不存在','url' => null];
        }
        if($password != $data['password']){
            return ['code' => 0, 'errorname' => 'password', 'msg' => '密码错误','url' => null];
        }
        unset($data['password']);
        session('user',$data);
        return ['code' => 1,'errorname' => null,'msg' => '登录成功','url' => '/user/index'];
    }

    public function register($data)
    {
        $res = User::data($data) -> insert();
        if($res === 1){
            return ['code' => 1,'msg' => '注册成功','url' => '/user/login'];
        }
        return ['code' => 0,'msg' => '注册失败','url' => null];
    }
}