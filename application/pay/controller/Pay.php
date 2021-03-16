<?php
namespace app\pay\controller;

use EasyWeChat\Factory;
use think\Controller;
use think\Db;
use think\Exception;

class Pay extends Controller
{
    /**
     * 直接输出二维码 + 生成二维码图片文件
     */
    public function create($qr_url){

        // 自定义二维码配置
        $config = [
            'title'         => true,
//            'title_content' => '请用微信扫码',
        ];
        $qr_code = new QrcodeServer($config);
        $qr_img = $qr_code -> createServer($qr_url);
        echo $qr_img;
        exit;
    }

    public function iswechat()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            // 非微信浏览器禁止浏览
            return false;
        } else {
            // 微信浏览器，允许访问
            return true;
        }
    }

    public function getOrderInfo($id)
    {
        $data = Db::name('orderinfo')
            -> alias('t1')
            -> field('t1.id,t1.status,order_id,create_time,fname,subtitle,tb_name,expiration,money')
            -> join('form_info t2','t1.form_id=t2.id')
            -> where('t1.id',$id)
            -> find();
        $sql = "SELECT  column_name,column_comment FROM information_schema.columns WHERE table_schema ='".config('database.database')."' AND table_name = '".$data['tb_name']."'";
        $tb = Db::query($sql);
        foreach ($tb as $v){
            if( $v['column_comment'] == '姓名' || $v['column_comment'] == '真实姓名'){
                $name = Db::name($data['tb_name'])
                    -> where('orderid',$data['id'])
                    -> value($v['column_name']);
                $data['fname'] = $data['fname'].' - '.$name;
            }
        }
        return $data;
    }

    //微信支付收银台
    public function index()
    {
        //@id orderinfo表中的id
        $id = input('id');
        $iswachat = $this -> iswechat();
        $data = $this -> getOrderInfo($id);
        if($iswachat){

        }else {
            $pay_info = $this->pay(['body' => $data["fname"], "out_trade_no" => $data["order_id"], "total_fee" => $data["money"] * 100]);
            if ($pay_info['return_code'] == "SUCCESS") {
                if ($pay_info['result_code'] == "FAIL") {
                    return $this->error($pay_info["err_code_des"], "/user/gorecording");
                }
                $data["pay_info"] = $pay_info;
            } else {
                return redirect("/user/gorecording");
            }
        }

        $this->assign('data', $data);
        return $this->fetch('pay/pay');
    }

    public function setConfig()
    {
        return [
            // 必要配置
            'app_id'             => 'wx73229bb5e20d94ea',
            'mch_id'             => '1605709892',
            'key'                => '1c63129ae9db9c60c3e8aa94d3e00495',   // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => 'D:\wwwroot\bm.cdpx.org\cert\apiclient_cert.pem',
            'key_path'           => 'D:\wwwroot\bm.cdpx.org\cert\apiclient_key.pem',
            'notify_url'         => 'http://bm.cdpx.org/pay/notify',     // 你也可以在下单时单独设置来想覆盖它
        ];
    }

    public function pay($mssage = array())
    {
        if(empty($mssage)){
            $mssage = input();
        }
        //微信下订单
        $config = $this -> setConfig();
        $app = Factory::payment($config);
        $result = $app->order->unify([
            'body' => $mssage['body'],
            'out_trade_no' => $mssage['out_trade_no'],
            'total_fee' => $mssage['total_fee'],
            'trade_type' => 'NATIVE',
        ]);
        return $result;
    }

    public function jsApipay()
    {
//        $this -> getOpenId(input("code"));
        $id = input("orderid");
        $data = $this -> getOrderInfo($id);
        $config = $this -> setConfig();
        $app = Factory::payment($config);
        $result = $app->order->unify([
            'body' => $data["fname"],
            'out_trade_no' => $data["order_id"],
            'total_fee' => $data["money"]*100,
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => session("useropen")["openid"],
        ]);
        $json = $app ->jssdk->bridgeConfig($result['prepay_id'],false);
        $this -> assign("date",time());
        $this -> assign("result",$json);
        return $this -> fetch("/pay/jsapi");
    }

    function getOpenId($code){
        $appid = "wx73229bb5e20d94ea";
        $secret = "1be88e8da232475cc27373db051925e5";
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
        $data = json_decode(file_get_contents($url),true);
        session("useropen",$data);
        return $data;
    }

    public function getOrder()
    {
        $order = Db::name('orderinfo')
            -> alias('t1')
            -> field('t1.id,t1.status,t1.order_id,t1.create_time,t2.fname,t2.money')
            -> join('form_info t2','t1.form_id = t2.id ')
            -> where('t1.id',input('id'))
            -> find();
        return ['code'=>0,'order'=>$order];
    }

    public function getOrderStatus()
    {
        $orderid = input('orderid');
        cookie('orderid', $orderid);
        return Db::name('orderinfo') -> where('order_id',$orderid) -> value('status');
    }

    public function notify()
    {
        $config = $this -> setConfig();
        $app = Factory::payment($config);
        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = Db::name('orderinfo') -> where('order_id',$message['out_trade_no']) -> value('status');
            if (empty($order) || $order == 1) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 修改订单表数据
            Db::name('orderinfo') -> where('order_id',$message['out_trade_no']) -> update(['status'=>1]);
            return true; // 返回处理完成
        });
        $response -> send();
    }

    public function jsapiNotify()
    {

    }

    public function getWechatOrderInfo()
    {
        $orderid = input("orderid");
        $config = $this -> setConfig();
        $app = Factory::payment($config);
        $data = $app->order->queryByOutTradeNumber($orderid);
        dump($data);
    }

    public function notice()
    {
        $inviteInfo = cookie('inviteInfo');
        $orderId = cookie('orderid');
        if (!empty($inviteInfo)) {
            // 通过用户推荐,保存推荐信息
            try {
                $res = Db::table('orderinfo')->where('order_id', $orderId)->update([
                    'is_recommend' => 1,
                    'user_id' => $inviteInfo['user_id'],
                ]);
                if ($res == false) {
                    throw new Exception('保存推荐信息失败');
                }
                // 存储用户的推荐余额数据
                $this->saveUserBalanceByOrderId($orderId);
            }catch (\Exception $ex) {
                return $ex->getMessage();
            }
        }
        return $this -> fetch("pay/success");
    }

    /**
     * 通过订单id存储用户推荐余额
     * @param $order_id string 订单id
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function saveUserBalanceByOrderId($order_id)
    {
        $orderArr = Db::name('orderinfo')->where('order_id', $order_id)->field('user_id, form_id')->find();
        // 通过表单id获取到报名课程价格
        $formArr = Db::name('form_info')->where('id', $orderArr['form_id'])->field('fname, money')->find();
        try {
            // 存储推荐返利数据和更新用户返利余额
            $res = Db::name('recommend')->insert([
                'user_id' => $orderArr['user_id'],  // 推荐人id
                'order_id' => $order_id,  // 订单id
                'recommend_user_id' => session('user')['id'], // 被推荐人id(自己本身id)
                'fname' => $formArr['fname'],  // 报名课程
                'fprice' => $formArr['money'],  // 课程价格
                'rebate_price' => $formArr['money']/100,  // 返利价格
                'create_time' => time(),
            ]);
            $balance = Db::name('user')->where('id', $orderArr['user_id'])->value('balance');
            $userResult = Db::name('user')->where('id', $orderArr['user_id'])->update([
                'balance' => $balance + ($formArr['money']/100),
            ]);
            if (!$res || $userResult === false) {
                throw new Exception('存储返利数据失败');
            }
        }catch (\Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}