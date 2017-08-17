<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/21
 * Time: 上午9:40
 */

namespace App\Http\Controllers\AppApi;

use App\Logics\Credit\PhotoLogic;
use App\Logics\Oss\OssLogic;
use App\Logics\Credit\CreditLogic;
use Illuminate\Http\Request;

class FaceIdController extends ApiController
{

    /**
     * @param Request $request
     * @return string
     * @desc 活体检测接口
     */
    public function getFacePic(Request $request){

        $data = $request->all();

        $token = $request->input('token');

        $confidence = $request->input('confidence','');

        $uid = $this->getUserIdByToken($token);

        \Log::info('faceId',$data); //todo 调试好后删除

        $pic1       = isset($_FILES['pic1']) ? $_FILES['pic1'] : [];
        $pic2       = isset($_FILES['pic2']) ? $_FILES['pic2'] : [];
        $pic3       = isset($_FILES['pic3']) ? $_FILES['pic3'] : [];
        $pic4       = isset($_FILES['pic4']) ? $_FILES['pic4'] : [];
        $ocrPic     = isset($_FILES['ocr_pic_front']) ? $_FILES['ocr_pic_front'] : [];
        $ocrBackPic = isset($_FILES['ocr_pic_back']) ? $_FILES['ocr_pic_back'] : [];

        if(!isset($pic1['tmp_name']) || !isset($pic2['tmp_name']) || !isset($pic3['tmp_name']) || !isset($pic4['tmp_name']) || !isset($ocrPic['tmp_name']) || !isset($ocrBackPic['tmp_name'])){

            return $this->responseJson([], self::CODE_NOT_FILES, "缺少上传文件");
        }

        $oss = new OssLogic();
        $uploadPic1    = $oss->putFile($pic1);
        $uploadPic2    = $oss->putFile($pic2);
        $uploadPic3    = $oss->putFile($pic3);
        $uploadPic4    = $oss->putFile($pic4);
        $uploadOrcPic  = $oss->putFile($ocrPic);
        $uploadBackPic = $oss->putFile($ocrBackPic);

        if(!$uploadPic1['status'] || !$uploadPic2['status'] || !$uploadPic3['status'] || !$uploadPic4['status'] || !$uploadOrcPic['status'] || !$uploadBackPic['status']){
            return $this->responseJson([], self::CODE_FAIL_FILES, "上传图像文件失败");
        }

        $data1=[
            'uid'   => $uid,
            'type'  => 'pic1',
            'image' => env("APP_URL").$uploadPic1['data']['path'].'/'.$uploadPic1['data']['name']
        ];
        $data2=[
            'uid'   => $uid,
            'type'  => 'pic2',
            'image' => env("APP_URL").$uploadPic2['data']['path'].'/'.$uploadPic2['data']['name']
        ];
        $data3=[
            'uid'   => $uid,
            'type'  => 'pic3',
            'image' => env("APP_URL").$uploadPic3['data']['path'].'/'.$uploadPic3['data']['name']
        ];
        $data4=[
            'uid'   => $uid,
            'type'  => 'pic4',
            'image' => env("APP_URL").$uploadPic4['data']['path'].'/'.$uploadPic4['data']['name']
        ];
        $data5=[
            'uid'   => $uid,
            'type'  => 'ocr_pic_front',
            'image' => env("APP_URL").$uploadOrcPic['data']['path'].'/'.$uploadOrcPic['data']['name']
        ];
        $data6=[
            'uid'   => $uid,
            'type'  => 'ocr_pic_back',
            'image' => env("APP_URL").$uploadBackPic['data']['path'].'/'.$uploadBackPic['data']['name']
        ];

        $logic = new PhotoLogic();
        $pic1     = $logic->doAdd($data1);
        $pic2     = $logic->doAdd($data2);
        $pic3     = $logic->doAdd($data3);
        $pic4     = $logic->doAdd($data4);
        $orcPic   = $logic->doAdd($data5);
        $backPic  = $logic->doAdd($data6);

        $userInfoLogic = new CreditLogic();
        $result = $userInfoLogic->updateUserConfidence($uid,$confidence);

        if(!$pic1['status'] || !$pic2['status'] || !$pic3['status'] || !$pic4['status'] || !$orcPic['status'] || !$backPic['status'] || !$result['status']){
            return $this->responseJson([], self::CODE_FAIL_FILES, "保存图像文件失败");
        }

        return $this->responseJson(['url'=>env('APP_URL').'app/credit/step/3'], self::CODE_SUCCESS, "添加成功");

    }

    /**
     * @param Request $request
     * @return string
     * @desc 获取用户电话本
     */
    public function getPhoneBook(Request $request){

        $json = $request->input('json','');

        $token = $request->input('token');

        $uid = $this->getUserIdByToken($token);

        $data = json_decode($json,true);

        if(isset($data['phonebook'])){
            $content = json_encode($data['phonebook']);
        }else{
            $content = '';
        }

        $type = 'phonebook';


        $logic = new CreditLogic();

        $result = $logic->addCreditPhone($uid, $type, $content);

        if(!$result['status']){
            return $this->responseJson([], self::CODE_PHONEBOOK_ERROR, $result['msg']);
        }

        return $this->responseJson([], self::CODE_SUCCESS, "添加成功");

    }

    /**
     * @param Request $request
     * @return string
     * 获取用户短信
     */
    public function getSms(Request $request){

        $json = $request->input('json','');

        $token = $request->input('token');

        $uid = $this->getUserIdByToken($token);

        $data = json_decode($json,true);

        if(isset($data['sms'])){
            $content = json_encode($data['sms']);
        }else{
            $content = '';
        }

        $type = 'sms';

        $logic = new CreditLogic();

        $result = $logic->addCreditPhone($uid, $type, $content);

        if(!$result['status']){
            return $this->responseJson([], self::CODE_SMS_ERROR, $result['msg']);
        }

        return $this->responseJson([], self::CODE_SUCCESS, "添加成功");

    }

    /**
     * @param Request $request
     * @return string
     * @desc 获取用户通话记录
     */
    public function getHistory(Request $request){

        $json = $request->input('json','');

        $token = $request->input('token');

        $uid = $this->getUserIdByToken($token);

        $data = json_decode($json,true);

        if(isset($data['history'])){
            $content = json_encode($data['history']);
        }else{
            $content = '';
        }

        $type = 'history';

        $logic = new CreditLogic();

        $result = $logic->addCreditPhone($uid, $type, $content);

        if(!$result['status']){
            return $this->responseJson([], self::CODE_HISTORY_ERROR, $result['msg']);
        }

        return $this->responseJson([], self::CODE_SUCCESS, "添加成功");

    }

    public function uploadPic(Request $request){
        $type = $request->input('type');
        $data = $_FILES['file'];

        $ossLogic = new OssLogic();
        $result = $ossLogic->putFile($data);

        $url = env("APP_URL").$result['data']['path'].'/'.$result['data']['name'];
        if($result['status']){
            return $this->responseJson(['type'=>$type,'url'=>$url], self::CODE_SUCCESS, "添加成功");
        }


    }


}