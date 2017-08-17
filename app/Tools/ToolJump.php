<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 上午10:41
 */

namespace App\Tools;

use Session;

/**
 *
 *
 * Class ToolJump
 * @package App\Tools
 */
class ToolJump
{

    const
        LOGIN_URL = 'LOGIN_SUCCESSFUL_JUMP_URL',

        END       = true;


    /**
     * 设置登陆成功后跳转的url
     *
     * @param string $url
     */
    public static function setLoginUrl($url = '/user'){
        Session::put(self::LOGIN_URL, $url);
        Session::save();
    }

    /**
     * 获取登陆成功后跳转的url 并删除 该session
     */
    public static function getLoginUrl(){
        $url = Session::get(self::LOGIN_URL);
        if($url) {
            Session::forget(self::LOGIN_URL);
        }else{
            $url = '/user';
        }
        return $url;
    }



}