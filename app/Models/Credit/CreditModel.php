<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/18
 * Time: 下午6:00
 */

namespace App\Models\Credit;

use App\Lang\LangModel;
use App\Models\Common\ExceptionCodeModel;
use App\Models\CommonScopeModel;
use App\Tools\ToolArray;

class CreditModel extends CommonScopeModel
{
    const
        EDU_PRIMARY             = 1,    //小学
        EDU_JUNIOR              = 2,    //初中
        EDU_SENIOR              = 3,    //高中
        EDU_COLLEGE             = 4,    //大学及以上

        MARRY_NO                = 1,    //未婚
        MARRY_YES               = 2,    //已婚
        MARRY_DIVORCED          = 3,    //离异

        SERVER_YEARS_ONE        = 1,    //1-2年
        SERVER_YEARS_TWO        = 2,    //2-3年
        SERVER_YEARS_THREE      = 3,    //3-5年
        SERVER_YEARS_FORE       = 4,    //5-10年
        SERVER_YEARS_FIVE       = 5,    //10年以上

        BIZ_SPECIAL_LINE        = 1,    //专线
        BIZ_THIRD_LINE          = 2,    //三方
        BIZ_CARS_LINE           = 3,    //车队
        BIZ_STORAGE_LINE        = 4,    //仓储
        BIZ_DISTRIBUTION        = 5,    //配送

        PROFIT_TYPE_ONE         = 1,    //300以内
        PROFIT_TYPE_TWO         = 2,    //300-500
        PROFIT_TYPE_THREE       = 3,    //500-1000
        PROFIT_TYPE_FOUR        = 4,    //1000-2000
        PROFIT_TYPE_FIVE        = 5,    //2000-5000
        PROFIT_TYPE_SIX         = 5,    //5000以上

        CREDIT_ING              = 10,  //审核中
        CREDIT_PASS             = 20,  //审核通过
        CREDIT_REFUSE           = 30,  //审核未通过

        END = TRUE;

    protected $table = 'user_info';

    public static $codeArr = [
        'doCreate'             => 1,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'real_name', 'id_card','cul_level','marital_status','home_address','service_years','company_name','company_start','company_address','biz_address','main_biz','employee_number','money_total','profit_total','anchored_car','own_car'
    ];

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return array
     * @desc 文化程度
     */
    public static function getCulLevel(){
        return [
            self::EDU_PRIMARY =>'小学',
            self::EDU_JUNIOR  =>'初中',
            self::EDU_SENIOR  =>'高中',
            self::EDU_COLLEGE =>'大学及以上',
        ];
    }

    /**
     * @return array
     * @desc 婚姻状况
     */
    public static function getMarital(){
        return [
            self::MARRY_NO       =>'未婚',
            self::MARRY_YES      =>'已婚',
            self::MARRY_DIVORCED =>'离异',
        ];
    }

    /**
     * @return array
     * @desc 从业年限
     */
    public static function getServiceYears(){
        return [
            self::SERVER_YEARS_ONE   =>'1-2年',
            self::SERVER_YEARS_TWO   =>'2-3年',
            self::SERVER_YEARS_THREE =>'3-5年',
            self::SERVER_YEARS_FORE  =>'5-10年',
            self::SERVER_YEARS_FIVE  =>'10年以上',
        ];
    }

    /**
     * @return array
     * @desc 主营业务
     */
    public static function getMainBiz(){
        return [
            self::BIZ_SPECIAL_LINE =>'专线',
            self::BIZ_THIRD_LINE   =>'三方',
            self::BIZ_CARS_LINE    =>'车队',
            self::BIZ_STORAGE_LINE =>'仓储',
            self::BIZ_DISTRIBUTION =>'配送',
        ];
    }

    /**
     * @return array
     * @desc 年收入
     */
    public static function getProfitTotal(){
        return [
            self::PROFIT_TYPE_ONE   =>'300以内',
            self::PROFIT_TYPE_TWO   =>'300-500',
            self::PROFIT_TYPE_THREE =>'500-1000',
            self::PROFIT_TYPE_FOUR  =>'1000-2000',
            self::PROFIT_TYPE_FIVE  =>'2000-5000',
            self::PROFIT_TYPE_SIX   =>'5000以上',
        ];
    }

    /**
     * @return array
     * @desc 审核状态
     */
    public static function getCreditStatus(){
        return [
            self::CREDIT_ING    => '授信审核中',
            self::CREDIT_PASS   => '立即借款',
            self::CREDIT_REFUSE => '授信未通过',
        ];
    }

