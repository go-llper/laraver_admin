<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/22
 * Time: 上午11:00
 */

namespace App\Logics\Credit;

use App\Logics\Common\BaseLogic;
use App\Models\Credit\PhotoModel;
use Log;

class PhotoLogic extends BaseLogic
{
    /**
     * @param array $param
     * @return array
     * @desc 保存图片路径
     */
    public function doAdd($param = []){
        $model = new PhotoModel();

        $uid   = !empty($param['uid']) ? $param['uid'] : 0 ;
        $type  = isset(PhotoModel::$type[$param['type']]) ? PhotoModel::$type[$param['type']] : 0;
        $image = !empty($param['image']) ? $param['image'] : '';

        try{
            $model->doAdd($uid,$type,$image);

        }catch (\Exception $e){

            $param['errorMsg'] = $e->getMessage();
            Log::error(__METHOD__ . 'Error', $param);
            return self::callError($e->getMessage());
        }

        Log::Info('uploadImage:',$param);
        return self::callSuccess(['url'=>env('APP_URL').'/'.$image]);
    }
}