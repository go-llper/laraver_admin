<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/18
 * Time: 下午5:49
 */

namespace App\Logics\Credit;

use App\Logics\Common\BaseLogic;
use App\Models\Borrow\BorrowModel;
use App\Models\Common\ValidationModel;
use App\Models\Credit\CreditContractModel;
use App\Models\Credit\CreditModel;

use App\Models\Credit\CreditPhoneModel;
use App\Models\Credit\PhotoModel;
use App\Tools\ToolIdCard;
use Log;

class CreditLogic extends BaseLogic
{

    public function getShowData(){

    }

    /**
     * @param int $uid
     * @param array $data
     * @return array
     * @desc 创建授信审核信息
     */
    public function create($uid, $data = []){

        $param = [
            'uid'               => $uid,
            'real_name'         => !empty($data['real_name']) ? $data['real_name'] : '',
            'id_card'           => !empty($data['id_card']) ? $data['id_card'] : '',
            'cul_level'         => !empty($data['cul_level']) ? $data['cul_level'] : '',
            'marital_status'    => !empty($data['marital_status']) ? $data['marital_status'] : '',
            'home_address'      => str_replace(' ',',',$data['home_address']).','.$data['home_address_ext'],
            'service_years'     => !empty($data['service_years']) ? $data['service_years'] : '',
            'company_name'      => !empty($data['company_name']) ? $data['company_name'] : '',
            'company_start'     => !empty($data['company_start']) ? strtotime($data['company_start']) : '',
            'company_address'   => str_replace(' ',',',$data['company_address']).','.$data['company_address_ext'],
            'main_biz'          => !empty($data['main_biz']) ? $data['main_biz'] : '',
            'biz_address'       => str_replace(' ',',',$data['biz_address']).','.$data['biz_address_ext'],
            'employee_number'   => !empty($data['employee_number']) ? $data['employee_number'] : '',
            'money_total'       => !empty($data['money_total']) ? $data['money_total'] : '',
            'profit_total'      => !empty($data['profit_total']) ? $data['profit_total'] : '',
            'anchored_car'      => !empty($data['anchored_car']) ? $data['anchored_car'] : '',
            'own_car'           => !empty($data['own_car']) ? $data['own_car'] : '',
        ];

        try{
            $creditModel = new CreditModel();
            $creditInfo = $creditModel->getUserInfo($uid);

            if($creditInfo !== null){
                return self::callError('您已参与授信');
            }

            $this->validationParamEmpty($param);
            ValidationModel::isName($data['real_name']);
            ValidationModel::isIdCard($data['id_card']);
            $model = new CreditModel();
            $model->doCreate($param);

        }catch(\Exception $e){

            $param['errorMsg'] = $e->getMessage();

            Log::error(__METHOD__ . 'Error', $param);
            return self::callError($e->getMessage());

        }

        return self::callSuccess();

    }

    /**
     * @param array $data
     * @return bool
     * @throws \Exception
     * @desc 判断字段是否有空值
     */
    public function validationParamEmpty($data = []){

        $creditParam = [
            'real_name' => '姓名',
            'id_card' => '身份证号码',
            'cul_level' => '文化程度',
            'marital_status' => '婚姻状况',
            'home_address' => '详细居住地址',
            'service_years' => '从业年限',
            'company_name' => '公司名称',
            'company_start' => '成立时间',
            'company_address' => '公司注册地址',
            'biz_address' => '经营地址',
            'main_biz' => '主营业务',
            'employee_number' => '雇员人数',
            'money_total' => '年收入',
            'profit_total' => '年净利润',
            'anchored_car' => '挂靠车辆数量',
            'own_car' => '自有车辆数量'
        ];

        foreach($creditParam as $key=>$value){
            if($data[$key] == ''){
                throw new \Exception($value.'不能为空');
                break;
            }
        }

        return true;

    }

