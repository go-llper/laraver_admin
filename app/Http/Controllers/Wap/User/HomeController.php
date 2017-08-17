<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/17
 * Time: 下午6:01
 */

namespace App\Http\Controllers\Wap\User;

use App\Http\Controllers\Controller;
use App\Logics\Borrow\BorrowLogic;
use App\Logics\Common\BaseLogic;
use App\Logics\Credit\CreditLogic;
use App\Logics\Credit\PhotoLogic;
use App\Models\Borrow\BankcardModel;
use App\Models\Borrow\BorrowModel;
use App\Logics\Oss\OssLogic;
use App\Models\Credit\CreditModel;
use App\Models\User\UserModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(){

        $uid = $this->getUserId();

        $userModel = new UserModel();
        $userInfo  = $userModel->getId($uid);

        # CreditInfo
        $creditModel = new CreditModel();
        $creditInfo  = $creditModel->getUserInfo($uid);
        $data = [
            'credit_total' => isset($userInfo['credit_total']) ? $userInfo['credit_total'] : 0,
            'credit_status'=> isset($creditInfo['credit_status']) ? $creditInfo['credit_status'] : null,
        ];

        # BorrowInfo
        $borrowModel = new BorrowModel();
        $borrowList  = $borrowModel->getUserBorrowList($uid);
        $data['borrow_status'] = BorrowLogic::BORROW_STATUS;
        $data['borrow_list']   = $borrowList;

        # bankCardInfo
        $bankCardModel = new BankcardModel();
        $cardInfo = $bankCardModel->getUserBindInfo($uid);
        $cardNo   = '';
        if(!empty($cardInfo)){
            if(!empty($cardInfo['card_no'] && !empty($cardInfo['card_bank']))){
                $bankList = BorrowLogic::SUPPORT_BANK_LIST;
                $cardNo   = $bankList[$cardInfo['card_bank']]['name'];
                $cardNo  .= ' '.BaseLogic::parseBankCardNo($cardInfo['card_no']);
            }
        }
        $data['card'] = $cardNo;

        $data['system'] = $this->getSystem();
        return view('wap.index',$data);

    }

    /**
     * @param Request $request
     * @return string
     * @desc 上传图片
     */
    public function doUpload(Request $request){

        $file  = $request->allFiles();
        $oss   = new OssLogic();

        foreach($file as $k=>$v){
            $param = [
                'name' => $v->getClientOriginalName(),
                'ext' => $v->getClientOriginalExtension(),
                'tmp_name' => $v->getRealPath()
            ];

            $upload = $oss->putFile($param);

            if(!$upload['status']){
                return self::returnJson($upload);
            }

            $image = env("APP_URL").'/'.$upload['data']['path'].'/'.$upload['data']['name'];
            return self::returnJson(['status'=>true, 'url'=>$image]);
        }

    }

    public function savePhoto(Request $request){

        $uid = $this->getUserId();

        $data1 = [
            'uid'   => $uid,
            'type'  => 'business',
            'image' => $request->input('business',''),
        ];
        $data2 = [
            'uid'   => $uid,
            'type'  => 'transport',
            'image' => $request->input('transport',''),
        ];
        $data3 = [
            'uid'   => $uid,
            'type'  => 'marry',
            'image' => $request->input('marry',''),
        ];
        $data4 = [
            'uid'   => $uid,
            'type'  => 'driver',
            'image' => $request->input('driver',''),
        ];
        $data5 = [
            'uid'   => $uid,
            'type'  => 'houseCover',
            'image' => $request->input('houseCover',''),
        ];
        $data6 = [
            'uid'   => $uid,
            'type'  => 'houseSign',
            'image' => $request->input('houseSign',''),
        ];
        $data7 = [
            'uid'   => $uid,
            'type'  => 'houseInfo',
            'image' => $request->input('houseInfo',''),
        ];
        $data8 = [
            'uid'   => $uid,
            'type'  => 'housePic',
            'image' => $request->input('housePic',''),
        ];
        $data9 = [
            'uid'   => $uid,
            'type'  => 'houseTitle',
            'image' => $request->input('houseTitle',''),
        ];
        $data10 = [
            'uid'   => $uid,
            'type'  => 'houseTip',
            'image' => $request->input('houseTip',''),
        ];

        $logic = new PhotoLogic();
        $pic1   = $logic->doAdd($data1);
        $pic2   = $logic->doAdd($data2);
        $pic3   = $logic->doAdd($data3);
        $pic4   = $logic->doAdd($data4);
        $pic5   = $logic->doAdd($data5);
        $pic6   = $logic->doAdd($data6);
        $pic7   = $logic->doAdd($data7);
        $pic8   = $logic->doAdd($data8);
        $pic9   = $logic->doAdd($data9);
        $pic10  = $logic->doAdd($data10);

        if($pic1['status'] && $pic2['status'] && $pic3['status'] && $pic4['status'] && $pic5['status'] && $pic6['status'] && $pic7['status'] && $pic8['status'] && $pic9['status'] && $pic10['status']){
            $userInfoLogic = new CreditLogic();
            $result = $userInfoLogic->updateUserCreditStatus($uid,CreditModel::CREDIT_ING);
            if($result['status']){
                return BaseLogic::callSuccess();
            }else{
                return BaseLogic::callError();
            }
        }else{
            return BaseLogic::callError();
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 注册协议
     */
    public function registerDeal(){
        return view('wap.user.register_deal');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 服务协议
     */
    public function serverDeal(){
        return view('wap.user.sever_deal');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 借款协议
     */
    public function loanDeal(){
        return view('wap.user.loan_deal');
    }
}