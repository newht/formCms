<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class update extends Controller
{
	public function show()
	{
		//已有的人的所有信息
		$data = Db::table('tb_users t1') 
			-> join('tb_work_units t2','t1.id = t2.uid') 
			-> join('tb_now_position t3','t3.uid = t1.id') 
			-> join('tb_job_declare t4','t4.uid = t1.id') 
			-> where('t1.cardid','511027197310077699')
			-> find();
		//要修改的人的id
		$upid = Db::name('users') -> where('cardid','371581198806216414') -> value('id');
        echo "<pre>";
        echo '已有业绩的：'.Db::name('users') -> where('cardid','511027197310077699') -> value('name');
        echo "<br/>";
        echo '添加业绩的：'.$data['name'];
        echo "</pre>";

		$tb_study_exp = Db::table('tb_study_exp') 
			-> field('uid,start_time,end_time,unit,certificate_no,witness,learningplace,major')
			-> where('uid',$data['id'])
			-> select();
		for($i = 0 ; $i < count($tb_study_exp) ; $i++){
			$tb_study_exp[$i]['uid'] = $upid;
		}
		
		$tb_work_exp = Db::table('tb_work_exp')
			-> field('uid,start_time,end_time,workunit,professional_work,post,witness')
			-> where('uid',$data['id'])
			-> select();
		for($i = 0 ; $i < count($tb_work_exp) ; $i++){
			$tb_work_exp[$i]['uid'] = $upid;
		}
		
		$tb_past_results = Db::table('tb_past_results')
			-> field('uid,start_time,end_time,technical_work,work_content,ompletion_effect')
			-> where('uid',$data['id'])
			-> select();
		for($i = 0 ; $i < count($tb_past_results) ; $i++){
			$tb_past_results[$i]['uid'] = $upid;
		}
		
		$tb_now_results = Db::table('tb_now_results')
			-> field('uid,start_time,end_time,technical_work,work_content,ompletion_effect')
			-> where('uid',$data['id'])
			-> select();
		for($i = 0 ; $i < count($tb_now_results) ; $i++){
			$tb_now_results[$i]['uid'] = $upid;
		}
		dump($tb_study_exp[0]);
		dump($tb_work_exp[0]);
		dump($tb_past_results[0]);
		dump($tb_now_results[0]);
//		$this -> insert($tb_study_exp,$tb_work_exp,$tb_past_results,$tb_now_results);
	}
	
	public function insert($tb_study_exp,$tb_work_exp,$tb_past_results,$tb_now_results)
	{
//	    Db::table('tb_study_exp') -> insertAll($tb_study_exp);
//	    Db::table('tb_work_exp') -> insertAll($tb_work_exp);
	    Db::table('tb_past_results') -> insertAll($tb_past_results);
	    Db::table('tb_now_results') -> insertAll($tb_now_results);
	}
}


