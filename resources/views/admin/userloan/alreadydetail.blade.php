@extends('layouts.admin-app')

@section('content')
    <style>
        body{
            overflow: auto;
        }
    </style>

    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span><a href="/admin/userLoan/alreadyRecord">已放款记录</a></span><span>借款详情</span></h2>
    </div>

    <div class="contentpanel">

        <div class="row">
            <div class="col-sm-9 col-lg-10">
                <div class="panel panel-default">
                    <div class="panel-body panel-body-nopadding">

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <tbody>
                                <tr>
                                    <td colspan="3"><b>借款信息</b></td>
                                </tr>
                                <tr>
                                    <td>姓名</td>
                                    <td>{{ $loanInfo['real_name'] or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>年龄</td>
                                    <td>{{ $loanInfo['age'] or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>身份证</td>
                                    <td>{{ $loanInfo['id_card'] or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>手机</td>
                                    <td>{{ $loanInfo['phone'] or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>审批额度</td>
                                    <td>{{ $loanInfo['credit_total'] or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>借款金额</td>
                                    <td>{{ $loanInfo['borrow_amount'] or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>银行卡号</td>
                                    <td>{{ $loanInfo['borrow_card_no'] or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>放款时间</td>
                                    <td>{{ $loanInfo['pay_time'] or null }}</td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div><!-- panel-body -->
                </div>

            </div><!-- col-sm-9 -->

        </div><!-- row -->

    </div>
@endsection