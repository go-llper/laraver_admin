<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 下午2:51
 */

namespace App\Models\Borrow;

use App\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Models\CommonScopeModel;

class RepaymentModel extends CommonScopeModel
{

    protected $table = 'user_repayment';



    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'borrow_id', 'repayment_amount','repayment_time','borrower_bank_no','borrower_receipt','audit_ret','audit_comment'
    ];

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     * */
    protected $guarded = ['id', 'created_at', 'updated_at'];


    /**
     * @param int $uid
     * @return mixed
     */
    public function getUserRepaymentAllList($uid=1){
        $result = self::Uid($uid)->get()->toArray();
        return $result;
    }


    /**
     * @param $borrow_id
     * @param int $uid
     * @return mixed
     */
    public function getUserRepaymentBorrowList($borrow_id,$uid=1){
        $result = self::Uid($uid)->where('borrow_id',$borrow_id)->get()->toArray();
        return $result;
    }


    /**
     * @param $repaymentInfo
     * @param int $uid
     * @return bool
     * @throws \Exception
     */
    public function addUserRepayment($repaymentInfo,$uid=1){
        $this->uid               = $uid;
        $this->borrow_id         = $repaymentInfo['borrow_id'];
        $this->repayment_amount  = $repaymentInfo['repayment_amount'];
        $this->repayment_time    = time();
        $this->borrower_bank_no  = $repaymentInfo['borrower_bank_no'];
        $this->borrower_receipt  = $repaymentInfo['borrower_receipt'];

        $this->save();
        if(!$this->id){
            throw new \Exception(LangModel::getLang('ERROR_SUBMIT_CREDIT_INFO'), self::getFinalCode('doCreate'));
        }
        return true;
    }



}