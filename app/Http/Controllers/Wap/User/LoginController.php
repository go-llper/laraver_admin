<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 上午9:29
 */

namespace App\Http\Controllers\Wap\User;

use App\Http\Controllers\Controller;
use App\Logics\Common\SessionLogic;
use App\Logics\User\LoginLogic;
use Illuminate\Http\Request;
use Cache;
use Session;

class LoginController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 登录页面
     */
    public function index(){

        if(isset($_COOKIE['USER_INFO'])){
            return redirect('/app');
        }
        return view('wap.user.login');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @desc 执行登录
     */
    public function doLogin(Request $request){

        $username = $request->input('username','');
        $password = $request->input('password','');

        $logic = new LoginLogic();

        $loginData = $logic->doLogin($username, $password);

        if($loginData['status']){
            //登录成功
            $session = new SessionLogic();
            $session->handleFrom($username);
            return self::returnJson(LoginLogic::callSuccess());
        }else{
            //登录失败
            return self::returnJson(LoginLogic::callError());
        }

    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @desc 退出
     */
    public function loginOut(){

        Cache::forget($_COOKIE['USER_INFO']);

        setcookie('USER_INFO', '', time() - 1);

        return self::returnJson(LoginLogic::callSuccess());
    }

}