<?php
namespace app\pay\controller;

use EasyWeChat\Factory;
use think\Controller;
use think\Db;

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
        // 写入文件
//        $qr_url = '这是个测试二维码';
//        $file_name = './static/qrcode';  // 定义保存目录
//        $config['file_name'] = $file_name;
//        $config['generate']  = 'writefile';
//
//        $qr_code = new QrcodeServer($config);
//        $rs = $qr_code->createServer($qr_url);
//        print_r($rs);
        exit;
    }

    //微信支付（native）收银台
    public function index()
    {
        $id = input('id');
        $data = Db::name('orderinfo')
            -> alias('t1')
            -> join('form_info t2','t1.form_id=t2.id')
            -> where('t1.id',$id)
            -> find();
        $pay_info = $this -> pay(['body' => $data["fname"],"out_trade_no" => $data["order_id"],"total_fee" => $data["money"]*100]);
        if($pay_info['return_code']== "SUCCESS") {
            if($pay_info['result_code'] == "FAIL"){
                return $this -> error($pay_info["err_code_des"],"/user/gorecording");
            }
            $data["pay_info"] = $pay_info;
            $this -> assign('data',$data);
//            dump($data);
            return $this -> fetch('pay/pay');
        }
        return redirect("/user/gorecording");
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
            'trade_type' => 'NATIVE'
        ]);
        return $result;
    }

    public function jsApipay()
    {


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
        return Db::name('orderinfo') -> where('order_id',$orderid) -> value('status');
    }

    public function notify()
    {
        $config = $this -> setConfig();
        $app = Factory::payment($config);
        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
//            Db::name('userinfo') -> where('uid','=','10') -> update(['email'=>$message['out_trade_no']]);
            $order = Db::name('orderinfo') -> where('order_id',$message['out_trade_no']) -> value('status');
//            Db::name('userinfo') -> where('uid','=','11') -> update(['email'=>$order]);
            if (empty($order) || $order == 1) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            Db::name('orderinfo') -> where('order_id',$message['out_trade_no']) -> update(['status'=>1]);
            return true; // 返回处理完成
        });
        $response -> send();
    }
}