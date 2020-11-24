<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2020/11/20
 * Time: 11:14
 */

namespace app\admin\model;


use think\Model;

class Form_info extends Model
{
    public function getAll()
    {
        $form_info = Form_info::select();
        return $form_info;
    }
}