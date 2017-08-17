<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 上午11:03
 */

namespace App\Models\Common;

use App\Lang\LangModel;

class ValidationModel
{

    const
        PASSWORD_MIN_LIMIT = 6,  // 密码最小长度

        PASSWORD_MAX_LIMIT = 16; // 密码最大长度

    public static function validationPhone($phone = null){

        $pattern = '/^(13\d|14[57]|15[012356789]|18\d|17[13678])\d{8}$/';
        if(!preg_match($pattern, $phone)) {

            $match  = '/^(170)\d{8}$/';
            if(preg_match($match, $phone)) {
                throw new \Exception(LangModel::getLang('NOT_SUPPORT_PHONE'));
            }

            throw new \Exception(LangModel::getLang('ERROR_USER_PHONE'));
        }

        return true;

    }

    /**
     * 验证密码：6-16位数字、字母和特殊字符组合（不全是字母、数字）
     * @param $password
     * @param int $min
     * @param int $max
     * @return array
     */
    public static function checkPasswordRule($password , $min=6 ,$max=16){
        $return = array(
            'status' => false,
            'msg'    => '必须'.$min.'-'.$max.'位密码',
        );

        $len = strlen($password);
        if($len >= $min && $len <= $max){
            $str = '/^[A-Za-z]+$/';
            $num = '/^\d+$/';
            $return['status'] = true;
            preg_match($str,$password) && $return = ['status' => false , 'msg'=>'密码不能都是字母'];
            preg_match($num,$password) && $return = ['status' => false , 'msg'=>'密码不能都是数字'];
        }
        return $return;
    }

    /**
     * 密码有效性验证
     * @param null $password
     * @return bool
     * @throws \Exception
     */
    public static function validationPassword($password =null){
        $passwordLength = strlen($password);
        //小于最小长度
        if($passwordLength < self::PASSWORD_MIN_LIMIT) {
            $message = LangModel::getLang('MODEL_USER_PASSWORD_TOO_SHORT');
            $message = sprintf($message, self::PASSWORD_MIN_LIMIT);
            \Log::error('validationPasswordError', [$password]);
            throw new \Exception($message);
        }

        //大于最大长度
        if($passwordLength > self::PASSWORD_MAX_LIMIT) {
            $message = LangModel::getLang('MODEL_USER_PASSWORD_TOO_LONG');
            $message = sprintf($message, self::PASSWORD_MAX_LIMIT);
            \Log::error('validationPasswordError', [$password]);
            throw new \Exception($message);
        }

        //不能是纯数字或者纯字母
        $check = self::checkPasswordRule($password , self::PASSWORD_MIN_LIMIT , self::PASSWORD_MAX_LIMIT);
        if(!$check['status']){
            $message = LangModel::getLang('MODEL_USER_PASSWORD_FORMAT_INVALID');
            \Log::error('validationPasswordError', [$password]);
            throw new \Exception($message);
        }
        return true;
    }

    /**
     * @param $oldPassword
     * @param $newPassword
     * @param $flag
     * @return bool
     * @throws \Exception
     * @desc 两密码比较验证，flag==fase验证不相等 flag==true验证相等
     */
    public static function validatePasswordIsSame($oldPassword,$newPassword,$flag){

        //验证不相等
        if($newPassword==$oldPassword && $flag==false){

            throw new \Exception(LangModel::getLang('MODEL_USER_PASSWORD_CONFIRM_CANT_SAME'));

        }
        //验证相等
        if($newPassword!=$oldPassword && $flag==true){

            throw new \Exception(LangModel::getLang('MODEL_USER_PASSWORD_CONFIRM_NOT_MATCH'));

        }

        return true;

    }

    /**
     * 注册协议同意与否
     * @param null $agreement
     * @return bool
     * @throws \Exception
     */
    public static function validationAgreement($agreement = null){
        if(empty($agreement)) {
            throw new \Exception(LangModel::getLang('USER_REGISTER_AGREEMENT'));
        }
        return true;
    }

    /**非空验证
     * @param null $field
     * @param $fieldName
     * @return bool
     * @throws \Exception
     */
    public static function validationFieldEmpty($field =null, $fieldName){
        if(empty(trim($field))){
            throw new \Exception($fieldName . LangModel::getLang('ERROR_EMPTY'));
        }
        return true;
    }

    /**
     * @param string $cardNo
     * @throws \Exception
     * 判断银行卡号是否合法
     */
    public static function isBankCard($cardNo = ''){

        $len = strlen($cardNo);

        if(!($len == 16 || $len == 17 || $len == 19 || preg_match('#^\d{18}$#', $cardNo))){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_BANK_CARD'));
        }
    }

    /**
     * 姓名格式校验
     * @param $name
     * @throws \Exception
     */
    public static function isName($name) {

        if(preg_match('#[a-z\d~!@\#$%^&*()_+{}|\[\]\-=:<>?/"\'\\\\]#', $name)) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_NAME'));
        }
    }

    /**
     * @param $idCard
     * @return bool
     * @throws \Exception
     * 身份证格式判断
     */
    public static function isIdCard($idCard)
    {

        if(!preg_match('/^(\d{15}|\d{17}X|\d{18})$/i', $idCard)) {
            $res = false;
        } else if(strlen($idCard) == 18) {
            $res     = self::idcard_checksum18($idCard);
        } else if((strlen($idCard) == 15)) {
            $idCard = self::idcard_15to18($idCard);
            $res     = self::idcard_checksum18($idCard);
        } else {
            $res     = false;
        }

        if(empty($res)) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_ID_CARD'));
        }else{
            if(!self::checkAgeByIDCard($idCard)){

                throw new \Exception(LangModel::getLang('ERROR_AGE_IS_LESS_EIGHTEEN'));
            }
        }

        return true;
    }

    // 将15位身份证升级到18位
    private static function idcard_15to18($idcard){
        if (strlen($idcard) != 15){
            return false;
        } else {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
                $idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
            } else {
                $idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
            }
        }
        $idcard = $idcard . self::idcard_verify_number($idcard);
        return $idcard;
    }

    // 18位身份证校验码有效性检查
    private static function idcard_checksum18($idcard){
        if (strlen($idcard) != 18){
            return false;
        }
        $idcard_base = substr($idcard, 0, 17);
        if (self::idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
            return false;
        }else{
            return true;
        }
    }

    // 计算身份证校验码，根据国家标准GB 11643-1999
    private static function idcard_verify_number($idcard_base)
    {
        if(strlen($idcard_base) != 17) {
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++) {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }

    /**
     * @param $IDCard
     * @return bool
     * 判断用户是否已年满十八岁
     */
    private static function checkAgeByIDCard($IDCard){

        if(strlen($IDCard)==18){

            $tyear = (int)substr($IDCard,6,4);

            $tmonth = (int)substr($IDCard,10,2);

            $tday = (int)substr($IDCard,12,2);

        }elseif(strlen($IDCard)==15){

            $tyear = (int)("19".substr($IDCard,6,2));

            $tmonth = (int)substr($IDCard,8,2);

            $tday = (int)substr($IDCard,10,2);

        }

        $birthday = strtotime($tyear.'-'.$tmonth.'-'.$tday.' + 18 years');

        $today = time();

        if($today > $birthday){

            return true;

        }else{

            return false;

        }
    }
}