<?php


namespace app\promotion\controller;


use app\admin\controller\BaseController;
use app\pay\controller\Pay;
use think\Controller;
use think\Db;
use think\Exception;

class Index extends BaseController
{
    /**
     * 推广页面
     * @return mixed
     */
    public function index()
    {
        $userId = input('get.user_id');  // 邀请人id
        $inviteNo = $this->generatorUserInviteNo($userId);
        $linkPath = config('app.web_url')."/user/login?user_id=$userId&invite_no=$inviteNo"; // 推广链接
        $this->assign('linkPath', $linkPath);
        $this->assign('inviteNo', $inviteNo);
        $this->assign('userId', $userId);
        return $this->fetch('/index');
    }

    /**
     * 我的推广
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function my()
    {
        $user_id = session('user')['id'];
        $list = Db::name('recommend')->where('user_id', $user_id)->order('create_time desc')->paginate(5,false);
        $data = $list->toArray()['data'];
        foreach ($data as $key => $val) {
            $data[$key]['recommend_user'] = Db::name('user')->where('id', $val['user_id'])->value('phone');
            $data[$key]['apply_user'] = Db::name('user')->where('id', $val['recommend_user_id'])->value('phone');
        }
        $pag = $this->configPaginateInfo($list); // 分页配置
        $balanceTotal = Db::name('user')->where('id', $user_id)->value('balance');
        $this->assign('recommendList', $data);
        $this->assign('balanceTotal', $balanceTotal);
        $this->assign('paginate', $pag);
        $this->assign('userId', $user_id);
        return $this->fetch('/my');
    }

    /**
     * 加载更多
     * @return \think\response\Json|bool
     * @throws Exception
     */
    public function loadMore()
    {
        $currentPage = (int)input('post.page');
        $currentUrl = trim(input('post.current_url'));
        $listRows = input('post.list_rows'); // 一页显示个数
        $lastPage = input('post.last_page');
        $userId = input('post.user_id');
        if ($currentPage > $lastPage) {
            return false;
        }
        // limit(offset, rows);  offset从第几条开始  rows显示几条
        $offset = intval(($currentPage - 1) * $listRows);
        try {
            $newList = Db::name('recommend')->where('user_id', $userId)->order('create_time desc')->limit($offset, $listRows)->select();
            foreach ($newList as $key => $val) {
                $newList[$key]['recommend_user'] = Db::name('user')->where('id', $val['user_id'])->value('phone');
                $newList[$key]['apply_user'] = Db::name('user')->where('id', $val['recommend_user_id'])->value('phone');
            }
            return json(['code' => 1, 'data' => $newList]);
        }catch (\Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * @param $user_id
     * @return string
     * 生成用户推荐码
     */
    private function generatorUserInviteNo($user_id)
    {
        $microArr = explode(' ', microtime());
        $inviteNo = substr($microArr[0],2,8).$user_id;
        return $inviteNo;
    }


}