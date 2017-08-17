<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/24
 * Time: 下午7:58
 */
namespace App\Models\Credit;

use App\Lang\LangModel;
use App\Models\Common\ExceptionCodeModel;
use App\Models\CommonScopeModel;

class CreditPhoneModel extends CommonScopeModel
{
    const
        PHONE_BOOK      = 10, //电话本
        CALL_HISTORY    = 20, //通话记录
        SMS             = 30, //短信记录

        END = TRUE;

    public static $getArr = [
        'phonebook'     => self::PHONE_BOOK,
        'history'       => self::CALL_HISTORY,
        'sms'           => self::SMS,
    ];

    protected $table = 'user_credit_phone';

    public static $codeArr = [
        'doCreate'             => 1,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['uid', 'type', 'content'];

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

        $this->uid     = $param['uid'];
        $this->type    = $param['type'];
        $this->content = $param['content'];

        $this->save();

        if(!$this->id){
            throw new \Exception(LangModel::getLang('ERROR_SUBMIT_CREDIT_CONTENT'), self::getFinalCode('doCreate'));
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