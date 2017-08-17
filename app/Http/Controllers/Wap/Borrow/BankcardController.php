<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/18
 * Time: 下午1:43
 */

namespace App\Http\Controllers\Wap\Borrow;

use App\Http\Controllers\Wap\AppController;
use App\Logics\Borrow\BorrowLogic;
use App\Models\Borrow\BankcardModel;
use App\Models\Credit\CreditModel;
use App\Service\Rea\Reapay;
use App\Service\Sms\SmsService;
use App\Tools\ToolCrypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class BankcardController extends AppController{

    const SMS_BIND_CARD_KEY_PREFIX  = 'SMS_BIND_CARD_';

    /**
     * @param $uid
     * @return mixed
     */
    public function bankCard(){
        $check = $this->checkUserInfo();
        if($check===false){
            return view('wap.common.jump',['jump_url'=>'/app/credit/step/1','jump_title'=>'失败','jump_text'=>'请先填写基本资料!']);
        }
        $uid = $this->getUserId();
        $bankModel = new BankcardModel();
        $ret = $bankModel->getUserBindInfo($uid);
        $data['bind_card'] = empty($ret)?0:1;
        $data['bank_list'] = BorrowLogic::SUPPORT_BANK_LIST;
        $data['sms_token'] = ToolCrypt::enCrypt(BorrowLogic::SMS_SIGN_PREFIX.'|'.time());
        $data['system']    = $this->getSystem();
        return view('wap.borrow.bankcard',$data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getVerifyCode(Request $request){
        $check = $this->checkUserInfo();
        if($check===false){
            return view('wap.common.jump',['jump_url'=>'/app/credit/step/1','jump_title'=>'失败','jump_text'=>'请先填写基本资料!']);
        }
        $parameters = $request->all();
        $token = $parameters['token'];
        if(!empty($token)) {
            $token = ToolCrypt::deCrypt($token);
            $token = explode('|',$token);
            array_pop($token);
            $token = implode('|',$token);
            if($token==BorrowLogic::SMS_SIGN_PREFIX){
                $code = SmsService::generateCode();
                $message = str_replace('#CODE#',$code,SmsService::BIND_CARD_TEMP);
                $ret = SmsService::sendSms($check['phone'],$message);
                $ret = json_decode($ret,true);
                if(!empty($ret) && $ret['error']==0){
                    $redis  = $this->getRedisInstance();
                    $smsKey = self::SMS_BIND_CARD_KEY_PREFIX.$check['phone'];
                    $redis->setex($smsKey,15*60,$code);
                    echo json_encode(array('code'=>1,'msg'=>'短信发送成功!'));
                    return;
                }
            }
        }
        echo json_encode(array('code'=>0,'msg'=>'短信发送失败!'));
    }




    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     * @throws \Exception
     */
    public function doBindBankCard(Request $request){
        $check = $this->checkUserInfo();
        if($check===false){
            return view('wap.common.jump',['jump_url'=>'/app/credit/step/1','jump_title'=>'失败','jump_text'=>'请先填写基本资料!']);
        }
        $parameters = $request->all();
        $verify = false;
        if(!empty($parameters['verifyCode'])){
            $verifyCode = $parameters['verifyCode'];
            $redis   = $this->getRedisInstance();
            $smsKey  = self::SMS_BIND_CARD_KEY_PREFIX.$check['phone'];
            $codeVal = $redis->get($smsKey);
            if($verifyCode==$codeVal){
                $verify = true;
                $redis->del($smsKey);
            }
        }
        if($verify==false){
            echo json_encode(['code'=>2,'msg'=>'短信验证码不正确']);
            return;
        }
        $uid = $this->getUserId();
        $allowBanks = array_keys(BorrowLogic::SUPPORT_BANK_LIST);
        if(in_array($parameters['bankName'],$allowBanks)){
            if(preg_match('/^[1-9]\d+$/',$parameters['bankNo'])){
                //银行卡四要素检验
                $reapayConfig = Config::get('reapay.REAPAY_CONFIG');
                $reaService   = new Reapay($reapayConfig);
                $result = $reaService->checkDepositCard($check['phone'],$check['real_name'],$check['id_card'],$parameters['bankNo']);
                $ret    =  $this->checkCardFormat($result);
                if($ret['status']=='succ'){
                    $bankModel = new BankcardModel();
                    $ret = $bankModel->addUserBankcard($parameters,$uid);
                    if($ret){
                        echo json_encode(['code'=>1,'msg'=>'添加银行卡成功']);
                    }else{
                        echo json_encode(['code'=>0,'msg'=>'添加银行卡成功']);
                    }
                }else{
                    echo json_encode(['code'=>0,'msg'=>$ret['msg']]);
                }
                return;
            }
        }
        $data = ['code'=>0,'msg'=>'银行名称或银行卡号参数不正确'];
        echo json_encode($data);
    }


    /**
     * @return array|bool
     */
    private function checkUserInfo(){
        $uid = $this->getUserId();
        $creditModel = new CreditModel();
        $userInfo =  $creditModel->getCreditUserBaseInfo($uid);
        if(empty($userInfo)){
            return false;
        }
        if(empty($userInfo['phone']) || empty($userInfo['real_name']) || empty($userInfo['id_card'])){
            return false;
        }
        return $userInfo;
    }


    /**
     * @param $result
     * @return mixed
     */
    private function checkCardFormat($result){
        if($result['result_code'] == '0000'){
            $return['status'] = 'succ';
        }else{
            $return['status'] = 'fail';
        }
        $return['msg'] = $result['result_msg'];
        $return['result_code'] = $result['result_code'];
        $return['bank_card_type'] = isset($result['bank_card_type']) ? $result['bank_card_type'] : '';
        return $return;
    }



}