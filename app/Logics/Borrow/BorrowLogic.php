<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 上午10:29
 */

namespace App\Logics\Borrow;

use App\Logics\Common\BaseLogic;
use App\Models\Borrow\BankcardModel;
use App\Models\Borrow\BorrowModel;
use App\Tools\ToolIdCard;
use Log;

class BorrowLogic extends BaseLogic{

    const SUPPORT_BANK_LIST = [
        1 => [
            'code' => 'ICBC',
            'name' => '中国工商银行'
        ],
        2 => [
            'code' => 'ABC',
            'name' => '中国农业银行'
        ],
        3 => [
            'code'  => 'BOC',
            'name'  => '中国银行'
        ],
        4 => [
            'code'  => 'CCB',
            'name'  => '中国建设银行'
        ],
        5 => [
            'code'  => 'BOCOM',
            'name'  => '交通银行'
        ],
        6 => [
            'code'  => 'CMB',
            'name'  => '招商银行'
        ],
        7 => [
            'code'  => 'SPDB',
            'name'  => '上海浦东发展银行'
        ],
        8 => [
            'code'  => 'CMBC',
            'name'  => '中国民生银行'
        ],
        9 => [
            'code'  => 'CIB',
            'name'  => '兴业银行'
        ],
        10 => [
            'code'  => 'CEB',
            'name'  => '中国光大银行'
        ],
        11 => [
            'code'  => 'BCCB',
            'name'  => '北京银行'
        ],
        12 => [
            'code'  => 'GDB',
            'name'  => '广发银行'
        ],
        13 => [
            'code'  => 'CNCB',
            'name'  => '中信银行'
        ],
        15 => [
            'code'  => 'HXB',
            'name'  => '华夏银行'
        ],
        14 => [
            'code'  => 'PSBC',
            'name'  => '中国邮政储蓄银行'
        ],
        16 => [
            'code'  => 'BOS',
            'name'  => '上海银行'
        ],
        17 => [
            'code'  => 'PAB',
            'name'  => '平安银行'
        ],
    ];



    const BORROW_STATUS_APPLY   = 0;
    const BORROW_STATUS_ACCEPT  = 1;
    const BORROW_STATUS_REJECT  = 2;
    const BORROW_STATUS_SUCCESS = 3;
    const BORROW_STATUS_REPAY   = 4;
    const BORROW_STATUS_OVERDUE = 5;
    const BORROW_STATUS_FINISH  = 6;

    const BORROW_STATUS = array(
        0 => array('code'=>0,'status'=>'审核中'),
        1 => array('code'=>1,'status'=>'放款中'),
        2 => array('code'=>2,'status'=>'审核拒绝'),
        3 => array('code'=>3,'status'=>'放款成功'),
        4 => array('code'=>4,'status'=>'还款中'),
        5 => array('code'=>5,'status'=>'已逾期'),
        6 => array('code'=>6,'status'=>'已还清'),
    );


    const REPAYMENT_BANK_NO = array(
        'card_no'     => '6214 8301 1232 4543',
        'bank_branch' => '招商银行北京建国路支行',
        'bank_name'   => '耀盛商业保理有限公司',
    );



    const SMS_SIGN_PREFIX = 'WL|JY|2017';



    /**
     * @param $id
     * @param int $uid
     * @return mixed
     */
    public function getSingleBorrowInfoById($id,$uid=1){
        $ret = array("code"=>"1000","message"=>"","result"=>array("type"=>"test"));
        $borrowModel  = new BorrowModel();
        $borrowDetail = $borrowModel->getSingleBorrowInfoById($id,$uid);
        if(empty($borrowDetail)){
            return false;
        }
        $borrowDetail['borrow_card_no'] = parent::parseBankCardNo($borrowDetail['borrow_card_no']);
        if($borrowDetail['borrow_status']<=3){
                $borrowDetail['borrow_days']      = 0;
                $borrowDetail['borrow_interest']  = 0;
                $borrowDetail['repayment_amount'] = 0;
        }else{
                $borrowDetail['borrow_days']  = parent::twoTimestampDays(time(),$borrowDetail['borrow_time']);
        }
        return $borrowDetail;
    }


