<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 下午2:51
 */

namespace App\Models\User;

use App\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Models\CommonScopeModel;

class UserModel extends CommonScopeModel
{

    protected $table = 'user';

    public static $codeArr = [
        'doRegister'             => 1,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'credit_total'
    ];

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     * */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @param $name
     * @return mixed
     * @根据用户名获取用户信息
     */
    public function getUser($name){

        $result = self::Phone($name)->first();

        if($result){
            $result = $result->toArray();
        }

        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * @根据用户ID获取用户信息
     */
    public function getId($id){

        $result = self::Id($id)->first();

        if($result){
            $result = $result->toArray();
        }

        return $result;
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     * @throws \Exception
     * @desc 执行注册
     */
    public function doRegister($username, $password){

        $password =md5(md5($password));

        $this->phone    = $username;
        $this->password = $password;

        $this->save();

        if(!$this->id){
            throw new \Exception(LangModel::getLang('USER_REGISTER_DO_REGISTER'), self::getFinalCode('doRegister'));
        }

        return true;
    }

}