    /**
     * @param       $uid
     * @param array $data
     * @return array
     * @desc    创建联系人信息
     */
    public function addContract($uid, $data = []){
        $param = [
            'uid'           => $uid,
            'family_name'   => !empty($data['family_name']) ? $data['family_name'] : '',
            'family_tel'    => !empty($data['family_tel']) ? $data['family_tel'] : '',
            'workmate_name' => !empty($data['workmate_name']) ? $data['workmate_name'] : '',
            'workmate_tel'  => !empty($data['workmate_tel']) ? $data['workmate_tel'] : '',
            'friend_name'   => !empty($data['friend_name']) ? $data['friend_name'] : '',
            'friend_tel'    => !empty($data['friend_tel']) ? $data['friend_tel'] : '',
            'marital_name'  => !empty($data['marital_name']) ? $data['marital_name'] : '',
            'marital_tel'   => !empty($data['marital_tel']) ? $data['marital_tel'] : '',
        ];

        try{
            ValidationModel::isName($param['family_name']);
            ValidationModel::validationPhone($param['family_tel']);
            ValidationModel::isName($param['workmate_name']);
            ValidationModel::validationPhone($param['workmate_tel']);
            ValidationModel::isName($param['friend_name']);
            ValidationModel::validationPhone($param['friend_tel']);
            if($param['marital_name'] != '' && $param['marital_tel'] != ''){
                ValidationModel::isName($param['marital_name']);
                ValidationModel::validationPhone($param['marital_tel']);
            }
            $model = new CreditContractModel();
            $model->doCreate($param);

        }catch(\Exception $e){

            $param['errorMsg'] = $e->getMessage();

            Log::error(__METHOD__ . 'Error', $param);
            return self::callError($e->getMessage());

        }

        return self::callSuccess();

    }

    /**
     * @desc    授信用户记录
     *
     **/
    public function getUserCreditRecord($status=CreditModel::CREDIT_ING){

        $model  = new CreditModel();

        $result = $model->getUserCreditRecord($status);

        return $result;

    }

