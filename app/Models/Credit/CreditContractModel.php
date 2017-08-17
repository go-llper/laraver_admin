<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/24
 * Time: 上午9:58
 */
namespace App\Models\Credit;

use App\Lang\LangModel;
use App\Models\Common\ExceptionCodeModel;
use App\Models\CommonScopeModel;

class CreditContractModel extends CommonScopeModel
{
    protected $table = 'user_credit_contract';

    public static $codeArr = [
        'doCreate'             => 1,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['uid', 'family_name', 'family_tel','workmate_name','workmate_tel','friend_name','friend_tel','marital_name','marital_tel'];

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @param $param
     * @return bool
     * @throws \Exception
     * @desc 提交授信审核信息
     */
    public function doCreate($param = []){

        $this->uid    = $param['uid'];
        $this->family_name = $param['family_name'];
        $this->family_tel = $param['family_tel'];
        $this->workmate_name = $param['workmate_name'];
        $this->workmate_tel = $param['workmate_tel'];
        $this->friend_name = $param['friend_name'];
        $this->friend_tel = $param['friend_tel'];
        $this->marital_name = $param['marital_name'];
        $this->marital_tel = $param['marital_tel'];

        $this->save();

        if(!$this->id){
            throw new \Exception(LangModel::getLang('ERROR_SUBMIT_CREDIT_INFO'), self::getFinalCode('doCreate'));
        }

        return true;
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
}