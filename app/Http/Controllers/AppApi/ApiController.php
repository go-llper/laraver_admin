<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/21
 * Time: 下午3:53
 */

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    const
        CODE_SUCCESS                  = "1000",  // 成功

        CODE_PHONEBOOK_ERROR          = "2001",  // 获取通讯录授权失败
        CODE_HISTORY_ERROR            = "2002",  // 获取通话记录授权失败
        CODE_SMS_ERROR                = "2003",  // 获取短信息授权失败

        CODE_NOT_FILES                = "5064",  // 缺少上传文件
        CODE_FAIL_FILES               = "5065",  // 上传图像文件失败
        CODE_FAIL_SAVE_FILES          = "5066",  // 保存图像文件失败

        CODE_NO_USER_ID               = "4006",  // 用户未登录
        CODE_ERROR                    = "2000",  // 非法请求
        END                           = true;


}