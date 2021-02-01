<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2021/2/1
 * Time: 14:20
 */

namespace app\admin\controller;


use think\Controller;

class Layuiedit extends Controller
{
    /**
     * @return
     * **/
    public function uploadImg()
    {
        $file = request()->file('file_edit');
        $info = $file -> move('./uploads/announcement');
        if($info){
            $return = [
                'code' => 0,
                'errno' => 0,
                'data' => [
                    'url' => '/uploads/announcement/'.$info -> getSaveName()
                ]
            ];
        }
        return json_encode($return);
    }

    public function getImg()
    {
        return 'img';
    }

}