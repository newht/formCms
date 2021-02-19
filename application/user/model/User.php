<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2020/12/3
 * Time: 11:34
 */

namespace app\user\model;


use think\Db;
use think\Model;

class User extends Model
{
    public function login($cardid,$password)
    {
        $data = User::where('phone','eq',$cardid) -> find();
        if(empty($data)){
            return ['code' => 0, 'errorname' => 'cardid', 'msg' => '用户不存在,请输入正确的账号','url' => null];
        }
        if(md5($password) != $data['password']){
            return ['code' => 0, 'errorname' => 'password', 'msg' => '密码错误','url' => null];
        }
        unset($data['password']);
        session('user',$data);
        return ['code' => 1,'errorname' => null,'msg' => '登录成功','url' => '/user/signup'];
    }

    public function register($data)
    {
        $olduser = User::where('cardid',$data['cardid'])->find();
        if(empty($olduser)){
            $oldphone = User::where('phone',$data['phone'])->find();
            if(empty($oldphone)){
                $data['password'] = md5(substr($data['phone'],-4));
                $res = User::insertGetId($data);
                Db::table('userinfo') -> insert(['uid' => $res]);
                if($res > 0){
                    return ['code' => 1,'msg' => '注册成功','url' => '/user/login'];
                }
                return ['code' => 0,'msg' => '注册失败','url' => null];
            }
            return ['code'=>0,'msg'=>'注册失败,该电话号码已注册','url'=>null];
        }
        return ['code'=>0,'msg'=>'注册失败,已存在的账号','url'=>null];
    }
}