    /**
     * @param $id
     * @param int $uid
     * @return mixed
     */
    public function getSingleBorrowRepaymentById($id,$uid=1){
        $borrowModel  = new BorrowModel();
        $repaymentDetail = $borrowModel->getSingleBorrowInfoById($id,$uid);
        if($repaymentDetail['borrow_status']>3){
            $days  = parent::twoTimestampDays(time(),$repaymentDetail['borrow_time']);
            $repaymentDetail['repayment_amount'] = $repaymentDetail['borrow_amount']+($days*$repaymentDetail['borrow_amount']*3/10000);
            return $repaymentDetail;
        }else{
            return false;
        }
    }


     /**
      * @desc   获取用户借款审核/待放款记录
      *
      *
      **/
     public function getUserLoanRecord($status='0'){

         $model  = new BorrowModel();

         $result = $model->getUserLoanRecord($status);

         return $result;
     }


    /**
     * @desc   获取用户借款列表
     *
     *
     **/
    public function getUserAlreadyLoanRecord(){

        $model  = new BorrowModel();

        $result = $model->getUserAlreadyLoanRecord();
        $statusArr  = self::BORROW_STATUS;
        foreach ($result['list'] as &$vv){
            $status_note    = "";
            if($vv->borrow_status){
                $status_note    = isset($statusArr[$vv->borrow_status])?$statusArr[$vv->borrow_status]['status']:"";
            }
            $vv->status_note = $status_note;

        }

        return $result;
    }

    /**
     * @dessc   借款详情
     *
     **/
    public function getUserLoanDetail($id, $status=BorrowModel::LOAN_STATUS_CHECK){
        $model  = new BorrowModel();
        $loanInfo   = $model->getLoanInfo($id);
        #dd($loanInfo);
        $loanPhoto  = $model->getLoanPhoto($id);
        #dd($loanPhoto);

        $result = $this->formatLoanDetail( $loanInfo, $loanPhoto, $status);
        #var_dump($result);exit;
        return $result;

    }

    /**
     * @desc 格式化借款详情
     *
     **/
    public function formatLoanDetail($loanInfo=[], $loanPhoto=[], $status=BorrowModel::LOAN_STATUS_CHECK){
        \Log::info(__METHOD__.' - '.__LINE__, $loanInfo);
        $id_card        = isset($loanInfo['id_card']) ? $loanInfo['id_card']:"";
        $loanInfo['age']= ToolIdCard::getAgeByIdCard($id_card);
        $loanInfo['sex']= ToolIdCard::getSexByIdCard($id_card);

        $loanInfo['id']             = isset($loanInfo['id'])        ? $loanInfo['id']:"";
        $loanInfo['uid']            = isset($loanInfo['uid'])       ? $loanInfo['uid']:"";
        $loanInfo['real_name']      = isset($loanInfo['real_name']) ? $loanInfo['real_name']:"";
        $loanInfo['id_card']        = $id_card;
        $loanInfo['phone']          = isset($loanInfo['phone'])     ? $loanInfo['phone']:"";

        $logInfo    = isset($loanInfo['log_info'])?json_decode($loanInfo['log_info'],true):array();
        $loanInfo['log_goods']      = isset($logInfo['log_goods'])?$logInfo['log_goods']:"";
        $loanInfo['log_point']      = isset($logInfo['log_point'])?$logInfo['log_point']:"";
        $loanInfo['log_price']      = isset($logInfo['log_price'])?$logInfo['log_price']:"";

        $driverInfo = isset($loanInfo['driver_info'])?json_decode($loanInfo['driver_info'],true):array();
        $loanInfo['driver_name']    = isset($driverInfo['driver_name']) ?$driverInfo['driver_name']:"";
        $loanInfo['driver_phone']   = isset($driverInfo['driver_phone'])?$driverInfo['driver_phone']:"";

        $truckInfo = isset($loanInfo['truck_info'])?json_decode($loanInfo['truck_info'],true):array();
        $loanInfo['truck_no']       = isset($truckInfo['truck_no'])     ?$truckInfo['truck_no']:"";
        $loanInfo['truck_image']    = isset($truckInfo['truck_image'])  ?$truckInfo['truck_image']:"";

        $resData    = [
            "loanInfo"      => $loanInfo,
            "loanPhoto"     => $loanPhoto,
        ];
        return $resData;

    }


