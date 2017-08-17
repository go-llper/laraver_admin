<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/7/20
 * Time: 上午11:00
 * Desc: 二维码生成工具
 * Use: 调用方式:ToolQrCode::createCode($url);
 */

namespace App\Tools;

use App\Http\Logics\Oss\OssLogic;
use Endroid\QrCode\QrCode;


class ToolQrCode{


    /**
     * @param $content
     * @param bool $getFilePath 默认直接输出二维码,如果需要返回二维码地址,参数为true
     * @param int $size
     * @param int $padding
     * @param string $imgType
     * @return bool|string
     * @desc 获取二维码
     */
    public static function createCode($content, $getFilePath=false, $size=300, $padding=0, $imgType=QrCode::IMAGE_TYPE_PNG)
    {

        $qrCode = new QrCode();

        $qrCode->setText($content)
            ->setSize($size)
            ->setPadding($padding)
            ->setImageType($imgType);

        if( $getFilePath ){

            $ossLogic = new OssLogic();

            $savePath = 'resources/qrcode/';
            $fileName = md5($content).'.'.$qrCode->getImageType();

            $exit     = $ossLogic->checkPathExit($savePath.$fileName);

            if($exit){
                return assetUrlByCdn($savePath.$fileName);
            }

            $saveRs = $ossLogic->writeFile($qrCode->get(),$savePath.$fileName);

            if( !$saveRs ){
                return false;
            }

            return assetUrlByCdn($savePath.$fileName);

        }

        header('Content-Type: '.$qrCode->getContentType());

        $qrCode->render();

    }

}