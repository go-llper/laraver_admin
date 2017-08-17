<?php

namespace App\Service\Sms;

class SmsService {


    const SMS_SEND_URL   = 'http://sms-api.luosimao.com/v1/send.json';

    const SMS_API_KEY    = 'api:key-14b4c92ee345f28284a1cf5a0bf77026';

    const LOGIN_TEMP     = '您的验证码为#CODE#,请在页面中提交验证码完成验证.【运单极速贷】';

    const REGISTER_TEMP  = '您的注册验证码为#CODE#,请在页面中提交验证码完成验证.【运单极速贷】';

    const BIND_CARD_TEMP = '验证码为#CODE#,请在页面中输入验证码.【运单极速贷】';


    /**
     * @return string
     */
    public static function generateCode(){
        $string = ['0','1','2','3','4','5','6','7','8','9'];
        $count  = count($string) - 1;
        $code   = '';
        for ($i = 0; $i < 4; $i++) {
            $code .= $string[rand(0, $count)];
        }
        return $code;
    }

    /**
     * @param $phone
     * @param $message
     * @return mixed
     */
    public static function sendSms($phone,$message){
        $content = array(
                'mobile'  => $phone,
                'message' => $message
            );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::SMS_SEND_URL);
        curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD  , self::SMS_API_KEY);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

        $res = curl_exec( $ch );
        curl_close( $ch );
        return $res;
    }


}
?>
