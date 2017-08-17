<?php
/**
 * @desc    用户授信功能
 * @date    2017年04月20日
 *
 **/
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Logics\Credit\CreditLogic;
use App\Models\Credit\CreditModel;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;

class UserCreditController extends BaseController
{

    protected $homeName = '授信管理';

    /**
     * @desc    授信用户审核记录
     *
     */
    public function index(Request $request)
    {
        if( empty(\Auth::guard('admin')->user()->id)){
            return redirect('/admin/login');
        }
        $status = $request->input('status', CreditModel::CREDIT_ING);
        $logic  = new CreditLogic();
        $list   = $logic->getUserCreditRecord($status);
        #dd($list['list']);
        $viewDate = [
            'home'      => $this -> homeName,
            'title'     => '授信用户记录',
            'status'    => $status,
            'list'      => $list['list'],
        ];

        return view('admin.usercredit.index', $viewDate);

    }


    /**
     * @desc    授信用户详情详情
     * @param   $uid    用户ID
     * @param   $type   类型-暂时不用
     **/
    public function creditDetail($uid='1', Request $request){
        if(empty(\Auth::guard('admin')->user()->id)){
            return redirect('/admin/login');
        }
        $type   = $request->input('type','');
        $logic  = new CreditLogic();
        $detail = $logic->getUserCreditDetail($uid,$type);

        return view('admin.usercredit.detail', $detail);
    }

    /**
     * @desc    授信用户审核操作
     * @param   $uid            用户ID
     * @param   $credit_total   申请额度
     **/
    public function creditStatus(Request $request){
        $uid            = $request->input('uid', 1);
        $credit_total   = $request->input('credit_total', '');

        if(empty($credit_total)){

            return redirect()->back()->with('fail', "请选择授信额度");

        }
        //额度大于0 表示授信成功
        if( $credit_total > 0 ){
            $credit_status  = CreditModel::CREDIT_PASS;
        }else{
            //授信失败
            $credit_status  = CreditModel::CREDIT_REFUSE;
        }

        $logic  = new CreditLogic();
        $result = $logic->updateUserCredit($uid, $credit_total);

        if($result['status']){
            return redirect("/admin/user_credit?status=$credit_status")->with('message', '操作成功');
        }else{
            return redirect()->back()->with('fail', "操作失败");
        }

    }

}
