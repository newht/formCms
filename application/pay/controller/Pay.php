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

    public function pay()
    {


        $config = [
            // 必要配置
            'app_id'             => 'wx73229bb5e20d94ea',
            'mch_id'             => '1605709892',
            'key'                => '1c63129ae9db9c60c3e8aa94d3e00495',   // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => 'E:\phpstudy_pro\WWW\think\public\static\cert\certapiclient_cert.pem',
            'key_path'           => 'E:\phpstudy_pro\WWW\think\public\static\cert\apiclient_key.pem',
//            'cert_path'          => 'D:\wwwroot\bm.cdpx.org\public\static\cert\apiclient_cert.pem', // XXX: 绝对路径！！！！
//            'key_path'           => 'D:\wwwroot\bm.cdpx.org\public\static\cert\apiclient_key.pem',      // XXX: 绝对路径！！！！
            'notify_url'         => 'http://bm.cdpx.org/wechatnotify',     // 你也可以在下单时单独设置来想覆盖它
        ];

        $config = [
            'token'          => 'test',
            'appid'          => 'wx73229bb5e20d94ea',
            'appsecret'      => '1be88e8da232475cc27373db051925e5',
            'encodingaeskey' => 'BJIUzE0gqlWy0GxfPp4J1oPTBmOrNDIGPNav1YFH5Z5',
            // 配置商户支付参数（可选，在使用支付功能时需要）
            'mch_id'         => "1605709892",
            'mch_key'        => '1c63129ae9db9c60c3e8aa94d3e00495',
            // 配置商户支付双向证书目录（可选，在使用退款|打款|红包时需要）
            'ssl_key'        => 'E:\phpstudy_pro\WWW\think\public\static\cert\certapiclient_cert.pem',
            'ssl_cer'        => 'E:\phpstudy_pro\WWW\think\public\static\cert\apiclient_key.pem',
            // 缓存目录配置（可选，需拥有读写权限）
//            'cache_path'     => '',
        ];
//        $app = Factory::payment($config);

        // 创建接口实例
        $wechat = new \WeChat\Pay($config);

        // 组装参数，可以参考官方商户文档
        $options = [
            'body'             => '测试商品',
            'out_trade_no'     => time(),
            'total_fee'        => '1',
            'openid'           => 'o38gpszoJoC9oJYz3UHHf6bEp0Lo',
            'trade_type'       => 'JSAPI',
            'notify_url'       => 'http://bm.cdpx.org/pay',
            'spbill_create_ip' => '127.0.0.1',
        ];

        try {

            // 生成预支付码
            $result = $wechat->createOrder($options);

            // 创建JSAPI参数签名
            $options = $wechat->createParamsForJsApi($result['prepay_id']);

            // @todo 把 $options 传到前端用js发起支付就可以了

        } catch (Exception $e) {

            // 出错啦，处理下吧
            echo $e->getMessage() . PHP_EOL;

        }

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

    public static function wxPay(){
        $wxConfig = [
            // 必要配置
            'app_id'             => '',
            'mch_id'             => '',
            'key'                => '',   // API 密钥
        ];
        return Factory::payment($wxConfig);
    }

    public function usewxpay(){
        try{

            $out_trade_no = date('YmdHis');//订单号(14+14)
            $total_fee    = $_POST['price']*100;//商品价格
//            $total_fee    = 0.1*100;//商品价格
            $app = self::wxPay();
            $result = $app->order->unify([
                'body' => $_POST['qstitle'],
                'out_trade_no' => $out_trade_no,
                'total_fee' => $total_fee,
                'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
                'openid' => $_POST['openid'],
            ]);
            $data = $app->jssdk->sdkConfig($result['prepay_id']);
            return json_encode($data,true);
        }catch (\Exception $e){
            var_dump($e->getMessage());die;
//            return ['code' => 0, 'msg' => '下订单失败'];
        }
    }



}