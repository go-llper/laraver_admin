<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 下午2:51
 */

namespace App\Models\Borrow;

use App\Logics\Borrow\BorrowLogic;
use App\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Models\CommonScopeModel;
use App\Models\Credit\PhotoModel;
use App\Tools\ToolArray;

class BorrowModel extends CommonScopeModel
{

    protected $table = 'user_loan';

    const
        LOAN_STATUS_CHECK   = 0,    //借款状态-审核中
        LOAN_STATUS_WAIT    = 1,    //借款状态-审核通过，等待放款
        LOAN_STATUS_REFUSE  = 2,    //借款状态-审核拒绝
        LOAN_STATUS_SUCCESS = 3,    //借款状态-放款成功
        LOAN_STATUS_REFUND  = 4,    //借款状态-还款中
        LOAN_STATUS_OVERDUE = 5,    //借款状态-已逾期
        LOAN_STATUS_FINISH  = 6,    //借款状态-已还清

        END = TRUE;


    const AUTO_RUN_NUMBER = 100;

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'order_no','borrow_amount','borrow_status', 'borrow_time','borrow_card_no','borrow_card_bank','log_info','driver_info','truck_info','audit_ret','audit_time','audit_admin','pay_time','pay_info','repayment_amount','repayment_time'
    ];

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     * */
    protected $guarded = ['id', 'created_at', 'updated_at'];


    /**
     *
     */
    public function getBorrowCount(){
        return  \DB::table($this->table)->count();
    }


    /**
     * @param $page
     * @return mixed
     */
    public function getAllBorrowList($page=1){
        $offset = $this->getLimitStart($page);
        $list   = \DB::table($this->table)->skip($offset)->take(self::AUTO_RUN_NUMBER)->get();
        return $list;
    }


    /**
     * @param int $uid
     * @return mixed
     */
    public function getUserBindInfo($uid=1){
        $result = self::Uid($uid)->get()->toArray();
        return $result;

    }

    /**
     * @param int $uid
     * @return mixed
     */
    public function getUserBorrowList($uid=1){
        $result = self::Uid($uid)->get()->toArray();
        return $result;
    }


    /**
     * @param int $uid
     * @return mixed
     */
    public function getUserBorrowingAccount($uid=1){
        $result = self::Uid($uid)->where('borrow_status','<',6)->where('borrow_status','!=','2')->count();
        return $result;
    }


    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateBorrowLoanById($id,$data){
        $result = self::ID($id)->update($data);
        return $result;
    }


    /**
     * @param $data
     * @return mixed
     */
    public function addUserMoneyLog($data){
        $result = \DB::table('user_money_record')->insert($data);
        return $result;
    }

    /**
     * @param $borrowId
     * @param $dayTime
     * @return mixed
     */
    public function getDayUserMoneyLogCount($borrowId,$dayTime){
        $count   = \DB::table('user_money_record')
            ->where('borrow_id', $borrowId)
            ->where('dated_at',$dayTime)
            ->count();

        return $count;
    }


    /**
     * @param $id
     * @param int $uid
     * @param array $select
     * @return mixed
     */
    public function getSingleBorrowInfoById($id,$uid=1,$select=array()){

        if(empty($select)){
            $result = self::Id($id)->Uid($uid)->first();
        }else{
            $result = self::Id($id)->Uid($uid)->select($select)->first();
        }
        if($result){
            $result = $result->toArray();
        }else{
            $result = array();
        }
        return $result;
    }


    /**
     * @param $loanInfo
     * @param int $uid
     * @return bool
     * @throws \Exception
     */
    public function addUserLoanApply($loanInfo,$uid=1){
        $this->uid                = $uid;
        $this->borrow_amount      = $loanInfo['borrow_amount'];
        $this->borrow_real_amount = empty($loanInfo['borrow_real_amount'])?0:$loanInfo['borrow_real_amount'];
        $this->order_no           = $loanInfo['order_no'];
        $this->borrow_time        = time();
        $this->borrow_card_no     =  $loanInfo['borrow_card_no'];
        $this->borrow_card_bank   =  $loanInfo['borrow_card_bank'];
        $this->log_info           = json_encode(array(
                                        'log_goods'=>$loanInfo['log_goods'],
                                        'log_point'=>$loanInfo['log_point'],
                                        'log_price'=>$loanInfo['log_price']
                                      ));
        $this->driver_info        = json_encode(array(
                                        'driver_name' =>$loanInfo['driver_name'],
                                        'driver_phone'=>$loanInfo['driver_phone']
                                      ));
        $this->truck_info         = json_encode(array(
                                        'truck_no' =>$loanInfo['truck_no'],
                                        'truck_image'=>$loanInfo['truck_image']
                                     ));
        $this->save();
        if(!$this->id){
            throw new \Exception(LangModel::getLang('ERROR_SUBMIT_CREDIT_INFO'), self::getFinalCode('doCreate'));
        }
        return true;
    }


    /**
     * @desc    借款待审核记录 | 借款待放款记录
     *
     **/
    public function getUserLoanRecord($status=self::LOAN_STATUS_CHECK){

        $list   = \DB::table('user_loan')
            ->leftJoin('user','user_loan.uid', '=', 'user.id')
            ->leftJoin('user_info', "user_loan.uid", "=", "user_info.uid")
            ->select("user_loan.*", "user.phone","user_info.real_name","user_info.id_card")
            ->where("user_loan.borrow_status",  $status)
            ->orderBy('user_loan.id',   'desc')
            ->paginate(20);

        return [ 'list' => $list ];

    }


    /**
     * @desc    获取已放款记录
     *
     **/
    public function getUserAlreadyLoanRecord(){

        $list   = \DB::table('user_loan')
            ->leftJoin('user','user_loan.uid', '=', 'user.id')
            ->leftJoin('user_info', "user_loan.uid", "=", "user_info.uid")
            ->select("user_loan.*", "user.phone","user_info.real_name","user_info.id_card")
            ->whereIn("user_loan.borrow_status",  [self::LOAN_STATUS_SUCCESS, self::LOAN_STATUS_REFUND,self::LOAN_STATUS_OVERDUE,self::LOAN_STATUS_FINISH])
            ->orderBy('user_loan.id',   'desc')
            ->paginate(3);

        return [ 'list' => $list ];

    }

    /**
     * @desc    获取借款详情
     **/
    public function getLoanInfo($id){
        $result   = \DB::table('user_loan')
            ->join('user',      'user.id', '=', 'user_loan.uid')
            ->join('user_info', 'user_info.uid', '=', 'user_loan.uid')
            ->select("user_info.real_name", "user_info.id_card", "user.phone","user.credit_total","user_loan.*")
            ->where('user_loan.id', $id)
            ->first();
        $result = ToolArray::objectToArray($result);
        return $result;

    }

    /**
     * @desc    获取照片信息
     * @param   $id user_loan表 主键ID
     **/
    public function getLoanPhoto($id, $type=PhotoModel::DRIVER){
        $result   = \DB::table('user_loan')
            ->leftJoin('user_credit_photo', 'user_credit_photo.uid', '=', 'user_loan.uid')
            ->select("user_credit_photo.*")
            ->where('user_loan.id', $id)
            ->where('user_credit_photo.type',$type)
            ->get();
        $result = ToolArray::objectToArray($result);
        return $result;

    }


    /**
     * @desc    借款放款操作
     *
     **/
    public function doLoanOperate($id, $editData=[]){

        $result =   \DB::table("user_loan")->where('id', $id)->update($editData);

        return $result;

    }


    /**
     * @param $page
     * @param int $size
     * @return mixed
     */
    private function getLimitStart($page,$size=self::AUTO_RUN_NUMBER){

        return ( max(0, $page -1) ) * $size;

    }

}