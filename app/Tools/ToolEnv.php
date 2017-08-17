<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/26
 * Time: 下午3:15
 */

namespace App\Tools;

/**
 * env
 *
 * Class ToolEnv
 * @package App\Tools
 */
class ToolEnv
{
    /**
     * 获取 app_env
     * @return mixed
     */
    public static function getAppEnv(){

        return env('APP_ENV', 'production');
        
    }



}