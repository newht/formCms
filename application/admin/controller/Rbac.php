<?php
/**
 * Created by PhpStorm.
 * User: 16665
 * Date: 2020/12/21
 * Time: 16:37
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;
use think\Exception;

class Rbac extends Controller
{
    public function goAdmin()
    {
        $sql = "select t1.id,t1.nick_name AS '管理员名字',t3.name AS '角色名称',t1.create_time as '创建时间' FROM admin t1 INNER JOIN admin_post t2 ON t1.id = t2.admin_id INNER JOIN post t3 ON t3.id = t2.post_id  ";
        $data = Db::query($sql);
        $this -> assign("data",$data);
        return $this -> fetch('rbac/admin');
    }

    public function insert($id = '')
    {
        $nick_name = input("nick_name");
        $name = input("name");
        $password = md5(input("password"));
        $post_id = input("post_id");
        if(empty($id)){
            Db::startTrans();
            try{
                $adminid = Db::table('admin') -> insertGetId(['nick_name'=>$nick_name,'name'=>$name,'password'=>$password]);
                Db::table("admin_post")->insert(['admin_id'=>$adminid,'post_id'=>$post_id]);
                Db::commit();
                return ['code'=>1,'error'=>null];
            }catch (Exception $e){
                Db::rollback();
                return ['code'=>0,'error'=>$e->getMessage()];
            }
        }else{
            Db::startTrans();
            try{
                Db::table('admin') -> where('id',$id) -> update(['nick_name'=>$nick_name,'name'=>$name,'password'=>$password]);
                Db::table("admin_post")-> where('admin_id',$id)-> update(['post_id'=>$post_id]);
                Db::commit();
                return ['code'=>1,'error'=>null];
            }catch (Exception $e){
                Db::rollback();
                return ['code'=>0,'error'=>$e->getMessage()];
            }
        }
    }

    public function getAdminInfo($id)
    {
        $data = Db::name('admin')
            -> alias('t1')
            -> field('t1.nick_name,t1.name,t3.id as post_id')
            -> where('t1.id',$id)
            -> join('admin_post t2','t1.id = t2.admin_id')
            -> join('post t3','t3.id = t2.post_id')
            -> find();
        return ['code' => 1 , 'data' => $data];
    }

        public function delete()
    {
        $data = input("data");
        foreach($data as $v){
            Db::name("admin") -> where('id',$v) -> delete();
            Db::name("admin_post")-> where('admin_id',$v) -> delete();
        }
        return ['code' =>1,"error"=>null];
    }

    public function goRbac()
    {
        $data = Db::name("rbac") -> select();
        $this -> assign("data",$data);
//        dump($data);
        return $this -> fetch("rbac/rbac");
    }
}