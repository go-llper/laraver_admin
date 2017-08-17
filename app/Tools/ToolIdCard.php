<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/1/10
 * Time: 下午1:34
 * Desc: 通过身份证号获取性别/尊称
 */

namespace App\Tools;

class ToolIdCard{

    /**
     * @param string $identityCard
     * @return string
     * @desc 通过身份证获取性别
     */
    public static function getSexByIdCard( $identityCard='' ){

        return (substr($identityCard, (strlen($identityCard) == 15 ? -1 : -2), 1) % 2) ? '男' : '女';

    }

    /**
     * @param string $identityCard
     * @return string
     * @desc 通过身份证获取性别尊称
     */
    public static function getSexNameByIdCard( $identityCard='' ){

        return (substr($identityCard, (strlen($identityCard) == 15 ? -1 : -2), 1) % 2) ? '先生' : '女士';

    }

    /**
     * @desc    获取年龄
     **/
    public static function getAgeByIdCard($identityCard = ''){
        if(empty($identityCard)) return '';
        //获得出生年月日的时间戳
        if(strlen($identityCard)==18){
            $date   = strtotime(substr($identityCard, 6, 8));
        }elseif(strlen($identityCard)==15){
            $tyear  = intval("19".substr($identityCard,6,2));
            $tmonth = intval(substr($identityCard,8,2));
            $tday   = intval(substr($identityCard,10,2));
            $tdate  = $tyear."-".$tmonth."-".$tday;
            $date   = strtotime($tdate);
        }else{
            return '';
        }
        //获得今日的时间戳
        $today  = strtotime('today');
        //得到两个日期相差的大体年数
        $diff   = floor(($today - $date) / 86400 / 365);
        //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
        $age = strtotime(substr($identityCard, 6, 8) . ' +' . $diff . 'years') > $today ? ($diff + 1) : $diff;
        return $age;
    }

}

