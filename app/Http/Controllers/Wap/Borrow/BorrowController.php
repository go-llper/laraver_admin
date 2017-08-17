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
use App\Logics\Common\BaseLogic;
use App\Models\Borrow\BorrowModel;
use App\Models\Borrow\BankcardModel;
use App\Models\Borrow\RepaymentModel;
use App\Models\Credit\CreditModel;
use App\Models\User\UserModel;
use App\Service\Sms\SmsService;
use Illuminate\Http\Request;
use Predis\Client;

class BorrowController extends AppController
{



    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apply(){
        $ret = $this->checkApplyInfo();
        if($ret['creditTotal']==0){
            return view('wap.common.jump',['jump_url'=>'/app','jump_title'=>'提示','jump_text'=>'您暂时没有额度,请先申请借款额度!']);
        }
        if($ret['borrowCount']>0){
            return view('wap.common.jump',['jump_url'=>'/app#tab2','jump_title'=>'提示','jump_text'=>'有一笔借款正在进行中,请完成后再试!']);
        }
        $uid = $this->getUserId();

        $bankModel = new BankcardModel();
        $ret = $bankModel->getUserBindInfo($uid);
        $data['bind_card'] = empty($ret)?0:1;
        $data['system'] = $this->getSystem();
        return view('wap.borrow.apply',$data);
    }


    /***
     * @return mixed
     */
    private function checkApplyInfo(){
        $uid = $this->getUserId();

        $creditTotal = $borrowCount = 0;
        $borrowModel = new BorrowModel();
        $borrowCount = $borrowModel->getUserBorrowingAccount($uid);

        $creditModel = new CreditModel();
        $userInfo = $creditModel->getCreditUserBaseInfo($uid);
        if($userInfo['credit_status']==CreditModel::CREDIT_PASS){
            if($userInfo['credit_total']>0){
                $creditTotal = $userInfo['credit_total'];
            }
        }
        return array('borrowCount'=>$borrowCount,'creditTotal'=>$creditTotal);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function doApply(Request $request){
        # step1 check if have other borrowing
        $ret = $this->checkApplyInfo();
        if($ret['creditTotal']==0){
            return view('wap.common.jump',['jump_url'=>'/app','jump_title'=>'提示','jump_text'=>'您暂时没有额度,请先申请借款额度!']);
        }
        if($ret['borrowCount']>0){
            return view('wap.common.jump',['jump_url'=>'/app#tab2','jump_title'=>'提示','jump_text'=>'有一笔借款正在进行中,请完成后再试!']);
        }
        # step2 check if bind the bank card
        $uid = $this->getUserId();
        $parameters = $request->all();
        $bankcardModel = new BankcardModel();
        $bankcard = $bankcardModel->getUserBindInfo($uid);
        if(empty($bankcard)){
            return view('wap.common.jump',['jump_url'=>'/app/bind/bankcard','jump_title'=>'失败','jump_text'=>'请先绑定银行卡!']);
        }else{
            $parameters['order_no']         = BaseLogic::genOrderNum();
            $parameters['borrow_card_no']   = $bankcard['card_no'];
            $parameters['borrow_card_bank'] = $bankcard['card_bank'];
        }
        $borrowModel = new BorrowModel();
        $ret = $borrowModel->addUserLoanApply($parameters,$uid);
        if($ret){
            return view('wap.common.jump',['jump_url'=>'/app#tab2','jump_title'=>'成功','jump_text'=>'提交成功,请等待审核!']);
        }else{
            return view('wap.common.jump',['jump_url'=>'/app/borrow/apply','jump_title'=>'失败','jump_text'=>'提交失败,请重试!']);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  detail($id){
        $id = preg_replace('/\D/','',$id);
        if(!empty($id)) {
            $uid = $this->getUserId();
            $borrowLogic  = new BorrowLogic();
            $borrowDetail = $borrowLogic->getSingleBorrowInfoById($id,$uid);
            $interestDetail = array('interest'=>0,'later'=>0);
            if($borrowDetail){
                if($borrowDetail['received_time']){
                    $borrowDetail['active_days'] = BaseLogic::twoTimestampDays(time(),$borrowDetail['received_time']);
                    $interestDetail = BaseLogic::getSumInterest($borrowDetail['borrow_amount'],$borrowDetail['active_days']);
                    $borrowDetail['repayment_amount'] = $borrowDetail['borrow_amount']+array_sum($interestDetail);
                }

                $borrowStatus = BorrowLogic::BORROW_STATUS;
                $borrowBank = BorrowLogic::SUPPORT_BANK_LIST;
                $system = $this->getSystem();
                return view('wap.borrow.detail', ['detail' => $borrowDetail, 'status' => $borrowStatus, 'bank' => $borrowBank,'interest'=>$interestDetail,'id'=>$id,'system'=>$system]);
            }else{
                return view('wap.common.jump',['jump_url'=>'/app#tab2','jump_title'=>'失败','jump_text'=>'数据不存在,请重试!']);
            }

        }
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function repayment($id){
        $id = preg_replace('/\D/','',$id);
        $system = $this->getSystem();
        return view('wap.common.jump',['jump_url'=>'/app/borrow/detail/'.$id,'jump_title'=>'提示','jump_text'=>'还款请联系010-56592060','system'=>$system,'id'=>$id]);
        if(!empty($id)) {
            $uid = $this->getUserId();
            $borrowLogic = new BorrowLogic();
            $repaymentDetail = $borrowLogic->getSingleBorrowRepaymentById($id,$uid);
            $repaymentBank   = borrowLogic::REPAYMENT_BANK_NO;
            if($repaymentDetail){
                return view('wap.borrow.repayment', ['detail' => $repaymentDetail,'bank'=>$repaymentBank]);
            }else{
                return view('wap.common.jump',['jump_url'=>'/app/borrow/detail/'.$id,'jump_title'=>'提示','jump_text'=>'该笔借款不需要还款!']);
            }

        }

    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function doRepayment(Request $request){
        $parameters   = $request->all();
        $amount       = $parameters['amount'];
        $borrowId     = $parameters['borrow_id'];
        $borrowerNo   = $parameters['borrower_bank'];
        $repaymentModel = new RepaymentModel();
        $uid = $this->getUserId();
        $ret = $repaymentModel->addUserRepayment(array(
                                    'borrow_id'       => $borrowId,
                                    'repayment_amount'=> $amount,
                                    'borrower_bank_no'=> $borrowerNo,
                                    'borrower_receipt'=> '',
                                ),$uid);
        if($ret){
            return view('wap.common.jump',['jump_url'=>'/app#tab2','jump_title'=>'成功','jump_text'=>'提交成功,结果会在1-3个工作日公布,请留意短信或者APP!']);
        }else{
            return view('wap.common.jump',['jump_url'=>'/app/borrow/repayment/'.$borrowId,'jump_title'=>'失败','jump_text'=>'提交失败,请重试!']);
        }

    }




}