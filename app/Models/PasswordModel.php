<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 下午1:39
 */

namespace App\Models;

class PasswordModel
{

    /**
     * 密码生成
     * @param $password
     * @param int $saltLength
     * @return string
     */
    public static function encryptionPassword($password, $saltLength=32) {

        $saltLength = empty($saltLength) ? 32 : min($saltLength, 32);

        $salt       = substr(self::getToken32(), rand(0,5), $saltLength);

        $password   = md5($password . $salt);

        return sprintf('%s:%s', $password, $salt);
    }

    /**
     * 随机码
     * @return string
     */
    protected static function getToken32() {
        return md5(md5(rand(111111111, 999999999)) . time());
    }
}