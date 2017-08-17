<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/20
 * Time: 上午10:04
 */

namespace App\Logics\Common;

use App\Models\User\UserModel;

use App\Tools\ToolCrypt;
use Session;
use Cache;

class SessionLogic extends BaseLogic
{

    /**
     * @param string $username
     * @desc  登录成功后记录session
     */
    public static function handleFrom($username = ''){

        $userModel = new UserModel();
        $userInfo = $userModel->getUser($username);

        $in =  $userInfo['id'].'|'.$userInfo['phone'].'|'.rand('10000','99999');

        $token = ToolCrypt::enCrypt($in);

        //用户信息写入缓存
        Cache::put($token,$userInfo,60*60*24*7);

        setcookie('USER_INFO', $token, time() + 60*60*24*7);

    }

    public static function getUserSession(){

        $token = isset($_COOKIE['USER_INFO']) ? $_COOKIE['USER_INFO'] : '';

        $userInfo = [];
        if($token != ''){
            $in  = ToolCrypt::deCrypt($token);

            $info = explode('|',$in);

            if(!empty($info[0]) && !empty($info[1])){
                $userInfo = [
                    'id'    => $info[0],
                    'phone' => $info[1],
                    'token' => $token
                ];
            }
        }

        return $userInfo;

    }
}