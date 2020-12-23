<?php
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

    public function delete($type)
    {
        $data = input("data");
        Db::startTrans();
        try{
            if($type == "admin"){
                foreach($data as $v){
                    Db::name("admin") -> where('id',$v) -> delete();
                    Db::name("admin_post")-> where('admin_id',$v) -> delete();
                }
                Db::commit();
            }elseif ($type=="post"){
                foreach($data as $v){
                    Db::table("post") -> where('id',$v) -> delete();
                    Db::table("admin_post")-> where('post_id',$v) -> delete();
                    Db::table('post_rbac')->where('post_id',$v)->delete();
                }
                Db::commit();
            }else{
                Db::rollback();
                return json(["code"=>0,"error"=>'数据格式错误！']);
            }
        }catch (Exception $e){
            return json(["code"=>0,"error"=>$e->getMessage()]);
        }
        return json(['code' =>1,"error"=>null]);
    }

    public function goRbac()
    {
        $rbac = Db::name("rbac") -> select();
        $this -> assign("rbac",$rbac);
        $data = Db::query("SELECT id,name,GROUP_CONCAT(rbac_id) AS rbac_id FROM post t1 left JOIN post_rbac t2 ON t1.id = t2.post_id GROUP BY t1.id");
        Db::name("post") -> select();
        $this -> assign("data",$data);
        return $this -> fetch("rbac/rbac");
    }

    //修改角色权限
    public function setRbac()
    {
        $id = input('id');
        $data = [];
        foreach(input() as $k => $v){
            if($k == 'name'){
                Db::name("post")->where('id',$id) -> update(['name'=>input('name')]);
            }else if(substr($k,0,5) == 'rbac_'){
                $temp = ['rbac_id' => $v,'post_id'=>$id];
                $data[]=$temp;
            }
        }
        Db::name('post_rbac') -> where('post_id',$id) -> delete();
        Db::name('post_rbac') -> insertAll($data);
        return ["code"=>1,'error'=>null];
    }

    public function insertPost()
    {
        $postname = input('name');
        Db::startTrans();
        try{
            $postid = Db::table("post")->insertGetId(["name"=>$postname]);
            $data = [];
            foreach(input() as $k=>$v){
                if(substr($k,0,5) == 'rbac_'){
                    $temp = ['rbac_id' => $v,'post_id'=>$postid];
                    $data[]=$temp;
                }
            }
            Db::name('post_rbac') -> insertAll($data);
            Db::commit();
        }catch (Exception $e){
            Db::rollback();
            return ['code'=>0,'error'=>$e->getMessage()];
        }
        return ['code'=>1,"error"=>null,"data"=>$data];
    }
}