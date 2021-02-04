<?php
namespace app\pay\controller;

use EasyWeChat\Factory;
use think\Controller;

class Pay extends Controller
{
    /**
     * 直接输出二维码 + 生成二维码图片文件
     */
    public function create($qr_url){

        // 自定义二维码配置
        $config = [
            'title'         => true,
            'title_content' => '请用微信扫码',
//            'logo'          => true,
//            'logo_url'      => './static/images/logo.png',
//            'logo_size'     => 150
        ];

        // 直接输出
//        $qr_url = 'http://www.baidu.com?id=' . rand(1000, 9999);

        $qr_code = new QrcodeServer($config);
        $qr_img = $qr_code -> createServer($qr_url);
        echo $qr_img;

        // 写入文件
        $qr_url = '这是个测试二维码';
        $file_name = './static/qrcode';  // 定义保存目录

        $config['file_name'] = $file_name;
        $config['generate']  = 'writefile';

        $qr_code = new QrcodeServer($config);
        $rs = $qr_code->createServer($qr_url);
        print_r($rs);

        exit;
    }

    //微信支付
    public function index()
    {
        return $this -> fetch('pay/pay');
    }

//    protected $_config;
//
//    public  function __construct(){
//        $this -> _config = [
//            // 必要配置
//            'app_id'             => 'wx73229bb5e20d94ea',
//            'mch_id'             => '1605709892',
//            'key'                => '1c63129ae9db9c60c3e8aa94d3e00495',   // API 密钥
//            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
//            'cert_path'          => 'D:\wwwroot\bm.cdpx.org\public\static\cert\apiclient_cert.pem', // XXX: 绝对路径！！！！
//            'key_path'           => 'D:\wwwroot\bm.cdpx.org\public\static\cert\apiclient_key.pem',      // XXX: 绝对路径！！！！
//            'notify_url'         => 'http://bm.cdpx.org/wechatnotify',     // 你也可以在下单时单独设置来想覆盖它
//        ];
//    }

    public function pay()
    {
        $config = [
            // 必要配置
            'app_id'             => 'wx73229bb5e20d94ea',
            'mch_id'             => '1605709892',
            'key'                => '1c63129ae9db9c60c3e8aa94d3e00495',   // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => 'D:\wwwroot\bm.cdpx.org\public\static\cert\apiclient_cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => 'D:\wwwroot\bm.cdpx.org\public\static\cert\apiclient_key.pem',      // XXX: 绝对路径！！！！
            'notify_url'         => 'http://bm.cdpx.org/wechatnotify',     // 你也可以在下单时单独设置来想覆盖它
        ];
        $app = Factory::payment($config);

        $content = $app->scheme(000001);
        $this -> create($content);

    }

    public function huidiao()
    {
        $config = [
            // 必要配置
            'app_id'             => 'wx73229bb5e20d94ea',
            'mch_id'             => '1605709892',
            'key'                => '1c63129ae9db9c60c3e8aa94d3e00495',   // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => 'D:\wwwroot\bm.cdpx.org\public\static\cert\apiclient_cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => 'D:\wwwroot\bm.cdpx.org\public\static\cert\apiclient_key.pem',      // XXX: 绝对路径！！！！
            'notify_url'         => 'http://bm.cdpx.org/wechatnotify',     // 你也可以在下单时单独设置来想覆盖它
        ];
        $app = Factory::payment($config);
        // 扫码支付通知接收第三个参数 `$alert`，如果触发该函数，会返回“业务错误”到微信服务器，触发 `$fail` 则返回“通信错误”
        $response = $app->handleScannedNotify(function ($message, $fail, $alert) use ($app) {
            // 如：$alert('商品已售空');
            // 如业务流程正常，则要调用“统一下单”接口，并返回 prepay_id 字符串，代码如下
            $message['product_id'] = 000001;

            $result = $app->order->unify([
                'trade_type' => 'NATIVE',
                'product_id' => $message['product_id'], // $message['product_id'] 则为生成二维码时的产品 ID
                // ...
            ]);

            return $result['prepay_id'];
        });

        $response->send();
    }
}