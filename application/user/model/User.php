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
    public function login($name,$password)
    {
        return User::where('name','eq',$name) -> find();
    }
}