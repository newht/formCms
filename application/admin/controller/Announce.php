<?php
/*
 * @Author: your name
 * @Date: 2021-01-04 16:06:12
 * @LastEditTime: 2021-01-06 17:28:24
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \project.com\application\admin\controller\Announce.php
 */
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\facade\View;

class Announce extends Controller{

    public function announce_edit($id = ""){

        // return $id;
        if($id){

            $data = Db::table('announcements')->where('id',$id)->find(); 
            // echo "<pre>";var_dump($data);die;
            $this->assign("data", $data);

        }
            return $this->fetch("announce/announce_edit");
        
        
    }
    public function announce(){
        $data = Db::table('announcements')->where('is_delete',0)->select();
        $this->assign("data", $data);
        return $this->fetch("announce/announce");
    }

    public function anon_del(){
        $id = $_POST['id'];
        $err = Db::table('announcements')->delete($id);
        if(!$err){

            return ['code' => 0,'msg' => '删除失败','url' => null];
        }
        return ['code' => 1,'msg' => '删除成功','url' => null];
    }

    public function anon_add(){

        // var_dump($_POST['static']);
        if(empty($_POST['static'])){
            $is_static = 0;
        }else{
            $is_static = 1;    
        }
        $id = $_POST['id'];
        $title = $_POST['title'];
        $subtitle = $_POST['subtitle'];
        $contents = $_POST['contents'];
        if(empty($_POST['id'])){
            $data = ['title' => $title, 'subtitle' =>  $subtitle,'is_static'=> $is_static,'contents'=>$contents,'create_time'=> time()];
            Db::name('announcements')->data($data)->insert();
            return $this->announce();
        }
        $data = ['title' => $title, 'subtitle' =>  $subtitle,'is_static'=> $is_static,'contents'=>$contents,'update_time'=> time()];
        Db::name('announcements')->where('id', $id)->update($data);
        return $this->announce();
 
    }

    public function announce_del(){

        // echo "已删除公告列表"; 
        $data = Db::table('announcements')->where('is_delete',1)->select();
        $this->assign("data", $data);
        return $this->fetch("announce/announce_del");
     }

}


?>