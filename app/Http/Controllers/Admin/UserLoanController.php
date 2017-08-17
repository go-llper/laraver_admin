<?php
/**
 * @desc    借款审核
 * @date    2017年04月20日
 *
 **/
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Logics\Borrow\BorrowLogic;
use App\Models\Borrow\BorrowModel;
use Illuminate\Http\Request;

class UserLoanController extends BaseController
{
    protected $homeName = '借款管理';
//
//    public function __construct()
//    {
//        parent::__construct();
//    }

    public function loanRecord(Request $request)
    {

        if( empty(\Auth::guard('admin')->user()->id)){
            return redirect('/admin/login');
        }
        $status = BorrowModel::LOAN_STATUS_CHECK;
        $logic  = new BorrowLogic();
        $list   = $logic->getUserLoanRecord($status);
        #dump($list['list']);
        $viewDate = [
            'home'      => $this -> homeName,
            'title'     => '借款审核记录',
            'list'      => $list['list'],
        ];

        return view('admin.userloan.loanrecord',$viewDate);
    }
    /**
     * @desc    待放款记录
     **/
    public function waitRecord(Request $request)
    {
        if( empty(\Auth::guard('admin')->user()->id)){
            return redirect('/admin/login');
        }
        $status = BorrowModel::LOAN_STATUS_WAIT;
        $logic  = new BorrowLogic();
        $list   = $logic->getUserLoanRecord($status);
        #dump($list['list']);
        $viewDate = [
            'home'      => $this -> homeName,
            'title'     => '待放款记录',
            'list'      => $list['list'],
        ];

        return view('admin.userloan.waitloan',$viewDate);

    }

    /**
     * @desc    已放款记录
     *
     **/
    public function alreadyRecord()
    {
        if( empty(\Auth::guard('admin')->user()->id)){
            return redirect('/admin/login');
        }
        $logic  = new BorrowLogic();
        $list   = $logic->getUserAlreadyLoanRecord();
        #dd($list['list']);
        $viewDate = [
            'home'      => $this -> homeName,
            'title'     => '已放款记录',
            'list'      => $list['list'],
        ];

        return view('admin.userloan.alreadyloan',$viewDate);
    }


    /**
     * @desc    借款详情
     * @param   $id     借款ID
     * @param   $status 类型-暂时不用
     **/
    public function loanDetail($id='1', Request $request){
        if( empty(\Auth::guard('admin')->user()->id)){
            return redirect('/admin/login');
        }
        $status = $request->input('status','');
        $logic  = new BorrowLogic();
        $detail = $logic->getUserLoanDetail($id, $status);
        $borrow_status  = isset($detail['loanInfo']['borrow_status'])?$detail['loanInfo']['borrow_status']:"";

        if($borrow_status == 0){
            return view('admin.userloan.loandetail', $detail);
        }elseif($borrow_status ==1){
            return view('admin.userloan.waitdetail', $detail);
        }else{
            return view('admin.userloan.alreadydetail', $detail);
        }

    }

    /**
     * @desc    借款审核操作
     * @param   $id     借款ID
     * @param
     **/
    public function loanCheck(Request $request){
        if( empty(\Auth::guard('admin')->user()->id)){
            return redirect('/admin/login');
        }
        $id             = $request->input('id', 1);
        $borrow_status  = $request->input('status', '');
        $borrow_need_fee= $request->input('borrow_need_fee', '0');
        #echo \Auth::guard('admin')->user()->name ;
        #exit;
        if(empty($borrow_status)){
            return redirect()->back()->with('fail', "操作状态丢失");

        }

        $logic  = new BorrowLogic();
        $result = $logic->updateLoanStatus($id, $borrow_status,$borrow_need_fee);

        if($result){
            return redirect('/admin/userLoan/waitRecord')->with('message', '操作成功');
        }else{
            return redirect()->back()->with('fail', "操作失败");
        }

    }

    /**
     * @desc    执行放款操作
     * @param   $id     借款ID
     * @param
     **/
    public function loanOperate(Request $request){
        $id             = $request->input('id', 1);
        #echo \Auth::guard('admin')->user()->name ;
        #exit;
        if(empty($id)){
            return redirect()->back()->with('fail', "非法操作，参数丢失");
        }

        $logic  = new BorrowLogic();
        $result = $logic->doLoanOperate($id);

        if($result['status']){
            return redirect('/admin/userLoan/alreadyRecord')->with('message', '操作成功');
        }else{
            return redirect()->back()->with('fail', "操作失败");
        }

    }



}