    /**
     * @desc    借款审核
     * @param   $id user_loan-数据表 主键ID
     * @param   $borrow_status借款状态
     **/
    public function updateLoanStatus($id, $borrow_status,$borrow_need_fee=0){

        try{
            $model  = new BorrowModel();
            $operate_user   = \Auth::guard('admin')->user()->id;
            $operate_user   = $operate_user ? $operate_user :"";
            //获取借款信息
            $loanInfo   = $model->getLoanInfo($id);
            if($loanInfo['borrow_status'] != BorrowModel::LOAN_STATUS_CHECK){
                return self::callError("请确定数据是否已审核");
            }
            $editData   = [
                "borrow_status" => $borrow_status,
                "audit_time"    => time(),
                "audit_admin"   => $operate_user,
                "updated_at"    => date('Y-m-d H:i:s'),

            ];
            if($borrow_status == BorrowModel::LOAN_STATUS_WAIT){
                $editData['audit_ret']  = 1;
                if( $borrow_need_fee > 0){
                    $current_amount = $loanInfo['borrow_amount']-$borrow_need_fee;
                    $editData['borrow_need_fee']= $borrow_need_fee;
                    $editData['current_amount'] = $loanInfo['borrow_amount'];
                    $editData['borrow_real_amount'] = $current_amount;

                }else{
                    $editData['current_amount']     = $loanInfo['borrow_amount'];
                    $editData['borrow_real_amount'] = $loanInfo['borrow_amount'];
                }
            }

            \Log::info(__METHOD__.':'.__LINE__.'-CHECKT-', $editData);
            //数据表user_info-授信状态，操作者
            $result = $model->doLoanOperate($id, $editData);
            if($result){
                return self::callSuccess();
            }else{
                return self::callError("操作失败");
            }
        }catch(\Exception $e){
            $param['errorMsg'] = $e->getMessage();
            Log::error(__METHOD__ . 'Error', $param);
            return self::callError($e->getMessage());

        }

    }


    /**
     * @desc    放款操作
     * @param   $id 借款ID
     * @param   $borrow_need_fee    手续费
     *
     * 逻辑：
     *  1、更新借款状态-3         必须状态1的才可以更新状态
     *  2、若有手续费 处理手续费   借款金额 = 实际借款金额+手续费
     *  3、当前应还金额，放款时间
     **/
    public function doLoanOperate($id){
        try{
            $model  = new BorrowModel();
            //获取借款信息
            $loanInfo   = $model->getLoanInfo($id);
            if($loanInfo['borrow_status'] != BorrowModel::LOAN_STATUS_WAIT){
                return self::callError("请确定数据是否已审核");
            }
            $currentTime= time();
            $editData   = [
                "borrow_status" => BorrowModel::LOAN_STATUS_SUCCESS,
                "pay_time"      => $currentTime,
                "received_time" => $currentTime,
                "updated_at"    => date('Y-m-d H:i:s'),

            ];

            \Log::info(__METHOD__.':'.__LINE__.'-OPERATE', $editData);
            //数据表user_info-授信状态，操作者
            $result = $model->doLoanOperate($id, $editData);
            if($result!==false){
                return self::callSuccess();
            }else{
                return self::callError("操作失败");
            }
        }catch(\Exception $e){
            $param['errorMsg'] = $e->getMessage();
            Log::error(__METHOD__ . 'Error', $param);
            return self::callError($e->getMessage());

        }

    }


}