    /**
     * @desc    授信用户记录
     *
     **/
    public function getUserCreditDetail($uid,$type=''){

        $model  = new CreditModel();

        #$result     = $model->getUserCreditDetail($uid,$type);
        $userInfo   = $model->getCreditUserBaseInfo($uid);
        $userContract=$model->getCreditUserContract($uid);
        $userPhone  = $model->getCreditUserPhone($uid);
        $userPhoto  = $model->getCreditUserPhoto($uid);
        $result = $this->formatCrediteDetail( $userInfo, $userContract, $userPhoto,$userPhone);
        #var_dump($result);exit;
        return $result;

    }
    /**
     * @desc    格式化详情数据
     *
     **/
    public function formatCrediteDetail($userInfo, $userContract, $userPhoto,$userPhone){
        //文化程度
        $culData      = CreditModel::getCulLevel();
        //婚姻状况
        $marital      = CreditModel::getMarital();
        //从业年限
        $serviceYears = CreditModel::getServiceYears();
        //主营业务
        $mainBiz      = CreditModel::getMainBiz();
        //年收入
        $profitTotal  = CreditModel::getProfitTotal();

        $id_card        = isset($userInfo['id_card']) ? $userInfo['id_card']:"";
        $userInfo['age']= ToolIdCard::getAgeByIdCard($id_card);
        $userInfo['sex']= ToolIdCard::getSexByIdCard($id_card);
        $userInfo['uid']            = isset($userInfo['uid'])       ? $userInfo['uid']:"";
        $userInfo['real_name']      = isset($userInfo['real_name']) ? $userInfo['real_name']:"";
        $credit_status  =        isset($userInfo['real_name']) ? $userInfo['real_name']:"";
        $userInfo['credit_status']  =  $credit_status;
        $credit_total   = "";
        if($credit_status == CreditModel::CREDIT_PASS){
            $credit_total   =   isset($userInfo['credit_total']) ? $userInfo['']:"0";
        }elseif($credit_status  == CreditModel::CREDIT_REFUSE){
            $credit_total   = "拒绝授信";
        }
        $userInfo['credit_total']   = $credit_total;
        $userInfo['id_card']        = $id_card;
        $userInfo['phone']          = isset($userInfo['phone'])     ? $userInfo['phone']:"";
        $userInfo['home_address']   = isset($userInfo['home_address'])  ? $userInfo['home_address']:"";
        $userInfo['cul_level']      = isset($userInfo['cul_level'])     ? $culData[$userInfo['cul_level']]:"";
        $userInfo['marital_status'] = isset($userInfo['marital_status'])? $marital[$userInfo['marital_status']]:"";
        $userInfo['service_years']  = isset($userInfo['service_years']) ? $serviceYears[$userInfo['service_years']]:"";
        $userInfo['main_biz']       = isset($userInfo['main_biz'])      ? $mainBiz[$userInfo['main_biz']]:"";
        $userInfo['money_total']    = isset($userInfo['money_total'])   ? $profitTotal[$userInfo['money_total']]:"";
        #人脸识别
        $userInfo['confidence']     = isset($userInfo['confidence'])    ? $userInfo['confidence']:"0";
        #通讯录数量
        $userInfo['phone_num']      = 0;
        #联系人与通讯录匹配数量
        $userInfo['phone_contract'] = 0;

        $userInfo['company_name']   = isset($userInfo['company_name'])  ? $userInfo['company_name']:"";
        $userInfo['company_start']  = isset($userInfo['company_start']) ? $userInfo['company_start']:"";
        $userInfo['company_address']= isset($userInfo['company_address']) ? $userInfo['company_address']:"";
        $userInfo['employee_number']= isset($userInfo['employee_number']) ? $userInfo['employee_number']:"";
        $userInfo['anchored_car']   = isset($userInfo['anchored_car'])    ? $userInfo['anchored_car']:"";


        #联系人
        $userContractNew['family_name'] = isset($userContract[0]['family_name'])? $userContract[0]['family_name']:"";
        $family_tel     = isset($userContract[0]['family_tel']) ? $userContract[0]['family_tel']:"";
        $userContractNew['family_tel']  = $family_tel;
        $userContractNew['workmate_name']=isset($userContract[0]['workmate_name'])?$userContract[0]['workmate_name']:"";
        $workmate_tel   = isset($userContract[0]['workmate_tel']) ?$userContract[0]['workmate_tel']:"";
        $userContractNew['workmate_tel']= $workmate_tel;
        $userContractNew['friend_name'] = isset($userContract[0]['friend_name'])? $userContract[0]['friend_name']:"";
        $friend_tel     = isset($userContract[0]['friend_tel']) ? $userContract[0]['friend_tel']:"";
        $userContractNew['friend_tel']  = $friend_tel;
        $userContractNew['marital_name']= isset($userContract[0]['marital_name']) ?$userContract[0]['marital_name']:"";
        $marital_tel    = isset($userContract[0]['marital_tel'])? $userContract[0]['marital_tel']:"";
        $userContractNew['marital_tel'] = $marital_tel;

        #联系人手机号
        $userContractPhone  = [$family_tel,$workmate_tel,$friend_tel,$marital_tel];
        #照片信息
        $userPhotoData  = $this->formatUserPhoto($userPhoto);

        //TODO:用户通讯相关信息
        $userPhoneData  = $this->formatUserPhone($userPhone,$userContractPhone);
        $phoneContract  = $userPhoneData["phoneContract"];
        $phoneRecord    = $userPhoneData["phoneRecord"];
        $phoneSms       = $userPhoneData["phoneSms"];

        $userInfo['phone_num']      = $userPhoneData['phone_num'];
        $userInfo['phone_contract'] = $userPhoneData['phone_contract'];

        $resData    = [
            "userInfo"      => $userInfo,
            "userContract"  => $userContractNew,
            "userPhoto"     => $userPhotoData,
            "phoneContract" => $phoneContract,
            "phoneRecord"   => $phoneRecord,
            "phoneSms"      => $phoneSms,

        ];
        return $resData;
    }


