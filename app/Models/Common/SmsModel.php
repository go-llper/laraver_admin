<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/18
 * Time: 下午2:01
 */

namespace App\Models\Common;

use App\Lang\LangModel;
use App\Tools\ToolEnv;

use Session;
use Cache;
use Config;
use Log;

/**
 * 内部方法 @晓蓉 从controller copy 过来
 * Class Sms
 * @package App\Http\Models\Common
 */
class SmsModel extends BaseModel
{

    const
        REGISTER_SMS_MAX_TIMES_KEY_PRE     = 'ydd_send_register_sms_times_pre_', //注册发送验证码次数缓存key前缀
        SEND_SMS_MAX_TIMES                 = 10,//发送注册认证码次数限制

        END = true;

    public static $codeArr = [

        'validationFieldEmpty'           => 1,
        'validationPhone'                => 2,
        'validationAgreement'            => 3,
        'validationPasswordEqual'        => 4,
        'validationPassword'             => 5,
        'requestFrom'                    => 6,
        'validationCoreData'             => 7,
        'doRegister'                     => 8,
        'doCoreApiRegister'              => 9,
        'sendRegisterSms'                => 10,
        'validationPhoneCode'            => 11,
        'sendRegisterSmsServerError'     => 12,
        'doCoreApiRegisterEmpty'         => 13,
        'validationInvitePhone'          => 14,
        'checkRegisterSmsTimes'          => 15,

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_USER_REGIESTER;

    /**
     * 获取验证码
     * @param string $base    验证码组成字符
     * @param int $length  默认长度
     * @return string
     */
    public static function getRandCode($base = '0123456789', $length = 6) {
        $shuffleBase    = str_shuffle($base);
        $code           = '';
        $min            = 0;
        $max            = $length - 1;
        for($i = 0; $i < $length; $i++) {
            $key    = rand($min, $max);
            $code  .= $shuffleBase[$key];
        }
        return $code;
    }

    /**
     * 设置短信验证码session
     * @param $code
     * @param $phone
     * @return bool
     */
    public static function setPhoneVerifyCodeCache($code, $phone){
        Cache::put('PHONE_VERIFY_CODE_'. $phone, $code, 30);
        Cache::put('PHONE_VERIFY_NUMBER_' . $phone, $phone, 30);

        Log::info('setPhoneVerifyCode'. $code . '#' . $phone);

        return true;
    }

    /**
     *
     * @param string $phone
     * @desc  发送成功记录发送次数
     */
    public static function sendRegisterSmsTimes($phone = null){
        try {
            $key     = self::REGISTER_SMS_MAX_TIMES_KEY_PRE . $phone;
            $times   = Cache::get($key);
            $times   = (int)$times;
            Log::info('checkRegisterSmsTimes : ', [$times, $phone]);
            $minutes = 60*24;//一天有效期
            if (empty($times)) {
                Cache::put($key, 1, $minutes);
            }else {
                Cache::put($key, ($times + 1), $minutes);
            }
        }catch (\Exception $e){
            $returnArray['code']    = $e->getCode();
            $returnArray['msg']     = $e->getMessage();
            //记录发送注册验证码次数挂了 那么 就不记录 允许继续执行
            Log::error(__METHOD__ . ' Error', $returnArray);
        }
    }

    /**
     * 检测发送验证码发送次数限制
     *
     * @param null $phone
     * @throws \Exception
     */
    public static function checkRegisterSmsTimes($phone = null){

        $times = self::getSendRegisterSmsTimes($phone);
        Log::info('checkRegisterSmsTimes : ', [$phone, $times]);
        if ($times >= self::SEND_SMS_MAX_TIMES) {
            $msg = '发送注册验证码达到最大次数限制';
            throw new \Exception($msg, self::getFinalCode('checkRegisterSmsTimes'));
        }

    }

    /**
     * 获取发送注册短信限制
     *
     * @param $phone
     */
    public static function getSendRegisterSmsTimes($phone = null){
        $key   = self::REGISTER_SMS_MAX_TIMES_KEY_PRE . $phone;
        return Cache::get($key);
    }

    /**
     * @param           $code
     * @param           $phone
     * @param bool|true $isClean
     * @return bool
     * @throws \Exception
     * @desc  手机验证码正确性检测 （正确 && 不超时）
     */
    public static function checkPhoneCode($code, $phone, $isClean = true){

        Log::info('checkPhoneCode1:'. $code . '#' . $phone);

        $sessionPhone =Cache::get('PHONE_VERIFY_NUMBER_' . $phone);

        $sessionCode = Cache::get('PHONE_VERIFY_CODE_' .$phone);

        Log::info('checkPhoneCode2:'. $sessionCode . '#' . $sessionPhone);

        if(empty($code) || empty($phone) || ($code !== $sessionCode) || ($phone !== $sessionPhone)) {
            throw new \Exception('手机验证码错误', self::getFinalCode('checkRegisterSmsTimes'));
        }else{
            if($isClean == true){
                \Cache::forget('PHONE_VERIFY_CODE' . $phone);
                \Cache::forget('PHONE_VERIFY_NUMBER'. $phone);
            }
        }

        return true;

    }




    /**
     * 获取跳秒剩余时间
     * @return number
     */
    public static function getSendCodeLeftTime() {
        $sendTime       = self::getSendCodeTime();
        if(empty($sendTime)) return 0;
        $leftTime       = Config::get('phone.TIMEOUT') - (time() - $sendTime);
        return $leftTime;
    }

    /**
     * 获取上一次发送短信时间
     * @return number
     */
    public static function getSendCodeTime() {
        $sendTime = Session::get("SEND_CODE_TIME");
        return $sendTime;
    }
}