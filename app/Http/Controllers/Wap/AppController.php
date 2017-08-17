<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 上午8:49
 */

namespace App\Http\Controllers\Wap;

use App\Http\Controllers\Controller;

class AppController extends Controller
{

    public function __construct()
    {
        $this->checkLogin(true);
    }

    /**
     * 用于ios提交审核
     */
    public function getShowType(){

        $config = env('APP_ENV');

        if($config !== 'product'){
            $type = 'test';
        }else{
            $type = 'product';
        }

        return self::responseJson(['type'=>$type], 1000);

    }

}
