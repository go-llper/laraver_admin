<?php
/**
 * Created by PhpStorm.
 * 融宝支付类
 * User: caelyn
 * Date: 16/1/25
 * Time: 下午15:23
 */
namespace App\Service\Rea;
$basePath = base_path();
require_once($basePath."/app/Service/Rea/api/util.class.php");

class Reapay {

    protected $config;
    private $payPublicKey           = '';   //支付接口相关公钥
    private $payPrivateKey          = '';   //支付接口相关私钥

    private $checkCardPublicKey     = '';   //验卡接口相关公钥
    private $checkCardPrivateKey    = '';   //验卡接口相关私钥

    public function __construct($config)
    {
        $this->basePath = base_path();
        $this->config = $config;

        $this->payPublicKey = $this->basePath.'/app/Service/Rea/cert/public.pem';
        $this->payPrivateKey = $this->basePath.'/app/Service/Rea/cert/private.pem';

        $this->checkCardPublicKey = $this->basePath.'/app/Service/Rea/check/public.pem';
        $this->checkCardPrivateKey = $this->basePath.'/app/Service/Rea/check/private.pem';

    }


    /**
     * 签约
     * @param array $data
     * @return mixed|string
     */
    public function signed($param){


        //业务接收签约参数
        //$data = $this->getSignData($param);

        $paramArr = array(
            'merchant_id' => $this->config['merchant_id'],
            'cert_type' => $this->config['cert_type'],
            'notify_url' => $param['notify_url'],
            'seller_email' => $this->config['seller_email'],
            'token_id' => $this->createUuid(),
        );

        $paramArr = array_merge($paramArr,$param);
        $result = send($paramArr, $this->config['signUrl'], $this->config['apiKey'], $this->payPublicKey, $this->config['merchant_id']);

        //解密返回数据
        $return = $this->deCode($result);
        return $return;
    }

    /**
     * 融宝确认支付
     * @param array $param [$order_no,$check_code]
     * @return array $res
     */
    public function submit($data){
        
        $result = send($data, $this->config['payUrl'], $this->config['apiKey'], $this->payPublicKey, $this->config['merchant_id']);
        $return = $this->deCode($result);
        return $return;
    }

    /**
     * 重发验证码
     */
    public function sendCode($orderId){
        
        $paramArr = array(
            'merchant_id' => $this->config['merchant_id'],
            'order_no' => $orderId,
        );
        $result = send($paramArr, $this->config['sendCodeUrl'], $this->config['apiKey'], $this->payPublicKey, $this->config['merchant_id']);
        $return = $this->deCode($result);
        return $return;
    }

    /**
     * 异步获取结果
     */
    public function notice(){

        $params = $_REQUEST;
        $vo = $this->deCode($params);
        return $vo;
    }


    /**
     * 查单
     */
    public function search($orderId){
        
        $paramArr = array(
            'merchant_id' => $this->config['merchant_id'],
            'order_no' => $orderId,
            'version' => '3.1.2'
        );


        $result = send($paramArr, $this->config['selectOrderUrl'], $this->config['apiKey'], $this->payPublicKey, $this->config['merchant_id']);
        $return = $this->deCode($result);
        return $return;
    }

    /**
     * @param $userName
     * @param $userIdentity
     * @param $cardNo
     * @param $phone
     * @param string $cvv2
     * @param string $validthru
     * @return array
     * 信用卡验卡
     */
    public function checkCreditCard($phone,$name,$idCard,$cardNo,$cvv2,$validthru){
        
        $memberId = date("YmdHis").rand(1000,9999);
        $paramArr = array(
            'card_no'       => $cardNo,
            'cert_no'       => $idCard,
            'cert_type'     => '01',
            'cvv2'          => $cvv2,
            'validthru'     => $validthru,
            'member_id'     => $memberId,
            'merchant_id'   => $this->config['merchantIdCheckCard'],
            'owner'         => $name,
            'phone'         => $phone,
            'version'       => '1.0.0',

        );
        return $this->checkCard($paramArr);

    }


    /**
     * @param $userName
     * @param $userIdentity
     * @param $cardNo
     * @param string $phone
     * @return mixed
     * 储蓄卡三四要素验卡
     */
    public function checkDepositCard($phone,$name,$idCard,$cardNo){

        $conf = $this->config;
        $memberId = date("YmdHis").rand(1000,9999);
        $paramArr = array(
            'card_no'       => $cardNo,
            'cert_no'       => $idCard,
            'cert_type'     => '01',
            'member_id'     => $memberId,
            'merchant_id'   => $conf['merchantIdCheckCard'],
            'owner'         => $name,
            'phone'         => $phone,
            'version'       => '1.0.0',

        );
        return $this->checkCard($paramArr);

    }


    private function checkCard($paramArr){

        $result = send($paramArr, $this->config['cardIdentifyUrl'], $this->config['apiKeyCheckCard'], $this->checkCardPublicKey, $this->config['merchantIdCheckCard']);
        $response = json_decode($result,true);
        $encryptkey = RSADecryptkey($response['encryptkey'],$this->checkCardPrivateKey);
        $result =  AESDecryptResponse($encryptkey,$response['data']);
        //返回
        return json_decode($result,true);
    }



    /**
     * 生成uuid
     */
    private function createUuid(){
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);
        return  $uuid;
    }

    /**
     * 获取签名数据处理
     */
    private function getSignData($param){
        
        switch($param['from']){
            case 1: $type='web';break;
            case 2: $type='wap';break;
            case 3: $type='mobile';break;
            default : $type='web';break;
        }
        //参数数组
        $data = array(
            'card_no' => $param['card_no'],
            'owner' => $param['account_name'],
            'cert_no' => $param['identity_card'],
            'order_no' => $param['order_no'],
            'phone'=> $param['phone'],
            'transtime' => date('Y-m-d H:i:s'),
            'total_fee' => $param['cash']*100,
            'title' => '充值',
            'body' => '九斗鱼充值',
            'terminal_type'=>$type,
            'terminal_info' => 'a4',//$this->getLogic()->getMacAddress(),//'a4:5e:60:f3:33:db',
            'member_ip' => $param['user_ip'],
            'member_id' => $param['user_id'],
        );
        return $data;
    }

    /**
     * 解密融宝返回数据
     * @param string $string
     * @return array
     */
    public function deCode($string){
        
        $response = json_decode($string,true);
        $encryptkey = RSADecryptkey($response['encryptkey'],$this->payPrivateKey);
        $return =  AESDecryptResponse($encryptkey,$response['data']);
        $return = json_decode($return,true);

        return $return;
    }



    public function deCodeNotice($params){

        $encryptkey = RSADecryptkey($params['encryptkey'],$this->payPrivateKey);
        $result =  AESDecryptResponse($encryptkey,$params['data']);
        $vo = json_decode($result,true);
        return $vo;
    }


    public function decrypt($params,$key){

        return createSign($params,$key);
    }
}
?>