    /**
     * @desc    用户通讯录，通话信息
     **/
    public function formatUserPhone($userPhone,$userContractPhone){
        #通讯录信息
        $phoneContract  = [];
        #通话记录
        $phoneRecord    = [];
        #短信信息
        $phoneSms       = [];
        #联系人数量
        $phone_num      = 0;
        #通讯录-联系人匹配数量
        $phone_contract = 0;

        if($userPhone){
            foreach ($userPhone as $pkk=>$pvv){
                if($pvv['type'] == CreditPhoneModel::PHONE_BOOK){
                    $phoneContract  = isset($pvv['content'])?$pvv['content']:[];
                }elseif($pvv['type']== CreditPhoneModel::CALL_HISTORY ){
                    $phoneRecord    = isset($pvv['content'])?$pvv['content']:[];
                }else{
                    $phoneSms       = isset($pvv['content'])?$pvv['content']:[];
                }
            }
        }

        $phoneContractNew   = [];
        //手机号-用户名
        $phoneContractArr   = [];
        $phoneContractPhone = [];
        if($phoneContract){
            $phoneContract  = json_decode($phoneContract,true);
            foreach ($phoneContract as $pckey=>$pcval){
                $name   = isset($pcval['name'])?$pcval['name']:"";
                $phone  = isset($pcval['tel'][0])?str_replace('-','',$pcval['tel'][0]):"";
                if($phone){
                    $phoneContractNew[$pckey]['name']   = $name;
                    $phoneContractNew[$pckey]['tel']    = $phone;

                    if(!in_array($phone, $phoneContractArr)){
                        $phoneContractArr[$phone] = $name;
                        $phoneContractPhone[] = $phone;
                    }
                }

            }
            $phone_num  = count($phoneContractArr);
        }
        $phoneRecordNew = [];
        #dd($phoneRecord);
        if($phoneRecord){
            $phoneRecord  = json_decode($phoneRecord,true);
            foreach ($phoneRecord as $prkey=>$prval){
                if($prval['to']){
                    $phoneRecordNew[$prkey]['tel_id']   = $prkey;
                    $phoneRecordNew[$prkey]['tel_no']   = isset($prval['to'])?str_replace('-','',$prval['to']):"";
                    $phoneRecordNew[$prkey]['tel_time'] = isset($prval['connect_time'])?$prval['connect_time']:"";
                    $phoneRecordNew[$prkey]['tel_name'] = isset($phoneContractArr[$prval['to']])?$phoneContractArr[$prval['to']]:"";
                }

            }
        }
        $phone_contract = count(array_intersect($phoneContractPhone,$userContractPhone));
         return [
             "phoneContract" => $phoneContractNew,
             "phoneRecord"   => $phoneRecordNew,
             "phoneSms"      => $phoneSms,
             'phone_num'     => $phone_num,
             'phone_contract'=> $phone_contract,

         ];

    }

    /**
     * @desc    用户照片信息
     **/
    public function formatUserPhoto($userPhoto=[]){

        $driver = $business = $transport = $marry  =$ocr_pic    = $ocr_pic_back = '';
        $house_cover = $house_info = $house_pic =$house_sign = $house_pic = $house_title = $house_tip='';
        $pic_one = $pic_two = $pic_three = $pic_four = '';

        foreach ($userPhoto as $key=>$val){
           switch ($val['type']){
               case PhotoModel::DRIVER:
                   $driver      = $val['image'];
                   break;
               case PhotoModel::BUSINESS:
                   $business    = $val['image'];
                   break;
               case PhotoModel::TRANSPORT:
                   $transport   = $val['image'];
                   break;
               case PhotoModel::MARRY:
                   $marry       = $val['image'];
                   break;
               case PhotoModel::HOUSE_COVER:
                   $house_cover = $val['image'];
                   break;
               case PhotoModel::HOUSE_SIGN:
                   $house_sign  = $val['image'];
                   break;
               case PhotoModel::HOUSE_INFO:
                   $house_info  = $val['image'];
                   break;
               case PhotoModel::HOUSE_PIC:
                   $house_pic   = $val['image'];
                   break;
               case PhotoModel::HOUSE_TITLE:
                   $house_title = $val['image'];
                   break;
               case PhotoModel::HOUSE_TIP:
                   $house_tip  = $val['image'];
                   break;
               case PhotoModel::PIC_ONE:
                   $pic_one    = $val['image'];
                   break;
               case PhotoModel::PIC_TWO:
                   $pic_two    = $val['image'];
                   break;
               case PhotoModel::PIC_THREE:
                   $pic_three   = $val['image'];
                   break;
               case PhotoModel::PIC_FOUR:
                   $pic_four    = $val['image'];
                   break;
               case PhotoModel::OCR_PIC:
                   $ocr_pic    = $val['image'];
                   break;
               case PhotoModel::OCR_PIC_BACK:
                   $ocr_pic_back    = $val['image'];
                   break;

           }
        }
        $userPhotoData['driver']        = $driver;
        $userPhotoData['business']      = $business;
        $userPhotoData['transport']     = $transport;
        $userPhotoData['marry']         = $marry;
        $userPhotoData['house_cover']   = $house_cover;
        $userPhotoData['house_sign']    = $house_sign;
        $userPhotoData['house_info']    = $house_info;
        $userPhotoData['house_pic']     = $house_pic;
        $userPhotoData['house_title']   = $house_title;
        $userPhotoData['house_tip']     = $house_tip;
        $userPhotoData['ocr_pic']       = $ocr_pic;
        $userPhotoData['ocr_pic_back']  = $ocr_pic_back;
        $userPhotoData['pic_one']       = $pic_one;
        $userPhotoData['pic_two']       = $pic_two;
        $userPhotoData['pic_three']     = $pic_three;
        $userPhotoData['pic_four']      = $pic_four;

        #dd($userPhotoData);
        return    $userPhotoData;
    }

