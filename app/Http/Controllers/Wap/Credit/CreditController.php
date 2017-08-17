<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/18
 * Time: 下午1:43
 */

namespace App\Http\Controllers\Wap\Credit;

use App\Http\Controllers\Wap\AppController;
use App\Logics\Credit\CreditLogic;
use App\Logics\Credit\PhotoLogic;
use App\Models\Credit\CreditContractModel;
use App\Models\Credit\CreditModel;
use App\Models\Credit\CreditPhoneModel;
use App\Models\Credit\PhotoModel;
use Illuminate\Http\Request;

class CreditController extends AppController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 授信审核信息输入页面
     */
    public function creditOne(){

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

        $system       = $this->getSystem();
        $data = [
            'culData'       => $culData,
            'marital'       => $marital,
            'serviceYears'  => $serviceYears,
            'mainBiz'       => $mainBiz,
            'profitTotal'   => $profitTotal,
            'system'        => $system,
        ];

        //如果该用户有授信审核信息,跳到通讯录授信页面
        $uid = $this->getUserId();
        $model = new CreditModel();
        $creditInfo = $model->getUserInfo($uid);

        if($creditInfo !== null){
            return redirect('/app/credit/step/2');
        }

        return view('wap.credit.credit0',$data);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 获取手机通讯录授权信息页
     */
    public function creditTwo(){

        //如果该用户有通讯录信息记录,跳到联系人信息页面
        $uid = $this->getUserId();

        $phoneModel = new CreditPhoneModel();
        $phoneInfo = $phoneModel->getUserInfo($uid);

        $data['system'] = $this->getSystem();

        if($phoneInfo !== null){
            $model = new CreditModel();
            $creditInfo = $model->getUserInfo($uid);

            if($creditInfo != null && $creditInfo['confidence'] != ''){
                return redirect('/app/credit/step/3');
            }

            return view('wap.credit.faceId',$data);

        }else{
            return view('wap.credit.credit1',$data);
        }

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 联系人信息页面
     */
    public function creditThree(){

        $uid = $this->getUserId();

        $model = new CreditContractModel();
        $creditInfo = $model->getUserInfo($uid);

        if($creditInfo !== null){
            return redirect('/app/credit/step/4');
        }

        $model = new CreditModel();
        $creditInfo = $model->getUserInfo($uid);
        if(empty($creditInfo)){
            $creditInfo['marital_status'] = false;
        }
        $creditInfo['system'] = $this->getSystem();
        return view('wap.credit.credit3',$creditInfo);

    }

    public function creditFour(){

        $data['system'] = $this->getSystem();

        return view('wap.credit.credit4',$data);
    }

    public function creditFive(){

        $data['system'] = $this->getSystem();

        return view('wap.credit.credit5',$data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     * @desc 提交授信审核信息
     */
    public function doCreditOne(Request $request){

        $data = $request->all();

        $uid = $this->getUserId();

        $logic = new CreditLogic();
        $result = $logic->create($uid, $data);

        return self::returnJson($result);

    }

    /**
     * @param Request $request
     * @return string
     * @desc 联系人信息提交
     */
    public function doCreditThree(Request $request){

        $data = $request->all();

        $uid = $this->getUserId();

        $logic = new CreditLogic();
        $result = $logic->addContract($uid, $data);

        return self::returnJson($result);
    }

}
