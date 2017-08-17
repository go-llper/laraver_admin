<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/10/28
 * Time: 上午11:16
 * Desc: 借款人账户信息
 */

namespace App\Models;

use App\Lang\LangModel;

class LoanUserModel extends CommonScopeModel{


    protected $table = 'loan_user';

    public static $codeArr = [
        'doAddOrUpdate'             => 1,
    ];


    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'bank_card', 'real_name', 'identity_card', 'balance', 'type', 'level', 'status'
    ];

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     * */
     protected $guarded = ['id', 'created_at', 'updated_at'];


    /**
     * @param $data
     * @return mixed
     * @desc 执行添加获取更新
     */
    public function doAddOrUpdate($data, $id=0){

        if( $id ){

            $res = self::updateOrCreate(['id' => $id], $data);

        }else{

            $res = self::updateOrCreate(['id' => null], $data);

        }

        if( !$res->id ){

            throw new \Exception(LangModel::getLang('ERROR_COMMON'), self::getFinalCode('doAddOrUpdate'));

        }

        return $res->id;

    }




}