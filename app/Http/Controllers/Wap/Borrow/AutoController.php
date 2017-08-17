<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/18
 * Time: 下午1:43
 */

namespace App\Http\Controllers\Wap\Borrow;

use App\Http\Controllers\Controller;
use App\Logics\Borrow\BorrowLogic;
use App\Logics\Common\BaseLogic;
use App\Models\Borrow\BorrowModel;
use App\Tools\ToolArray;
use Illuminate\Http\Request;

class AutoController extends Controller{


    const ALLOW_IPS = array('127.0.0.1');

    public function borrowInterestRun(Request $request){
        $clientIp = $request->ip();
        if(in_array($clientIp,self::ALLOW_IPS) || 1){
            $borrowModel = new BorrowModel();
            $count = $borrowModel->getBorrowCount();
            if($count>$borrowModel::AUTO_RUN_NUMBER){
                $round = ceil($count/$borrowModel::AUTO_RUN_NUMBER);
                for($i=1;$i<=$round;$i++){
                    $list = $borrowModel->getAllBorrowList($i);
                    $this->handleInterestRun($list);
                }
            }else{
                $list  = $borrowModel->getAllBorrowList();
                $this->handleInterestRun($list);

            }
        }

    }


    private function handleInterestRun($list){
        foreach($list as $item){
            $item   = ToolArray::objectToArray($item);
            $status = $item['borrow_status'];
            $currentDay   = date('Y-m-d',time());
            $currentTime  = strtotime($currentDay);
            $capital      = $item['borrow_amount'];
            $receivedTime = $item['received_time'];
            $betweenDays  = BaseLogic::twoTimestampDays($currentTime,$receivedTime);
            $borrowModel  = new BorrowModel();
            $isRun = $borrowModel->getDayUserMoneyLogCount($item['id'],$currentTime);
            if($isRun){
                return;
            }
            switch($status){
                # 打款成功 开始每日计息 期间不能还款
                case BorrowLogic::BORROW_STATUS_SUCCESS:
                    if($betweenDays <= BaseLogic::ONE_MONTH_DAYS){
                        $dayInterest = $capital * BaseLogic::ONE_TO_THIRTY_DAYS_RATE;
                    }
                    $borrow_interest  = ($item['borrow_interest'] + $dayInterest);
                    $borrow_interest  = round($borrow_interest,2);
                    $current_interest = ($item['current_interest'] + $dayInterest);
                    $current_interest = round( $current_interest,2);
                    $updateData['borrow_interest']  = $borrow_interest;
                    $updateData['current_interest'] = $current_interest;

                    if($betweenDays==BaseLogic::NEED_REPAY_DAYS){
                        $updateData['borrow_status'] = BorrowLogic::BORROW_STATUS_REPAY;
                    }
                    $borrowModel->doLoanOperate($item['id'],$updateData);

                    # Add Money Log
                    $logData = array(
                        'uid'           => $item['uid'],
                        'borrow_id'     => $item['id'],
                        'cash_before'   => $item['borrow_amount'],
                        'cash_change'   => 0,
                        'cash'          => $item['borrow_amount'],

                        'late_before'   => $item['borrow_late'],
                        'late_change'   => 0,
                        'late'          => $item['borrow_late'],

                        'interest_before' => $item['current_interest'],
                        'interest_change' => round($dayInterest,2),
                        'interest'        => $current_interest,

                        'type'            => BaseLogic::MONEY_CHANGE_TYPE_INTEREST,
                        'dated_at'        => $currentTime,
                        'note'            => '每日更新利息:'.$dayInterest
                    );
                    $borrowModel->addUserMoneyLog($logData);
                    break;
                # 还款中
                case BorrowLogic::BORROW_STATUS_REPAY:
                    $repaymentCapital  = $item['repayment_amount'];

                    $needCapital = $capital-$repaymentCapital;
                    $updateData  = array();
                    if($betweenDays <= BaseLogic::ONE_MONTH_DAYS){
                        $dayInterest = $needCapital * BaseLogic::ONE_TO_THIRTY_DAYS_RATE;
                    }elseif($betweenDays> BaseLogic::ONE_MONTH_DAYS && $betweenDays<=BaseLogic::TWO_MONTH_DAYS) {
                        $dayInterest = $needCapital * BaseLogic::THIRTY_TO_SIXTY_DAYS_RATE;
                    //}elseif($betweenDays == (BaseLogic::TWO_MONTH_DAYS+1)){
                    }else{
                        $dayInterest = $needCapital * BaseLogic::SIXTY_DAYS_RATE;
                        $late= $needCapital * BaseLogic::ONE_TO_THIRTY_DAYS_LATE;
                    }

                    $borrow_interest  = ($item['borrow_interest'] + $dayInterest);
                    $borrow_interest  = round($borrow_interest,2);
                    $current_interest = ($item['current_interest'] + $dayInterest);
                    $current_interest = round( $current_interest,2);
                    $updateData['borrow_interest']  = $borrow_interest;
                    $updateData['current_interest'] = $current_interest;
                    if(!empty($late)){
                        $borrow_late  = ($item['borrow_late'] + $late);
                        $borrow_late  = round($borrow_late,2);
                        $current_late = ($item['current_late'] + $late);
                        $current_late = round( $current_late,2);
                        $updateData['borrow_late']  = $borrow_late;
                        $updateData['current_late'] = $current_late;
                        $updateData['borrow_status'] = BorrowLogic::BORROW_STATUS_OVERDUE;
                    }

                    $borrowModel->doLoanOperate($item['id'],$updateData);

                    # Add Money Log
                    $logData = array(
                        'uid'           => $item['uid'],
                        'borrow_id'     => $item['id'],
                        'cash_before'   => $item['borrow_amount'],
                        'cash_change'   => 0,
                        'cash'          => $item['borrow_amount'],

                        'late_before'   => $item['borrow_late'],
                        'late_change'   => 0,
                        'late'          => $item['borrow_late'],

                        'interest_before' => $item['current_interest'],
                        'interest_change' => round($dayInterest,2),
                        'interest'        => $current_interest,

                        'type'            => BaseLogic::MONEY_CHANGE_TYPE_INTEREST,
                        'dated_at'        => $currentTime,
                        'note'            => '每日更新利息:'.$dayInterest
                    );
                    if(!empty($late)){
                        $logData['late_change'] = $late;
                        $logData['late'] = $current_late;
                        $logData['note'] = $item['note'].',第一次逾期收取违约金'.$late;
                    }

                    $borrowModel->addUserMoneyLog($logData);
                    break;
                # 已经逾期
                case BorrowLogic::BORROW_STATUS_OVERDUE:
                    $repaymentCapital  = $item['repayment_amount'];
                    $needCapital = $capital-$repaymentCapital;

                    if($betweenDays==91){
                        $late= $needCapital * BaseLogic::THIRTY_DAYS_LATE;
                    }
                    $dayInterest = $needCapital * BaseLogic::SIXTY_DAYS_RATE;

                    $borrow_interest  = ($item['borrow_interest'] + $dayInterest);
                    $borrow_interest  = round($borrow_interest,2);
                    $current_interest = ($item['current_interest'] + $dayInterest);
                    $current_interest = round( $current_interest,2);
                    $updateData['borrow_interest']  = $borrow_interest;
                    $updateData['current_interest'] = $current_interest;
                    if(!empty($late)){
                        $borrow_late  = ($item['borrow_late'] + $late);
                        $borrow_late  = round($borrow_late,2);
                        $current_late = ($item['current_late'] + $late);
                        $current_late = round( $current_late,2);
                        $updateData['borrow_late']  = $borrow_late;
                        $updateData['current_late'] = $current_late;
                    }

                    $borrowModel->doLoanOperate($item['id'],$updateData);
                    # Add Money Log
                    $logData = array(
                        'uid'           => $item['uid'],
                        'borrow_id'     => $item['id'],
                        'cash_before'   => $item['borrow_amount'],
                        'cash_change'   => 0,
                        'cash'          => $item['borrow_amount'],

                        'late_before'   => $item['borrow_late'],
                        'late_change'   => 0,
                        'late'          => $item['borrow_late'],

                        'interest_before' => $item['current_interest'],
                        'interest_change' => round($dayInterest,2),
                        'interest'        => $current_interest,

                        'type'            => BaseLogic::MONEY_CHANGE_TYPE_INTEREST,
                        'dated_at'        => $currentTime,
                        'note'            => '每日更新利息:'.$dayInterest
                    );
                    if(!empty($late)){
                        $logData['late_change'] = $late;
                        $logData['late'] = $current_late;
                        $logData['note'] = $item['note'].',第二次逾期收取违约金'.$late;
                    }
                    $borrowModel->addUserMoneyLog($logData);


                    break;

            }
        }

    }





}