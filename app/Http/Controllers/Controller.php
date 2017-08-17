<?php

namespace App\Http\Controllers;

use App\Logics\Common\SessionLogic;
use App\Tools\ToolCrypt;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Predis\Client;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * json return
     *
     * @param array $data
     * @return string
     */
    public static function returnJson($data = []){
        if (!headers_sent()) {
            header(sprintf('%s: %s', 'Content-Type', 'application/json'));
        }
        exit(json_encode($data));
    }

    /**
     * @param bool $forceLogin
     * @return bool
     * 判断用户是否已登录
     */
    protected function checkLogin($forceLogin = false) {

        if(!$this->getUserId()){
            if($forceLogin) {

                Header("Location: /app/login");
                exit();

            }
            return false;
        }
        return true;
    }

    /**
     * 获取用户ID
     * @return int
     */
    protected function getUserId(){
        $session = SessionLogic::getUserSession();
        if(isset($session['id'])){
            return $session['id'];
        }
        return 0;
    }

    /**
     * @param string $token
     * 通过token获取用户ID
     * @return int
     */
    protected function getUserIdByToken($token){
        $in  = ToolCrypt::deCrypt($token);
        $info = explode('|',$in);
        if(!empty($info[0]) && !empty($info[1])){
            $uid = $info[0];
        }else{
            $uid = 0;
        }
        return $uid;
    }

    /**
     * 获取用户token
     * @return int
     */
    protected function getToken(){
        $session = SessionLogic::getUserSession();
        if(isset($session['token'])){
            return $session['token'];
        }
        return '';
    }

    protected function getSystem(){

        if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
            $system = "ios";
        }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
            $system = "android";
        }else{
            $system = "else";
        }

        return $system;
    }

    protected function responseJson($data=[], $code, $message = ''){

        $data = array(
            'code'    => $code,
            'message' => $message,
            'result'  => $data,
        );

        return self::returnJson($data);

    }


    /**
     * @return Client
     */
    protected function getRedisInstance(){
        $redisConfig = array(
            'host' => env('MY_REDIS_HOST', 'localhost'),
            'port' => env('MY_REDIS_PORT', 6379),
        );
        $redis = new Client($redisConfig);
        return $redis;
    }

}
