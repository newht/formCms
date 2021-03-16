<?php
namespace app\admin\controller;
use app\admin\model\Form_info;
use app\wxpay\controller\JsApi;
use think\Controller;
use think\Db;
use think\Env;
use think\facade\Config;

class Index extends Controller
{
    public function index()
    {
        $form_info = new Form_info();
        $data = $form_info->getAll();
        $this->assign("data", $data);
        return $this->fetch('index/index');
    }

    /**
     * 后台信息页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function info()
    {
        $userNum = Db::name('user')->count('id');
        $announceNum = Db::name('announcements')->count('id');
        $orderNum = Db::name('orderinfo')->count('id');
        $depositNum = Db::name('deposit')->where('audit_status',2)->count(); // 提现未审核人数
        $tables = $this->getTables();
        $this->assign('userNum', $userNum);
        $this->assign('announceNum', $announceNum);
        $this->assign('orderNum', $orderNum);
        $this->assign('tables', $tables);
        $this->assign('depositNum', $depositNum);
        return $this->fetch('index/info');
    }

    //查询表中的所有数据
    public function getData($tb_name)
    {
        $database =  Config::get('database.database');
        $th = Db::query("SELECT a.table_name '表名', b.column_name '字段名', b.column_comment '字段说明'
          FROM information_schema. TABLES a
          LEFT JOIN information_schema.columns b ON a.table_name = b.table_name
          WHERE a.table_schema = '" . $database . "'
          and a.table_name = '" . $tb_name . "'
          or a.table_name = 'orderinfo'");
        $str = '';
        foreach ($th as $k => $v){
            if($v['表名'] == $tb_name &&  $v['字段说明'] != '订单id'){
                $str .= $tb_name."." . $v['字段名'] . ",";
            }else if($v['表名'] == 'orderinfo' && $v['字段说明'] == '订单状态'){
                $str .= "orderinfo." . $v['字段名'] . ",";
            }else{
                unset($th[$k]);
            }
        }
        $str = substr($str,0,-1);
        if (!empty(input('get.flag'))) {
            // 只获取未审核数据列表
            $sql = "select " . $str ." from ".$tb_name." left join orderinfo on " .$tb_name.".orderid = orderinfo.id".
                " where ".$tb_name.".auditstates = '未审核'";
        }
        else {
            $sql = "select " . $str ." from ".$tb_name." left join orderinfo on " .$tb_name.".orderid = orderinfo.id";
        }
        $data = Db::query($sql);
        $this->assign('th', $th);
        $this->assign('data', $data);
        $this->assign('jsonData', json_encode($data));
        return $this -> fetch("index/tables");
    }

    public function auditStates()
    {
        $tbname = input('tbname');
        $data = input('data');
        foreach($data as $v){
            Db::table($tbname) -> where("id",$v['id']) -> update(['auditstates' => $v['auditstates']]);
        }
        return 1;
    }

    /**
     * 清理推荐二维码图片
     * @return bool
     * @throws \think\Exception
     */
    public function clearQrcode()
    {
        return clearDirData($_SERVER['DOCUMENT_ROOT'].'/qrcode');
    }

    /**
     * 清理缓存
     * @return bool
     * @throws \think\Exception
     */
    public function clearCache()
    {
        $path = Env('root_path').'runtime/temp';
        return clearDirData($path);
    }

    /**
     * 清理日志
     * @return bool
     * @throws \think\Exception
     */
    public function clearLog()
    {
        $path = Env('root_path').'runtime/log';
         return clearDirData($path);
    }

    /**
     * 获取form表单信息
     * @return array|\PDOStatement|string|\think\Collection|\think\model\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getTables()
    {
        $data = Db::table("form_info")
            ->field("id,fname,tb_name,subtitle,status,expiration")
            ->select();
        foreach ($data as $k => $v) {
            $data[$k]['count'] = Db::table($v['tb_name'])->count();
            $data[$k]['unreviewed_count'] = Db::table($v['tb_name'])->where('auditstates','=','未审核')->count();
        }
        return $data;
    }
}