    /**
     * @param $uid
     * @return mixed
     * @根据用户ID获取用户授信信息
     */
    public function getUserInfo($uid){

        $result = self::Uid($uid)->first();

        if($result){
            $result = $result->toArray();
        }
        return $result;
    }

    /**
     * @param $param
     * @return bool
     * @throws \Exception
     * @desc 提交授信审核信息
     */
    public function doCreate($param = []){

        $this->uid    = $param['uid'];
        $this->real_name = $param['real_name'];
        $this->id_card = $param['id_card'];
        $this->cul_level = $param['cul_level'];
        $this->marital_status = $param['marital_status'];
        $this->home_address = $param['home_address'];
        $this->service_years = $param['service_years'];
        $this->company_name = $param['company_name'];
        $this->company_start = $param['company_start'];
        $this->company_address = $param['company_address'];
        $this->biz_address = $param['biz_address'];
        $this->main_biz = $param['main_biz'];
        $this->employee_number = $param['employee_number'];
        $this->money_total = $param['money_total'];
        $this->profit_total = $param['profit_total'];
        $this->anchored_car = $param['anchored_car'];
        $this->own_car = $param['own_car'];

        $this->save();

        if(!$this->id){
            throw new \Exception(LangModel::getLang('ERROR_SUBMIT_CREDIT_INFO'), self::getFinalCode('doCreate'));
        }

        return true;
    }

    /**
     * @desc  用户授信记录
     *
     **/
    public function getUserCreditRecord($status=self::CREDIT_ING){

        $list   = \DB::table('user')
            ->leftJoin('user_info','user.id', '=', 'user_info.uid')
            ->select("user_info.*", "user.phone")
            ->where('user_info.credit_status', $status)
            ->orderBy('user.id', 'desc')
            ->paginate(20);

        return [ 'list' => $list ];

    }

    /**
     * @desc  用户基本信息
     **/
    public function getCreditUserBaseInfo($uid){
        $result   = \DB::table('user')
            ->join('user_info','user.id', '=', 'user_info.uid')
            ->select("user_info.*", "user.phone","user.credit_total")
            ->where('user.id', $uid)
            ->first();
        $result = ToolArray::objectToArray($result);
        return $result;
    }

    /**
     * @desc 用户联系人
     *
     **/
    public function getCreditUserContract($uid){

        $result   = \DB::table('user_credit_contract')
            ->where('uid', $uid)
            ->get();
        $result = ToolArray::objectToArray($result);
        return $result;

    }

    /**
     * @desc 用户电话信息
     *
     **/
    public function getCreditUserPhone($uid){

        $result   = \DB::table('user_credit_phone')
            ->where('uid', $uid)
            ->get();
        $result = ToolArray::objectToArray($result);
        return $result;

    }

    /**
     * @desc   获取用户照片信息
     **/

    public function getCreditUserPhoto($uid){

        $result   = \DB::table('user_credit_photo')
            ->where('uid', $uid)
            ->get();
        $result = ToolArray::objectToArray($result);

        return $result;

    }

    /**
     * @desc    更新用户授信额度
     *
     **/
    public function updateUserCredit($uid, $credit_total){

        $result = \DB::table("user")->where('id',$uid)->update([
                'credit_total'  => $credit_total,
                'updated_at'    => date('Y-m-d H:i:s')]);

        return $result;
    }

    /**
     * @desc    更新用户授信状态
     *
     **/
    public function updateUserStatus($uid, $credit_status){

        $result = \DB::table("user_info")->where('uid',$uid)->update([
            'credit_status'  => $credit_status,
            'updated_at'    => date('Y-m-d H:i:s')]);

        return $result;
    }

    /**
     * @desc    更新用户脸部识别分数
     *
     **/
    public function updateUserConfidence($uid, $confidence){

        $result = \DB::table("user_info")->where('uid',$uid)->update([
            'confidence'  => $confidence,
            'updated_at'    => date('Y-m-d H:i:s')]);

        return $result;
    }


    /**
     * @desc    更新用户信息表
     *
     **/
    public function updateUserInfoCredit($uid,$credit_status='',$operate_user=''){

        $result =   \DB::table("user_info")->where('uid', $uid)->update([
                        'credit_status' => $credit_status,
                        'operate_user'  => $operate_user,
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]);

        return $result;

    }


}
