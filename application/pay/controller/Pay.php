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

    //微信支付
    public function index()
    {
        return $this -> fetch('pay/pay');
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

    public function pay()
    {
        $mssage = input();
        $config = $this -> setConfig();
        $app = Factory::payment($config);
        $result = $app->order->unify([
            'body' => $mssage['body'],
            'out_trade_no' => date('Ymdhis'),
            'total_fee' => $mssage['total_fee'],
            'trade_type' => 'NATIVE'
        ]);
        if($result['return_code']=='SUCCESS'){
            if($result['result_code']=='SUCCESS'){
                $this -> create($result['code_url']);
                exit;
            }
            if($result['result_code']=='FAIL'){
                echo $result['err_code_des'];
                exit;
            }
        }
        dump($result);
        exit;

    }

    public function notify()
    {

    }

}