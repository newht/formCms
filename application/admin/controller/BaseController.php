<?php
/**
 * Created by PhpStorm.
 * User: g
 * Date: 2021/3/12
 * Time: 10:05
 */

namespace app\admin\controller;


use think\Controller;

class BaseController extends Controller
{

    /**
     * 配置分页数据
     * @param $paginate object 分页对象
     * @return mixed array
     */
    protected function configPaginateInfo($paginate)
    {
        $arr['total'] = $paginate->total(); // 总数量
        $arr['current_page'] = $paginate->currentPage(); // 当前页
        $arr['list_rows'] = $paginate->listRows(); // 一页显示个数
        $arr['last_page'] = $paginate->lastPage(); //总页数
        $arr['current_url'] = request()->url();  //当前地址
        return $arr;
    }
}