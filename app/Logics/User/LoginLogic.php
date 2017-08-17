<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 上午10:29
 */

namespace App\Logics\User;

use App\Logics\Common\BaseLogic;
use App\Models\User\UserModel;
use App\Models\Common\ValidationModel;
use Session;
use Log;
use Cache;

class LoginLogic extends BaseLogic
{

    public function doLogin($username, $password){

        if($username == '' || $password == ''){
            return self::callError('用户名或密码不能为空');
        }

        try{
            //验证账号
            ValidationModel::validationPhone($username);
            //验证密码
            ValidationModel::validationPassword($password);
            //获取用户信息
            $userModel = new UserModel();
            $userInfo = $userModel->getUser($username);
            //判断密码是否正确
            $pwd = md5(md5($password));
            if(empty($userInfo['password']) || $userInfo['password'] !== $pwd){
                \Log::error(__METHOD__,$userInfo);
                return self::callError('用户名与密码错误,请重新输入');
            }

        }catch(\Exception $e){
            \Log::error(__METHOD__,[$e->getMessage()]);
            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

}