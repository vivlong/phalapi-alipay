<?php
namespace PhalApi\Alipay;

require_once API_ROOT.'/vendor/alipay-sdk/AopSdk.php';

class Lite {

    protected $config;

    public function __construct($config = NULL) {
        $this->config = $config;
        if ($this->config === NULL) {
            $this->config = \PhalApi\DI()->config->get('app.Alipay');
        }
    }

    public function getConfig() {
        return $this->config;
    }

    /**
     * getOrderString
     * @return string
     */
    public function getOrderString($out_trade_no, $total_amount, $title, $subject, $passback_params, $timeout_express = '15m') {
        try{
            $aop = new \AopClient();
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
            $aop->appId = $this->config['appId'];
            $aop->rsaPrivateKey = $this->config['rsaPrivateKey'];
            $aop->format = 'json';
            $aop->charset = 'UTF-8';
            $aop->signType = 'RSA2';
            $aop->alipayrsaPublicKey = $this->config['alipayrsaPublicKey'];
            $request = new \AlipayTradeAppPayRequest();
            $bizObj = array(
                'body' => strval($title),
                'subject' => strval($subject),
                'out_trade_no' => strval($out_trade_no),
                'total_amount' => strval($total_amount),
                'product_code' => 'QUICK_MSECURITY_PAY',
                'timeout_express' => strval($timeout_express),
                'passback_params' => urlencode($passback_params),
            );
            $bizcontent = json_encode($bizObj);
            /*
            $bizcontent = '{"body":"'.$title.'",'
                        .'"subject": "'.$subject.'",'
                        .'"out_trade_no": "'.$out_trade_no.'",'
                        .'"timeout_express": "'.$timeout_express.'",'
                        .'"total_amount": "'.$total_amount.'",'
                        .'"product_code":"QUICK_MSECURITY_PAY",'
                        .'"passback_params": "'.urlencode($passback_params).'"'
                        .'}';
            */
            $request->setNotifyUrl($this->config['notifyUrl']);
            $request->setBizContent($bizcontent);
            //这里和普通的接口调用不同，使用的是sdkExecute
            $response = $aop->sdkExecute($request);
            //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
            return $response; //orderString 可以直接给客户端请求，无需再做处理。
        } catch(Exception $e) {
            \PhalApi\DI()->logger->error('Alipay\ getOrderString', $e->getMessage());
            return false;
        }
    }

}
