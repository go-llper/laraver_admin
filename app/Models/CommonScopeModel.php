<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/11/2
 * Time: 下午12:02
 * Desc: 公共查询条件类
 */

namespace App\Models;

use App\Models\Common\BaseModel;

class CommonScopeModel extends BaseModel{


    public function scopeId($query, $id){

        return $query->where('id', $id);

    }

    public function scopeIds($query, $ids){

        return $query->whereIn('id', $ids);

    }

    public function scopePhone($query, $phone){

        return $query->where('phone', $phone);

    }

    public function scopeUid($query, $uid){

        return $query->where('uid', $uid);

    }

}