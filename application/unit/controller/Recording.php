<?php

namespace app\unit\controller;


use think\Controller;
use think\Db;
use think\facade\Config;

class Recording extends Controller
{
    public function goRecording()
    {
//        $this->assign('data', $data);
        return $this->fetch('index/recording/index');
    }

    public function goCompleted()
    {
        return $this->fetch('index/recording/completed');
    }

    public function goUndone()
    {
        return $this->fetch('index/recording/undone');
    }
}