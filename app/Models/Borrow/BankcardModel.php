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

class BankcardModel extends CommonScopeModel
{

    protected $table = 'user_bank_card';



    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'card_no', 'card_bank','card_deposit','is_delete'
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
    public function getUserBindInfo($uid=1){
        $result = self::Uid($uid)->first();
        if($result){
            $result = $result->toArray();
        }else{
            $result = array();
        }
        return $result;
    }

    /**
     * @param $bankInfo
     * @param int $uid
     * @return bool
     * @throws \Exception
     */
    public function addUserBankcard($bankInfo,$uid=1){
        $this->uid          = $uid;
        $this->card_no      = $bankInfo['bankNo'];
        $this->card_bank   = $bankInfo['bankName'];
        $this->card_deposit = '';
        $this->is_delete    = 0;
        $this->save();
        if(!$this->id){
            throw new \Exception(LangModel::getLang('ERROR_SUBMIT_CREDIT_INFO'), self::getFinalCode('doCreate'));
        }
        return true;
    }



}