<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 上午11:11
 */

namespace App\Lang;

class LangModel
{
    const
        ERROR_USER_PHONE                                      = '请输入有效的手机号码',
        NOT_SUPPORT_PHONE                                     = '暂不支持170手机号段',
        ERROR_PHONE_EXIST                                     = '手机号已存在',
        ERROR_USER_EXIST                                      = '该用户不存在',

        ERROR_USER_PASSWORD                                   = '密码必须为6-16位字母数字组合',
        MODEL_USER_PASSWORD_CONFIRM_NOT_MATCH                 = '两次密码不匹配',
        MODEL_USER_PASSWORD_TOO_SHORT                         = '密码过短，需要%s位以上',
        MODEL_USER_PASSWORD_TOO_LONG                          = '密码过长，最多%s位',
        MODEL_USER_PASSWORD_FORMAT_INVALID                    = '密码不能是纯数字或者纯字母',
        MODEL_USER_PASSWORD_CONFIRM_CANT_SAME                 = '新密码不能和旧密码一致',

        USER_REGISTER_FIELD_PHONE                             = '手机号',
        USER_REGISTER_FIELD_PASSWORD                          = '密码',
        MODEL_USER_FIELD_PHONE_CODE                           = '验证码',
        USER_REGISTER_FIELD_AGREEMENT                         = '注册协议',
        USER_REGISTER_AGREEMENT                               = '请勾选注册协议',
        ERROR_EMPTY                                           = '不能为空',

        PHONE_VERIFY_CODE_REGISTER                            = '注册校验码：%s，30分钟内有效 【运单贷】',
        USER_REGISTER_SEND_REGISTER_SMS_ERROR                 = '发送短信验证码失败',

        ERROR_SUBMIT_CREDIT_INFO                              = '审核资料提交失败',
        ERROR_SUBMIT_CREDIT_CONTENT                           = '授权失败',

        ERROR_INVALID_BANK_CARD                               = '无效的银行卡号',
        ERROR_INVALID_ID_CARD                                 = '无效的身份证号',
        ERROR_AGE_IS_LESS_EIGHTEEN                            = '未满十八岁，不能进行审核',
        ERROR_INVALID_NAME                                    = '无效的姓名',

        END              = TRUE;

    /**
     * @param $name
     * @return string
     */
    public static function getLang($name)
    {

        $className = __CLASS__;

        $lang = defined("$className::$name") ? constant("$className::$name") : $name;

        return $lang;

    }
}