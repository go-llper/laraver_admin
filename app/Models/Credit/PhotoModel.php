<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/22
 * Time: 上午10:43
 */

namespace App\Models\Credit;

use App\Lang\LangModel;
use App\Models\Common\ExceptionCodeModel;
use App\Models\CommonScopeModel;

class PhotoModel extends CommonScopeModel
{
    const
        DRIVER              = 10, //行驶证
        BUSINESS            = 20, //营业执照
        TRANSPORT           = 30, //道路运输许可证
        MARRY               = 40, //结婚证
        HOUSE_COVER         = 51, //房产证－封面
        HOUSE_SIGN          = 52, //房产证－发证机关页
        HOUSE_INFO          = 53, //房产证－房屋信息页
        HOUSE_PIC           = 54, //房产证－平面图页
        HOUSE_TITLE         = 55, //房产证－权利摘要页
        HOUSE_TIP           = 56, //房产证－注意事项页

        PIC_ONE             = 61, //活体1
        PIC_TWO             = 62, //活体2
        PIC_THREE           = 63, //活体3
        PIC_FOUR            = 64, //活体4

        OCR_PIC             = 71, //身份证正面
        OCR_PIC_BACK        = 72, //身份证反面

        END = TRUE;


    protected $table = 'user_credit_photo';

    public static $codeArr = [
        'doAdd'             => 1,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    public static $type = [
        'driver'        => self::DRIVER,
        'business'      => self::BUSINESS,
        'transport'     => self::TRANSPORT,
        'marry'         => self::MARRY,
        'houseCover'    => self::HOUSE_COVER,
        'houseSign'     => self::HOUSE_SIGN,
        'houseInfo'     => self::HOUSE_INFO,
        'housePic'      => self::HOUSE_PIC,
        'houseTitle'    => self::HOUSE_TITLE,
        'houseTip'      => self::HOUSE_TIP,
        'pic1'          => self::PIC_ONE,
        'pic2'          => self::PIC_TWO,
        'pic3'          => self::PIC_THREE,
        'pic4'          => self::PIC_FOUR,
        'ocr_pic_front' => self::OCR_PIC,
        'ocr_pic_back'  => self::OCR_PIC_BACK,
    ];

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['uid', 'type', 'image'];

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @param $uid
     * @param $type
     * @param $image
     * @return bool
     * @throws \Exception
     * @desc 添加图片
     */
    public function doAdd($uid, $type, $image){

        $this->uid   = $uid;
        $this->type  = $type;
        $this->image = $image;

        $this->save();

        if(!$this->id){
            throw new \Exception(LangModel::getLang('ERROR_SUBMIT_CREDIT_INFO'), self::getFinalCode('doAdd'));
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