    /**
     * @desc    更新用户授信状态
     * 逻辑：    授信成功-用户获取额度
     *          授信失败-更新用户授信状态
     *
     * 数据表  user、user_info
     **/
    public function updateUserCredit($uid, $credit_total){
        try{
            $model  = new CreditModel();
            //额度大于0 表示授信成功
            if( $credit_total > 0 ){
                $credit_status  = CreditModel::CREDIT_PASS;
                $result = $model->updateUserCredit($uid,$credit_total);
            }else{
                //授信失败
                $credit_status  = CreditModel::CREDIT_REFUSE;
            }
            $operate_user   = \Auth::guard('admin')->user()->name;
            $operate_user   = $operate_user ? $operate_user :"";
            //数据表user_info-授信状态，操作者
            $result = $model->updateUserInfoCredit($uid, $credit_status, $operate_user);
        }catch(\Exception $e){

            $param['errorMsg'] = $e->getMessage();

            Log::error(__METHOD__ . 'Error', $param);
            return self::callError($e->getMessage());

        }
        return self::callSuccess();
    }

    /**
     * @param $uid
     * @param $creditStatus
     * @return array
     * @desc 上传证件照片成功后修改用户授信状态为授信中
     */
    public function updateUserCreditStatus($uid,$creditStatus){
        try{
            $model = new CreditModel();
            $model->updateUserStatus($uid,$creditStatus);
        }catch(\Exception $e){
            $param['errorMsg'] = $e->getMessage();

            Log::error(__METHOD__ . 'Error', $param);
            return self::callError($e->getMessage());
        }
        return self::callSuccess();
    }

    /**
     * @param $uid
     * @param $creditStatus
     * @return array
     * @desc 修改用户面部识别分数
     */
    public function updateUserConfidence($uid,$creditStatus){
        try{
            $model = new CreditModel();
            $model->updateUserConfidence($uid,$creditStatus);
        }catch(\Exception $e){
            $param['errorMsg'] = $e->getMessage();

            Log::error(__METHOD__ . 'Error', $param);
            return self::callError($e->getMessage());
        }
        return self::callSuccess();
    }

    /**
     * @param int    $uid
     * @param        $type
     * @param string $content
     * @return array
     * @desc 用户手机授权相关信息录入
     */
    public function addCreditPhone($uid = 1, $type, $content = ''){

        try{
            $model = new CreditPhoneModel();

            $param = [
                'uid' => $uid,
                'type' => CreditPhoneModel::$getArr[$type],
                'content' => $content
            ];

            $model->doCreate($param);


        }catch(\Exception $e){

            $param['errorMsg'] = $e->getMessage();

            Log::error(__METHOD__ . 'Error', $param);
            return self::callError($e->getMessage());

        }

        Log::info($type.'data', $param);
        return self::callSuccess();
    }

}