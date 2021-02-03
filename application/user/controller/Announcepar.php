<?php

namespace app\user\controller;

use think\Controller;
use think\Db;

class Announcepar extends Controller
{

    public function dataList()
    {
        $data = Db::table('announcements') -> where('is_delete', 0)-> select();
        $this -> assign('data',$data);
        return $this -> fetch('announcepar/announceparlist');
    }

    public function announcepar($id){
        $data = Db::table('announcements') -> where('id',$id) ->find();
        $data['create_time'] = date('Y-m-d',$data['create_time']);
        $this -> assign('data',$data);
        return $this->fetch("announcepar/announcepar");

    }
}