<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/13
 * Time: 下午1:38
 * Desc: 错误码
 */

namespace App\Models\Common;

class ExceptionCodeModel
{

    /**
     * 错误码切分：2_4_3
     */

    const
        //BASE 100
        EXP_MODEL_BASE                                      = 101001000,

        //用户注册
        EXP_MODEL_USER_REGIESTER                            = 101012000,

        //最后一个，新增请在这个上面添加
        EXP_LAST_ITEM                                       = 100000000;



}
