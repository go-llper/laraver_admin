<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 下午3:26
 */

namespace App\Logics\User;

use App\Models\User\UserModel;
use App\Models\Common\ValidationModel;
use App\Lang\LangModel;
use App\Logics\Common\BaseLogic;
use App\Models\Common\SmsModel as Sms;
use App\Service\Sms\SmsService;
use Log;

class RegisterLogic extends BaseLogic
{

    /**
     * @param array $data
     * @return array
     * @desc 执行注册
     */
    public function doRegister($data = []){

        try{

            //非空验证
            ValidationModel::validationAgreement($data['agreement']);
            ValidationModel::validationFieldEmpty($data['username'], LangModel::getLang('USER_REGISTER_FIELD_PHONE'));
            ValidationModel::validationFieldEmpty($data['code'], LangModel::getLang('MODEL_USER_FIELD_PHONE_CODE'));
            ValidationModel::validationFieldEmpty($data['password'], LangModel::getLang('USER_REGISTER_FIELD_PASSWORD'));
            //格式验证
            ValidationModel::validationPhone($data['username']);
            ValidationModel::validationPassword($data['password']);
            //验证手机号是否已注册
            $userModel = new UserModel();
            $userInfo  = $userModel->getUser($data['username']);

            if(!empty($userInfo)){
                return self::callError(LangModel::getLang('ERROR_PHONE_EXIST'));
            }

            Sms::checkPhoneCode($data['code'], $data['username']);

            //执行注册
            $userModel->doRegister($data['username'],$data['password']);

        }catch(\Exception $e){
            $logData = $data;
            unset($logData['password']);
            Log::error(__METHOD__ . 'Error', $logData);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();

    }

    /**
     * @param null $phone
     * @return array
     * @desc 执行发送验证码
     */
    public function sendRegisterSms($phone = null){

        try{
            // 验证手机号 有效性
            ValidationModel::validationPhone($phone);

            $userModel = new UserModel();
            $userInfo  = $userModel->getUser($phone);

            if( !empty($userInfo) ){
                return self::callError('手机号已注册');
            }

            // 验证码生成
            $code    = SmsService::generateCode();
            $message = str_replace('#CODE#',$code,SmsService::REGISTER_TEMP);

            Log::info('手机号'.$phone.' 注册发送短信验证码' . $message);

            // 发送短信验证码
            SmsService::sendSms($phone,$message);
            Sms::setPhoneVerifyCodeCache($code, $phone);

        }catch (\Exception $e){
            $data['phone']   = $phone;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();

            Log::error(__METHOD__ . 'Error', $data);

            return self::callError($e->getMessage());
        }
        //发送成功则记录发送次数
        Sms::sendRegisterSmsTimes($phone);

        return self::callSuccess(['code'=>$code], '验证码发送成功');
    }
}
