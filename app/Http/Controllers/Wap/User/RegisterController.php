<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 上午8:40
 */

namespace App\Http\Controllers\Wap\User;

use App\Http\Controllers\Controller;
use App\Logics\Common\SessionLogic;
use App\Logics\User\LoginLogic;
use App\Logics\User\RegisterLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Session;

class RegisterController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 注册页面
     */
    public function index(){

        if(isset($_COOKIE['USER_INFO'])){
            return redirect('/app');
        }

        return view('wap.user.register');

    }


    /**
     * @param Request $request
     * @desc 用户注册
     */
    public function doRegister(Request $request){

        $data = [
            'username'  => $request->input('username',''),
            'password'  => $request->input('password',''),
            'code'      => $request->input('code',''),
            'agreement' => $request->input('agreement',''),
        ];

        $logic = new RegisterLogic();

        $result = $logic->doRegister($data);

        if($result['status']){
            $session = new SessionLogic();
            $session->handleFrom($data['username']);
        }

        return self::returnJson($result);
    }

    /**
     * @param Request $request
     * @return mixed
     * @desc 发送注册验证码
     */
    public function sendSms(Request $request){

        $phone = $request->input('phone');

        $registerLogic = new RegisterLogic();
        $result = $registerLogic->sendRegisterSms($phone);

        if($result['status']){
            Session::put("SEND_CODE_TIME", time());
        }

        return self::returnJson($result);

    }

}