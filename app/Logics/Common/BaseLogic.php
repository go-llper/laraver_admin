<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/10/27
 * Time: 下午2:33
 */

namespace App\Logics\Common;

use Illuminate\Support\Facades\DB;

class BaseLogic{


    /**
     * @todo 不同的业务场景，可以定义不同code
     */
    const  CODE_SUCCESS        = 200; //成功

    const  CODE_ERROR          = 500;  //失败

    const  CODE_NO_USER_ID     = 4006;  //用户未登录


    // 定义不同阶梯利率
    const  ONE_TO_THIRTY_DAYS_RATE   = 0.0005;

    const  THIRTY_TO_SIXTY_DAYS_RATE = 0.0007;

    const  SIXTY_DAYS_RATE           = 0.003;

    // 定义不同阶梯违约金
    const ONE_TO_THIRTY_DAYS_LATE    = 0.02;

    const THIRTY_DAYS_LATE           = 0.03;

    // 定义不同的天数范围
    const ONE_MONTH_DAYS    = 30;

    const TWO_MONTH_DAYS    = 60;

    const THREE_MONTH_DAYS  = 90;

    #  定义借款之后多久可以还款

    const NEED_REPAY_DAYS   = 30;

    // 定义资金变更类型
    const MONEY_CHANGE_TYPE_CAPITAL  = 1;

    const MONEY_CHANGE_TYPE_INTEREST = 2;

    const MONEY_CHANGE_TYPE_LATER    = 3;





    public static function beginTransaction(){
        DB::beginTransaction();
    }

    public static function rollback(){
        DB::rollback();
    }

    public static function commit(){
        DB::commit();
    }

    /**
     * @param array $data
     * @param string $msg
     * @return array
     * @desc 统一返回成功数据
     */
    public static function callSuccess($data = [], $msg = '成功')
    {

        return [
            'status'    => true,
            'code'      => self::CODE_SUCCESS,
            'msg'       => $msg,
            'data'      => empty($data) ? '' : $data
        ];

    }

    /**
     * @param string $msg
     * @param int $code
     * @param array $data
     * @return array
     * @desc 统一返回失败数据
     */
    public static function callError($msg = '', $code = self::CODE_ERROR, $data = [])
    {

        return [
            'status'    => false,
            'code'      => $code,
            'msg'       => $msg,
            'data'      => empty($data) ? '' : $data
        ];

    }

    /**
     * @param int $page
     * @param int $size
     * @return mixed
     * @desc 生成offset值
     */
    public static function getOffset($page=1, $size=10){

        return max( ($page - 1), 1 ) * $size;

    }

    /**
     * 将银行卡号变成 6222 **** ****** *** 8888 格式
     * @param $cardNo
     * @return string
     */
    public static function parseBankCardNo($cardNo){
        $length = strlen($cardNo);
        if($length <= 8){
            return $cardNo;
        }else{
            $newCardNo  = substr($cardNo,0,4);
            //$newCardNo .= str_repeat('*',$length-8);
            $newCardNo .= '********';
            $newCardNo .= substr($cardNo,-4);
            return $newCardNo;
        }
    }

    /**
     * @param $time1
     * @param $time2
     * @return float
     */
    public static function twoTimestampDays($time1,$time2){
        $timeMax = max($time1,$time2);
        $timeMin = min($time1,$time2);
        $dateMax = date('Y-m-d',$timeMax);
        $dateMin = date('Y-m-d',$timeMin);
        $timeMax = strtotime($dateMax);
        $timeMin = strtotime($dateMin);
        return ($timeMax-$timeMin)/(24*60*60);
    }


    /**
     *
     * @param int $length
     * @return string
     *
     */
    public static function genKey($length=16) {
        if($length>32){
            $length = 32;
        }
        $sessid = '';
        while ( strlen ( $sessid ) < $length ) {
            $sessid .= mt_rand ( 0, mt_getrandmax () );
        }
        $key = md5 ( uniqid ( $sessid, TRUE ) );
        return $key;
    }

    /**
     * @param int $uid
     * @return string
     */
    public static function genOrderNum($uid=0){
        $prefix  = 'WL-';
        if(!empty($uid)){
            $prefix.= $uid.'-';
        }
        $prefix .= date('Ymdhis');
        $str     = '';
        while ( strlen ( $str ) < 6 ) {
            $str .= mt_rand ( 0, mt_getrandmax () );
        }
        return $prefix.$str;
    }


    /**
     * @param $capital
     * @param int $days
     * @return array
     */
    public static function generateDayInterest($capital,$days=1){
        $interest = $later = 0;
        if($days<=30){
            $interest = $capital * self::ONE_TO_THIRTY_DAYS_RATE;
        }elseif($days>=31 && $days<=60){
            $interest = $capital * self::THIRTY_TO_SIXTY_DAYS_RATE;
        }elseif($days==61){
            $interest = $capital * self::SIXTY_DAYS_RATE;
            $later    = $capital * self::ONE_TO_THIRTY_DAYS_LATE;
        }elseif($days>=62 && $days<=90){
            $interest = $capital * self::SIXTY_DAYS_RATE;
        }elseif($days==91){
            $interest = $capital * self::SIXTY_DAYS_RATE;
            $later    = $capital * self::THIRTY_DAYS_LATE;
        }elseif($days>=92){
            $interest = $capital * self::SIXTY_DAYS_RATE;
        }
        return array('interest'=>round($interest,2),'later'=>round($later,2));
    }


    /**
     * @param $capital
     * @param int $days
     * @return array
     */
    public static function getSumInterest($capital,$days=1){
        $interest = $later = 0;
        if($days<=30){
            $interest = $capital * self::ONE_TO_THIRTY_DAYS_RATE * $days;
        }elseif($days>=31 && $days<=60){
            $interest = ($capital * self::ONE_TO_THIRTY_DAYS_RATE * 30) + $capital * self::THIRTY_TO_SIXTY_DAYS_RATE*($days-30);
        }elseif($days>=61 && $days<=90){
            $interest = ($capital * self::ONE_TO_THIRTY_DAYS_RATE * 30) + ($capital * self::THIRTY_TO_SIXTY_DAYS_RATE*30);
            $interest+=  $capital * self::SIXTY_DAYS_RATE * ($days-60);
            $later    =  $capital * self::ONE_TO_THIRTY_DAYS_LATE;
        }elseif($days>=91){
            $interest = ($capital * self::ONE_TO_THIRTY_DAYS_RATE * 30) + ($capital * self::THIRTY_TO_SIXTY_DAYS_RATE*30);
            $interest+=  $capital * self::SIXTY_DAYS_RATE * ($days-60);
            $later    =  $capital * self::ONE_TO_THIRTY_DAYS_LATE;
            $later   +=  $capital * self::THIRTY_DAYS_LATE;
        }
        return array('interest'=>round($interest,2),'later'=>round($later,2));




    }





}