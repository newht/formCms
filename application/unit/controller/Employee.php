<?php
namespace app\unit\controller;

use think\Controller;
use think\Db;

class Employee extends Controller
{
    public function employeeList()
    {
        $unit = session("unit");
        $data = Db::name("user")->where("unit_id",$unit['id'])->select();
        $this->assign("data",$data);
        return $this -> fetch("index/employee/list");
    }

    public function showAdd()
    {
        return $this -> fetch('index/employee/adduser');
    }

    public function adduser()
    {
        return input();
    }
}